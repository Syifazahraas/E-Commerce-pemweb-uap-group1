@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title">
        <i class="fas fa-users"></i>
        Kelola Pengguna
    </div>
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

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card" style="border: none; background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">Total Pengguna</p>
                        <h3 style="margin: 10px 0 0 0; font-weight: 700;">{{ $totalUsers }}</h3>
                    </div>
                    <div style="font-size: 40px; opacity: 0.3;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card" style="border: none; background: linear-gradient(135deg, #10b981 0%, #34d399 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">Admin</p>
                        <h3 style="margin: 10px 0 0 0; font-weight: 700;">{{ $adminUsers }}</h3>
                    </div>
                    <div style="font-size: 40px; opacity: 0.3;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card" style="border: none; background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">Member</p>
                        <h3 style="margin: 10px 0 0 0; font-weight: 700;">{{ $memberUsers }}</h3>
                    </div>
                    <div style="font-size: 40px; opacity: 0.3;">
                        <i class="fas fa-user-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-table"></i> Daftar Pengguna Terdaftar
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Nama Pengguna</th>
                            <th>Email</th>
                            <th style="width: 100px;">Role</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $users->firstItem() + $loop->index }}</strong>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #60a5fa); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 16px;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <strong style="color: #1e3a8a;">{{ $user->name }}</strong>
                                            @if($user->id === auth()->id())
                                                <br><small style="color: #64748b;">(Akun Anda)</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small>{{ $user->email }}</small>
                                </td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-shield-alt"></i> Admin
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-user"></i> Member
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->store && $user->store->is_verified == 1)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Seller
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-minus-circle"></i> User
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>

                                    @if($user->id !== auth()->id())

                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus User">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled title="Tidak bisa mengubah akun sendiri">
                                            <i class="fas fa-lock"></i> Terkunci
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div style="color: #64748b; font-size: 13px;">
                    Menampilkan <strong>{{ $users->firstItem() }}</strong> hingga <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> pengguna
                </div>
                <div>
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5 style="color: #94a3b8; margin-top: 15px;">Belum Ada Pengguna</h5>
                <p style="margin-top: 10px;">Tidak ada pengguna yang terdaftar di sistem saat ini</p>
            </div>
        @endif
    </div>
</div>
@endsection
