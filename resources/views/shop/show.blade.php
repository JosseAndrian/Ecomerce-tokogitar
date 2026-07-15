@extends('layouts.app')

@section('title', $product->name . ' - Musik Store')

@section('content')
<div class="container my-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none">Shop</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-body p-0">
            <div class="row g-0">
                <!-- Product Image -->
                <div class="col-md-6 border-end p-5 text-center d-flex align-items-center justify-content-center bg-white rounded-start-4">
                    <img src="{{ $product->image_url }}" id="main-product-image" alt="{{ $product->name }}" class="img-fluid" style="max-height: 500px; object-fit: contain; transition: all 0.3s ease;">
                </div>
                
                <!-- Product Details -->
                <div class="col-md-6 p-5">
                    @if($product->is_featured)
                        <span class="badge bg-danger rounded-pill px-3 py-2 mb-3">Produk Unggulan</span>
                    @endif
                    
                    <h2 class="fw-bold mb-3">{{ $product->name }}</h2>
                    
                    <div class="d-flex align-items-center mb-2 text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }} me-1"></i>
                        @endfor
                        <span class="text-muted ms-2 small">({{ $product->reviews()->count() }} Ulasan)</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4 text-muted">
                        <span class="me-3"><i class="bi bi-tag-fill me-1"></i> {{ $product->category->name }}</span>
                        <span class="me-3"><i class="bi bi-bag-check-fill me-1"></i> Terjual {{ $product->sold_count }}</span>
                        <span><i class="bi bi-box-seam-fill me-1"></i> Stok: 
                            <span class="{{ $product->stock > 0 ? 'text-success fw-bold' : 'text-danger fw-bold' }}">{{ $product->stock }}</span>
                        </span>
                    </div>
                    
                    <h1 class="text-primary fw-bold mb-4" id="display-price">{{ $product->formatted_price }}</h1>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">Deskripsi Produk:</h6>
                        <p class="text-secondary mb-0" style="line-height: 1.8;">{!! nl2br(e($product->description)) !!}</p>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        @if(count($product->variations_array) > 0)
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-3">Pilih Variasi:</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($product->variations_array as $index => $var)
                                    @php $isOutOfStock = ($var['stock'] ?? 0) <= 0; @endphp
                                    <input type="radio" class="btn-check variation-selector" name="variation" id="variation-{{ $index }}" 
                                        value="{{ $var['name'] }}" 
                                        data-price="{{ $var['price'] }}"
                                        data-formatted="{{ $var['formatted_price'] }}"
                                        data-image="{{ $var['image'] ? asset('storage/' . $var['image']) : $product->image_url }}"
                                        {{ $isOutOfStock ? 'disabled' : '' }}
                                        {{ ($loop->first && !$isOutOfStock) ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-primary rounded-pill px-4 {{ $isOutOfStock ? 'opacity-50' : '' }}" for="variation-{{ $index }}">
                                        {{ $var['name'] }} @if($isOutOfStock) <small>(Habis)</small> @endif
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceDisplay = document.getElementById('display-price');
            const productImage = document.getElementById('main-product-image');
            const selectors = document.querySelectorAll('.variation-selector');

            if (selectors.length > 0) {
                // Set initial state based on checked radio
                const firstChecked = document.querySelector('.variation-selector:checked');
                if (firstChecked) {
                    if (priceDisplay) priceDisplay.innerText = firstChecked.getAttribute('data-formatted');
                    if (productImage) productImage.src = firstChecked.getAttribute('data-image');
                }

                selectors.forEach(selector => {
                    selector.addEventListener('change', function() {
                        if (this.checked) {
                            // Update Price
                            if (priceDisplay) priceDisplay.innerText = this.getAttribute('data-formatted');
                            
                            // Update Image
                            const newImage = this.getAttribute('data-image');
                            if (productImage && newImage) {
                                productImage.style.opacity = '0.5';
                                setTimeout(() => {
                                    productImage.src = newImage;
                                    productImage.style.opacity = '1';
                                }, 100);
                            }
                        }
                    });
                });
            }
        });
    </script>
    @endpush
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-3">Kuantitas:</label>
                            <div class="d-flex align-items-center bg-light rounded-pill p-1 shadow-sm" style="width: 140px;">
                                <button type="button" class="btn btn-white rounded-circle shadow-sm" onclick="stepQty('qty', -1)" style="width: 35px; height: 35px; padding: 0;">-</button>
                                <input type="number" name="quantity" id="qty" class="form-control text-center border-0 fw-bold bg-transparent" value="1" min="1" max="{{ $product->stock }}" readonly style="box-shadow: none;">
                                <button type="button" class="btn btn-white rounded-circle shadow-sm" onclick="stepQty('qty', 1)" style="width: 35px; height: 35px; padding: 0;">+</button>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm {{ !$product->is_in_stock() ? 'disabled' : '' }}">
                                <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="fw-bold mb-4">Ulasan Pembeli ({{ $product->reviews->count() }})</h4>
            
            @if($product->reviews->isEmpty())
                <div class="bg-white rounded-4 p-5 text-center shadow-sm">
                    <i class="bi bi-chat-left-dots display-1 text-muted mb-3"></i>
                    <p class="text-muted">Belum ada ulasan untuk produk ini.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($product->reviews()->latest()->get() as $rev)
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                            <i class="bi bi-person fs-4"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $rev->user->name }}</h6>
                                            <small class="text-muted">{{ $rev->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-secondary mb-0">"{{ $rev->comment }}"</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <h4 class="fw-bold mb-4">Produk Terkait</h4>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
        @foreach($relatedProducts as $related)
        <div class="col">
            <div class="card h-100 product-card shadow-sm border-0">
                <a href="{{ route('shop.show', $related->slug) }}">
                    <img src="{{ $related->image_url }}" class="card-img-top p-3 bg-white border-bottom" alt="{{ $related->name }}" style="height: 200px; object-fit: contain;">
                </a>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-truncate fw-bold mb-2">
                        <a href="{{ route('shop.show', $related->slug) }}" class="text-dark text-decoration-none">{{ $related->name }}</a>
                    </h6>
                    <h5 class="text-primary fw-bold mt-auto mb-0">{{ $related->formatted_price }}</h5>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
