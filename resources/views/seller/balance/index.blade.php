@extends('layouts.seller')

@section('title', 'Saldo Toko')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/balance/balance.css') }}">
@endpush

@section('content')

<div class="page-title">
    <i class="fas fa-wallet"></i>
    Saldo Toko
</div>

<!-- Balance Overview -->
<div class="row mb-4">
    <!-- Current Balance Card -->
    <div class="col-lg-4 col-md-6">
        <div class="stat-card green">
            <div>
                <div class="stat-card-content">
                    <h5>Saldo Tersedia</h5>
                    <h2>Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}</h2>
                </div>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>

    <!-- Total Income Card -->
    <div class="col-lg-4 col-md-6">
        <div class="stat-card blue">
            <div>
                <div class="stat-card-content">
                    <h5>Total Pemasukan</h5>
                    <h2>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
                </div>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-arrow-down"></i>
            </div>
        </div>
    </div>

    <!-- Total Withdraw Card -->
    <div class="col-lg-4 col-md-6">
        <div class="stat-card cyan">
            <div>
                <div class="stat-card-content">
                    <h5>Total Penarikan</h5>
                    <h2>Rp {{ number_format($totalWithdraw, 0, ',', '.') }}</h2>
                </div>
            </div>
            <div class="stat-card-icon">
                <i class="fas fa-arrow-up"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="mb-1" style="color: #1e3a8a; font-weight: 700;">Kelola Saldo</h5>
                <p class="text-muted mb-0" style="font-size: 14px;">Tarik saldo atau lihat detail transaksi</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('seller.withdrawals.index') }}" class="btn btn-primary">
                    <i class="fas fa-money-bill-wave"></i> Tarik Saldo
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Balance History -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-history me-2"></i>Riwayat Saldo</span>
    </div>
    <div class="card-body">
        @if ($histories->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Referensi</th>
                            <th>Keterangan</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($histories as $history)
                            <tr>
                                <td>
                                    <div class="date-info">
                                        <strong>{{ $history->created_at->format('d M Y') }}</strong>
                                        <small class="d-block text-muted">
                                            {{ $history->created_at->format('H:i') }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $history->type === 'income' ? 'success' : 'warning' }}">
                                        <i class="fas fa-{{ $history->type === 'income' ? 'arrow-down' : 'arrow-up' }} me-1"></i>
                                        {{ $history->type === 'income' ? 'Pemasukan' : 'Penarikan' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="reference-info">
                                        <small class="text-muted d-block" style="font-size: 11px;">
                                            {{ $history->reference_type }}
                                        </small>
                                        <code style="font-size: 12px;">{{ Str::limit($history->reference_id, 20) }}</code>
                                    </div>
                                </td>
                                <td>
                                    <span class="remarks-text">{{ $history->remarks }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="amount-badge amount-{{ $history->type }}">
                                        {{ $history->type === 'income' ? '+' : '-' }}
                                        Rp {{ number_format($history->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $histories->links('vendor.pagination.custom') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                <h5 class="mt-3">Belum Ada Riwayat</h5>
                <p class="text-muted">Riwayat transaksi saldo Anda akan muncul di sini</p>
            </div>
        @endif
    </div>
</div>

@endsection
