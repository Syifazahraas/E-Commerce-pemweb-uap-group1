@extends('layouts.buyer')

@section('title', 'Your Bag - WALKUNO')

@section('content')

<section class="min-h-[80vh] py-10 md:py-20">
    <div class="container-custom">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-24 items-start">
            
            <!-- CART LIST -->
            <div class="flex-grow w-full">
                <h1 class="font-heading font-black text-3xl mb-8 uppercase tracking-tighter">Shopping Bag <span class="text-slate-400 text-xl ml-2 font-medium normal-case">({{ session('cart') ? count(session('cart')) : 0 }} items)</span></h1>
                
                @if(session('cart') && count(session('cart')) > 0)
                    <div class="divide-y divide-gray-100 border-t border-b border-gray-100">
                        @foreach(session('cart') as $id => $item)
                        <div class="py-8 flex gap-6 group">
                            <!-- Image -->
                            <div class="w-24 h-32 md:w-32 md:h-40 bg-gray-50 flex-shrink-0 relative overflow-hidden">
                                @php $img = \App\Models\Product::find($item['id'])->images->first()->image ?? null; @endphp
                                @if($img)
                                    <img src="{{ Str::startsWith($img, 'http') ? $img : asset('storage/' . $img) }}" class="w-full h-full object-cover">
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="flex-grow flex flex-col justify-between py-1">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-bold text-lg text-slate-900 leading-tight">{{ $item['name'] }}</h3>
                                        <p class="font-medium text-slate-900">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                    <p class="text-sm text-slate-500 mb-1">Size: <span class="text-slate-900 font-bold">{{ $item['size'] ?? '-' }}</span></p>
                                    
                                    <!-- Quantity Controls -->
                                    <div class="flex items-center gap-4 py-2">
                                        <div class="flex items-center border border-gray-200 rounded">
                                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="action" value="decrease">
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-gray-100 transition-colors {{ $item['qty'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $item['qty'] <= 1 ? 'disabled' : '' }}>-</button>
                                            </form>
                                            
                                            <span class="w-10 text-center text-sm font-bold">{{ $item['qty'] }}</span>
                                            
                                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="action" value="increase">
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-500 hover:bg-gray-100 transition-colors">+</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-red-600 transition-colors border-b border-transparent hover:border-red-600 w-max">
                                        Remove Item
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-20 text-center border-t border-b border-gray-100">
                        <p class="text-slate-400 mb-6 text-lg">Your bag is empty.</p>
                        <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-black text-white text-xs font-bold uppercase tracking-widest hover:bg-slate-800 transition-colors">
                            Continue Shopping
                        </a>
                    </div>
                @endif
            </div>

            <!-- SUMMARY -->
            <div class="w-full lg:w-[400px] flex-shrink-0 lg:sticky lg:top-24">
                <div class="bg-gray-50 p-8">
                    <h2 class="font-heading font-black text-xl mb-6 uppercase tracking-tight">Summary</h2>
                    
                    <div class="space-y-4 mb-8 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format(collect(session('cart'))->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="text-slate-400 italic">Calculated at checkout</span>
                        </div>
                        <div class="pt-4 border-t border-gray-200 flex justify-between font-bold text-slate-900 text-base">
                            <span>Total</span>
                            <span>Rp {{ number_format(collect(session('cart'))->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}" class="block w-full py-4 bg-black text-white font-bold uppercase tracking-widest text-xs text-center hover:bg-blue-600 transition-colors">
                        Checkout
                    </a>
                    
                    <div class="mt-6 flex flex-col gap-2 text-[10px] text-slate-400 uppercase tracking-widest text-center">
                        <span>Free Shipping & Returns</span>
                        <span>Secure Checkout</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
