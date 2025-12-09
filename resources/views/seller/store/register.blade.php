@extends('layouts.seller')

@section('title', 'Buat Toko')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/store/register.css') }}">
@endpush

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div style="display: flex; gap: 12px; align-items: flex-start;">
            <i class="fas fa-check-circle" style="flex-shrink: 0; margin-top: 2px;"></i>
            <div style="flex: 1;">
                <strong>Berhasil!</strong>
                <p style="margin: 4px 0 0 0;">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div style="display: flex; gap: 12px; align-items: flex-start;">
            <i class="fas fa-exclamation-triangle" style="flex-shrink: 0; margin-top: 2px;"></i>
            <div style="flex: 1;">
                <strong>Perhatian!</strong>
                <p style="margin: 4px 0 0 0;">{{ session('warning') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div style="display: flex; gap: 12px; align-items: flex-start;">
            <i class="fas fa-times-circle" style="flex-shrink: 0; margin-top: 2px;"></i>
            <div style="flex: 1;">
                <strong>Error!</strong>
                <p style="margin: 4px 0 0 0;">{{ session('error') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="page-title">
    <i class="fas fa-store"></i>
    Buat Toko Baru
</div>

<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div style="display: flex; gap: 12px; align-items: flex-start;">
                    <i class="fas fa-exclamation-circle" style="flex-shrink: 0; margin-top: 2px;"></i>
                    <div style="flex: 1;">
                        <strong>Ada kesalahan dalam form Anda!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('seller.store.store') }}" method="POST" enctype="multipart/form-data" id="storeForm">
            @csrf

            <!-- Informasi Dasar Section -->
            <div class="form-section">
                <div class="form-section-header">
                    <h5><i class="fas fa-info-circle"></i> Informasi Dasar</h5>
                    <p>Isi data dasar tentang toko Anda</p>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Nama Toko <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" placeholder="Masukkan nama toko Anda"
                           value="{{ old('name') }}" required>
                    <small class="form-text">Nama toko yang akan ditampilkan kepada pembeli</small>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="about" class="form-label">Tentang Toko</label>
                    <textarea class="form-control @error('about') is-invalid @enderror"
                              id="about" name="about" placeholder="Ceritakan tentang toko, produk, dan keunggulan Anda..."
                              rows="4">{{ old('about') }}</textarea>
                    <small class="form-text">Deskripsi singkat tentang toko Anda (maksimal 500 karakter)</small>
                    @error('about')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="logo" class="form-label">Logo Toko</label>
                    <div class="logo-upload-wrapper">
                        <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/jpg" class="d-none" @error('logo') is-invalid @enderror>

                        <label for="logo" class="logo-upload-box" id="logoLabel">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <span class="upload-text">Klik untuk upload atau drag & drop</span>
                            <p class="upload-hint">JPG, JPEG, PNG, WEBP (maksimal 2MB)</p>
                        </label>

                        <div id="logoPreview" class="logo-preview-wrapper" style="display: none;">
                            <div class="logo-preview-box">
                                <img id="previewImg" src="" alt="Preview" class="logo-preview-img">
                                <div class="logo-preview-actions">
                                    <button type="button" class="btn btn-sm btn-warning" onclick="changeLogoImage()">
                                        <i class="fas fa-redo"></i> Ubah
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeLogoImage()">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <small class="form-text">Logo akan ditampilkan di profil dan header toko Anda</small>
                    @error('logo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Informasi Kontak Section -->
            <div class="form-section">
                <div class="form-section-header">
                    <h5><i class="fas fa-phone"></i> Informasi Kontak</h5>
                    <p>Nomor yang dapat dihubungi pelanggan</p>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Nomor HP</label>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                           id="phone" name="phone" placeholder="Contoh: 08123456789"
                           value="{{ old('phone') }}">
                    <small class="form-text">Nomor yang dapat dihubungi oleh pembeli untuk informasi toko</small>
                    @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Informasi Alamat Section -->
            <div class="form-section">
                <div class="form-section-header">
                    <h5><i class="fas fa-map-marker-alt"></i> Alamat Toko</h5>
                    <p>Lokasi fisik toko Anda</p>
                </div>

                <div class="form-group">
                    <label for="address" class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control @error('address') is-invalid @enderror"
                              id="address" name="address" placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan..."
                              rows="3">{{ old('address') }}</textarea>
                    <small class="form-text">Alamat lengkap toko Anda yang akan ditampilkan kepada pembeli</small>
                    @error('address')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city" class="form-label">Kota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                   id="city" name="city" placeholder="Masukkan nama kota"
                                   value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="postal_code" class="form-label">Kode Pos</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                   id="postal_code" name="postal_code" placeholder="Contoh: 12345"
                                   value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Simpan Toko
                </button>
                <a href="{{ route('seller.dashboard') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/seller/store/register.js') }}"></script>
@endpush

@endsection
