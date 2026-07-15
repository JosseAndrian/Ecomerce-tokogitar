@extends('layouts.admin')

@section('title', 'Edit Produk - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Edit Produk</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Produk</label>
                        <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Produk</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="6" required>{{ old('description', $product->description) }}</textarea>
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
                    </div>

                    @push('scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const body = document.getElementById('variation-body');
                            const addBtn = document.getElementById('add-variation');
                            let rowIndex = 0;

                            function addRow(name = '', price = '', stock = '0', imageUrl = '', imagePath = '') {
                                const row = document.createElement('tr');
                                let imageDisplay = '';
                                if (imageUrl) {
                                    imageDisplay = `<div class="mb-1"><img src="${imageUrl}" class="rounded border" style="height: 40px; width: 40px; object-fit: contain;"></div>`;
                                }
                                
                                row.innerHTML = `
                                    <td><input type="text" name="var_names[${rowIndex}]" class="form-control" value="${name}" placeholder="Standard" required></td>
                                    <td><input type="number" name="var_prices[${rowIndex}]" class="form-control" value="${price}" placeholder="100000" required></td>
                                    <td><input type="number" name="var_stocks[${rowIndex}]" class="form-control" value="${stock}" min="0" required></td>
                                    <td>
                                        ${imageDisplay}
                                        <input type="file" name="var_images[${rowIndex}]" class="form-control form-control-sm" accept="image/*">
                                        <input type="hidden" name="var_existing_images[${rowIndex}]" value="${imagePath}">
                                    </td>
                                    <td class="text-center"><button type="button" class="btn btn-link text-danger remove-row"><i class="bi bi-trash"></i></button></td>
                                `;
                                body.appendChild(row);
                                rowIndex++;
                            }

                            // Load existing variations
                            const existing = @json($product->variations_array);
                            if (existing.length > 0) {
                                existing.forEach(v => {
                                    const url = v.image ? `/storage/${v.image}` : '';
                                    addRow(v.name, v.price, v.stock || 0, url, v.image || '');
                                });
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
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', (int)$product->price) }}" min="0">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Stok</label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" min="0">
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar Produk (Opsional)</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ $product->image_url }}" alt="Current Image" class="img-thumbnail" style="height: 100px;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar. Format: JPG, PNG.</div>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3 bg-light p-3 rounded-3 border">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_featured">
                                Produk Unggulan
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">
                                Status Aktif
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">Perbarui Produk</button>
        </form>
    </div>
</div>
@endsection
