@extends('layouts.app')

@section('title', 'Chat dengan Admin - Musik Store')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-3 d-flex align-items-center">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="bi bi-robot fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">MusikBot AI &amp; Customer Service</h6>
                        <small class="opacity-75">Admin Musik Store • Online</small>
                    </div>
                </div>
                
                <div class="card-body p-0" style="height: 450px; display: flex; flex-direction: column;">
                    <!-- Chat Area -->
                    <div id="chat-box" class="p-4" style="flex: 1; overflow-y: auto; background-color: #f0f2f5;">
                        @if($messages->isEmpty())
                            <div class="text-center mt-5 text-muted">
                                <i class="bi bi-chat-dots display-1 opacity-25"></i>
                                <p class="mt-3 fw-semibold">Halo! Ada yang bisa kami bantu hari ini?</p>
                                <p class="small text-muted">Coba tanyakan: <em>"ada gitar?"</em>, <em>"cara pesan"</em>, atau <em>"lokasi toko"</em></p>
                            </div>
                        @else
                            @foreach($messages as $msg)
                                <div class="mb-3 d-flex {{ $msg->is_from_admin ? 'justify-content-start' : 'justify-content-end' }}">
                                    @if($msg->is_from_admin)
                                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2 flex-shrink-0 align-self-end shadow-sm" style="width:36px;height:36px;border:1px solid #e0e0e0;">
                                            @if($msg->is_bot)
                                                <i class="bi bi-robot" style="font-size:1rem;"></i>
                                            @else
                                                <i class="bi bi-headset" style="font-size:1rem;"></i>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="d-flex flex-column {{ $msg->is_from_admin ? 'align-items-start' : 'align-items-end' }}" style="max-width: 75%;">
                                        @if($msg->is_from_admin)
                                            <small class="text-muted fw-bold mb-1 ps-1" style="font-size: 0.7rem;">
                                                {{ $msg->is_bot ? 'MusikBot AI' : 'Admin Musik Store' }}
                                            </small>
                                        @endif
                                        
                                        <div class="p-3 rounded-4 shadow-sm {{ $msg->is_from_admin ? 'bg-white text-dark border' : 'bg-primary text-white' }}" 
                                             style="border-bottom-{{ $msg->is_from_admin ? 'left' : 'right' }}-radius: 0;">
                                            @if($msg->is_from_admin)
                                                {{-- Bot/Admin message: contains HTML links, render directly + nl2br --}}
                                                <div class="chat-bot-msg mb-1" style="font-size: 0.95rem; line-height: 1.5;">{!! nl2br($msg->message) !!}</div>
                                            @else
                                                {{-- User message: always escape to prevent XSS --}}
                                                <p class="mb-1" style="font-size: 0.95rem;">{{ $msg->message }}</p>
                                            @endif
                                            <div class="text-end mt-1">
                                                <small class="{{ $msg->is_from_admin ? 'text-muted' : 'text-white-50' }}" style="font-size: 0.7rem;">{{ $msg->created_at->format('H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Input Area -->
                    <div class="p-3 border-top bg-white">
                        <form action="{{ route('chat.send') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="message" class="form-control rounded-pill border-light bg-light px-4" 
                                   placeholder="Ketik pesan Anda di sini..." required autocomplete="off">
                            <button type="submit" class="btn btn-primary rounded-circle p-2" style="width: 45px; height: 45px;">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Bot message link styling */
    .chat-bot-msg a.bot-product-link,
    .chat-bot-msg a {
        color: #2563eb;
        font-weight: 600;
        text-decoration: underline;
        text-decoration-color: rgba(37,99,235,0.35);
        transition: color 0.2s;
    }
    .chat-bot-msg a.bot-product-link:hover,
    .chat-bot-msg a:hover {
        color: #7c3aed;
        text-decoration-color: #7c3aed;
    }
</style>

@push('scripts')
<script>
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
@endsection

