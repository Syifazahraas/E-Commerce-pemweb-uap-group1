@extends('layouts.seller')

@section('title', 'Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/order/order.css') }}">
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
    <i class="fas fa-shopping-cart"></i>
    Kelola Pesanan
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="stat-card blue">
            <div>
                <div class="stat-card-content">
                    <h5>Total Pesanan</h5>
                    <h2>{{ $totalOrders }}</h2>
                </div>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="stat-card cyan">
            <div>
                <div class="stat-card-content">
                    <h5>Belum Dibayar</h5>
                    <h2>{{ $unpaidOrders }}</h2>
                </div>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="stat-card green">
            <div>
                <div class="stat-card-content">
                    <h5>Perlu Dikirim</h5>
                    <h2>{{ $unshippedOrders }}</h2>
                </div>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-shipping-fast"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('seller.orders.index') }}" method="GET" class="filter-form">
            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <input type="text"
                           class="form-control"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari kode pesanan...">
                </div>

                <div class="col-lg-3 col-md-6">
                    <select class="form-select" name="status">
                        <option value="">Semua Status Pembayaran</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="waiting_payment" {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>
                            Menunggu Pembayaran
                        </option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                            Sudah Dibayar
                        </option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>
                            Dikirim
                        </option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <select class="form-select" name="tracking_status">
                        <option value="">Semua Status Pengiriman</option>
                        <option value="unshipped" {{ request('tracking_status') == 'unshipped' ? 'selected' : '' }}>
                            Belum Dikirim
                        </option>
                        <option value="shipped" {{ request('tracking_status') == 'shipped' ? 'selected' : '' }}>
                            Sudah Dikirim
                        </option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('seller.orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders List -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-list me-2"></i>Daftar Pesanan
    </div>
    <div class="card-body">

        @if ($orders->count() > 0)
            <div class="orders-list">
                @foreach ($orders as $order)
                    <div class="order-item">
                        <div class="order-header">
                            <div class="order-info">
                                <h6 class="order-code">{{ $order->code }}</h6>
                                <small class="order-date">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </small>
                            </div>

                            <div class="order-status">

                                {{-- STATUS PEMBAYARAN SESUAI MIGRATION --}}
                                @if($order->status === 'paid')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle me-1"></i>Dibayar
                                    </span>
                                @elseif($order->status === 'waiting_payment')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock me-1"></i>Menunggu Pembayaran
                                    </span>
                                @elseif($order->status === 'pending')
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-clock me-1"></i>Pending
                                    </span>
                                @else
                                    <span class="badge badge-info">
                                        <i class="fas fa-info-circle me-1"></i>{{ ucfirst($order->status) }}
                                    </span>
                                @endif

                                {{-- STATUS PENGIRIMAN --}}
                                @if($order->tracking_number)
                                    <span class="badge badge-info">
                                        <i class="fas fa-shipping-fast me-1"></i>Dikirim
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-box me-1"></i>Belum Dikirim
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="order-content">
                            <div class="order-products">
                                @foreach($order->transactionDetails->take(2) as $detail)
                                    <div class="product-mini">
                                        @if($detail->product->productImages->count() > 0)
                                            <img src="{{ asset('storage/' . $detail->product->productImages->first()->image) }}"
                                                 alt="{{ $detail->product->name }}">
                                        @else
                                            <div class="product-mini-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                        <div class="product-mini-info">
                                            <span class="product-mini-name">{{ Str::limit($detail->product->name, 30) }}</span>
                                            <span class="product-mini-qty">{{ $detail->qty }}x</span>
                                        </div>
                                    </div>
                                @endforeach
                                @if($order->transactionDetails->count() > 2)
                                    <small class="text-muted">
                                        +{{ $order->transactionDetails->count() - 2 }} produk lainnya
                                    </small>
                                @endif
                            </div>

                            <div class="order-details">
                                <div class="order-detail-item">
                                    <span class="label">Pembeli:</span>
                                    <span class="value">{{ $order->buyer->user->name }}</span>
                                </div>
                                <div class="order-detail-item">
                                    <span class="label">Alamat:</span>
                                    <span class="value">{{ Str::limit($order->address, 50) }}</span>
                                </div>
                                <div class="order-detail-item">
                                    <span class="label">Pengiriman:</span>
                                    <span class="value">{{ $order->shipping_type }}</span>
                                </div>

                                @if($order->tracking_number)
                                    <div class="order-detail-item">
                                        <span class="label">Nomor Resi:</span>
                                        <span class="value fw-bold text-primary">{{ $order->tracking_number }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="order-total">
                                <span class="total-label">Total Pembayaran</span>
                                <span class="total-amount">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="order-actions">
                            <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links('vendor.pagination.custom') }}
            </div>

        @else
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <h5 class="mt-3">Belum Ada Pesanan</h5>
                <p class="text-muted">Pesanan dari pembeli akan muncul di sini</p>
            </div>
        @endif
    </div>
</div>

@endsection
