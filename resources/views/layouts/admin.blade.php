<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Walkuno Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
            font-family: "Poppins", sans-serif;
            min-height: 100vh;
        }

        .sidebar {
            min-height: 100vh;
            width: 280px;
            background: linear-gradient(180deg, #3b82f6 0%, #60a5fa 100%);
            padding: 30px 20px;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-brand .logo-container {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand .logo-icon {
            font-size: 28px;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-brand .brand-text h5 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 18px;
        }

        .sidebar-brand .brand-text small {
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

        .sidebar-footer {
            position: absolute;
            bottom: 30px;
            left: 20px;
            right: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            padding-top: 20px;
        }

        .content {
            margin-left: 280px;
            padding: 30px 40px;
            min-height: 100vh;
        }

        .topbar {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
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
            display: flex;
            align-items: center;
            gap: 15px;
            padding-left: 30px;
            border-left: 2px solid #e2e8f0;
            position: relative;
        }

        .user-profile-trigger {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .user-profile-trigger:hover {
            background-color: #f8fafc;
        }

        .topbar-user img,
        .user-avatar {
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

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-info .user-name {
            color: #1e3a8a;
            font-weight: 600;
            font-size: 14px;
        }

        .user-info .user-email {
            color: #64748b;
            font-size: 12px;
        }

        .dropdown-icon {
            color: #64748b;
            font-size: 12px;
            margin-left: 5px;
            transition: transform 0.3s;
        }

        .user-profile-trigger.active .dropdown-icon {
            transform: rotate(180deg);
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            min-width: 220px;
            padding: 8px;
            display: none;
            z-index: 1000;
            animation: dropdownSlide 0.3s ease;
        }

        .profile-dropdown.show {
            display: block;
        }

        @keyframes dropdownSlide {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 8px;
            color: #334155;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
        }

        .profile-dropdown-item:hover {
            background-color: #f1f5f9;
            color: #3b82f6;
        }

        .profile-dropdown-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .profile-dropdown-divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 8px 0;
        }

        .profile-dropdown-item.logout {
            color: #ef4444;
        }

        .profile-dropdown-item.logout:hover {
            background-color: #fee2e2;
            color: #dc2626;
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

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 30px;
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

        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

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

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
            color: white;
        }

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

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-label {
            color: #1e3a8a;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .footer {
            margin-top: 50px;
            padding: 20px 0;
            text-align: center;
            color: #64748b;
            font-size: 13px;
            border-top: 2px solid rgba(59, 130, 246, 0.1);
        }

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

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 20px;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .topbar-user {
                border-left: none;
                border-top: 2px solid #e2e8f0;
                padding-left: 0;
                padding-top: 15px;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-container">
                <i class="fas fa-store logo-icon"></i>
            </div>
            <div class="brand-text">
                <h5>Walkuno</h5>
                <small>ADMIN PANEL</small>
            </div>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('admin.stores.index') }}" class="{{ request()->routeIs('admin.stores.*') ? 'active' : '' }}">
                <i class="fas fa-store"></i>
                <span>Toko</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Pengguna</span>
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="topbar">
            <div class="topbar-greeting">
                <div>
                    <h5>Halo, {{ Auth::user()->name }}! 👋</h5>
                    <small>Selamat datang kembali di dashboard Walkuno</small>
                </div>
            </div>
            <div class="topbar-user">
                <div class="user-profile-trigger" id="profileTrigger">
                    <div class="user-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-email">{{ Auth::user()->email }}</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </div>

                <div class="profile-dropdown" id="profileDropdown">
                    <a href="{{ route('profile.edit') }}" class="profile-dropdown-item">
                        <i class="fas fa-user-circle"></i>
                        <span>Edit Profile</span>
                    </a>
                    <a href="#" class="profile-dropdown-item">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                    <div class="profile-dropdown-divider"></div>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="profile-dropdown-item logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        @yield('content')

        <div class="footer">
            <p>&copy; {{ date('Y') }} Walkuno - Store Management System. All rights reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Profile Dropdown Toggle
        const profileTrigger = document.getElementById('profileTrigger');
        const profileDropdown = document.getElementById('profileDropdown');

        profileTrigger.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
            profileTrigger.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileTrigger.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('show');
                profileTrigger.classList.remove('active');
            }
        });

        // Prevent dropdown from closing when clicking inside it
        profileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    </script>
</body>
</html>
