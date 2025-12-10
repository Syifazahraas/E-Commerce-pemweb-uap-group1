@extends('layouts.seller')

@section('title', 'Kelola Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/product/product.css') }}">
    <style>
        /* Additional styles for full table */
        .table {
            font-size: 0.875rem;
        }

        .table td {
            vertical-align: middle;
            padding: 12px 8px;
        }

        .product-thumbnail {
            width: 60px;
            height: 60px;
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .product-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            border-radius: 6px;
            color: #999;
        }

        .product-name {
            margin: 0;
            font-weight: 600;
            font-size: 0.9rem;
            color: #2c3e50;
        }

        .product-slug {
            color: #7f8c8d;
            font-size: 0.75rem;
        }

        .price-badge {
            font-weight: 600;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
            white-space: nowrap;
        }

        .stock-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .stock-high {
            background: #d4edda;
            color: #155724;
        }

        .stock-low {
            background: #fff3cd;
            color: #856404;
        }

        .stock-empty {
            background: #f8d7da;
            color: #721c24;
        }

        .condition-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .condition-new {
            background: #d1ecf1;
            color: #0c5460;
        }

        .condition-second {
            background: #e2e3e5;
            color: #383d41;
        }

        .weight-badge {
            color: #6c757d;
            font-size: 0.8rem;
        }

        .material-text {
            color: #495057;
            font-size: 0.8rem;
        }

        .sizes-container {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .size-chip {
            display: inline-block;
            padding: 2px 8px;
            background: #e9ecef;
            border-radius: 10px;
            font-size: 0.7rem;
            color: #495057;
        }

        .promo-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: #ff6b6b;
            color: white;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
            justify-content: center;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Sticky first column */
        .table thead th:first-child,
        .table tbody td:first-child {
            position: sticky;
            left: 0;
            background: white;
            z-index: 10;
        }

        .table thead th:first-child {
            z-index: 11;
        }
    </style>
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
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="min-width: 80px;">Gambar</th>
                            <th style="min-width: 200px;">Produk</th>
                            <th style="min-width: 120px;">Kategori</th>
                            <th style="min-width: 120px;">Harga</th>
                            <th style="min-width: 80px;">Stok</th>
                            <th style="min-width: 100px;">Berat</th>
                            <th style="min-width: 100px;">Kondisi</th>
                            <th style="min-width: 150px;">Material</th>
                            <th style="min-width: 150px;">Ukuran</th>
                            <th style="min-width: 100px;">Status</th>
                            <th style="min-width: 150px;">Deskripsi</th>
                            <th style="min-width: 120px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <!-- Gambar -->
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

                                <!-- Produk -->
                                <td>
                                    <div class="product-info">
                                        <h6 class="product-name" title="{{ $product->name }}">
                                            {{ Str::limit($product->name, 40) }}
                                        </h6>
                                        <small class="product-slug">{{ $product->slug }}</small>
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $product->productCategory->name }}
                                    </span>
                                </td>

                                <!-- Harga -->
                                <td>
                                    <span class="price-badge">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </td>

                                <!-- Stok -->
                                <td>
                                    <span class="stock-badge {{ $product->stock > 10 ? 'stock-high' : ($product->stock > 0 ? 'stock-low' : 'stock-empty') }}">
                                        {{ $product->stock }} pcs
                                    </span>
                                </td>

                                <!-- Berat -->
                                <td>
                                    <span class="weight-badge">
                                        <i class="fas fa-weight"></i> {{ number_format($product->weight, 0) }} gr
                                    </span>
                                </td>

                                <!-- Kondisi -->
                                <td>
                                    <span class="condition-badge condition-{{ $product->condition }}">
                                        {{ $product->condition === 'new' ? 'Baru' : 'Bekas' }}
                                    </span>
                                </td>

                                <!-- Material -->
                                <td>
                                    @if($product->material)
                                        <span class="material-text" title="{{ $product->material }}">
                                            {{ Str::limit($product->material, 20) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <!-- Ukuran -->
                                <td>
                                    @if($product->sizes && count($product->sizes) > 0)
                                        <div class="sizes-container">
                                            @foreach(array_slice($product->sizes, 0, 3) as $size)
                                                <span class="size-chip">{{ $size }}</span>
                                            @endforeach
                                            @if(count($product->sizes) > 3)
                                                <span class="size-chip">+{{ count($product->sizes) - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <!-- Status Promo -->
                                <td>
                                    @if($product->is_on_sale)
                                        <span class="promo-badge">
                                            <i class="fas fa-tag"></i> Promo
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <!-- Deskripsi -->
                                <td>
                                    @if($product->description)
                                        <span class="text-muted" title="{{ $product->description }}">
                                            {{ Str::limit($product->description, 50) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('seller.products.edit', $product->id) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Edit Produk">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('seller.products.images.manage', $product->id) }}"
                                           class="btn btn-sm btn-info"
                                           title="Kelola Gambar">
                                            <i class="fas fa-images"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $product->id }})"
                                                title="Hapus Produk">
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
