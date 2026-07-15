@extends('layouts.admin')

@section('title', 'Tambah Kategori - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Tambah Kategori Baru</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Kategori</label>
                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Ikon (Bootstrap Icons)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-bootstrap"></i></span>
                    <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', 'bi-music-note') }}" placeholder="Contoh: bi-guitar">
                </div>
                <div class="form-text">Cari nama ikon di <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>.</div>
                @error('icon')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Deskripsi (Opsional)</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">Simpan Kategori</button>
        </form>
    </div>
</div>
@endsection
