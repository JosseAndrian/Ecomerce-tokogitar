@extends('layouts.admin')

@section('title', 'Pelanggan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Manajemen Pelanggan</h1>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3" width="50">No</th>
                        <th class="py-3">Nama</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">No. HP</th>
                        <th class="py-3">Tanggal Bergabung</th>
                        <th class="pe-4 py-3 text-end" width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="ps-4 py-3">{{ $loop->iteration }}</td>
                        <td class="py-3 fw-bold">
                            <i class="bi bi-person-circle text-primary me-2"></i>{{ $customer->name }}
                        </td>
                        <td class="py-3">{{ $customer->email }}</td>
                        <td class="py-3">{{ $customer->phone ?? '-' }}</td>
                        <td class="py-3">{{ $customer->created_at->format('d M Y') }}</td>
                        <td class="pe-4 py-3 text-end">
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Yakin ingin menghapus data pelanggan ini?')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people display-4 d-block mb-3"></i>
                            Belum ada data pelanggan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
