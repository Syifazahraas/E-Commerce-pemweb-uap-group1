@extends('layouts.seller')

@section('title', 'Edit Kategori')

@section('content')

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title"><i class="fas fa-edit"></i> Edit Kategori</h1>
        <p class="page-subtitle">Perbarui data kategori</p>
    </div>
    <a href="{{ route('seller.category.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('seller.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $category->name) }}" required>

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">Slug (otomatis)</label>
                <input type="text" name="slug" id="slug"
                       class="form-control bg-light"
                       value="{{ old('slug', $category->slug) }}" readonly>
            </div>
            <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
                </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="fas fa-save"></i> Update Kategori
            </button>

        </form>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/seller/category/edit.js') }}"></script>
@endpush

@endsection
