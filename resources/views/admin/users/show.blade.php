@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
<div class="page-title mb-4">
    <i class="fas fa-user-circle"></i>
    Detail Pengguna: {{ $user->name }}
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-information-circle"></i> Informasi Pengguna
            </div>
            <div class="card-body">
                <div style="text-align: center; margin-bottom: 30px;">
                    <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #60a5fa); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 40px; margin: 0 auto; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; display: block; margin-bottom: 10px;">
                            <i class="fas fa-user" style="color: #3b82f6;"></i> Nama Pengguna
                        </label>
                        <h5 style="color: #1e3a8a; font-weight: 700; margin: 0;">{{ $user->name }}</h5>
                    </div>
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; display: block; margin-bottom: 10px;">
                            <i class="fas fa-envelope" style="color: #3b82f6;"></i> Email
                        </label>
                        <h5 style="color: #1e3a8a; font-weight: 700; margin: 0; word-break: break-all;">{{ $user->email }}</h5>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; display: block; margin-bottom: 10px;">
                            <i class="fas fa-badge" style="color: #3b82f6;"></i> Role Pengguna
                        </label>
                        @if($user->role === 'admin')
                            <span class="badge bg-danger" style="padding: 10px 15px; font-size: 13px;">
                                <i class="fas fa-shield-alt"></i> Administrator
                            </span>
                        @else
                            <span class="badge bg-secondary" style="padding: 10px 15px; font-size: 13px;">
                                <i class="fas fa-user"></i> Member
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; display: block; margin-bottom: 10px;">
                            <i class="fas fa-id-card" style="color: #3b82f6;"></i> User ID
                        </label>
                        <p style="color: #475569; margin: 0; font-family: 'Courier New', monospace; font-size: 13px; font-weight: 600;">{{ $user->id }}</p>
                    </div>
                </div>

                <hr class="my-4" style="border-color: #e2e8f0;">

                <div class="row">
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">
                            <i class="fas fa-calendar-alt" style="color: #3b82f6;"></i> Tanggal Bergabung
                        </label>
                        <p style="color: #475569; margin: 0; font-size: 14px;">{{ $user->created_at->format('d M Y') }}</p>
                        <small style="color: #94a3b8; font-size: 12px;">{{ $user->created_at->format('H:i') }}</small>
                    </div>
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">
                            <i class="fas fa-sync-alt" style="color: #3b82f6;"></i> Terakhir Update
                        </label>
                        <p style="color: #475569; margin: 0; font-size: 14px;">{{ $user->updated_at->format('d M Y') }}</p>
                        <small style="color: #94a3b8; font-size: 12px;">{{ $user->updated_at->format('H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>

        @if($store)
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-store"></i> Informasi Toko
                </div>
                <div class="card-body">
                    @if($store->logo)
                        <div style="margin-bottom: 20px; text-align: center;">
                            <img src="{{ asset('storage/' . $store->logo) }}"
                                 style="max-width: 150px; max-height: 150px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);"
                                 alt="{{ $store->name }}">
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                                <i class="fas fa-shop" style="color: #3b82f6;"></i> Nama Usaha
                            </label>
                            <p style="color: #1e3a8a; font-weight: 700; margin: 0;">{{ $store->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                                <i class="fas fa-user-tie" style="color: #3b82f6;"></i> Nama Pemilik
                            </label>
                            <p style="color: #1e3a8a; font-weight: 700; margin: 0;">{{ $store->owner }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                                <i class="fas fa-check-circle" style="color: #3b82f6;"></i> Status Verifikasi
                            </label>
                            @if($store->is_verified == 1)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Disetujui
                                </span>
                            @elseif($store->is_verified == 0)
                                <span class="badge bg-secondary">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                                <i class="fas fa-link" style="color: #3b82f6;"></i> Lihat Toko
                            </label>
                            <a href="{{ route('admin.stores.show', $store->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-external-link-alt"></i> Detail Toko
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-store"></i> Informasi Toko
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> Pengguna ini belum memiliki toko yang terdaftar.
                    </div>
                </div>
            </div>
        @endif

        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-bolt"></i> Aksi
            </div>
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.toggleRole', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ubah role user ini?')">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-exchange-alt"></i> Toggle Role
                            </button>
                        </form>

                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i> Hapus User
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning w-100" role="alert">
                            <i class="fas fa-info-circle"></i> Ini adalah akun Anda. Anda tidak dapat mengubah atau menghapus akun sendiri.
                        </div>
                    @endif

                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-bar"></i> Ringkasan
            </div>
            <div class="card-body">
                <div style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); color: white; padding: 20px; border-radius: 12px; text-align: center; margin-bottom: 15px;">
                    <div style="font-size: 24px; font-weight: 700;">
                        @if($user->role === 'admin')
                            Admin
                        @else
                            Member
                        @endif
                    </div>
                    <div style="font-size: 12px; margin-top: 8px; opacity: 0.9;">Status Akun</div>
                </div>

                <div style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); color: white; padding: 20px; border-radius: 12px; text-align: center; margin-bottom: 15px;">
                    <div style="font-size: 24px; font-weight: 700;">
                        @if($store && $store->is_verified == 1)
                            Seller
                        @else
                            User
                        @endif
                    </div>
                    <div style="font-size: 12px; margin-top: 8px; opacity: 0.9;">Tipe Pengguna</div>
                </div>

                <div style="background: #f1f5f9; padding: 15px; border-radius: 12px;">
                    <p style="color: #64748b; font-size: 12px; margin: 0; font-weight: 600;">ID PENGGUNA</p>
                    <p style="color: #1e3a8a; font-family: 'Courier New', monospace; font-size: 14px; margin: 8px 0 0 0; font-weight: 700; word-break: break-all;">{{ $user->id }}</p>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-shield-alt"></i> Keamanan
            </div>
            <div class="card-body">
                <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 14px; border-radius: 8px; font-size: 13px; color: #92400e;">
                    <p style="margin: 0 0 10px 0; font-weight: 600;">
                        <i class="fas fa-warning"></i> Hati-hati saat menghapus user
                    </p>
                    <ul style="margin: 8px 0 0 0; padding-left: 18px;">
                        <li>Data user dan toko akan dihapus permanent</li>
                        <li>Tindakan tidak dapat dibatalkan</li>
                        <li>Konfirmasi akan diminta sebelum penghapusan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
