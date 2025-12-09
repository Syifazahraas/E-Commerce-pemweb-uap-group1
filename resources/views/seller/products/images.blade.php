@extends('layouts.seller')

@section('title', 'Kelola Gambar Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/product/images.css') }}">
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
    <i class="fas fa-images"></i>
    Kelola Gambar Produk
</div>

<!-- Product Info -->
<div class="product-info-card">
    <div class="product-info-content">
        <h5>{{ $product->name }}</h5>
        <div class="product-meta">
            <span><i class="fas fa-tag"></i> Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            <span><i class="fas fa-boxes"></i> Stok: {{ $product->stock }}</span>
        </div>
    </div>
    <a href="{{ route('seller.products.edit', $product->id) }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-edit"></i> Edit Produk
    </a>
</div>

<!-- Upload Section -->
<div class="card mb-4">
    <div class="card-body">
        <div class="upload-section-header">
            <h5><i class="fas fa-cloud-upload-alt"></i> Upload Gambar Baru</h5>
            <p>Upload 1-10 gambar (JPG, JPEG, PNG - maksimal 5MB per file)</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div style="display: flex; gap: 12px; align-items: flex-start;">
                    <i class="fas fa-exclamation-circle" style="flex-shrink: 0; margin-top: 2px;"></i>
                    <div style="flex: 1;">
                        <strong>Ada kesalahan!</strong>
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

        <form action="{{ route('seller.products.images.store', $product->id) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <div class="images-upload-wrapper">
                <input type="file" id="images" name="images[]" accept="image/jpeg,image/png,image/jpg" multiple class="d-none" required>

                <label for="images" class="images-upload-box" id="imagesLabel">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <span class="upload-text">Klik untuk upload atau drag & drop</span>
                    <p class="upload-hint">JPG, JPEG, PNG (maksimal 5MB per file)</p>
                    <p class="upload-hint">Total gambar saat ini: <strong>{{ $product->productImages->count() }}/10</strong></p>
                </label>

                <div id="imagesPreview" class="images-preview-wrapper">
                    <!-- Preview akan ditampilkan di sini -->
                </div>
            </div>

            <div class="upload-actions">
                <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                    <i class="fas fa-upload"></i> Upload Gambar
                </button>
                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Existing Images -->
<div class="card">
    <div class="card-body">
        <div class="existing-section-header">
            <h5><i class="fas fa-images"></i> Gambar Produk ({{ $product->productImages->count() }})</h5>
            @if($product->productImages->count() > 0)
                <p>Klik gambar untuk set sebagai thumbnail, atau hapus gambar yang tidak diperlukan</p>
            @endif
        </div>

        @if($product->productImages->count() > 0)
            <div class="existing-images-grid">
                @foreach($product->productImages as $image)
                    <div class="image-card {{ $image->is_thumbnail ? 'thumbnail-active' : '' }}">
                        <img src="{{ asset('storage/' . $image->image) }}" alt="Product Image">

                        @if($image->is_thumbnail)
                            <div class="thumbnail-badge">
                                <i class="fas fa-star"></i> Thumbnail
                            </div>
                        @endif

                        <div class="image-overlay">
                            <div class="image-actions">
                                @if(!$image->is_thumbnail)
                                    <form action="{{ route('seller.products.images.thumbnail', [$product->id, $image->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Set sebagai thumbnail">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('seller.products.images.destroy', [$product->id, $image->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus gambar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus gambar">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-image"></i>
                <h6>Belum Ada Gambar</h6>
                <p>Upload gambar produk untuk menampilkan produk Anda kepada pembeli</p>
            </div>
        @endif
    </div>
</div>

<!-- Back Button -->
<div class="mt-4 text-center">
    <a href="{{ route('seller.products.index') }}" class="btn btn-secondary btn-lg">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Produk
    </a>
</div>

@push('scripts')
    <script src="{{ asset('js/seller/product/images.js') }}"></script>
@endpush

@endsection
