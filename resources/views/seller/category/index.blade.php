@extends('layouts.seller')

@section('title', 'Kelola Kategori')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
            <i class="fas fa-tags"></i>
            Kelola Kategori
        </h1>
        <p class="page-subtitle">Kelola kategori produk toko Anda</p>
    </div>
    <a href="{{ route('seller.category.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kategori
    </a>
</div>

@if ($categories->count() > 0)
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Deskripsi</th>
                            <th>Total Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td>
                                <div class="product-info">
                                    <h6 class="product-name">{{ $category->name }}</h6>
                                </div>
                            </td>

                            <td>
                                <small class="product-slug">{{ $category->slug }}</small>
                            </td>

                            <td>
                                <small class="description">{{ $category->description }}</small>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $category->products_count ?? 0 }} Produk
                                </span>
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('seller.category.edit', $category->id) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button class="btn btn-sm btn-danger"
                                            onclick="confirmDelete({{ $category->id }})"
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

            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links('vendor.pagination.custom') }}
            </div>

        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <div class="empty-state">
                <i class="fas fa-tag"></i>
                <h5 class="mt-3">Belum ada kategori</h5>
                <p class="text-muted">Mulai dengan membuat kategori pertama Anda</p>
                <a href="{{ route('seller.category.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>
            </div>
        </div>
    </div>
@endif

{{-- Modal Delete --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori ini? Kategori yang memiliki produk tidak bisa dihapus.</p>
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
    <script src="{{ asset('js/seller/category/index.js') }}"></script>
@endpush

@endsection
