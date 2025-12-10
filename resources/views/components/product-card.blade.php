@props(['product'])

<div class="group relative flex flex-col h-full">
    <a href="{{ route('product.show', $product->id) }}" class="block overflow-hidden rounded-lg bg-gray-100 mb-3 relative aspect-[4/5]">
        @if($product->images && $product->images->count() > 0)
            <img src="{{ Str::startsWith($product->images->first()->image, 'http') ? $product->images->first()->image : asset('storage/' . $product->images->first()->image) }}" 
                 alt="{{ $product->name }}"
                 class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-500 ease-out">
        @else
            <div class="h-full w-full flex items-center justify-center text-gray-300">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        @endif

        <!-- Badges -->
        @if($product->stock < 5 && $product->stock > 0)
            <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded-sm uppercase tracking-wider shadow-sm">Low Stock</span>
        @elseif($product->created_at->diffInDays(now()) < 7)
            <span class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded-sm uppercase tracking-wider shadow-sm">New</span>
        @endif

        <!-- Quick Action -->
        <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 hidden lg:block">
            <button class="w-full bg-white text-black font-bold text-xs py-3 uppercase tracking-widest hover:bg-black hover:text-white transition-colors shadow-lg">
                View Details
            </button>
        </div>
    </a>
    
    <div>
        <h3 class="text-sm font-bold text-gray-900 mb-1 truncate group-hover:text-gray-600 transition-colors">
            <a href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a>
        </h3>
        <p class="text-xs text-gray-500 mb-2">{{ $product->category->name ?? 'Shoe' }}</p>
        <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
    </div>
</div>
