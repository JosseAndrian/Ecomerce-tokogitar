@extends('layouts.admin')

@section('title', 'Produk - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Manajemen Produk</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
        <i class="bi bi-plus-lg me-2"></i>Tambah Produk
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3" width="50">No</th>
                        <th class="py-3">Produk</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Harga</th>
                        <th class="py-3 text-center">Stok</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="pe-4 py-3 text-end" width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4 py-3">{{ $loop->iteration }}</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded bg-light border p-1 me-3" style="width: 50px; height: 50px; object-fit: contain;">
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $product->name }}</h6>
                                    @if($product->is_featured)
                                        <span class="badge bg-danger rounded-pill" style="font-size: 0.65em;">Unggulan</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3">{{ $product->category->name }}</td>
                        <td class="py-3 fw-bold text-primary">{{ $product->formatted_price }}</td>
                        <td class="py-3 text-center">
                            @if($product->stock > 0)
                                <span class="badge bg-success rounded-pill">{{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger rounded-pill">Habis</span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            @if($product->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill">Aktif</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill">Nonaktif</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle btn-delete"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal"
                                data-product-name="{{ $product->name }}"
                                data-product-id="{{ $product->id }}"
                                title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-body text-center px-5 py-4">
                <div class="mb-3">
                    <div class="bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px;">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-2">Hapus Produk?</h4>
                <p class="text-muted mb-1">Anda yakin ingin menghapus produk:</p>
                <p class="fw-bold text-dark fs-5 mb-2" id="deleteProductName"></p>
                <p class="text-muted small mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Semua data terkait produk ini (pesanan, keranjang, review) juga akan dihapus. Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold">
                            <i class="bi bi-trash me-1"></i>Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productName = button.getAttribute('data-product-name');
            const productId = button.getAttribute('data-product-id');

            document.getElementById('deleteProductName').textContent = productName;
            document.getElementById('deleteForm').action = '/admin/products/' + productId;
        });
    });
</script>
@endpush
