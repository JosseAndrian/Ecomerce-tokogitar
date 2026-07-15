@extends('layouts.app')

@section('content')

<!-- Hero Banner -->
<div class="bg-dark text-white rounded-4 mx-3 mb-5 overflow-hidden position-relative shadow">
    <img src="https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?q=80&w=1920&auto=format&fit=crop" alt="Banner" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0" style="opacity: 0.4;">
    <div class="container position-relative py-5">
        <div class="row py-5">
            <div class="col-md-8 col-lg-6 py-5">
                <h1 class="display-4 fw-bold mb-3">Temukan Alat <br><span class="text-primary">Musikmu</span> Hari Ini</h1>
                <p class="lead mb-4 text-light">Koleksi alat musik premium dari brand ternama dunia. Harga terbaik, jaminan keaslian 100%, dan pengiriman aman ke seluruh Indonesia.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">Mulai Belanja <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Kategori -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Kategori Alat Musik</h3>
            <a href="{{ route('shop.index') }}" class="text-decoration-none fw-semibold">Lihat Semua Kategori</a>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            @foreach($categories as $category)
            <div class="col">
                <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm text-center py-4 category-card hover-primary transition">
                        <div class="card-body">
                            <i class="bi {{ $category->icon }} display-5 text-primary mb-3"></i>
                            <h6 class="card-title text-dark fw-bold mb-0">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} Produk</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
    <div class="mb-5 bg-white p-4 p-md-5 rounded-4 shadow-sm border border-light">
        <h3 class="fw-bold mb-4 text-center">Rekomendasi <span class="text-primary">Terbaik</span></h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @foreach($featuredProducts as $product)
            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0 position-relative">
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2 z-1 px-3 py-1 rounded-pill shadow-sm">Unggulan</span>
                    <a href="{{ route('shop.show', $product->slug) }}">
                        <img src="{{ $product->image_url }}" class="card-img-top p-3 bg-white" alt="{{ $product->name }}" style="height: 220px; object-fit: contain;" onerror="this.src='https://placehold.co/400x400?text=No+Image'">
                    </a>
                    <div class="card-body d-flex flex-column bg-light rounded-bottom-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">{{ $product->category->name }}</small>
                            @if($product->sold_count > 0)
                                <small class="text-muted small"><i class="bi bi-bag-check me-1"></i>Terjual {{ $product->sold_count }}</small>
                            @endif
                        </div>
                        <h6 class="card-title text-truncate fw-bold mb-2">
                            <a href="{{ route('shop.show', $product->slug) }}" class="text-dark text-decoration-none">{{ $product->name }}</a>
                        </h6>
                        <div class="mt-auto">
                            <h5 class="text-primary fw-bold mb-3">{{ $product->formatted_price }}</h5>
                            @if(count($product->variations_array) > 0)
                                <button type="button" class="btn btn-dark w-100 rounded-pill open-variation-modal" 
                                    data-id="{{ $product->id }}" 
                                    data-name="{{ $product->name }}"
                                    data-image="{{ $product->image_url }}"
                                    data-variations='@json($product->variations_array)'>
                                    <i class="bi bi-cart-plus me-2"></i>Keranjang
                                </button>
                            @else
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-dark w-100 rounded-pill {{ !$product->is_in_stock() ? 'disabled' : '' }}">
                                        <i class="bi bi-cart-plus me-2"></i>{{ $product->is_in_stock() ? 'Keranjang' : 'Habis' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Latest Products -->
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Produk Terbaru</h3>
            <a href="{{ route('shop.index') }}" class="text-decoration-none fw-semibold">Lihat Semua</a>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            @foreach($latestProducts as $product)
            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0">
                    <a href="{{ route('shop.show', $product->slug) }}">
                        <img src="{{ $product->image_url }}" class="card-img-top p-3 bg-white border-bottom" alt="{{ $product->name }}" style="height: 200px; object-fit: contain;" onerror="this.src='https://placehold.co/400x400?text=No+Image'">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">{{ $product->category->name }}</small>
                            @if($product->sold_count > 0)
                                <small class="text-muted"><i class="bi bi-bag-check me-1"></i>Terjual {{ $product->sold_count }}</small>
                            @endif
                        </div>
                        <h6 class="card-title text-truncate fw-bold mb-2">
                            <a href="{{ route('shop.show', $product->slug) }}" class="text-dark text-decoration-none">{{ $product->name }}</a>
                        </h6>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <h5 class="text-primary fw-bold mb-0" style="font-size: 1.1rem;">{{ $product->formatted_price }}</h5>
                            @if(count($product->variations_array) > 0)
                                <button type="button" class="btn btn-primary rounded-circle btn-sm p-2 open-variation-modal" 
                                    data-id="{{ $product->id }}" 
                                    data-name="{{ $product->name }}"
                                    data-image="{{ $product->image_url }}"
                                    data-variations='@json($product->variations_array)'
                                    title="Tambah ke Keranjang">
                                    <i class="bi bi-plus-lg fw-bold"></i>
                                </button>
                            @else
                                <form action="{{ route('cart.add') }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary rounded-circle btn-sm p-2 {{ !$product->is_in_stock() ? 'disabled' : '' }}" title="Tambah ke Keranjang">
                                        <i class="bi bi-plus-lg fw-bold"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
.category-card { transition: all 0.3s ease; }
.category-card:hover { 
    transform: translateY(-5px);
    background-color: #f8f9fa;
    border-color: var(--bs-primary) !important;
}
.product-card .card-img-top { transition: transform 0.3s ease; }
.product-card:hover .card-img-top { transform: scale(1.05); }
</style>
@endsection
