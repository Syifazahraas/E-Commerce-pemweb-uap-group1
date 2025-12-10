@extends('layouts.seller')

@section('title', 'Edit Produk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/product/edit.css') }}">
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

@if (session('new_product'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <div style="display: flex; gap: 12px; align-items: flex-start;">
            <i class="fas fa-info-circle" style="flex-shrink: 0; margin-top: 2px;"></i>
            <div style="flex: 1;">
                <strong>Produk berhasil dibuat!</strong>
                <p style="margin: 4px 0 0 0;">Silakan upload gambar produk di bawah ini.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="page-title">
    <i class="fas fa-edit"></i>
    Edit Produk
</div>

<!-- Quick Actions -->
<div class="quick-actions-card">
    <div class="quick-action-item">
        <i class="fas fa-images"></i>
        <div>
            <h6>Kelola Gambar</h6>
            <p>Upload, hapus, atau atur thumbnail gambar produk</p>
        </div>
        <a href="{{ route('seller.products.images.manage', $product->id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-right"></i> Kelola Gambar
        </a>
    </div>
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

        <form action="{{ route('seller.products.update', $product->id) }}" method="POST" id="productForm">
            @csrf
            @method('PUT')

            <!-- Informasi Dasar Section -->
            <div class="form-section">
                <div class="form-section-header">
                    <h5><i class="fas fa-info-circle"></i> Informasi Produk</h5>
                    <p>Isi data dasar tentang produk Anda</p>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" placeholder="Masukkan nama produk"
                           value="{{ old('name', $product->name) }}" required>
                    <small class="form-text">Nama produk yang akan ditampilkan kepada pembeli</small>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('product_category_id') is-invalid @enderror"
                                    id="product_category_id" name="product_category_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @if ($category->children->count() > 0)
                                        @foreach ($category->children as $subcategory)
                                            <option value="{{ $subcategory->id }}" {{ old('product_category_id', $product->product_category_id) == $subcategory->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;└─ {{ $subcategory->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                            @error('product_category_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="condition" class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select class="form-select @error('condition') is-invalid @enderror"
                                    id="condition" name="condition" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="new" {{ old('condition', $product->condition) == 'new' ? 'selected' : '' }}>Baru</option>
                                <option value="second" {{ old('condition', $product->condition) == 'second' ? 'selected' : '' }}>Bekas</option>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi Produk <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" placeholder="Deskripsikan produk Anda dengan detail..."
                              rows="5" required>{{ old('description', $product->description) }}</textarea>
                    <small class="form-text">Deskripsi lengkap produk (wajib diisi)</small>
                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Harga dan Stok Section -->
            <div class="form-section">
                <div class="form-section-header">
                    <h5><i class="fas fa-money-bill-wave"></i> Harga dan Stok</h5>
                    <p>Atur harga dan ketersediaan stok</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" placeholder="0"
                                       value="{{ old('price', $product->price) }}" min="0" step="100" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                       id="stock" name="stock" placeholder="0"
                                       value="{{ old('stock', $product->stock) }}" min="0" required>
                                <span class="input-group-text">pcs</span>
                            </div>
                            @error('stock')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="weight" class="form-label">Berat <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" class="form-control @error('weight') is-invalid @enderror"
                               id="weight" name="weight" placeholder="0"
                               value="{{ old('weight', $product->weight) }}" min="0" required>
                        <span class="input-group-text">gram</span>
                    </div>
                    <small class="form-text">Berat produk dalam gram untuk perhitungan ongkir</small>
                    @error('weight')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Detail Produk Section (Opsional - untuk sepatu) -->
            <div class="form-section">
                <div class="form-section-header">
                    <h5><i class="fas fa-shoe-prints"></i> Detail Produk Tambahan</h5>
                    <p>Opsional - Khusus untuk produk seperti sepatu, tas, dll.</p>
                </div>

                <div class="form-group">
                    <label for="material" class="form-label">Material <span class="text-muted">(opsional)</span></label>
                    <input type="text" class="form-control @error('material') is-invalid @enderror"
                           id="material" name="material" placeholder="Contoh: Kulit Asli, Canvas, Suede"
                           value="{{ old('material', $product->material) }}">
                    <small class="form-text">Material/bahan pembuatan produk</small>
                    @error('material')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Ukuran Tersedia <span class="text-muted">(opsional)</span></label>
                    <div id="sizesContainer">
                        @if(old('sizes') || $product->sizes)
                            @foreach(old('sizes', $product->sizes ?? []) as $size)
                                <div class="size-input-group mb-2">
                                    <input type="text" class="form-control" name="sizes[]" value="{{ $size }}" placeholder="Contoh: 39, 40, M, L, XL">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeSizeInput(this)">
                                        <i class="fas fa-times"></i> Hapus
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="size-input-group mb-2">
                                <input type="text" class="form-control" name="sizes[]" placeholder="Contoh: 39, 40, M, L, XL">
                                <button type="button" class="btn btn-sm btn-success" onclick="addSizeInput()">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-success mt-2" onclick="addSizeInput()">
                        <i class="fas fa-plus"></i> Tambah Ukuran
                    </button>
                    <small class="form-text d-block mt-2">Tambahkan ukuran yang tersedia untuk produk ini</small>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_on_sale" name="is_on_sale" value="1"
                               {{ old('is_on_sale', $product->is_on_sale) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_on_sale">
                            <i class="fas fa-tag"></i> Produk Sedang Diskon/Promo
                        </label>
                    </div>
                    <small class="form-text">Tandai jika produk ini sedang dalam masa promo atau diskon</small>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Perbarui Produk
                </button>
                <a href="{{ route('seller.products.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function addSizeInput() {
    const container = document.getElementById('sizesContainer');
    const newInput = document.createElement('div');
    newInput.className = 'size-input-group mb-2';
    newInput.innerHTML = `
        <input type="text" class="form-control" name="sizes[]" placeholder="Contoh: 39, 40, M, L, XL">
        <button type="button" class="btn btn-sm btn-danger" onclick="removeSizeInput(this)">
            <i class="fas fa-times"></i> Hapus
        </button>
    `;
    container.appendChild(newInput);
}

function removeSizeInput(btn) {
    btn.parentElement.remove();
}
</script>
@endpush

@endsection
