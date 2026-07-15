@extends('layouts.app')

@section('title', 'Upload Pembayaran - Musik Store')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-cloud-arrow-up display-4 text-primary mb-3"></i>
                        <h3 class="fw-bold">Upload Bukti Pembayaran</h3>
                        <p class="text-muted">Pesanan: <strong class="text-dark">{{ $order->order_code }}</strong></p>
                    </div>

                    <div class="alert alert-info border-0 rounded-3 mb-4 text-center">
                        Total yang harus dibayar: <br>
                        <strong class="fs-4">{{ $order->formatted_total }}</strong>
                    </div>

                    <form action="{{ route('payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bank Tujuan Transfer</label>
                            <select name="bank_name" class="form-select @error('bank_name') is-invalid @enderror" required>
                                <option value="">Pilih Bank</option>
                                <option value="BCA">BCA</option>
                                <option value="Mandiri">Mandiri</option>
                            </select>
                            @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Pemilik Rekening Pengirim</label>
                            <input type="text" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ old('account_name', auth()->user()->name) }}" required>
                            @error('account_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nomor Rekening Pengirim</label>
                            <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}" required>
                            @error('account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Upload Bukti Transfer (Image)</label>
                            <input type="file" name="proof_image" class="form-control @error('proof_image') is-invalid @enderror" accept="image/*" required>
                            <div class="form-text">Format: JPG, PNG. Maksimal: 2MB.</div>
                            @error('proof_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm transition" onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span>Memproses Pembayaran...'; this.classList.add('disabled');">
                            Kirim Bukti Pembayaran
                        </button>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-link text-decoration-none w-100 text-center mt-3 text-muted">Kembali ke Pesanan</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
