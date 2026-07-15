<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Musik Store - E-Commerce Alat Musik')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main { flex: 1; }
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        /* Premium Translucent Glassmorphism Navbar */
        .navbar-custom {
            background: rgba(18, 18, 18, 0.85) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
            padding: 12px 0;
        }
        
        .navbar-brand-custom {
            font-weight: 800;
            font-size: 1.35rem;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #3b82f6 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .navbar-brand-custom i {
            background: linear-gradient(135deg, #3b82f6, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-right: 8px;
            filter: drop-shadow(0 2px 8px rgba(59, 130, 246, 0.4));
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }
        .navbar-brand-custom:hover {
            filter: brightness(1.15) drop-shadow(0 0 12px rgba(168, 85, 247, 0.35));
            transform: scale(1.02);
        }
        
        /* Modern Navigation Links */
        .nav-link-custom {
            font-weight: 500;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.75) !important;
            padding: 8px 16px !important;
            margin: 0 4px;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .nav-link-custom:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }
        .nav-link-custom.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(168, 85, 247, 0.15));
            border: 1px solid rgba(168, 85, 247, 0.25);
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.08);
        }
        
        /* Auth & Profile buttons */
        .nav-link-login {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 16px !important;
        }
        .nav-link-login:hover {
            color: #3b82f6 !important;
        }
        .btn-register-custom {
            background: linear-gradient(135deg, #3b82f6, #a855f7);
            border: none;
            color: white !important;
            font-weight: 600;
            padding: 8px 20px !important;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-register-custom:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 6px 20px rgba(168, 85, 247, 0.4);
            filter: brightness(1.1);
        }

        /* Cart Link custom style */
        .cart-link-custom {
            color: rgba(255, 255, 255, 0.85) !important;
            padding: 8px 12px !important;
            border-radius: 50%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cart-link-custom:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #3b82f6 !important;
        }

        /* Dropdown custom styling */
        .dropdown-menu-custom {
            background: rgba(24, 24, 27, 0.95) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3) !important;
            padding: 6px !important;
            margin-top: 10px !important;
        }
        .dropdown-item-custom {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 8px 16px !important;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }
        .dropdown-item-custom:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(168, 85, 247, 0.12)) !important;
            color: #ffffff !important;
        }
        .dropdown-item-custom.text-danger:hover {
            background: rgba(239, 68, 68, 0.15) !important;
            color: #ef4444 !important;
        }
        .hover-white:hover { color: white !important; }
        .toast-container { z-index: 1060; }
        
        /* Fly Animation */
        .fly-item {
            position: fixed;
            z-index: 9999;
            width: 50px;
            height: 50px;
            object-fit: contain;
            background: white;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            pointer-events: none;
            transition: all 0.8s cubic-bezier(0.19, 1, 0.22, 1);
        }

        /* Bot message link styling (Chat page & Widget) */
        .chat-bot-msg a, .chat-widget-msg a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: underline;
            text-decoration-color: rgba(37,99,235,0.4);
            transition: color 0.2s ease;
        }
        .chat-bot-msg a:hover, .chat-widget-msg a:hover {
            color: #7c3aed;
            text-decoration-color: #7c3aed;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top navbar-custom shadow-sm">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom" href="{{ route('home') }}">
                <i class="bi bi-music-note-beamed"></i>Musik Store
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('shop.*') ? 'active' : '' }}" href="{{ route('shop.index') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ request()->routeIs('chat.*') ? 'active' : '' }}" href="{{ route('chat.index') }}">Chat Admin</a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        @if(auth()->user()->isCustomer())
                            <li class="nav-item me-3">
                                <a href="{{ route('cart.index') }}" class="nav-link position-relative cart-link-custom" id="cart-nav-link">
                                    <i class="bi bi-cart3 fs-5"></i>
                                    @php
                                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                                    @endphp
                                    <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartCount > 0 ? '' : 'd-none' }}" style="font-size: 0.65em;">
                                        {{ $cartCount }}
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle nav-link-custom" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow dropdown-menu-custom">
                                    <li><a class="dropdown-item py-2 dropdown-item-custom" href="{{ route('profile.index') }}"><i class="bi bi-person-gear me-2 text-primary"></i>Pengaturan Profil</a></li>
                                    <li><a class="dropdown-item py-2 dropdown-item-custom" href="{{ route('orders.index') }}"><i class="bi bi-box-seam me-2 text-primary"></i>Pesanan Saya</a></li>
                                    <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item dropdown-item-custom text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-register-custom btn-sm">Admin Dashboard</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link nav-link-login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm ms-2 px-4 rounded-pill btn-register-custom">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-auto">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-4">
                    <h5 class="mb-3 text-white"><i class="bi bi-music-note-beamed me-2 text-primary"></i>Musik Store</h5>
                    <p class="text-secondary">Toko alat musik terpercaya dengan koleksi terlengkap dan harga terbaik di Indonesia.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3 text-white">Menu Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-secondary text-decoration-none hover-white">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('shop.index') }}" class="text-secondary text-decoration-none hover-white">Katalog Produk</a></li>
                        @guest
                            <li class="mb-2"><a href="{{ route('login') }}" class="text-secondary text-decoration-none hover-white">Login Member</a></li>
                        @else
                            <li class="mb-2"><a href="{{ route('orders.index') }}" class="text-secondary text-decoration-none hover-white">Pesanan Saya</a></li>
                        @endguest
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3 text-white">Kontak Kami</h5>
                    <ul class="list-unstyled text-secondary">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Jl. Margonda Raya No. 100, Depok</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> +62 812-3456-7890</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> cs@musikstore.com</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary mt-4 mb-3">
            <div class="text-center text-secondary small">
                &copy; {{ date('Y') }} Musik Store. All rights reserved. <br> Tugas Akhir Sistem Informasi / Teknik Informatika.
            </div>
        </div>
    </footer>

    <!-- Toast Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function (toastEl) {
                var toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
                toast.show();
                return toast;
            });
        });
    </script>
    @stack('scripts')
    <!-- AI Chatbot Widget -->
    <div id="ai-chatbot" class="position-fixed bottom-0 end-0 m-4 z-3">
        <button id="chatbot-toggle" class="btn btn-primary rounded-circle shadow-lg p-3" style="width: 60px; height: 60px;">
            <i class="bi bi-robot fs-2"></i>
        </button>
        
        <div id="chatbot-window" class="card shadow-lg border-0 rounded-4 d-none" style="width: 350px; height: 450px; bottom: 80px; position: absolute; right: 0;">
            <div class="card-header bg-primary text-white p-3 rounded-top-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="bi bi-robot me-2 fs-4"></i>
                    <div>
                        <h6 class="mb-0 fw-bold">MusikBot AI</h6>
                        <small class="opacity-75">Online | Siap Membantu</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" id="chatbot-close"></button>
            </div>
            <div class="card-body p-0 d-flex flex-column" style="height: 400px;">
                <div class="p-2 border-bottom bg-light d-flex justify-content-center">
                    <a href="{{ route('chat.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-chat-dots me-1"></i> Hubungi Admin
                    </a>
                </div>
                <div id="chatbot-messages" class="p-3 overflow-auto flex-grow-1" style="background: #f8f9fa;">
                    <div class="mb-3 text-start">
                        <div class="d-inline-block p-2 rounded-3 bg-white shadow-sm small text-dark">
                            Halo! Saya MusikBot 🤖. Mau cari gitar, piano, atau tanya rekomendasi alat musik yang pas buat budget kamu?
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white p-3 rounded-bottom-4">
                <form id="chatbot-form">
                    <div class="input-group">
                        <input type="text" id="chatbot-input" class="form-control border-0 bg-light rounded-pill-start" placeholder="Tanya sesuatu..." autocomplete="off">
                        <button class="btn btn-primary rounded-pill-end px-3" type="submit">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('chatbot-toggle');
            const window = document.getElementById('chatbot-window');
            const close = document.getElementById('chatbot-close');
            const form = document.getElementById('chatbot-form');
            const input = document.getElementById('chatbot-input');
            const messages = document.getElementById('chatbot-messages');

            toggle.addEventListener('click', () => window.classList.toggle('d-none'));
            close.addEventListener('click', () => window.classList.add('d-none'));

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const text = input.value.trim();
                if (!text) return;

                // Add user message
                addMessage(text, 'user');
                input.value = '';

                // Add typing indicator
                const typingId = 'typing-' + Date.now();
                messages.innerHTML += `<div class="mb-3 text-start" id="${typingId}">
                    <div class="d-inline-block p-2 rounded-3 bg-white shadow-sm small text-muted">
                        <span class="spinner-grow spinner-grow-sm"></span> MusikBot sedang berpikir...
                    </div>
                </div>`;
                messages.scrollTop = messages.scrollHeight;

                try {
                    const response = await fetch('{{ route("ai.chat") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ message: text })
                    });
                    const data = await response.json();
                    
                    document.getElementById(typingId).remove();
                    addMessage(data.response, 'bot');
                } catch (error) {
                    document.getElementById(typingId).remove();
                    addMessage('Maaf, saya sedang mengalami gangguan teknis. Bisa ulangi?', 'bot');
                }
            });

            function addMessage(text, sender) {
                const align = sender === 'user' ? 'text-end' : 'text-start';
                const bg = sender === 'user' ? 'bg-primary text-white' : 'bg-white text-dark';
                // Bot messages may contain HTML links — render raw; user messages use textContent
                let contentHtml;
                if (sender === 'bot') {
                    // Replace \n with <br> for line breaks, allow HTML from trusted server source
                    contentHtml = text.replace(/\n/g, '<br>');
                } else {
                    // Escape user text to prevent XSS
                    const div = document.createElement('div');
                    div.textContent = text;
                    contentHtml = div.innerHTML;
                }
                const msgDiv = document.createElement('div');
                msgDiv.className = `mb-3 ${align}`;
                msgDiv.innerHTML = `<div class="d-inline-block p-2 rounded-3 ${bg} shadow-sm small chat-widget-msg" style="max-width: 80%; text-align:left;">${contentHtml}</div>`;
                messages.appendChild(msgDiv);
                messages.scrollTop = messages.scrollHeight;
            }
        });
    </script>
    <!-- Variation Modal (Global) -->
    <div class="modal fade" id="variationModal" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalProductName">Pilih Variasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center mb-3 p-3 bg-light rounded-4 mx-3">
                            <img id="modalProductImage" src="" class="rounded shadow-sm" style="height: 160px; width: 160px; object-fit: contain; background: white;">
                        </div>
                        <input type="hidden" name="product_id" id="modalProductId">
                        <div class="mb-4 px-2 text-center">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2 d-block">Jumlah:</label>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-outline-primary rounded-circle p-0" style="width: 32px; height: 32px;" onclick="stepQty('modal-qty', -1)">-</button>
                                <input type="number" name="quantity" id="modal-qty" class="form-control form-control-sm text-center border-0 fw-bold mx-2" value="1" min="1" style="width: 60px; background: transparent;" readonly>
                                <button type="button" class="btn btn-outline-primary rounded-circle p-0" style="width: 32px; height: 32px;" onclick="stepQty('modal-qty', 1)">+</button>
                            </div>
                        </div>
                        <div class="mb-4 px-2">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2">Variasi Tersedia:</label>
                            <div id="modalVariationOptions" class="d-flex flex-wrap gap-2">
                                <!-- Options via JS -->
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center bg-primary text-white p-3 rounded-4 shadow-sm mx-2">
                            <span class="fw-bold small opacity-75">HARGA</span>
                            <h4 class="fw-bold mb-0" id="modalDisplayPrice">-</h4>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm w-100 mx-2">
                            <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function stepQty(id, delta) {
            const input = document.getElementById(id);
            if (!input) return;
            let val = parseInt(input.value) + delta;
            if (val < 1) val = 1;
            input.value = val;
        }

        // AJAX Add to Cart & Fly Animation
        async function handleAddToCart(form, event) {
            event.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnContent = submitBtn.innerHTML;
            
            // Animasi Loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menambah...';
            
            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.status === 401) {
                    showToast('Silakan login terlebih dahulu untuk menambahkan produk ke keranjang.', 'danger');
                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}?redirect=" + encodeURIComponent(window.location.href);
                    }, 1500);
                    return;
                }
                
                const result = await response.json();
                
                if (result.success) {
                    // Jalankan Animasi Terbang
                    const cartIcon = document.querySelector('#cart-nav-link');
                    // Cari gambar produk terdekat
                    let productImg = form.closest('.card')?.querySelector('img') || 
                                   document.querySelector('#modalProductImage') || 
                                   document.querySelector('.product-detail-img');
                    
                    if (productImg && cartIcon) {
                        const imgClone = productImg.cloneNode();
                        const imgRect = productImg.getBoundingClientRect();
                        const cartRect = cartIcon.getBoundingClientRect();
                        
                        imgClone.classList.add('fly-item');
                        imgClone.style.top = imgRect.top + 'px';
                        imgClone.style.left = imgRect.left + 'px';
                        imgClone.style.width = imgRect.width + 'px';
                        imgClone.style.height = imgRect.height + 'px';
                        
                        document.body.appendChild(imgClone);
                        
                        setTimeout(() => {
                            imgClone.style.top = cartRect.top + 'px';
                            imgClone.style.left = cartRect.left + 'px';
                            imgClone.style.width = '20px';
                            imgClone.style.height = '20px';
                            imgClone.style.opacity = '0.5';
                        }, 50);
                        
                        setTimeout(() => {
                            imgClone.remove();
                            // Update Badge
                            const badge = document.querySelector('#cart-badge');
                            if (badge) {
                                badge.textContent = result.cart_count;
                                badge.classList.remove('d-none');
                            }
                        }, 800);
                    }
                    
                    // Show Toast
                    showToast(result.message, 'success');
                    
                    // Tutup modal jika terbuka
                    const modal = bootstrap.Modal.getInstance(document.getElementById('variationModal'));
                    if (modal) modal.hide();
                    
                } else {
                    showToast(result.message || 'Gagal menambahkan produk.', 'danger');
                }
            } catch (error) {
                console.error(error);
                showToast('Terjadi kesalahan koneksi.', 'danger');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnContent;
            }
        }

        function showToast(message, type = 'success') {
            const toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) return;
            
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
            toast.show();
            
            toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Global listener untuk form add to cart
            document.addEventListener('submit', function(e) {
                if (e.target.matches('form[action$="/cart/add"]')) {
                    handleAddToCart(e.target, e);
                }
            });

            const variationModalElem = document.getElementById('variationModal');
            if (!variationModalElem) return;
            
            const modal = new bootstrap.Modal(variationModalElem);
            const variationContainer = document.getElementById('modalVariationOptions');
            const modalPrice = document.getElementById('modalDisplayPrice');
            const modalImage = document.getElementById('modalProductImage');
            const modalName = document.getElementById('modalProductName');
            const modalId = document.getElementById('modalProductId');

            // Delegasi event untuk tombol buka modal (mendukung konten dinamis)
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.open-variation-modal');
                if (btn) {
                    const id = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name');
                    const image = btn.getAttribute('data-image');
                    const variations = JSON.parse(btn.getAttribute('data-variations'));

                    modalId.value = id;
                    modalName.innerText = name;
                    modalImage.src = image;
                    variationContainer.innerHTML = '';
                    modalPrice.innerText = '-';

                    variations.forEach((v, index) => {
                        const wrapper = document.createElement('div');
                        const radioId = `m-var-global-${id}-${index}`;
                        const isOutOfStock = v.stock <= 0;
                        const isChecked = (!isOutOfStock && index === 0) ? 'checked' : '';
                        const disabledAttr = isOutOfStock ? 'disabled' : '';
                        const stockLabel = isOutOfStock ? ' <small>(Habis)</small>' : '';
                        
                        wrapper.innerHTML = `
                            <input type="radio" class="btn-check modal-var-radio" name="variation" id="${radioId}" value="${v.name}" 
                                data-price="${v.formatted_price}" data-img="${v.image ? '/storage/'+v.image : image}" 
                                data-stock="${v.stock}" ${isChecked} ${disabledAttr} required>
                            <label class="btn btn-outline-primary rounded-pill px-3 py-2 ${isOutOfStock ? 'opacity-50' : ''}" for="${radioId}">
                                ${v.name}${stockLabel}
                            </label>
                        `;
                        variationContainer.appendChild(wrapper);

                        // Set initial if first available
                        if (isChecked) {
                            modalPrice.innerText = v.formatted_price;
                            if (v.image) modalImage.src = '/storage/' + v.image;
                        }
                    });

                    // Disable submit if no variation is checked (all out of stock)
                    const checkedRadio = variationContainer.querySelector('.modal-var-radio:checked');
                    const submitBtn = variationModalElem.querySelector('button[type="submit"]');
                    if (!checkedRadio) {
                        submitBtn.disabled = true;
                        submitBtn.innerText = "Stok Habis";
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.innerText = "Tambah ke Keranjang";
                    }

                    // Listener untuk perubahan variasi
                    variationContainer.querySelectorAll('.modal-var-radio').forEach(radio => {
                        radio.addEventListener('change', function() {
                            if (this.checked) {
                                modalPrice.innerText = this.getAttribute('data-price');
                                modalImage.src = this.getAttribute('data-img');
                            }
                        });
                    });

                    modal.show();
                }
            });
        });
    </script>
</body>
</html>
