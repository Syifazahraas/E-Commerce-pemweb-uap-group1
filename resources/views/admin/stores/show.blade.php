@extends('layouts.admin')

@section('title', 'Detail Toko')

@section('content')
<div class="page-title mb-4">
    <i class="fas fa-store"></i>
    Detail Toko: {{ $store->name }}
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-information-circle"></i> Informasi Toko
            </div>
            <div class="card-body">
                @if($store->logo)
                    <div style="margin-bottom: 25px; text-align: center;">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 10px;">
                            <i class="fas fa-image"></i> Logo Usaha
                        </label>
                        <img src="{{ asset('storage/' . $store->logo) }}"
                             style="max-width: 250px; max-height: 250px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border: 2px solid #e2e8f0;"
                             alt="{{ $store->name }}">
                    </div>
                    <hr class="my-4">
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                            <i class="fas fa-shop"></i> Nama Usaha
                        </label>
                        <h5 style="color: #1e3a8a; font-weight: 700; margin: 0;">{{ $store->name }}</h5>
                    </div>
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                            <i class="fas fa-user"></i> User Pemilik
                        </label>
                        <p style="color: #475569; margin: 0;">{{ $store->user->name }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                            <i class="fas fa-envelope"></i> Email Pemilik
                        </label>
                        <p style="color: #475569; margin: 0;">{{ $store->user->email }}</p>
                    </div>
                     <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                            <i class="fas fa-fingerprint"></i> ID Toko
                        </label>
                        <p style="color: #475569; margin: 0; font-family: monospace; font-size: 13px;">{{ $store->id }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                            <i class="fas fa-check-circle"></i> Status Verifikasi
                        </label>
                        @if($store->is_verified == 0)
                            <span class="badge bg-secondary" style="padding: 10px 15px; font-size: 12px;">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        @elseif($store->is_verified == 1)
                            <span class="badge bg-success" style="padding: 10px 15px; font-size: 12px;">
                                <i class="fas fa-check-circle"></i> Disetujui
                            </span>
                        @else
                            <span class="badge bg-danger" style="padding: 10px 15px; font-size: 12px;">
                                <i class="fas fa-times-circle"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                            <i class="fas fa-calendar"></i> Tanggal Dibuat
                        </label>
                        <p style="color: #475569; margin: 0;">{{ $store->created_at->format('d M Y - H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label style="color: #64748b; font-size: 12px; text-transform: uppercase; font-weight: 700; display: block; margin-bottom: 8px;">
                            <i class="fas fa-sync"></i> Terakhir Diperbarui
                        </label>
                        <p style="color: #475569; margin: 0;">{{ $store->updated_at->format('d M Y - H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-sitemap"></i> Aksi
            </div>
            <div class="card-body">
                <div class="d-flex gap-2 flex-wrap">
                    @if($store->is_verified != 1)
                        <a href="{{ route('admin.stores.approve', $store->id) }}" class="btn btn-success" onclick="return confirm('Setujui toko ini?')">
                            <i class="fas fa-check"></i> Setujui Toko
                        </a>
                    @endif
                    <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus Toko
                        </button>
                    </form>
                    <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Informasi Tambahan
            </div>
            <div class="card-body">
                <div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; border-radius: 8px;">
                    <p style="color: #047857; font-size: 13px; margin: 0;">
                        <i class="fas fa-info-circle"></i> Toko ini sedang memiliki status <strong>
                        @if($store->is_verified == 0)
                            Pending
                        @elseif($store->is_verified == 1)
                            Disetujui
                        @else
                            Ditolak
                        @endif
                        </strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-shield-alt"></i> Catatan Penting
            </div>
            <div class="card-body">
                <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 14px; border-radius: 8px; font-size: 13px; color: #92400e;">
                    <p style="margin: 0 0 8px 0; font-weight: 600;">
                        <i class="fas fa-exclamation-triangle"></i> Sebelum Menyetujui:
                    </p>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        <li>Verifikasi data pemilik toko dengan benar</li>
                        <li>Pastikan logo usaha jelas dan profesional</li>
                        <li>Periksa keaslian informasi yang diberikan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
