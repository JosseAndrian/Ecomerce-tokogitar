@extends('layouts.admin')

@section('title', 'Kategori Alat Musik - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Manajemen Kategori</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
        <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3" width="50">No</th>
                        <th class="py-3" width="80">Ikon</th>
                        <th class="py-3">Nama Kategori</th>
                        <th class="py-3">Slug</th>
                        <th class="py-3 text-center">Jumlah Produk</th>
                        <th class="pe-4 py-3 text-end" width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="ps-4 py-3">{{ $loop->iteration }}</td>
                        <td class="py-3 text-center">
                            <i class="bi {{ $category->icon }} fs-4 text-primary"></i>
                        </td>
                        <td class="py-3 fw-bold">{{ $category->name }}</td>
                        <td class="py-3 text-muted">{{ $category->slug }}</td>
                        <td class="py-3 text-center">
                            <span class="badge bg-secondary rounded-pill">{{ $category->products_count }}</span>
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Yakin ingin menghapus kategori ini?')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
