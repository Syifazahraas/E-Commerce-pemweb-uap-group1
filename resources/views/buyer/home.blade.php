@extends('layouts.buyer')

@section('title', 'Selamat Datang - WALKUNO')

@section('content')

<!-- Hero Section - Dark Theme - Compact when search active -->
<section class="relative {{ request('search') || request('category') ? 'min-h-[60vh]' : 'min-h-screen' }} bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#0f172a] flex items-center justify-center overflow-hidden">
    <!-- Animated Background Glow -->
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-20 left-1/4 w-96 h-96 bg-[#60A5FA] rounded-full filter blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-20 right-1/4 w-96 h-96 bg-[#93C5FD] rounded-full filter blur-[120px] animate-pulse delay-1000"></div>
    </div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 z-10 py-12 md:py-24">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <!-- Welcome Text -->
            <div class="text-center md:text-left animate-fade-in order-2 md:order-1">
                <p class="text-blue-400 font-bold tracking-widest uppercase text-sm md:text-base mb-4">Premium Sportswear</p>
                <h1 class="font-heading font-extrabold text-4xl sm:text-5xl md:text-6xl lg:text-7xl tracking-tight leading-tight mb-6 text-white">
                    Selamat Datang <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600">di WalkUno</span>
                </h1>
                <p class="text-slate-400 text-lg md:text-xl max-w-lg mx-auto md:mx-0 leading-relaxed mb-8">
                    Elevate your game with premium sports equipment. Built for champions, designed for the future.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="#collection" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full transition-all shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 hover:-translate-y-1">
                        Shop Now
                    </a>
                </div>
            </div>

            <!-- Hero Image - Scattered -->
            <div class="relative h-[400px] md:h-[500px] w-full animate-fade-in delay-200 order-1 md:order-2">
                <!-- Decorative Glow -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-blue-500/10 blur-[80px] rounded-full pointer-events-none"></div>
                
                <!-- Floating Shoe 1 (Main) -->
                <img src="{{ asset('floating_shoe_v2_1.png') }}" 
                     alt="Floating Shoe 1" 
                     class="absolute top-1/2 left-[45%] -translate-x-1/2 -translate-y-1/2 w-[350px] md:w-[450px] object-contain mix-blend-screen contrast-125 animate-float z-20 [mask-image:radial-gradient(closest-side,white_60%,transparent_100%)]">

                <!-- Floating Shoe 2 (Small Top Right) -->
                <img src="{{ asset('floating_shoe_v2_2.png') }}" 
                     alt="Floating Shoe 2" 
                     class="absolute top-12 right-12 md:right-32 w-[180px] md:w-[220px] object-contain mix-blend-screen contrast-125 animate-float-delayed z-10 opacity-90 filter blur-[0.5px] [mask-image:radial-gradient(closest-side,white_60%,transparent_100%)]">

                <!-- Floating Shoe 3 (Small Bottom Left) -->
                <img src="{{ asset('floating_shoe_v2_3.png') }}" 
                     alt="Floating Shoe 3" 
                     class="absolute bottom-12 left-4 md:left-16 w-[200px] md:w-[240px] object-contain mix-blend-screen contrast-125 animate-float-slow z-10 opacity-90 filter blur-[0.5px] [mask-image:radial-gradient(closest-side,white_60%,transparent_100%)]">
            </div>
        </div>


    </div>
</section>

@if(request('search') || request('category') || request()->filled('min_price') || request()->filled('max_price'))
    {{-- Show Search Results FIRST when filtering/searching --}}
    <section id="search-results" class="py-16 md:py-20 bg-gradient-to-br from-[#1e293b] via-[#0f172a] to-[#1e293b]">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Results Header with Filters -->
            <div class="mb-8 md:mb-10">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                    <div>
                        <h2 class="font-heading font-black text-3xl md:text-4xl uppercase tracking-tighter text-white">Search Results</h2>
                        <p class="text-[#93C5FD] mt-2">Found {{ $products->total() }} products matching your search</p>
                    </div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[#93C5FD] hover:text-white text-sm font-bold transition-colors px-4 py-2 border-2 border-[#93C5FD] hover:border-white rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear All Filters
                    </a>
                </div>
                
                <!-- Active Filters -->
                <div class="flex flex-wrap gap-2">
                    @if(request('search'))
                        <span class="inline-flex items-center gap-2 bg-[#60A5FA] text-white px-4 py-2 rounded-full font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            "{{ request('search') }}"
                        </span>
                    @endif
                    @if(request('category'))
                        <span class="inline-flex items-center gap-2 bg-[#93C5FD] text-[#1E3A8A] px-4 py-2 rounded-full font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            {{ request('category') }}
                        </span>
                    @endif
                    @if(request()->filled('min_price') || request()->filled('max_price'))
                        <span class="inline-flex items-center gap-2 bg-[#93C5FD] text-[#1E3A8A] px-4 py-2 rounded-full font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ request('min_price') ? 'Rp ' . number_format(request('min_price'), 0, ',', '.') : '' }}
                            {{ request('min_price') && request('max_price') ? ' - ' : '' }}
                            {{ request('max_price') ? 'Rp ' . number_format(request('max_price'), 0, ',', '.') : '' }}
                        </span>
                    @endif
                </div>
            </div>

            @if($products->count() > 0)
                <!-- Grid Layout for Search Results -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 mb-8">
                    @foreach($products as $product)
                    <div class="group">
                        <a href="{{ route('product.show', $product->id) }}" class="flex flex-col h-full bg-white rounded-2xl md:rounded-3xl p-4 border border-gray-100 hover:border-blue-400 transition-all duration-300 hover:shadow-xl hover:shadow-blue-200/50">
                            <div class="relative bg-gray-50 rounded-xl md:rounded-2xl overflow-hidden aspect-[4/5] mb-4 flex-shrink-0">
                                @if($product->stock < 5)
                                    <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider z-10 shadow-lg">Low Stock</span>
                                @else
                                    <span class="absolute top-3 left-3 bg-gradient-to-r from-blue-600 to-blue-400 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider z-10 shadow-lg">New</span>
                                @endif
                                
                                @if($product->images && $product->images->count() > 0)
                                    <img src="{{ Str::startsWith($product->images->first()->image, 'http') ? $product->images->first()->image : asset('storage/' . $product->images->first()->image) }}" 
                                         alt="{{ $product->name }}"
                                         onerror="this.src='https://placehold.co/400x500/f8fafc/3b82f6?text={{ urlencode($product->name) }}'"
                                         class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="px-2 flex-grow flex flex-col">
                                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2 flex-shrink-0">{{ $product->category->name }}</p>
                                <h3 class="font-heading font-bold text-lg md:text-xl text-gray-900 leading-tight mb-3 line-clamp-2 min-h-[3.5rem] flex-shrink-0">{{ $product->name }}</h3>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="font-bold text-lg text-blue-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-[#1a2332] rounded-3xl border-2 border-[#334155]">
                    <p class="text-slate-400 text-lg mb-6">No products found</p>
                    <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-[#60A5FA] to-[#93C5FD] hover:from-[#93C5FD] hover:to-[#60A5FA] text-white font-bold uppercase tracking-wider text-sm rounded-full transition-all shadow-lg">
                        View All
                    </a>
                </div>
            @endif
        </div>
    </section>
@endif

<!-- Promotional Banner -->
<section class="py-8 md:py-12 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl bg-white p-8 md:p-12 shadow-xl border-2 border-gray-200">
            <!-- Decorative Elements -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-100 rounded-full blur-3xl opacity-30"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-30"></div>
            
            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <!-- Left Content -->
                <div class="text-center md:text-left order-2 md:order-1">
                    <!-- Promo Badge -->
                    <div class="inline-flex items-center gap-2 bg-blue-100 px-4 py-2 rounded-full mb-4 border-2 border-blue-300">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-blue-900 font-bold text-sm uppercase tracking-wider">Limited Time Offer</span>
                    </div>
                    
                    <!-- Main Heading -->
                    <h2 class="font-heading font-black text-4xl md:text-5xl lg:text-6xl text-gray-900 mb-3 uppercase tracking-tight">
                        Mega Sale
                    </h2>
                    <p class="text-2xl md:text-3xl font-black text-orange-500 mb-4">
                        UP TO 50% OFF
                    </p>
                    <p class="text-gray-800 text-base md:text-lg font-semibold mb-6">
                        Premium footwear collection at unbeatable prices. Limited stock available!
                    </p>
                    
                    <!-- CTA Button -->
                    <a href="{{ route('sale') }}" 
                       class="group inline-flex items-center gap-3 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-lg uppercase tracking-wider rounded-full shadow-2xl transition-all duration-300 hover:scale-105">
                        <span>Shop Now</span>
                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <p class="text-xs text-gray-600 text-center md:text-left mt-3 font-semibold">Free shipping on orders over Rp 1.000.000</p>
                </div>
                
                <!-- Right Product Image -->
                <div class="order-1 md:order-2 flex items-center justify-center">
                    <div class="relative">
                        <!-- Product Image -->
                        <div class="relative transform hover:scale-105 transition-transform duration-500">
                            <img src="{{ asset('artifacts/promo_shoe_white_bg.png') }}" 
                                 alt="Premium Shoe" 
                                 class="w-full max-w-sm md:max-w-md"
                                 onerror="this.style.display='none'">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Best Seller Products - Dark Theme -->
<section id="bestseller" class="py-16 md:py-20 bg-[#0f172a]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8 md:mb-10">
            <div>
                <h2 class="font-heading font-black text-3xl md:text-4xl uppercase tracking-tighter text-white">Best Sellers</h2>
                <p class="text-[#93C5FD] mt-2">Top picks from our collection</p>
            </div>
            <div class="hidden md:block w-32 h-1 bg-[#60A5FA] rounded-full"></div>
        </div>

        <!-- Horizontal Scroll Container -->
        <div class="relative">
            <div class="flex gap-4 md:gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide">
                @foreach(App\Models\Product::with('images', 'category')->inRandomOrder()->take(8)->get() as $product)
                <div class="flex-none w-64 md:w-72 snap-start group">
                    <a href="{{ route('product.show', $product->id) }}" class="flex flex-col h-full bg-white rounded-2xl md:rounded-3xl p-4 border border-gray-100 hover:border-blue-400 transition-all duration-300 hover:shadow-xl hover:shadow-blue-200/50">
                        <!-- Image -->
                        <div class="relative bg-gray-50 rounded-xl md:rounded-2xl overflow-hidden aspect-[4/5] mb-4 flex-shrink-0">
                            <span class="absolute top-3 left-3 bg-gradient-to-r from-blue-600 to-blue-400 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider z-10 shadow-lg">Best Seller</span>
                            
                            @if($product->images && $product->images->count() > 0)
                                <img src="{{ Str::startsWith($product->images->first()->image, 'http') ? $product->images->first()->image : asset('storage/' . $product->images->first()->image) }}" 
                                     alt="{{ $product->name }}"
                                     onerror="this.src='https://placehold.co/400x500/f8fafc/3b82f6?text={{ urlencode($product->name) }}'"
                                     class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Info -->
                        <div class="px-2 flex-grow flex flex-col">
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2 flex-shrink-0">{{ $product->category->name }}</p>
                            <h3 class="font-heading font-bold text-lg md:text-xl text-gray-900 leading-tight mb-3 line-clamp-2 min-h-[3.5rem] flex-shrink-0">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="font-bold text-lg text-blue-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@if(!(request('search') || request('category') || request()->filled('min_price') || request()->filled('max_price')))
<!-- All Products - Dark Theme -->
<section id="collection" class="py-16 md:py-20 bg-gradient-to-br from-[#1e293b] via-[#0f172a] to-[#1e293b]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8 md:mb-10">
            <div>
                <h2 class="font-heading font-black text-3xl md:text-4xl uppercase tracking-tighter text-white">All Products</h2>
                <p class="text-[#93C5FD] mt-2">Discover our complete collection</p>
            </div>
            <a href="{{ route('products.index') }}" class="group flex items-center gap-2 text-[#60A5FA] font-bold uppercase tracking-wider text-sm hover:text-white transition-colors">
                View All
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        @if($products->count() > 0)
            <!-- Horizontal Scroll Container -->
            <div class="flex gap-4 md:gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide mb-8">
                @foreach($products as $product)
                <div class="flex-none w-64 md:w-72 snap-start group">
                    <div class="flex flex-col h-full bg-white rounded-2xl md:rounded-3xl p-4 border border-gray-100 hover:border-blue-400 transition-all duration-300 hover:shadow-xl hover:shadow-blue-200/50">
                        <div class="relative bg-gray-50 rounded-xl md:rounded-2xl overflow-hidden aspect-[4/5] mb-4 flex-shrink-0">
                            @if($product->stock < 5)
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider z-10 shadow-lg">Low Stock</span>
                            @else
                                <span class="absolute top-3 left-3 bg-gradient-to-r from-blue-600 to-blue-400 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider z-10 shadow-lg">New</span>
                            @endif
                            
                            @if($product->images && $product->images->count() > 0)
                                <img src="{{ Str::startsWith($product->images->first()->image, 'http') ? $product->images->first()->image : asset('storage/' . $product->images->first()->image) }}" 
                                     alt="{{ $product->name }}"
                                     onerror="this.src='https://placehold.co/400x500/f8fafc/3b82f6?text={{ urlencode($product->name) }}'"
                                     class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="px-2 flex-grow flex flex-col">
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2 flex-shrink-0">{{ $product->category->name }}</p>
                            <h3 class="font-heading font-bold text-lg md:text-xl text-gray-900 leading-tight mb-3 line-clamp-2 min-h-[3.5rem] flex-shrink-0">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="font-bold text-lg text-blue-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('product.show', $product->id) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold uppercase rounded-lg transition-colors shadow-lg">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-[#1a2332] rounded-3xl border-2 border-[#334155]">
                <p class="text-slate-400 text-lg mb-6">No products found</p>
                <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-[#60A5FA] to-[#93C5FD] hover:from-[#93C5FD] hover:to-[#60A5FA] text-white font-bold uppercase tracking-wider text-sm rounded-full transition-all shadow-lg">
                    View All
                </a>
            </div>
        @endif
    </div>
</section>
@endif
    {{-- Normal order when NO search/filter: Best Sellers FIRST, then All Products --}}

<!-- Best Seller Products - Dark Theme -->
<section id="bestseller" class="py-16 md:py-20 bg-[#0f172a]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8 md:mb-10">
            <div>
                <h2 class="font-heading font-black text-3xl md:text-4xl uppercase tracking-tighter text-white">Best Sellers</h2>
                <p class="text-[#93C5FD] mt-2">Top picks from our collection</p>
            </div>
            <div class="hidden md:block w-32 h-1 bg-[#60A5FA] rounded-full"></div>
        </div>

        <!-- Horizontal Scroll Container -->
        <div class="relative">
            <div class="flex gap-4 md:gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide">
                @foreach(App\Models\Product::with('images', 'category')->inRandomOrder()->take(8)->get() as $product)
                <div class="flex-none w-64 md:w-72 snap-start group">
                    <a href="{{ route('product.show', $product->id) }}" class="flex flex-col h-full bg-white rounded-2xl md:rounded-3xl p-4 border border-gray-100 hover:border-blue-400 transition-all duration-300 hover:shadow-xl hover:shadow-blue-200/50">
                        <!-- Image -->
                        <div class="relative bg-gray-50 rounded-xl md:rounded-2xl overflow-hidden aspect-[4/5] mb-4 flex-shrink-0">
                            <span class="absolute top-3 left-3 bg-gradient-to-r from-blue-600 to-blue-400 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider z-10 shadow-lg">Best Seller</span>
                            
                            @if($product->images && $product->images->count() > 0)
                                <img src="{{ Str::startsWith($product->images->first()->image, 'http') ? $product->images->first()->image : asset('storage/' . $product->images->first()->image) }}" 
                                     alt="{{ $product->name }}"
                                     onerror="this.src='https://placehold.co/400x500/f8fafc/3b82f6?text={{ urlencode($product->name) }}'"
                                     class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Info -->
                        <div class="px-2 flex-grow flex flex-col">
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2 flex-shrink-0">{{ $product->category->name }}</p>
                            <h3 class="font-heading font-bold text-lg md:text-xl text-gray-900 leading-tight mb-3 line-clamp-2 min-h-[3.5rem] flex-shrink-0">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="font-bold text-lg text-blue-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- All Products - Dark Theme -->
<section id="collection" class="py-16 md:py-20 bg-gradient-to-br from-[#1e293b] via-[#0f172a] to-[#1e293b]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8 md:mb-10">
            <div>
                <h2 class="font-heading font-black text-3xl md:text-4xl uppercase tracking-tighter text-white">All Products</h2>
                <p class="text-[#93C5FD] mt-2">Discover our complete collection</p>
            </div>
            <a href="{{ route('products.index') }}" class="group flex items-center gap-2 text-[#60A5FA] font-bold uppercase tracking-wider text-sm hover:text-white transition-colors">
                View All
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        @if($products->count() > 0)
            <!-- Horizontal Scroll Container -->
            <div class="flex gap-4 md:gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide mb-8">
                @foreach($products as $product)
                <div class="flex-none w-64 md:w-72 snap-start group">
                    <div class="flex flex-col h-full bg-white rounded-2xl md:rounded-3xl p-4 border border-gray-100 hover:border-blue-400 transition-all duration-300 hover:shadow-xl hover:shadow-blue-200/50">
                        <div class="relative bg-gray-50 rounded-xl md:rounded-2xl overflow-hidden aspect-[4/5] mb-4 flex-shrink-0">
                            @if($product->stock < 5)
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider z-10 shadow-lg">Low Stock</span>
                            @else
                                <span class="absolute top-3 left-3 bg-gradient-to-r from-blue-600 to-blue-400 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider z-10 shadow-lg">New</span>
                            @endif
                            
                            @if($product->images && $product->images->count() > 0)
                                <img src="{{ Str::startsWith($product->images->first()->image, 'http') ? $product->images->first()->image : asset('storage/' . $product->images->first()->image) }}" 
                                     alt="{{ $product->name }}"
                                     onerror="this.src='https://placehold.co/400x500/f8fafc/3b82f6?text={{ urlencode($product->name) }}'"
                                     class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="px-2 flex-grow flex flex-col">
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-2 flex-shrink-0">{{ $product->category->name }}</p>
                            <h3 class="font-heading font-bold text-lg md:text-xl text-gray-900 leading-tight mb-3 line-clamp-2 min-h-[3.5rem] flex-shrink-0">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="font-bold text-lg text-blue-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('product.show', $product->id) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold uppercase rounded-lg transition-colors shadow-lg">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-[#1a2332] rounded-3xl border-2 border-[#334155]">
                <p class="text-slate-400 text-lg mb-6">No products found</p>
                <a href="{{ route('home') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-[#60A5FA] to-[#93C5FD] hover:from-[#93C5FD] hover:to-[#60A5FA] text-white font-bold uppercase tracking-wider text-sm rounded-full transition-all shadow-lg">
                    View All
                </a>
            </div>
        @endif
    </div>
</section>



<style>
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }
    .animate-float { animation: float 6s ease-in-out infinite; }
    .animate-float-delayed { animation: float 6s ease-in-out 2s infinite; }
    .animate-float-slow { animation: float 8s ease-in-out 1s infinite; }
    html, body { overflow-x: hidden !important; width: 100%; max-width: 100vw; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 1s ease-out; }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
</style>
@endsection
