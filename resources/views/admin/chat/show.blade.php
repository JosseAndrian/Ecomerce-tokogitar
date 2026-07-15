@extends('layouts.admin')

@section('title', 'Chat dengan ' . $user->name . ' - Admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.chat.index') }}" class="text-decoration-none small fw-bold text-muted">
            <i class="bi bi-arrow-left me-1"></i> KEMBALI KE DAFTAR CHAT
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="max-width: 900px;">
        <div class="card-header bg-dark text-white p-3 d-flex align-items-center">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                <i class="bi bi-person fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                <small class="opacity-75">Customer - {{ $user->email }}</small>
            </div>
        </div>
        
        <div class="card-body p-0" style="height: 500px; display: flex; flex-direction: column;">
            <!-- Chat Area -->
            <div id="chat-box" class="p-4" style="flex: 1; overflow-y: auto; background-color: #f8f9fa;">
                @foreach($messages as $msg)
                    <div class="mb-3 d-flex {{ $msg->is_from_admin ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="p-3 rounded-4 shadow-sm {{ $msg->is_from_admin ? 'bg-primary text-white' : 'bg-white text-dark' }}" 
                             style="max-width: 80%; border-bottom-{{ $msg->is_from_admin ? 'right' : 'left' }}-radius: 0;">
                            <p class="mb-1">{{ $msg->message }}</p>
                            <small class="opacity-50" style="font-size: 0.7rem;">{{ $msg->created_at->format('d M, H:i') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Input Area -->
            <div class="p-3 border-top bg-white">
                <form action="{{ route('admin.chat.send', $user->id) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="message" class="form-control rounded-pill border-light bg-light px-4" 
                           placeholder="Ketik balasan Anda..." required autocomplete="off" autofocus>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-send-fill me-2"></i>Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
@endsection
