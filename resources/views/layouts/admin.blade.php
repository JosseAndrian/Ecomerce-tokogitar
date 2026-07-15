<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Musik Store')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .admin-sidebar {
            min-height: calc(100vh - 56px);
            background-color: #343a40;
            color: white;
        }
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.8rem 1rem;
        }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        .admin-sidebar .nav-link i { margin-right: 10px; }
        .toast-container { z-index: 1060; }
    </style>
</head>
<body class="bg-light">

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-music-note-beamed text-primary me-2"></i>Musik Store Admin
            </a>
            
            <div class="d-flex align-items-center ms-auto text-light">
                <div class="dropdown">
                    <a href="#" class="text-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}"><i class="bi bi-person-gear me-2"></i>Profil Saya</a></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank"><i class="bi bi-globe me-2"></i>Lihat Website</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 admin-sidebar d-none d-md-block shadow">
                <div class="p-3">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 px-3" style="font-size: 0.75rem;">Menu Utama</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                <i class="bi bi-tags"></i> Kategori
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                                <i class="bi bi-box-seam"></i> Produk
                            </a>
                        </li>
                    </ul>

                    <h6 class="text-uppercase text-muted fw-bold mb-3 px-3" style="font-size: 0.75rem;">Transaksi</h6>
                    <ul class="nav flex-column mb-4">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                                <i class="bi bi-cart-check"></i> Pesanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                                <i class="bi bi-people"></i> Pelanggan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                                <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}" href="{{ route('admin.chat.index') }}">
                                <i class="bi bi-chat-dots"></i> Pesan Pelanggan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @if(session('success'))
            <div class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS Bundle -->
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

    <!-- Firebase Realtime Order Listener -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getDatabase, ref, onChildAdded, query, orderByChild, limitToLast } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

        const firebaseConfig = {
            databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
        };
        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);

        console.log("🔔 Firebase Listener aktif - menunggu pesanan baru...");

        // Catat waktu halaman dibuka (timestamp JS lokal)
        const pageLoadTime = Date.now();
        let isInitialLoad = true;

        const ordersRef = ref(db, 'orders');
        // Ambil 1 data terakhir saja, lalu dengarkan data baru berikutnya
        const recentQuery = query(ordersRef, limitToLast(1));

        onChildAdded(recentQuery, (snapshot) => {
            // Skip data yang sudah ada saat halaman pertama kali dimuat
            if (isInitialLoad) {
                isInitialLoad = false;
                console.log("📋 Data existing di-skip, menunggu pesanan baru...");
                return;
            }

            const order = snapshot.val();
            console.log("🛒 Pesanan baru masuk!", order);

            const toastHtml = `
            <div class="toast align-items-center text-bg-primary border-0 shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body fw-bold">
                        <i class="bi bi-bell-fill me-2 fs-5"></i> Pesanan Baru Masuk!
                        <br><span class="fw-normal">#${order.order_code || 'N/A'} - ${order.user_name || 'Customer'}</span>
                        <br><small class="fw-normal">Total: Rp ${parseInt(order.total_price || 0).toLocaleString('id-ID')}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
            
            const container = document.querySelector('.toast-container');
            if (container) {
                container.insertAdjacentHTML('beforeend', toastHtml);
                const newToast = container.lastElementChild;
                new bootstrap.Toast(newToast, { autohide: true, delay: 10000 }).show();
            }

            // Juga play sound notification jika tersedia
            try {
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YVoGAACBhYqFbF1fdH2JjIyGfnN1eYOIi4qFfXhzdX2FiouKhnx0c3l/hYmLioN7dHV5gIaMi4eDenR1eICGi4qJgnp0dXqBh4uKiIB5c3Z7goiLiomAeHN2e4OJi4qIf3dzdnuEiouKh391c3d8hIqMiod+dHN4fIWKjIqGfXNzeX2Gi4yKhXxzc3l+houMioR7cnN6f4aLjImEenJ0e3+HjIyJg3lxdHuAiIyMiIN4cXR8gIiMjIiCd3F1fIGJjIyHgXZxdX2BiYyMh4B2cXV9gomMjIeAdnF1fYKJjIyHf3Vxdn6DioyMhn90cXZ+g4qMjIZ+c3F3foSKjIyFfXJxd3+EioyMhXxycXeAhYuMjIV8cXF3gIWLjIyFe3FyeICFi4yMhHtxcniAhYuNjIR7cHJ4gYaMjIyDenByeYGGjIyMg3lwcnmBhoyMjIN5cHJ5goaMjIuCeXByeoKGjIyLgXhwcnqChoyMi4F4cHN6g4eMjIqBd3BzeoOHjIyKgHdwc3uDh4yMioB3cHN7hIeMjIl/dnBze4SHjYyJfnZwdHuEh42MiH52cHR8hIeNjIh+dXB0fISIjYyIfXVwdHyFiI2MiH11b3R9hYiNjId8dW91fYWIjYyHfHVvdX2FiI2Mh3x1b3V9hYiNjId8dW91fYWIjYyHfHVvdX6GiI2Mhnx0b3Z+hoiNjIZ7dG92foaIjYyGe3Rvdn6GiI6Mhnt0b3Z+h4iOjIV7dG93f4eIjoyFe3Nvd3+HiI6MhXpzb3d/h4mOjIV6c293f4eJjoyFenNvd3+HiY6MhHpzb3eAiImOjIR5c294gIiJjoyEeXNveICIiY6MhHlzb3iAiImOjIR5c294gIiJjoyDeXNveICIiY6Mg3lzb3iAiImPjIN4cm94gYmJj4yDeHJveIGJiY+Mg3hyb3iBiYmPjINBAgEB');
                audio.volume = 0.5;
                audio.play().catch(() => {});
            } catch(e) {}
        });
    </script>
    
    @stack('scripts')
</body>
</html>
