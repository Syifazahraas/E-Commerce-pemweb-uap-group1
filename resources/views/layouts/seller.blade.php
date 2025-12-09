<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard - @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
            font-family: "Poppins", "Segoe UI", sans-serif;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            min-height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, #3b82f6 0%, #60a5fa 100%);
            padding: 30px 20px;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-logo {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-logo img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        .sidebar-logo i {
            font-size: 24px;
            color: #3b82f6;
        }

        .sidebar-text h5 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 16px;
        }

        .sidebar-text small {
            color: rgba(255, 255, 255, 0.8);
            display: block;
            font-size: 11px;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 15px 0;
        }

        /* Content Wrapper */
        .content-wrapper {
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        .topbar {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            margin: 30px 40px 30px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-greeting {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar-greeting h5 {
            color: #1e3a8a;
            font-weight: 700;
            margin: 0;
            font-size: 20px;
        }

        .topbar-greeting small {
            color: #64748b;
            font-size: 13px;
        }

        .topbar-user {
            position: relative;
            display: flex;
            align-items: center;
            gap: 15px;
            padding-left: 30px;
            border-left: 2px solid #e2e8f0;
            cursor: pointer;
            user-select: none;
        }

        .topbar-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .topbar-user-info {
            display: flex;
            flex-direction: column;
        }

        .topbar-user-name {
            color: #1e3a8a;
            font-weight: 600;
            font-size: 14px;
        }

        .topbar-user-email {
            color: #64748b;
            font-size: 12px;
        }

        .topbar-user i.fa-chevron-down {
            color: #64748b;
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .topbar-user.active i.fa-chevron-down {
            transform: rotate(180deg);
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: absolute;
            top: calc(100% + 15px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            min-width: 240px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1100;
            overflow: hidden;
        }

        .profile-dropdown::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 20px;
            width: 16px;
            height: 16px;
            background: white;
            transform: rotate(45deg);
            box-shadow: -2px -2px 5px rgba(0, 0, 0, 0.05);
        }

        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-dropdown-header {
            padding: 20px;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .profile-dropdown-header h6 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
        }

        .profile-dropdown-header small {
            font-size: 12px;
            opacity: 0.9;
        }

        .profile-dropdown-menu {
            padding: 10px 0;
        }

        .profile-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #334155;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 14px;
        }

        .profile-dropdown-item:hover {
            background-color: #f8fafc;
            color: #3b82f6;
        }

        .profile-dropdown-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .profile-dropdown-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 10px 0;
        }

        .profile-dropdown-item.logout {
            color: #ef4444;
        }

        .profile-dropdown-item.logout:hover {
            background-color: #fef2f2;
            color: #dc2626;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 0 40px 30px 40px;
        }

        .page-title {
            color: #1e3a8a;
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title i {
            color: #3b82f6;
        }

        /* Card */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: white;
            border: none;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            font-weight: 700;
        }

        .card-body {
            padding: 25px;
        }

        /* Stats Card */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-left: 5px solid;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-card.blue {
            border-left-color: #3b82f6;
        }

        .stat-card.cyan {
            border-left-color: #06b6d4;
        }

        .stat-card.green {
            border-left-color: #10b981;
        }

        .stat-card-content h5 {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card-content h2 {
            color: #1e3a8a;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
        }

        .stat-card-icon {
            font-size: 40px;
            opacity: 0.15;
            margin-right: 15px;
        }

        .stat-card.blue .stat-card-icon {
            color: #3b82f6;
        }

        .stat-card.cyan .stat-card-icon {
            color: #06b6d4;
        }

        .stat-card.green .stat-card-icon {
            color: #10b981;
        }

        /* Table */
        .table {
            color: #334155;
            margin-bottom: 0;
        }

        .table thead {
            background-color: #f1f5f9;
            border-bottom: 2px solid #e2e8f0;
        }

        .table thead th {
            color: #1e3a8a;
            font-weight: 700;
            border: none;
            padding: 15px;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        /* Badge */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* Button */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 8px 16px;
            font-size: 13px;
            transition: all 0.3s;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-info {
            background-color: #06b6d4;
            color: white;
        }

        .btn-info:hover {
            background-color: #0891b2;
            color: white;
        }

        .btn-warning {
            background-color: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background-color: #d97706;
            color: white;
        }

        .btn-success {
            background-color: #10b981;
            color: white;
        }

        .btn-success:hover {
            background-color: #059669;
            color: white;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
            color: white;
        }

        /* Alert */
        .alert {
            border: none;
            border-radius: 12px;
            border-left: 4px solid;
            margin-bottom: 25px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert-success {
            background-color: #d1fae5;
            border-left-color: #10b981;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fee2e2;
            border-left-color: #ef4444;
            color: #7f1d1d;
        }

        /* Form */
        .form-control,
        .form-select {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-label {
            color: #1e3a8a;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding: 20px 40px;
            text-align: center;
            color: #64748b;
            font-size: 13px;
            border-top: 2px solid rgba(59, 130, 246, 0.1);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 50px;
            color: #cbd5e1;
            margin-bottom: 15px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                padding: 15px;
                display: flex;
                align-items: center;
                gap: 20px;
                position: static;
            }

            .sidebar-header {
                border: none;
                margin: 0;
                padding: 0;
                gap: 12px;
            }

            .sidebar-menu {
                display: none;
            }

            .content-wrapper {
                margin-left: 0;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                margin: 20px;
            }

            .topbar-user {
                border-left: none;
                border-top: 2px solid #e2e8f0;
                padding-left: 0;
                padding-top: 15px;
                width: 100%;
            }

            .main-content {
                padding: 0 20px 30px 20px;
            }

            .footer {
                padding: 20px;
            }

            .stat-card {
                flex-direction: column;
                text-align: center;
                align-items: flex-start;
            }

            .stat-card-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .profile-dropdown {
                right: auto;
                left: 0;
            }

            .profile-dropdown::before {
                right: auto;
                left: 20px;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 24px;
            }

            .stat-card-content h2 {
                font-size: 24px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                @if (Auth::user()->seller && Auth::user()->seller->logo)
                    <img src="{{ asset('storage/' . Auth::user()->seller->logo) }}" alt="Logo">
                @else
                    <i class="fas fa-store"></i>
                @endif
            </div>
            <div class="sidebar-text">
                <h5>
                    @if (Auth::user()->seller)
                        {{ Auth::user()->seller->name ?? 'Toko Saya' }}
                    @else
                        Seller
                    @endif
                </h5>
                <small>SELLER</small>
            </div>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('seller.dashboard') }}"
                class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('seller.products.index') }}"
                class="{{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Produk</span>
            </a>
            <a href="{{ route('seller.category.index') }}"
                class="{{ request()->routeIs('seller.category.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span>Kategori</span>
            </a>
            <a href="{{ route('seller.orders.index') }}" class="{{ request()->routeIs('seller.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Pesanan</span>
            </a>
            <a href="{{ route('seller.balance.index') }}"
                class="{{ request()->routeIs('seller.balance.*') ? 'active' : '' }}">
                <i class="fas fa-wallet"></i>
                <span>Saldo</span>
            </a>
            <a href="{{ route('seller.withdrawals.index') }}" class="{{ request()->routeIs('seller.withdrawals.*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i>
                <span>Penarikan Dana</span>
            </a>

            <div class="sidebar-divider"></div>

            <a href="{{ route('seller.store.edit') }}"
                class="{{ request()->routeIs('seller.store.*') ? 'active' : '' }}">
                <i class="fas fa-store-alt"></i>
                <span>Pengaturan Toko</span>
            </a>
        </div>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-greeting">
                <div>
                    <h5>Halo👋</h5>
                    <small>Selamat datang kembali di dashboard seller Anda</small>
                </div>
            </div>
            <div class="topbar-user" id="profileDropdownToggle">
                <div class="topbar-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="topbar-user-info">
                    <span class="topbar-user-name">{{ Auth::user()->name }}</span>
                    <span class="topbar-user-email">{{ Auth::user()->email }}</span>
                </div>
                <i class="fas fa-chevron-down"></i>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-dropdown-header">
                        <h6>{{ Auth::user()->name }}</h6>
                        <small>{{ Auth::user()->email }}</small>
                    </div>
                    <div class="profile-dropdown-menu">
                        <a href="{{ route('profile.edit') }}" class="profile-dropdown-item">
                            <i class="fas fa-user-circle"></i>
                            <span>Pengaturan Akun</span>
                        </a>
                        <a href="{{ route('seller.store.edit') }}" class="profile-dropdown-item">
                            <i class="fas fa-store-alt"></i>
                            <span>Pengaturan Toko</span>
                        </a>
                        <div class="profile-dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="profile-dropdown-item logout"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Seller Dashboard - Walkuno Store Management System. All rights reserved.</p>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Profile Dropdown Toggle
        const profileToggle = document.getElementById('profileDropdownToggle');
        const profileDropdown = document.getElementById('profileDropdown');

        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            profileToggle.classList.toggle('active');
            profileDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileToggle.contains(e.target)) {
                profileToggle.classList.remove('active');
                profileDropdown.classList.remove('show');
            }
        });

        // Prevent dropdown from closing when clicking inside it
        profileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
@stack('scripts')
</body>

</html>
