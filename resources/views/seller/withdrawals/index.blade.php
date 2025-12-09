@extends('layouts.seller')

@section('title', 'Penarikan Dana')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/withdrawal/index.css') }}">
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
            <i class="fas fa-money-bill-wave"></i>
            Penarikan Dana
        </h1>
        <p class="page-subtitle">Kelola penarikan saldo toko Anda</p>
    </div>
    <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajukan Penarikan
    </a>
</div>

<!-- Balance Info Card -->
<div class="card mb-4">
    <div class="card-body">
        <div class="balance-info-container">
            <div class="balance-item">
                <div class="balance-icon balance-icon-green">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="balance-details">
                    <small class="balance-label">Saldo Tersedia</small>
                    <h3 class="balance-amount">Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}</h3>
                </div>
            </div>

            @if($lastWithdrawal)
            <div class="balance-divider"></div>

            <div class="balance-item">
                <div class="balance-icon balance-icon-blue">
                    <i class="fas fa-university"></i>
                </div>
                <div class="balance-details">
                    <small class="balance-label">Rekening Terdaftar</small>
                    <h6 class="mb-1">{{ $lastWithdrawal->bank_name }}</h6>
                    <p class="mb-0 text-muted" style="font-size: 13px;">
                        {{ $lastWithdrawal->bank_account_name }} - {{ $lastWithdrawal->bank_account_number }}
                    </p>
                </div>
            </div>
            @endif

            <div class="balance-divider"></div>

            <div class="balance-item">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bankAccountModal">
                    <i class="fas fa-edit"></i> Kelola Rekening
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal History -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-history me-2"></i>Riwayat Penarikan</span>
    </div>
    <div class="card-body">
        @if ($withdrawals->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Bank</th>
                            <th>Rekening</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($withdrawals as $withdrawal)
                            <tr>
                                <td>
                                    <div class="date-info">
                                        <strong>{{ $withdrawal->created_at->format('d M Y') }}</strong>
                                        <small class="d-block text-muted">
                                            {{ $withdrawal->created_at->format('H:i') }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="amount-badge">
                                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="bank-info">
                                        <strong>{{ $withdrawal->bank_name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="account-info">
                                        <div>{{ $withdrawal->bank_account_name }}</div>
                                        <small class="text-muted">{{ $withdrawal->bank_account_number }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($withdrawal->status === 'pending')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock me-1"></i>Menunggu
                                        </span>
                                    @elseif($withdrawal->status === 'approved')
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle me-1"></i>Disetujui
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times-circle me-1"></i>Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $withdrawals->links('vendor.pagination.custom') }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-money-bill-wave"></i>
                <h5 class="mt-3">Belum Ada Penarikan</h5>
                <p class="text-muted">Anda belum pernah mengajukan penarikan dana</p>
                <a href="{{ route('seller.withdrawals.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Ajukan Penarikan
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Bank Account Modal -->
<div class="modal fade" id="bankAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-university me-2"></i>
                    Kelola Rekening Bank
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('seller.withdrawals.updateBankAccount') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Bank</label>
                        <input type="text"
                               class="form-control"
                               name="bank_name"
                               value="{{ $lastWithdrawal->bank_name ?? '' }}"
                               placeholder="Contoh: BCA, Mandiri, BRI"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pemilik Rekening</label>
                        <input type="text"
                               class="form-control"
                               name="bank_account_name"
                               value="{{ $lastWithdrawal->bank_account_name ?? '' }}"
                               placeholder="Sesuai KTP"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Rekening</label>
                        <input type="text"
                               class="form-control"
                               name="bank_account_number"
                               value="{{ $lastWithdrawal->bank_account_number ?? '' }}"
                               placeholder="Nomor rekening"
                               required>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Informasi ini akan digunakan untuk penarikan dana berikutnya</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
