@extends('layouts.seller')

@section('title', 'Ajukan Penarikan Dana')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/withdrawal/create.css') }}">
@endpush

@section('content')

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
            Ajukan Penarikan Dana
        </h1>
        <p class="page-subtitle">Tarik saldo Anda ke rekening bank</p>
    </div>
    <a href="{{ route('seller.withdrawals.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<!-- Balance Info -->
<div class="card mb-4">
    <div class="card-body">
        <div class="balance-display">
            <div class="balance-icon-large">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <small class="balance-label">Saldo Tersedia</small>
                <h2 class="balance-amount-large">Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}</h2>
                <p class="text-muted mb-0" style="font-size: 13px;">
                    Minimum penarikan: Rp 10.000
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Form -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-file-invoice-dollar me-2"></i>
        Form Penarikan Dana
    </div>
    <div class="card-body">
        <form action="{{ route('seller.withdrawals.store') }}" method="POST" id="withdrawalForm">
            @csrf

            <!-- Amount -->
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-money-bill-wave text-primary me-2"></i>
                    Jumlah Penarikan
                </label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text">Rp</span>
                    <input type="number"
                           class="form-control @error('amount') is-invalid @enderror"
                           name="amount"
                           id="withdrawalAmount"
                           value="{{ old('amount') }}"
                           placeholder="0"
                           min="10000"
                           max="{{ $storeBalance->balance }}"
                           required>
                </div>
                @error('amount')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Maksimal: Rp {{ number_format($storeBalance->balance, 0, ',', '.') }}
                </small>
            </div>

            <hr class="my-4">

            <!-- Bank Information -->
            <h6 class="mb-3" style="color: #1e3a8a; font-weight: 700;">
                <i class="fas fa-university text-primary me-2"></i>
                Informasi Rekening Bank
            </h6>

            <div class="mb-3">
                <label class="form-label">Nama Bank</label>
                <select class="form-select @error('bank_name') is-invalid @enderror"
                        name="bank_name"
                        required>
                    <option value="">Pilih Bank</option>
                    <option value="BCA" {{ old('bank_name', $lastWithdrawal->bank_name ?? '') == 'BCA' ? 'selected' : '' }}>BCA</option>
                    <option value="Mandiri" {{ old('bank_name', $lastWithdrawal->bank_name ?? '') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                    <option value="BRI" {{ old('bank_name', $lastWithdrawal->bank_name ?? '') == 'BRI' ? 'selected' : '' }}>BRI</option>
                    <option value="BNI" {{ old('bank_name', $lastWithdrawal->bank_name ?? '') == 'BNI' ? 'selected' : '' }}>BNI</option>
                    <option value="CIMB Niaga" {{ old('bank_name', $lastWithdrawal->bank_name ?? '') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                    <option value="Permata" {{ old('bank_name', $lastWithdrawal->bank_name ?? '') == 'Permata' ? 'selected' : '' }}>Permata</option>
                    <option value="Danamon" {{ old('bank_name', $lastWithdrawal->bank_name ?? '') == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                    <option value="BTN" {{ old('bank_name', $lastWithdrawal->bank_name ?? '') == 'BTN' ? 'selected' : '' }}>BTN</option>
                    <option value="Lainnya" {{ old('bank_name', $lastWithdrawal->bank_name ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('bank_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Pemilik Rekening</label>
                <input type="text"
                       class="form-control @error('bank_account_name') is-invalid @enderror"
                       name="bank_account_name"
                       value="{{ old('bank_account_name', $lastWithdrawal->bank_account_name ?? '') }}"
                       placeholder="Nama sesuai KTP"
                       required>
                @error('bank_account_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Nomor Rekening</label>
                <input type="text"
                       class="form-control @error('bank_account_number') is-invalid @enderror"
                       name="bank_account_number"
                       value="{{ old('bank_account_number', $lastWithdrawal->bank_account_number ?? '') }}"
                       placeholder="1234567890"
                       required>
                @error('bank_account_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Warning Alert -->
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Perhatian:</strong>
                <ul class="mb-0 mt-2" style="padding-left: 20px;">
                    <li>Pastikan informasi rekening bank Anda sudah benar</li>
                    <li>Saldo akan otomatis dikurangi setelah pengajuan</li>
                    <li>Proses pencairan membutuhkan waktu 1-3 hari kerja setelah disetujui</li>
                    <li>Penarikan yang ditolak akan dikembalikan ke saldo</li>
                </ul>
            </div>

            <!-- Submit Button -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg flex-fill">
                    <i class="fas fa-paper-plane me-2"></i>
                    Ajukan Penarikan
                </button>
                <a href="{{ route('seller.withdrawals.index') }}" class="btn btn-secondary btn-lg">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/seller/withdrawal/create.js') }}"></script>
@endpush

@endsection
