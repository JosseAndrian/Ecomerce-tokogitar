@extends('layouts.admin')

@section('title', 'Tambah Produk - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Tambah Produk Baru</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Produk</label>
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Produk</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="6" required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Variasi Produk & Harga Khusus</label>
                        <div id="variation-container">
                            <table class="table table-bordered" id="variation-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nama Variasi</th>
                                        <th>Harga (Rp)</th>
                                        <th>Stok</th>
                                        <th>Gambar (Opsional)</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="variation-body">
                                    <!-- Baris diisi via JS -->
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" id="add-variation">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Variasi
                            </button>
                        </div>
                        <div class="form-text mt-2 text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Jika variasi diisi, total stok produk akan dihitung otomatis dari jumlah stok variasi.
                        </div>
                    </div>

                    @push('scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const body = document.getElementById('variation-body');
                            const addBtn = document.getElementById('add-variation');
                            let rowIndex = 0;

                            function addRow(name = '', price = '', stock = '0') {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td><input type="text" name="var_names[${rowIndex}]" class="form-control" value="${name}" placeholder="Standard" required></td>
                                    <td><input type="number" name="var_prices[${rowIndex}]" class="form-control" value="${price}" placeholder="100000" required></td>
                                    <td><input type="number" name="var_stocks[${rowIndex}]" class="form-control" value="${stock}" min="0" required></td>
                                    <td><input type="file" name="var_images[${rowIndex}]" class="form-control form-control-sm" accept="image/*"></td>
                                    <td class="text-center"><button type="button" class="btn btn-link text-danger remove-row"><i class="bi bi-trash"></i></button></td>
                                `;
                                body.appendChild(row);
                                rowIndex++;
                            }

                            addBtn.addEventListener('click', () => addRow());
                            body.addEventListener('click', (e) => {
                                if (e.target.closest('.remove-row')) e.target.closest('tr').remove();
                            });
                        });
                    </script>
                    @endpush
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" min="0">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Stok</label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" min="0">
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar Produk</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <div class="form-text">Format: JPG, PNG. Maksimal: 2MB.</div>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3 bg-light p-3 rounded-3 border">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_featured">
                                Produk Unggulan
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">
                                Status Aktif
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">Simpan Produk</button>
        </form>
    </div>
</div>
@endsection
