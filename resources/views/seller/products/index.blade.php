@extends('layouts.seller')

@section('title', 'Kelola Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/product/product.css') }}">
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

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            <i class="fas fa-box"></i>
            Kelola Produk
        </h1>
        <p class="page-subtitle">Kelola semua produk toko Anda</p>
    </div>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

@if ($products->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    <div class="product-thumbnail">
                                        @if ($product->productImages->count() > 0)
                                            <img src="{{ asset('storage/' . $product->productImages->where('is_thumbnail', true)->first()?->image ?? $product->productImages->first()->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="product-img">
                                        @else
                                            <div class="product-img-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="product-info">
                                        <h6 class="product-name">{{ Str::limit($product->name, 40) }}</h6>
                                        <small class="product-slug">{{ $product->slug }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $product->productCategory->name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="price-badge">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="stock-badge {{ $product->stock > 10 ? 'stock-high' : ($product->stock > 0 ? 'stock-low' : 'stock-empty') }}">
                                        {{ $product->stock }} pcs
                                    </span>
                                </td>
                                <td>
                                    <span class="condition-badge condition-{{ $product->condition }}">
                                        {{ $product->condition === 'new' ? 'Baru' : 'Bekas' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('seller.products.edit', $product->id) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $product->id }})"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h5 class="mt-3">Belum ada produk</h5>
                <p class="text-muted">Mulai dengan membuat produk pertama Anda</p>
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Tambah Produk
                </a>
            </div>
        </div>
    </div>
@endif

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/seller/product/index.js') }}"></script>
@endpush

@endsection
