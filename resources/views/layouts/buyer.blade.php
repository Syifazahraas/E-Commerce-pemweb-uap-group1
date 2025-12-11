<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WALKUNO - Premium Footwear')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600|outfit:500,700,900&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Outfit', sans-serif; }
        
        /* Floating animation for shoes */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(-5deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-white text-slate-900 flex flex-col min-h-screen" x-data="{ 
    mobileMenu: false, 
    searchModal: false,
    activeSection: 'store'
}" x-init="
    // Intersection Observer for active section tracking
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && entry.intersectionRatio >= 0.3) {
                $data.activeSection = entry.target.id || 'store';
                console.log('Active section changed to:', $data.activeSection);
            }
        });
    }, { threshold: [0, 0.3, 0.5, 0.7, 1.0] });
    
    // Observe sections after DOM is ready
    setTimeout(() => {
        const sections = ['collection', 'bestseller'];
        sections.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                observer.observe(element);
                console.log('Observing section:', id);
            } else {
                console.log('Section not found:', id);
            }
        });
    }, 500);
">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-[#1E3A8A] to-[#60A5FA] text-white text-xs text-center py-2.5 font-bold uppercase tracking-widest">
        ⚡ Free Shipping on orders over Rp 1.000.000
    </div>

    <!-- Main Header -->
    <header class="sticky top-0 z-50 bg-white shadow-md border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/walkuno-logo.jpg') }}" alt="WalkUno Logo" class="h-10 w-auto object-contain">
                    <div class="hidden sm:block">
                        <span class="font-heading font-black text-2xl text-[#1E3A8A] tracking-tight">WALK</span><span class="font-heading font-black text-2xl text-[#60A5FA]">UNO</span>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" 
                       @click="activeSection = 'store'"
                       :class="activeSection === 'store' || activeSection === '' ? 'text-[#60A5FA] border-b-2 border-[#60A5FA]' : ''"
                       class="text-sm font-bold text-slate-700 hover:text-[#60A5FA] hover:border-b-2 hover:border-[#60A5FA] transition-all uppercase tracking-wide pb-1">
                        Store
                    </a>
                    <a href="{{ route('home') }}#collection" 
                       @click="activeSection = 'collection'"
                       :class="activeSection === 'collection' ? 'text-[#60A5FA] border-b-2 border-[#60A5FA]' : ''"
                       class="text-sm font-bold text-slate-700 hover:text-[#60A5FA] hover:border-b-2 hover:border-[#60A5FA] transition-all uppercase tracking-wide pb-1">
                        Collection
                    </a>
                    <a href="{{ route('home') }}#bestseller" 
                       @click="activeSection = 'bestseller'"
                       :class="activeSection === 'bestseller' ? 'text-[#60A5FA] border-b-2 border-[#60A5FA]' : ''"
                       class="text-sm font-bold text-slate-700 hover:text-[#60A5FA] hover:border-b-2 hover:border-[#60A5FA] transition-all uppercase tracking-wide pb-1">
                        Best Sellers
                    </a>
                </nav>

                <!-- Right Section -->
                <div class="flex items-center gap-3 md:gap-4">
                    <!-- Category Filter Dropdown -->
                    <div class="relative hidden md:block" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-2 px-4 py-2 bg-[#F8F8FF] hover:bg-[#93C5FD]/20 rounded-full transition-colors group border border-gray-200 hover:border-[#60A5FA]">
                            <svg class="w-4 h-4 text-slate-600 group-hover:text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="text-xs font-bold text-slate-700 group-hover:text-[#60A5FA]">{{ request('category') ?? 'Category' }}</span>
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-2xl py-2 border border-gray-100 z-50">
                            <a href="{{ route('home') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-[#F8F8FF] hover:text-[#60A5FA] transition-colors {{ !request('category') ? 'bg-[#F8F8FF] text-[#60A5FA] font-bold' : '' }}">
                                All Categories
                            </a>
                            @foreach(['Sneakers', 'Running Shoes', 'Loafers', 'Boots', 'Sandals'] as $cat)
                                <a href="{{ route('home', ['category' => $cat]) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-[#F8F8FF] hover:text-[#60A5FA] transition-colors {{ request('category') == $cat ? 'bg-[#F8F8FF] text-[#60A5FA] font-bold' : '' }}">
                                    {{ $cat }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter Dropdown -->
                    <div class="relative hidden md:block" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-2 px-4 py-2 bg-[#F8F8FF] hover:bg-[#93C5FD]/20 rounded-full transition-colors group border border-gray-200 hover:border-[#60A5FA]">
                            <svg class="w-4 h-4 text-slate-600 group-hover:text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs font-bold text-slate-700 group-hover:text-[#60A5FA]">Price</span>
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-2xl p-4 border border-gray-100 z-50">
                            <form action="{{ route('home') }}" method="GET">
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-xs font-bold text-slate-600 mb-2 block">Min Price</label>
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" 
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#60A5FA]">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-600 mb-2 block">Max Price</label>
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="10000000" 
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#60A5FA]">
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 bg-gradient-to-r from-[#60A5FA] to-[#1E3A8A] text-white py-2 rounded-lg text-xs font-bold uppercase hover:shadow-lg transition-all">
                                            Apply
                                        </button>
                                        <a href="{{ route('home', request('category') ? ['category' => request('category')] : []) }}" 
                                           class="px-4 py-2 border border-gray-200 text-slate-600 rounded-lg text-xs font-bold uppercase hover:bg-gray-50 transition-colors">
                                            Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Search Icon (Desktop) -->
                    <button @click="searchModal = true" 
                            type="button"
                            class="hidden md:flex items-center justify-center w-10 h-10 text-slate-600 hover:text-[#60A5FA] rounded-full transition-colors"
                            aria-label="Search products">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- Cart -->
                    <a href="{{ route('cart') }}" class="relative p-2 text-slate-600 hover:text-[#60A5FA] transition-colors group">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-[#60A5FA] text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-lg animate-pulse">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    <!-- User Menu -->
                    @auth
                        <div class="relative hidden md:block" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" 
                                    class="flex items-center gap-2 px-4 py-2 bg-[#F8F8FF] hover:bg-[#93C5FD]/20 rounded-full transition-colors group">
                                <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-200">
                                    @if(Auth::user()->buyer && Auth::user()->buyer->profile_picture)
                                        <img src="{{ asset('storage/' . Auth::user()->buyer->profile_picture) }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-[#60A5FA] to-[#1E3A8A] flex items-center justify-center text-white text-xs font-bold">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-[#60A5FA]">{{ Str::limit(Auth::user()->name, 10) }}</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-2xl py-2 border border-gray-100 overflow-hidden z-50">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-xs text-slate-500 uppercase tracking-wide">Signed in as</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-[#F8F8FF] hover:text-[#60A5FA] transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    My Profile
                                </a>
                                <a href="{{ route('transaction.history') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-[#F8F8FF] hover:text-[#60A5FA] transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    My Orders
                                </a>
                                
                                {{-- Store Navigation - Create or Go to Store --}}
                                @if(Auth::user()->store)
                                    {{-- User has a store - link to dashboard --}}
                                    <a href="{{ route('seller.dashboard') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-[#F8F8FF] hover:text-[#60A5FA] transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        My Store
                                    </a>
                                @else
                                    {{-- User doesn't have a store - link to create --}}
                                    <a href="{{ route('seller.store.create') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-[#F8F8FF] hover:text-[#60A5FA] transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                        Create Store
                                    </a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-100 mt-1 pt-1">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 font-bold transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden md:inline-flex items-center gap-2 bg-gradient-to-r from-[#60A5FA] to-[#1E3A8A] text-white px-6 py-2.5 text-xs font-bold uppercase tracking-wider rounded-full hover:shadow-lg hover:shadow-[#60A5FA]/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            Login
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-slate-600 hover:text-[#60A5FA]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="md:hidden border-t border-gray-100 bg-white shadow-lg">
            <nav class="container mx-auto px-4 py-4 space-y-4">
                <!-- Navigation Links -->
                <div class="space-y-1">
                    <a href="{{ route('home') }}" class="block px-4 py-3 text-sm font-bold text-slate-700 hover:bg-[#F8F8FF] hover:text-[#60A5FA] rounded-lg transition-colors uppercase tracking-wide">Store</a>
                    <a href="#collection" class="block px-4 py-3 text-sm font-bold text-slate-700 hover:bg-[#F8F8FF] hover:text-[#60A5FA] rounded-lg transition-colors uppercase tracking-wide">Collection</a>
                    <a href="{{ route('cart') }}" class="block px-4 py-3 text-sm font-bold text-slate-700 hover:bg-[#F8F8FF] hover:text-[#60A5FA] rounded-lg transition-colors uppercase tracking-wide">
                        Cart @if(session('cart')) ({{ count(session('cart')) }}) @endif
                    </a>
                    @auth
                        <a href="{{ route('transaction.history') }}" class="block px-4 py-3 text-sm font-bold text-slate-700 hover:bg-[#F8F8FF] hover:text-[#60A5FA] rounded-lg transition-colors uppercase tracking-wide">My Orders</a>
                    @endauth
                    
                    <!-- Mobile Search Button -->
                    <button @click="searchModal = true; mobileMenu = false" class="w-full flex items-center gap-2 px-4 py-3 text-sm font-bold bg-gradient-to-r from-[#60A5FA] to-[#1E3A8A] text-white rounded-lg transition-all uppercase tracking-wide">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search Products
                    </button>
                </div>

                <!-- Filters Section -->
                <div class="border-t border-gray-100 pt-4 space-y-4">
                    <!-- Category Filter -->
                    <div x-data="{ catOpen: false }">
                        <button @click="catOpen = !catOpen" class="w-full flex items-center justify-between px-4 py-3 bg-[#F8F8FF] rounded-lg text-sm font-bold text-slate-700">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                {{ request('category') ?? 'Category' }}
                            </span>
                            <svg class="w-4 h-4 transition-transform" :class="catOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="catOpen" x-transition class="mt-2 space-y-1 pl-4">
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-slate-700 hover:text-[#60A5FA] {{ !request('category') ? 'text-[#60A5FA] font-bold' : '' }}">All Categories</a>
                            @foreach(['Sneakers', 'Running Shoes', 'Loafers', 'Boots', 'Sandals'] as $cat)
                                <a href="{{ route('home', ['category' => $cat]) }}" class="block px-4 py-2 text-sm text-slate-700 hover:text-[#60A5FA] {{ request('category') == $cat ? 'text-[#60A5FA] font-bold' : '' }}">
                                    {{ $cat }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div x-data="{ priceOpen: false }">
                        <button @click="priceOpen = !priceOpen" class="w-full flex items-center justify-between px-4 py-3 bg-[#F8F8FF] rounded-lg text-sm font-bold text-slate-700">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Price Filter
                            </span>
                            <svg class="w-4 h-4 transition-transform" :class="priceOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="priceOpen" x-transition class="mt-2 pl-4">
                            <form action="{{ route('home') }}" method="GET" class="space-y-3 px-4 py-3 bg-gray-50 rounded-lg">
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                <div>
                                    <label class="text-xs font-bold text-slate-600 mb-1 block">Min Price</label>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" 
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#60A5FA]">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-600 mb-1 block">Max Price</label>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="10000000" 
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#60A5FA]">
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="flex-1 bg-gradient-to-r from-[#60A5FA] to-[#1E3A8A] text-white py-2 rounded-lg text-xs font-bold uppercase">
                                        Apply
                                    </button>
                                    <a href="{{ route('home', request('category') ? ['category' => request('category')] : []) }}" 
                                       class="px-4 py-2 border border-gray-200 text-slate-600 rounded-lg text-xs font-bold uppercase">
                                        Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Auth Actions -->
                <div class="border-t border-gray-100 pt-4">
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-50 rounded-lg transition-colors uppercase tracking-wide">Sign Out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-3 text-sm font-bold bg-gradient-to-r from-[#60A5FA] to-[#1E3A8A] text-white rounded-lg transition-all uppercase tracking-wide text-center">Login</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#1E3A8A] text-white pt-16 pb-8">
        <div class="container-custom">
            <!-- Main Footer Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                
                <!-- Company Info -->
                <div>
                    <h3 class="font-heading font-black text-2xl mb-6 text-[#93C5FD]">WALKUNO</h3>
                    <p class="text-[#93C5FD] text-sm leading-relaxed mb-6">
                        The premier destination for professional sports equipment. Elevate your performance with our curated selection of high-end footwear.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-[#93C5FD] hover:bg-white text-[#1E3A8A] rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-[#93C5FD] hover:bg-white text-[#1E3A8A] rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-[#93C5FD] hover:bg-white text-[#1E3A8A] rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Shop Links -->
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-widest mb-6 text-[#93C5FD]">Shop</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-[#93C5FD] transition-colors">New Arrivals</a></li>
                        <li><a href="{{ route('home') }}" class="hover:text-[#93C5FD] transition-colors">Best Sellers</a></li>
                        <li><a href="{{ route('home', ['category' => 'Sneakers']) }}" class="hover:text-[#93C5FD] transition-colors">Sneakers</a></li>
                        <li><a href="{{ route('home', ['category' => 'Running Shoes']) }}" class="hover:text-[#93C5FD] transition-colors">Running Shoes</a></li>
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">Sale</a></li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-widest mb-6 text-[#93C5FD]">Customer Service</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">Shipping & Delivery</a></li>
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">Returns & Exchanges</a></li>
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">Size Guide</a></li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-widest mb-6 text-[#93C5FD]">Company</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">Store Locator</a></li>
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">Sustainability</a></li>
                        <li><a href="#" class="hover:text-[#93C5FD] transition-colors">Press</a></li>
                    </ul>
                </div>
            </div>

            <!-- Payment Methods & Trust Badges -->
            <div class="border-t border-[#93C5FD]/30 pt-8 mb-8">
                <div class="flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-[#93C5FD] mb-3">Secure Payment</p>
                        <div class="flex gap-2">
                            <div class="h-8 bg-white rounded px-3 flex items-center text-xs font-bold text-[#1E3A8A]">VISA</div>
                            <div class="h-8 bg-white rounded px-3 flex items-center text-xs font-bold text-[#1E3A8A]">MC</div>
                            <div class="h-8 bg-white rounded px-3 flex items-center text-xs font-bold text-[#1E3A8A]">BCA</div>
                            <div class="h-8 bg-white rounded px-3 flex items-center text-xs font-bold text-[#1E3A8A]">OVO</div>
                        </div>
                    </div>
                    <div class="text-xs text-[#93C5FD] space-y-2">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            SSL Secure Checkout
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                            </svg>
                            Free Shipping Over Rp 1.000.000
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-[#93C5FD]/30 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-[#93C5FD] gap-4">
                <p class="flex items-center justify-center md:justify-start gap-1">&copy; 2025 WalkUno. All rights reserved. Made with <svg class="w-4 h-4 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg> for Shoe Lovers.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Live Search Modal (Positioned at end of body for proper z-index) -->
    <div x-show="searchModal" 
         @click.self="searchModal = false"
         @keydown.escape.window="searchModal = false"
         class="fixed inset-0 z-[99999] isolate bg-black/60 backdrop-blur-sm flex items-start justify-center p-4 pt-24"
         style="display: none; z-index: 99999 !important;"
         x-transition>
        
        <div class="w-full max-w-3xl">
            <!-- Search Box -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-4 border-[#60A5FA]"
                 x-data="{
                    searchQuery: '',
                    searchResults: [],
                    loading: false,
                    async search() {
                        if (this.searchQuery.length < 2) {
                            this.searchResults = [];
                            return;
                        }
                        this.loading = true;
                        try {
                            const response = await fetch(`{{ route('api.search') }}?q=${encodeURIComponent(this.searchQuery)}`);
                            this.searchResults = await response.json();
                        } catch (error) {
                            console.error('Search error:', error);
                        }
                        this.loading = false;
                    }
                 }"
                 x-init="setTimeout(() => $refs.searchInput && $refs.searchInput.focus(), 150)"
                 @keydown.escape.window="searchModal = false">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-[#60A5FA] to-[#1E3A8A] px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <h3 class="text-xl font-black text-white uppercase">Search Products</h3>
                    </div>
                    <button @click="searchModal = false" class="p-2 hover:bg-white/20 rounded-lg transition-colors">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Search Input -->
                <div class="px-6 pt-6 pb-12 bg-gray-50">
                    <input type="text" 
                           x-ref="searchInput"
                           x-model="searchQuery"
                           @input.debounce.300ms="search()"
                           @keydown.enter="if(searchQuery.length >= 2) { window.location.href = '{{ route('home') }}?search=' + encodeURIComponent(searchQuery); }"
                           placeholder="Type product name (e.g. Air Max, Boot, Sandal)..."
                           class="w-full pl-6 pr-6 py-5 bg-white border-3 border-[#60A5FA] rounded-xl text-lg font-bold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#1E3A8A] focus:ring-4 focus:ring-[#60A5FA]/30 shadow-lg">
                    
                    <!-- Helper text - Centered with generous spacing -->
                    <p class="mt-4 mb-6 text-sm text-slate-600 flex items-center justify-center gap-2 text-center">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Type at least 2 characters. Press Enter to see all matching products in catalog</span>
                    </p>
                </div>

                <!-- Results -->
                <div class="max-h-96 overflow-y-auto bg-white">
                    <!-- Loading -->
                    <div x-show="loading" class="p-8 text-center bg-gray-50">
                        <svg class="animate-spin h-10 w-10 mx-auto text-[#60A5FA]" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-3 text-base font-bold text-slate-700">Searching...</p>
                    </div>

                    <!-- Results List -->
                    <template x-if="!loading && searchResults.length > 0">
                        <div>
                            <div class="px-4 py-3 bg-[#F8F8FF] border-b-2 border-[#60A5FA]">
                                <p class="text-sm font-bold text-slate-700">Found <span class="text-[#60A5FA]" x-text="searchResults.length"></span> products</p>
                            </div>
                            <template x-for="product in searchResults" :key="product.id">
                                <a :href="product.url" class="flex items-center gap-4 p-4 hover:bg-[#F8F8FF] transition-colors border-b border-gray-200 last:border-0">
                                    <img :src="product.image || '/images/placeholder.png'" 
                                         :alt="product.name"
                                         class="w-20 h-20 object-cover rounded-xl bg-gray-100 border-2 border-gray-200"
                                         onerror="this.src='https://placehold.co/100x100/e2e8f0/64748b?text=No+Image'">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-black text-base text-slate-900 truncate" x-text="product.name"></h4>
                                        <p class="text-sm font-bold text-[#60A5FA]" x-text="product.category"></p>
                                        <p class="text-base font-black text-[#1E3A8A]" x-text="product.price_formatted"></p>
                                    </div>
                                    <svg class="w-6 h-6 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </template>
                        </div>
                    </template>

                    <!-- No Results -->
                    <div x-show="!loading && searchQuery.length >= 2 && searchResults.length === 0" class="py-12 px-10 text-center bg-gray-50">
                        <svg class="w-20 h-20 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="mt-4 text-lg font-black text-gray-500">No products found</p>
                        <p class="text-sm font-bold text-gray-400 mb-4">Try searching for "Air Max", "Boot", or "Sandal"</p>
                    </div>

                    <!-- Empty State -->
                    <div x-show="searchQuery.length < 2 && !loading" class="p-6 text-center bg-gradient-to-br from-gray-50 to-blue-50">
                        <svg class="w-16 h-16 mx-auto text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <p class="mt-3 text-base font-black text-slate-700">Start typing to search</p>
                        <p class="text-xs font-bold text-slate-500 mt-1">Popular searches:</p>
                        <div class="flex flex-wrap gap-2 justify-center mt-2">
                            <span class="px-3 py-1.5 bg-white border-2 border-[#60A5FA] text-[#60A5FA] rounded-full text-xs font-bold">Air Max</span>
                            <span class="px-3 py-1.5 bg-white border-2 border-[#60A5FA] text-[#60A5FA] rounded-full text-xs font-bold">Boots</span>
                            <span class="px-3 py-1.5 bg-white border-2 border-[#60A5FA] text-[#60A5FA] rounded-full text-xs font-bold">Sandals</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
