@extends('layouts.admin')

@section('title', 'Daftar Chat Pelanggan - Admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Pesan Pelanggan</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="list-group list-group-flush">
            @forelse($chats as $chatUser)
            <a href="{{ route('admin.chat.show', $chatUser->id) }}" class="list-group-item list-group-item-action p-4 border-bottom d-flex align-items-center">
                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 55px; height: 55px;">
                    <i class="bi bi-person fs-3"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="fw-bold mb-0">{{ $chatUser->name }}</h5>
                        <small class="text-muted">{{ $chatUser->messages->max('created_at')->diffForHumans() }}</small>
                    </div>
                    <p class="text-muted mb-0 text-truncate" style="max-width: 500px;">
                        {{ $chatUser->messages->last()->message }}
                    </p>
                </div>
                @if($chatUser->unread_count > 0)
                    <span class="badge bg-danger rounded-pill ms-3 px-3 py-2">{{ $chatUser->unread_count }} Pesan Baru</span>
                @endif
            </a>
            @empty
            <div class="p-5 text-center text-muted">
                <i class="bi bi-chat-dots display-1 opacity-25"></i>
                <p class="mt-3">Belum ada percakapan dengan pelanggan.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
