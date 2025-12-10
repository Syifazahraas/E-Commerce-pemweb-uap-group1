@extends('layouts.seller')

@section('title', 'Detail Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/seller/order/orderShow.css') }}">
@endpush

@section('content')

{{-- SUCCESS ALERT --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div style="display: flex; gap: 12px; align-items: flex-start;">
            <i class="fas fa-check-circle" style="flex-shrink: 0; margin-top: 2px;"></i>
            <div>
                <strong>Berhasil!</strong>
                <p style="margin: 4px 0 0;">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ERROR ALERT --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div style="display: flex; gap: 12px; align-items: flex-start;">
            <i class="fas fa-times-circle" style="flex-shrink: 0; margin-top: 2px;"></i>
            <div>
                <strong>Error!</strong>
                <p style="margin: 4px 0 0;">{{ session('error') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">
            <i class="fas fa-receipt"></i> Detail Pesanan
        </h1>
        <p class="page-subtitle">{{ $order->code }}</p>
    </div>
    <a href="{{ route('seller.orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@php
    $courier = $order->tracking_number ? substr($order->tracking_number, 0, 3) : null;
@endphp

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">

        {{-- ORDER INFO --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>Informasi Pesanan
            </div>
            <div class="card-body">
                <div class="info-grid">

                    <div class="info-item">
                        <span class="info-label">Kode Pesanan</span>
                        <span class="info-value fw-bold">{{ $order->code }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Tanggal Pesanan</span>
                        <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Status Pembayaran</span>
                        @if($order->status === 'paid')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Dibayar
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock me-1"></i>Belum Dibayar
                            </span>
                        @endif
                    </div>

                    <div class="info-item">
                        <span class="info-label">Status Pengiriman</span>
                        @if($order->tracking_number)
                            <span class="badge bg-info text-dark">
                                <i class="fas fa-shipping-fast me-1"></i>Dikirim
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-box me-1"></i>Belum Dikirim
                            </span>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- PRODUCT LIST --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-box me-2"></i>Produk Pesanan
            </div>
            <div class="card-body">

                <div class="products-list">
                    @foreach($order->transactionDetails as $detail)
                        <div class="product-item">

                            <div class="product-image">
                                @if($detail->product->productImages->count())
                                    <img src="{{ asset('storage/' . $detail->product->productImages->first()->image) }}"
                                         alt="{{ $detail->product->name }}">
                                @else
                                    <div class="product-placeholder"><i class="fas fa-image"></i></div>
                                @endif
                            </div>

                            <div class="product-details">
                                <h6 class="product-name">{{ $detail->product->name }}</h6>
                                <p class="product-qty">{{ $detail->qty }} x Rp {{ number_format($detail->subtotal / $detail->qty, 0, ',', '.') }}</p>
                            </div>

                            <div class="product-subtotal">
                                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- BUYER INFO --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-user me-2"></i>Informasi Pembeli
            </div>
            <div class="card-body">

                <div class="buyer-info">
                    <div class="buyer-avatar">{{ substr($order->buyer->user->name, 0, 1) }}</div>

                    <div>
                        <h6 class="buyer-name">{{ $order->buyer->user->name }}</h6>
                        <p class="buyer-email">{{ $order->buyer->user->email }}</p>

                        @if($order->buyer->phone_number)
                            <p><i class="fas fa-phone me-2"></i>{{ $order->buyer->phone_number }}</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- SHIPPING ADDRESS --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-map-marker-alt me-2"></i>Alamat Pengiriman
            </div>
            <div class="card-body">
                <p>{{ $order->address }}</p>
                <p>{{ $order->city }}, {{ $order->postal_code }}</p>
            </div>
        </div>

    </div>

    <!-- Right Column -->
    <div class="col-lg-4">

        {{-- SHIPPING INFO --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-shipping-fast me-2"></i>Informasi Pengiriman
            </div>
            <div class="card-body">

                <div class="shipping-details">

                    <div class="shipping-item">
                        <span class="shipping-label">Kurir</span>
                        <span class="shipping-value fw-bold">
                            {{ $courier ?? '-' }}
                        </span>
                    </div>

                    <div class="shipping-item">
                        <span class="shipping-label">Layanan</span>
                        <span class="shipping-value">{{ $order->shipping_type }}</span>
                    </div>

                    <div class="shipping-item">
                        <span class="shipping-label">Biaya</span>
                        <span class="shipping-value">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>

                    @if($order->tracking_number)
                        <div class="shipping-item">
                            <span class="shipping-label">Nomor Resi</span>
                            <span class="shipping-value text-primary fw-bold">{{ $order->tracking_number }}</span>
                        </div>
                    @endif

                </div>

                @if($order->status === 'paid')
                    <button class="btn btn-primary w-100 mt-3"
                            data-bs-toggle="modal"
                            data-bs-target="#updateShippingModal">
                        <i class="fas fa-edit"></i>
                        {{ $order->tracking_number ? 'Update Info Pengiriman' : 'Input Nomor Resi' }}
                    </button>
                @endif

            </div>
        </div>

        {{-- PAYMENT SUMMARY --}}
        <div class="card">
            <div class="card-header">
                <i class="fas fa-money-bill-wave me-2"></i>Ringkasan Pembayaran
            </div>
            <div class="card-body">

                @php
                    $subtotal = $order->transactionDetails->sum('subtotal');
                @endphp

                <div class="summary-item">
                    <span>Subtotal Produk</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="summary-item">
                    <span>Biaya Pengiriman</span>
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>

                <div class="summary-item">
                    <span>Pajak</span>
                    <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-total">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- SHIPPING MODAL --}}
<div class="modal fade" id="updateShippingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shipping-fast me-2"></i>Update Informasi Pengiriman
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('seller.orders.updateShipping', $order->id) }}" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Kurir</label>
                        <select class="form-select" name="shipping" required>
                            <option value="">Pilih Kurir</option>
                            <option value="JNE" {{ $courier == 'JNE' ? 'selected' : '' }}>JNE</option>
                            <option value="J&T" {{ $courier == 'J&T' ? 'selected' : '' }}>J&T</option>
                            <option value="SCP" {{ $courier == 'SCP' ? 'selected' : '' }}>SiCepat</option>
                            <option value="ANT" {{ $courier == 'ANT' ? 'selected' : '' }}>Anteraja</option>
                            <option value="NIN" {{ $courier == 'NIN' ? 'selected' : '' }}>Ninja Express</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Resi</label>
                        <input type="text"
                               class="form-control"
                               name="tracking_number"
                               value="{{ $order->tracking_number }}"
                               placeholder="Contoh: JNE12345678"
                               required>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pastikan nomor resi sudah benar sebelum disimpan
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Batal</button>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
