@extends('layouts.admin')

@section('title', 'Daftar Toko')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title">
        <i class="fas fa-store"></i>
        Daftar Toko
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <i class="fas fa-table"></i> Data Toko Terdaftar
    </div>
    <div class="card-body">
        @if($stores->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 120px;">Logo Usaha</th>
                            <th>Nama Usaha</th>
                            <th>Nama Pemilik</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stores as $store)
                            <tr>
                                <td>
                                    <strong>{{ $stores->firstItem() + $loop->index }}</strong>
                                </td>
                                <td>
                                    @if($store->logo)
                                        <img src="{{ asset('storage/' . $store->logo) }}"
                                             alt="{{ $store->name }}"
                                             style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 2px solid #e2e8f0;">
                                    @else
                                        <div style="width: 80px; height: 80px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; border: 2px dashed #cbd5e1;">
                                            <i class="fas fa-image" style="color: #94a3b8; font-size: 24px;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $store->name }}</strong>
                                </td>
                                <td>
                                    <small>{{ $store->user->name }}</small><br>
                                    <small style="color: #64748b;">{{ $store->user->email }}</small>
                                </td>
                                <td>
                                    @if ($store->is_verified == 0)
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @elseif($store->is_verified == 1)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Disetujui
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.stores.show', $store->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div style="color: #64748b; font-size: 13px;">
                    Menampilkan <strong>{{ $stores->firstItem() }}</strong> hingga <strong>{{ $stores->lastItem() }}</strong> dari <strong>{{ $stores->total() }}</strong> toko
                </div>
                <div>
                    {{ $stores->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5 style="color: #94a3b8; margin-top: 15px;">Belum Ada Toko Terdaftar</h5>
                <p style="margin-top: 10px;">Tidak ada toko yang terdaftar di sistem saat ini</p>
            </div>
        @endif
    </div>
</div>
@endsection
