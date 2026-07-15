@extends('layouts.app')

@section('title', 'Katalog Produk - Musik Store')

@section('content')
<div class="container my-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0"><i class="bi bi-grid text-primary me-2"></i>Katalog Produk</h2>
            <p class="text-muted mb-0">Temukan alat musik impianmu</p>
        </div>
        <div class="col-md-6 mt-3 mt-md-0">
            <form action="{{ route('shop.index') }}" method="GET" class="d-flex shadow-sm rounded-pill overflow-hidden">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="text" name="search" class="form-control border-0 py-2 px-4" placeholder="Cari alat musik..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Kategori</h5>
                    <div class="list-group list-group-flush border-bottom mb-4">
                        <a href="{{ route('shop.index', ['search' => request('search'), 'sort' => request('sort')]) }}" 
                           class="list-group-item list-group-item-action px-0 border-0 d-flex justify-content-between align-items-center {{ !request('category') ? 'text-primary fw-bold' : '' }}">
                            Semua Kategori
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('shop.index', ['category' => $category->slug, 'search' => request('search'), 'sort' => request('sort')]) }}" 
                               class="list-group-item list-group-item-action px-0 border-0 d-flex justify-content-between align-items-center {{ request('category') == $category->slug ? 'text-primary fw-bold' : '' }}">
                                {{ $category->name }}
                                <span class="badge bg-light text-dark rounded-pill">{{ $category->products_count }}</span>
                            </a>
                        @endforeach
                    </div>

                    <h5 class="fw-bold mb-3">Urutkan</h5>
                    <form action="{{ route('shop.index') }}" method="GET">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <select name="sort" class="form-select form-select-sm mb-3 rounded-3" onchange="this.form.submit()">
                            <option value="">Terbaru</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-lg-9">
            @if($products->isEmpty())
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <i class="bi bi-box-seam display-1 text-muted mb-3"></i>
                    <h4>Produk Tidak Ditemukan</h4>
                    <p class="text-muted">Coba ubah kata kunci atau filter kategori Anda.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-primary rounded-pill mt-2">Hapus Filter</a>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
                    @foreach($products as $product)
                    <div class="col">
                        <div class="card h-100 product-card shadow-sm border-0 position-relative">
                            @if($product->is_featured)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2 z-1 px-3 py-2 rounded-pill">Unggulan</span>
                            @endif
                            <a href="{{ route('shop.show', $product->slug) }}">
                                <img src="{{ $product->image_url }}" class="card-img-top p-3 bg-white border-bottom" alt="{{ $product->name }}" style="height: 220px; object-fit: contain;">
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
                
                <div class="d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
