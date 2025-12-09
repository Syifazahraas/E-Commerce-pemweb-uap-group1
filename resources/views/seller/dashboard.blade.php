@extends('layouts.seller')

@section('title', 'Dashboard')

@section('content')

<div class="page-title">
    <i class="fas fa-chart-pie"></i>
    Dashboard Penjualan
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <!-- Total Products Card -->
    <div class="col-lg-4 col-md-6">
        <div class="stat-card blue">
            <div>
                <div class="stat-card-content">
                    <h5>Total Produk</h5>
                    <h2>{{ $totalProducts }}</h2>
                </div>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders Card -->
    <div class="col-lg-4 col-md-6">
        <div class="stat-card cyan">
            <div>
                <div class="stat-card-content">
                    <h5>Pesanan Masuk</h5>
                    <h2>{{ $totalOrders }}</h2>
                </div>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
    </div>

    <!-- Balance Card -->
    <div class="col-lg-4 col-md-6">
        <div class="stat-card green">
            <div>
                <div class="stat-card-content">
                    <h5>Saldo Toko</h5>
                    <h2>Rp {{ number_format($balance, 0, ',', '.') }}</h2>
                </div>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>
</div>

@endsection
