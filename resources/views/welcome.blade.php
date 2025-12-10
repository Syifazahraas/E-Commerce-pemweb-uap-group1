<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} - Marketplace Terpercaya</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Tailwind CSS v4.0.7 - inline styles tetap sama */
                /* ... (copy dari template asli) ... */
            </style>
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18]">
        <!-- Header Navigation -->
        <header class="w-full bg-white dark:bg-[#161615] shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <nav class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-[#f53003] dark:text-[#FF4433]">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>

                    <!-- Auth Links -->
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-[#fff2f2] to-white dark:from-[#1D0002] dark:to-[#0a0a0a] overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 lg:py-24">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Text Content -->
                    <div class="space-y-6">
                        <h1 class="text-4xl lg:text-6xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                            Temukan Produk Terbaik di Marketplace Kami
                        </h1>
                        <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                            Belanja berbagai produk berkualitas dari seller terpercaya dengan harga terbaik
                        </p>
                        <div class="flex gap-4">
                            <a href="#products" class="inline-block px-6 py-3 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] rounded-sm hover:bg-black dark:hover:bg-white transition-colors">
                                Mulai Belanja
                            </a>
                            <a href="#categories" class="inline-block px-6 py-3 border border-[#19140035] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm hover:border-[#1915014a] dark:hover:border-[#62605b] transition-colors">
                                Lihat Kategori
                            </a>
                        </div>
                    </div>

                    <!-- Laravel Logo (dari template asli) -->
                    <div class="relative">
                        <!-- SVG Logo tetap sama seperti template asli -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section id="categories" class="py-16 bg-white dark:bg-[#161615]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Kategori Populer
                    </h2>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        Jelajahi berbagai kategori produk pilihan
                    </p>
                </div>

                @if($categories->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                        @foreach($categories as $category)
                            <a href="{{ route('products.category', $category->slug) }}"
                               class="group p-6 bg-[#FDFDFC] dark:bg-[#0a0a0a] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#f53003] dark:hover:border-[#FF4433] transition-all duration-300 text-center">
                                <div class="mb-4">
                                    @if($category->icon)
                                        <img src="{{ Storage::url($category->icon) }}" alt="{{ $category->name }}" class="w-16 h-16 mx-auto object-cover rounded-full">
                                    @else
                                        <div class="w-16 h-16 mx-auto bg-[#f53003] dark:bg-[#FF4433] rounded-full flex items-center justify-center">
                                            <span class="text-2xl text-white font-bold">{{ substr($category->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] group-hover:text-[#f53003] dark:group-hover:text-[#FF4433] transition-colors">
                                    {{ $category->name }}
                                </h3>
                                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mt-1">
                                    {{ $category->products_count }} produk
                                </p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">Belum ada kategori tersedia</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- Featured Products Section -->
        <section id="products" class="py-16 bg-[#FDFDFC] dark:bg-[#0a0a0a]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl lg:text-4xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                        Produk Terbaru
                    </h2>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">
                        Temukan produk terbaru dari seller kami
                    </p>
                </div>

                @if($featuredProducts->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($featuredProducts as $product)
                            <div class="group bg-white dark:bg-[#161615] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] overflow-hidden hover:shadow-lg transition-all duration-300">
                                <!-- Product Image -->
                                <div class="relative aspect-square overflow-hidden">
                                    @php
                                        $thumbnail = $product->productImages->first();
                                    @endphp
                                    @if($thumbnail)
                                        <img src="{{ Storage::url($thumbnail->image) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <span class="text-gray-400 text-4xl">📦</span>
                                        </div>
                                    @endif

                                    <!-- Stock Badge -->
                                    @if($product->stock <= 10)
                                        <div class="absolute top-2 right-2 px-2 py-1 bg-red-500 text-white text-xs rounded">
                                            Stok: {{ $product->stock }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="p-4">
                                    <p class="text-xs text-[#706f6c] dark:text-[#A1A09A] mb-1">
                                        {{ $product->productCategory->name ?? 'Kategori' }}
                                    </p>
                                    <h3 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2 line-clamp-2 group-hover:text-[#f53003] dark:group-hover:text-[#FF4433] transition-colors">
                                        {{ $product->name }}
                                    </h3>
                                    <div class="flex items-baseline gap-2 mb-3">
                                        <span class="text-lg font-bold text-[#f53003] dark:text-[#FF4433]">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-[#706f6c] dark:text-[#A1A09A] mb-3">
                                        <span>{{ $product->store->name ?? 'Toko' }}</span>
                                    </div>
                                    <a href="{{ route('products.show', $product->slug) }}"
                                       class="block w-full text-center px-4 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] rounded-sm hover:bg-black dark:hover:bg-white transition-colors text-sm">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-[#706f6c] dark:text-[#A1A09A]">Belum ada produk tersedia</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white dark:bg-[#161615] border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1">
                        <h3 class="text-lg font-bold text-[#f53003] dark:text-[#FF4433] mb-4">
                            {{ config('app.name', 'Laravel') }}
                        </h3>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Marketplace terpercaya untuk berbagai kebutuhan Anda
                        </p>
                    </div>
                    <div>
                        <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Layanan</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433]">Cara Belanja</a></li>
                            <li><a href="#" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433]">Jadi Seller</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Bantuan</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433]">FAQ</a></li>
                            <li><a href="#" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433]">Kontak</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Tentang</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433]">Tentang Kami</a></li>
                            <li><a href="#" class="text-[#706f6c] dark:text-[#A1A09A] hover:text-[#f53003] dark:hover:text-[#FF4433]">Kebijakan Privasi</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-12 pt-8 border-t border-[#e3e3e0] dark:border-[#3E3E3A] text-center text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>
            </div>
        </footer>
    </body>
</html>
