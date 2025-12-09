@extends('layouts.buyer')

@section('title', 'Sale Products - WALKUNO')

@section('content')

<!-- Sale Hero Section - Dark Theme -->
<section class="relative min-h-[60vh] bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#0f172a] flex items-center justify-center overflow-hidden pt-24">
    <!-- Animated Background -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#60A5FA] rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-[#93C5FD] rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-[#1E3A8A] rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Sale Badge -->
        <div class="inline-flex items-center gap-2 bg-red-500 px-6 py-3 rounded-full mt-8 mb-6 animate-pulse shadow-2xl">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <span class="text-white font-black text-lg uppercase tracking-wider">Mega Sale Event</span>
        </div>

        <!-- Main Heading -->
        <h1 class="font-heading font-black text-5xl md:text-7xl lg:text-8xl uppercase tracking-tighter text-white mb-4 animate-fade-in">
            <span class="text-[#93C5FD]">Up To</span> 50% Off
        </h1>
        
        <p class="text-[#93C5FD] text-xl md:text-2xl font-medium mb-8 max-w-2xl mx-auto animate-fade-in delay-100">
            Premium footwear at unbeatable prices. Limited time only!
        </p>

        <!-- Stats -->
        <div class="flex flex-wrap justify-center gap-8 mb-8">
            <div class="text-center">
                <p class="font-black text-4xl text-[#60A5FA] mb-1">{{ $saleProducts->total() }}</p>
                <p class="text-slate-300 text-sm uppercase tracking-wider font-semibold">Products on Sale</p>
            </div>
            <div class="text-center">
                <p class="font-black text-4xl text-yellow-400 mb-1">50%</p>
                <p class="text-slate-300 text-sm uppercase tracking-wider font-semibold">Max Discount</p>
            </div>
        </div>
    </div>
</section>

<!-- Sale Products Grid - Dark Theme -->
<section class="py-16 md:py-20 bg-gradient-to-br from-[#1e293b] via-[#0f172a] to-[#1e293b]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        @if($saleProducts->count() > 0)
            <!-- Products Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 mb-8">
                @foreach($saleProducts as $product)
                <div class="group">
                    <a href="{{ route('product.show', $product->id) }}" class="block h-full bg-gradient-to-br from-[#1a2332] to-[#0f172a] rounded-2xl md:rounded-3xl p-4 border-2 border-[#334155] hover:border-[#93C5FD] transition-all duration-300 hover:shadow-xl hover:shadow-[#93C5FD]/20">
                        <div class="relative bg-[#0a0f1a] rounded-xl md:rounded-2xl overflow-hidden aspect-[4/5] mb-4">
                            <!-- SALE Badge -->
                            <span class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-orange-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider z-10 shadow-lg animate-pulse">SALE</span>
                            
                            @if($product->images && $product->images->count() > 0)
                                <img src="{{ Str::startsWith($product->images->first()->image, 'http') ? $product->images->first()->image : asset('storage/' . $product->images->first()->image) }}" 
                                     alt="{{ $product->name }}"
                                     onerror="this.src='https://placehold.co/400x500/0f172a/60A5FA?text={{ urlencode($product->name) }}'"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#0a0f1a]">
                                    <svg class="w-16 h-16 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="px-2">
                            <p class="text-[10px] font-bold text-[#60A5FA] uppercase tracking-widest mb-2">{{ $product->category->name }}</p>
                            <h3 class="font-heading font-bold text-lg md:text-xl text-white leading-tight mb-3 line-clamp-2">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="font-bold text-lg text-[#93C5FD]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $saleProducts->links() }}
            </div>
        @else
            <!-- No Sale Products -->
            <div class="text-center py-20 bg-[#1a2332] rounded-3xl border-2 border-[#334155]">
                <svg class="w-24 h-24 mx-auto text-[#60A5FA] mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-slate-400 text-lg mb-6">No products on sale at the moment</p>
                <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-[#60A5FA] to-[#93C5FD] hover:from-[#93C5FD] hover:to-[#60A5FA] text-white font-bold uppercase tracking-wider text-sm rounded-full transition-all shadow-lg">
                    Browse All Products
                </a>
            </div>
        @endif
    </div>
</section>

@endsection
