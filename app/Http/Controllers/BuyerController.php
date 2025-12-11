<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Transaction;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuyerController extends Controller
{
    public function home(Request $request)
    {
        $query = Product::with(['category', 'images'])
            ->where('stock', '>', 0);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        // Additional collections for homepage sections
        $popularProducts = Product::with('images')->inRandomOrder()->take(4)->get();
        $recommendedProducts = Product::with('images')->inRandomOrder()->take(4)->get();

        return view('buyer.home', compact('products', 'popularProducts', 'recommendedProducts'));
    }

    public function sale()
    {
        // Get only products that are on sale
        $saleProducts = Product::with(['category', 'images'])
            ->where('is_on_sale', true)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('buyer.sale', compact('saleProducts'));
    }

    public function products(Request $request)
    {
        $query = Product::with(['category', 'images'])->where('stock', '>', 0);

        // Simple sort filters could be added here later

        $products = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('buyer.products', compact('products'));
    }

    public function productDetail($id)
    {
        $product = Product::with(['category', 'images', 'reviews.user', 'reviews.transaction.buyer.user'])->findOrFail($id);

        $canReview = false;
        if (Auth::check()) {
            $userId = Auth::id();
            $buyerId = Auth::user()->buyer ? Auth::user()->buyer->id : 0;

            // Check if user has already reviewed this product
            // We check matching user_id OR matching transaction->buyer_id (if they have one)
            $alreadyReviewed = \App\Models\ProductReview::where('product_id', $id)
                ->where(function($q) use ($userId, $buyerId) {
                    $q->where('user_id', $userId);
                    
                    if ($buyerId) {
                        $q->orWhereHas('transaction', function($t) use ($buyerId) {
                            $t->where('buyer_id', $buyerId);
                        });
                    }
                })->exists();

            $canReview = !$alreadyReviewed;
        }

        return view('buyer.product-detail', compact('product', 'canReview'));
    }

    public function cart()
    {
        return view('buyer.cart');
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'size' => 'required|string', // Size is now required
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->qty > $product->stock) {
            return back()->with('error', 'Quantity exceeds available stock');
        }

        $cart = session()->get('cart', []);

        // Generate a unique key validation based on product ID AND size
        $cartKey = $product->id . '-' . $request->size;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] += $request->qty;
            $cart[$cartKey]['subtotal'] = $cart[$cartKey]['qty'] * $product->price;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $request->qty,
                'size' => $request->size, // Store the selected size
                'image' => $product->images->first()->image ?? null,
                'subtotal' => $product->price * $request->qty,
            ];
        }

        session()->put('cart', $cart);

        if ($request->action == 'buy_now') {
            return redirect()->route('checkout');
        }

        return redirect()->route('cart')->with('success', 'Product added to cart!');
    }

    public function updateCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($request->action == 'increase') {
                // Check stock
                $product = Product::find($cart[$id]['id']);
                if ($product && $cart[$id]['qty'] < $product->stock) {
                    $cart[$id]['qty']++;
                    $cart[$id]['subtotal'] = $cart[$id]['qty'] * $cart[$id]['price'];
                } else {
                    return back()->with('error', 'Cannot add more. Out of stock!');
                }
            } elseif ($request->action == 'decrease') {
                if ($cart[$id]['qty'] > 1) {
                    $cart[$id]['qty']--;
                    $cart[$id]['subtotal'] = $cart[$id]['qty'] * $cart[$id]['price'];
                }
            }

            session()->put('cart', $cart);
        }

        return redirect()->route('cart')->with('success', 'Cart updated');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart')->with('success', 'Product removed from cart');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        return view('buyer.checkout');
    }

    public function processCheckout(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'phone' => 'required|string',
        'address' => 'required|string',
        'city' => 'required|string',
        'postal_code' => 'required|string',
        'shipping_type' => 'required|in:Regular,Express,Same Day',
        'payment_method' => 'required|in:COD,Transfer',
    ]);

    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return redirect()->route('cart')->with('error', 'Your cart is empty');
    }

    try {
        // Ensure user has buyer record - create if not exists
        $buyer = Auth::user()->buyer;
        if (!$buyer) {
            $buyer = \App\Models\Buyer::create([
                'user_id' => Auth::user()->id,
            ]);
        }

        DB::beginTransaction();

        // Calculate totals
        $subtotal = collect($cart)->sum('subtotal');
        $shippingCost = 0;

        switch($request->shipping_type) {
            case 'Express': $shippingCost = 30000; break;
            case 'Same Day': $shippingCost = 50000; break;
            default: $shippingCost = 15000; // Regular
        }

        $grandTotal = $subtotal + $shippingCost;

        // ✅ FIX: Get store_id from first product in cart
        $firstProductId = collect($cart)->first()['id'];
        $firstProduct = Product::findOrFail($firstProductId);
        $storeId = $firstProduct->store_id;

        // Validate all products are from same store
        foreach ($cart as $item) {
            $product = Product::findOrFail($item['id']);
            if ($product->store_id != $storeId) {
                throw new \Exception('All products must be from the same store');
            }
        }

        // Create transaction
        $transaction = Transaction::create([
            'code' => 'TRX-' . time(),
            'buyer_id' => $buyer->id,
            'store_id' => $storeId, // ✅ Use actual store_id
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'shipping_type' => $request->shipping_type,
            'shipping_cost' => $shippingCost,
            'grand_total' => $grandTotal,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);

        // Create transaction details
        foreach ($cart as $item) {
            $transaction->details()->create([
                'product_id' => $item['id'],
                'qty' => $item['qty'],
                'size' => $item['size'],
                'subtotal' => $item['subtotal'],
            ]);

            // Update stock
            Product::where('id', $item['id'])->decrement('stock', $item['qty']);
        }

        DB::commit();

        // Clear cart
        session()->forget('cart');

        // Different flow based on payment method
        if ($request->payment_method === 'COD') {
            // COD: Mark as paid immediately and record balance
            $transaction->status = 'paid';
            $transaction->save();

            // ✅ Record income to store balance
            $this->recordStoreIncome($transaction);

            return redirect()->route('transaction.history')
                ->with('success', 'Order placed successfully! Order ID: ' . $transaction->code);
        } else {
            // Bank Transfer: Show payment page for confirmation
            return redirect()->route('payment.show', $transaction->id)
                ->with('success', 'Order placed successfully! Please complete your payment.');
        }

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to process order: ' . $e->getMessage());
    }
}

/**
 * ✅ NEW METHOD: Record income to store balance when transaction is paid
 */
private function recordStoreIncome(Transaction $transaction)
{
    // Get or create store balance
    $storeBalance = \App\Models\StoreBalance::firstOrCreate(
        ['store_id' => $transaction->store_id],
        ['balance' => 0]
    );

    // Add income to balance
    $storeBalance->balance += $transaction->grand_total;
    $storeBalance->save();

    // Record in balance history
    \App\Models\StoreBalanceHistory::create([
        'store_balance_id' => $storeBalance->id,
        'type' => 'income',
        'amount' => $transaction->grand_total,
        'reference_type' => 'Transaction',
        'reference_id' => $transaction->code,
        'remarks' => 'Pembayaran dari pesanan ' . $transaction->code,
    ]);
}

    public function showPayment($id)
    {
        // If no buyer record, redirect to home
        if (!Auth::user()->buyer) {
            return redirect()->route('home')->with('error', 'Transaction not found.');
        }

        $transaction = Transaction::with(['details.product.images'])
            ->where('buyer_id', Auth::user()->buyer->id)
            ->findOrFail($id);

        return view('buyer.payment', compact('transaction'));
    }

    public function confirmPayment($id)
{
    // Check if user has buyer record
    if (!Auth::user()->buyer) {
        return redirect()->route('home')->with('error', 'Transaction not found.');
    }

    $transaction = Transaction::where('buyer_id', Auth::user()->buyer->id)
        ->findOrFail($id);

    // Update status to paid
    $transaction->status = 'paid';
    $transaction->save();

    // ✅ Record income to store balance
    $this->recordStoreIncome($transaction);

    return redirect()->route('transaction.history')
        ->with('success', 'Payment confirmed! Your order is being processed.');
}

    public function transactionHistory()
    {
        // If no buyer record, show empty transactions
        if (!Auth::user()->buyer) {
            $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                10,
                1,
                ['path' => request()->url()]
            );
            return view('buyer.transaction-history', compact('transactions'));
        }

        $transactions = Transaction::with(['details.product.images'])
            ->where('buyer_id', Auth::user()->buyer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('buyer.transaction-history', compact('transactions'));
    }

    public function transactionDetail($id)
    {
        // If no buyer record, redirect
        if (!Auth::user()->buyer) {
            return redirect()->route('home')->with('error', 'Transaction not found.');
        }

        $transaction = Transaction::with(['details.product.images'])
            ->where('buyer_id', Auth::user()->buyer->id)
            ->findOrFail($id);

        return view('buyer.transaction-detail', compact('transaction'));
    }

    // Live Search API for instant results
    public function searchProducts(Request $request)
    {
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $products = Product::with(['category', 'images'])
            ->where('name', 'like', '%' . $search . '%')
            ->where('stock', '>', 0)
            ->take(5)
            ->get()
            ->map(function($product) {
                // Get image URL - check if it's external or local
                $imageUrl = null;
                if ($product->images && $product->images->first()) {
                    $image = $product->images->first()->image;
                    $imageUrl = str_starts_with($image, 'http')
                        ? $image
                        : asset('storage/' . $image);
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'price_formatted' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                    'category' => $product->category->name ?? '',
                    'image' => $imageUrl,
                    'url' => route('product.show', $product->id),
                ];
            });

        return response()->json($products);
    }
}
