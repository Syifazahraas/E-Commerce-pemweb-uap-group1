<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:5|max:1000',
        ]);

        $user = Auth::user();
        // Allow review even if no buyer profile, since we generally allow it now
        // if (!$user->buyer) { ... }

        // Try to find a verified transaction to link (Optional but good for Verified badge)
        $transaction = null;
        if ($user->buyer) {
            $transaction = Transaction::where('buyer_id', $user->buyer->id)
                ->whereHas('details', function ($query) use ($request) {
                    $query->where('product_id', $request->product_id);
                })
                ->first();
        }

        // Check availability strictly only if we want to enforce it. 
        // For now, we ALLOW general reviews, but we check duplicates based on User + Product
        
        // Check if review already exists for this USER and product
        // (Previously checked transaction_id, now checking user_id or transaction_id)
        $query = ProductReview::where('product_id', $request->product_id);
        
        if ($transaction) {
            $query->where(function($q) use ($transaction, $user) {
                $q->where('transaction_id', $transaction->id)
                  ->orWhere('user_id', $user->id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        if ($query->exists()) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        ProductReview::create([
            'user_id' => $user->id,
            'transaction_id' => $transaction ? $transaction->id : null, 
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
