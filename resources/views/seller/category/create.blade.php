@extends('layouts.seller')

@section('title', 'Tambah Kategori')

@section('content')

    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title"><i class="fas fa-plus"></i> Tambah Kategori</h1>
            <p class="page-subtitle">Buat kategori baru untuk produk Anda</p>
        </div>
        <a href="{{ route('seller.category.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('seller.category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Elektronik, Fashion"
                        value="{{ old('name') }}" required>

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label class="form-label">Slug (otomatis)</label>
                    <input type="text" name="slug" id="slug" class="form-control bg-light" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>



                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fas fa-save"></i> Simpan Kategori
                </button>

            </form>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/seller/category/create.js') }}"></script>
@endpush

@endsection
