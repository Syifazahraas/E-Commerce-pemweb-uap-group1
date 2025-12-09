@extends('layouts.buyer')

@section('title', 'Product Catalog - WALKUNO')

@section('content')

<!-- Catalog Header -->
<section class="relative bg-[#0f172a] py-20 px-4 sm:px-6 lg:px-8 border-b border-gray-800">
    <div class="container mx-auto">
        <h1 class="font-heading font-black text-4xl md:text-5xl lg:text-6xl text-white uppercase tracking-tighter mb-4">
            Product <span class="text-[#60A5FA]">Catalog</span>
        </h1>
        <p class="text-xl text-gray-400 max-w-2xl">
            Explore our complete collection of premium footwear.
        </p>
    </div>
</section>

<!-- Product Grid -->
<section class="py-12 bg-gradient-to-br from-[#1e293b] via-[#0f172a] to-[#1e293b] min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Products -->
        @if($products->count() > 0)
            <!-- Extra Dense Grid for Compact Catalog View -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-3 mb-8">
                @foreach($products as $product)
                <div class="group">
                    <a href="{{ route('product.show', $product->id) }}" class="block h-full bg-gradient-to-br from-[#1a2332] to-[#0f172a] rounded-xl md:rounded-2xl p-3 border border-[#334155] hover:border-[#60A5FA] transition-all duration-300 hover:shadow-xl hover:shadow-[#60A5FA]/20 group-hover:-translate-y-1">
                        <!-- Image -->
                        <div class="relative bg-[#0a0f1a] rounded-lg md:rounded-xl overflow-hidden aspect-[4/5] mb-3">
                            @if($product->stock < 5)
                                <span class="absolute top-2 left-2 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider z-10 shadow-lg">Low Stock</span>
                            @elseif($product->created_at->diffInDays(now()) < 30)
                                <span class="absolute top-2 left-2 bg-gradient-to-r from-[#1E3A8A] to-[#60A5FA] text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider z-10 shadow-lg">New</span>
                            @endif
                            
                            @if($product->images && $product->images->count() > 0)
                                <img src="{{ Str::startsWith($product->images->first()->image, 'http') ? $product->images->first()->image : asset('storage/' . $product->images->first()->image) }}" 
                                     alt="{{ $product->name }}"
                                     loading="lazy"
                                     onerror="this.src='https://placehold.co/400x500/0f172a/60A5FA?text={{ urlencode($product->name) }}'"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-[#0a0f1a]">
                                    <svg class="w-10 h-10 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Info -->
                        <div class="px-1">
                            <p class="text-[9px] font-bold text-[#60A5FA] uppercase tracking-widest mb-1 truncate">{{ $product->category->name }}</p>
                            <h3 class="font-heading font-bold text-sm text-white leading-tight mb-2 line-clamp-2 h-9 group-hover:text-[#93C5FD] transition-colors">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-auto">
                                <span class="font-bold text-sm md:text-base text-[#93C5FD]">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <p class="text-gray-400 text-xl">No products found.</p>
                <a href="{{ route('home') }}" class="text-[#60A5FA] hover:text-white mt-4 inline-block">Back to Home</a>
            </div>
        @endif
        
    </div>
</section>

@endsection
