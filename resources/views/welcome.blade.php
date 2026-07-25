<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>আমার ই-কমার্স শপ</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.jpeg') }}">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .product-img {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">

    <!-- হেডার/নেভবার -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">MyShop</a>
            @auth
                <div>
                 <a href="/admin" class="btn btn-outline-light btn-sm" target="_blank">Admin Panel</a>
                </div>
            @endauth
            <div>
                <a href="{{ route('cart.show') }}" class="btn btn-outline-light me-2">
                    🛒 Cart <span class="badge bg-danger">{{ count((array) session('cart')) }}</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- ব্যানার -->
    <div class="bg-primary text-white text-center py-5 mb-4">
        <div class="container">
            <h1 class="fw-bold">আমাদের শপে আপনাকে স্বাগতম!</h1>
            <p class="lead">সেরা মানের প্রোডাক্ট পেয়ে যান আকর্ষণীয় মূল্যে</p>
        </div>
    </div>

    <!-- সার্চ ও ক্যাটাগরি ফিল্টার সেকশন -->
    <div class="container mb-4">
        <form action="/" method="GET" class="row g-3 bg-white p-3 rounded shadow-sm">
            <!-- সার্চ ইনপুট -->
            <div class="col-md-6">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="প্রোডাক্টের নাম লিখে সার্চ করুন...">
            </div>

            <!-- ক্যাটাগরি ড্রপডাউন -->
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">সকল ক্যাটাগরি</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ফিল্টার বাটন -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>
    </div>

    <!-- প্রোডাক্ট গ্রিড -->
    <div class="container my-5">
        <h3 class="mb-4 text-center fw-bold">সকল প্রোডাক্টস</h3>
        
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 product-card border-0">
                        <!-- প্রোডাক্টের ছবি (লিংক সহ) -->
<a href="{{ route('product.details', $product->id) }}">
    @if($product->image)
        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-img" alt="{{ $product->name }}">
    @else
        <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top product-img" alt="No Image">
    @endif
</a>

<div class="card-body d-flex flex-column">
    <small class="text-muted mb-1">{{ $product->category->name ?? 'Uncategorized' }}</small>
    
    <!-- প্রোডাক্ট টাইটেল (লিংক সহ) -->
    <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none text-dark">
        <h5 class="card-title text-truncate">{{ $product->name }}</h5>
    </a>
    
    <p class="card-text text-primary fw-bold fs-5 mt-auto mb-3">৳ {{ number_format($product->price, 2) }}</p>
    
    <form action="{{ route('cart.add', $product->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary w-100">Add to Cart</button>
    </form>
</div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">এখনো কোনো প্রোডাক্ট আপলোড করা হয়নি।</p>
                </div>
            @endforelse
        </div>
    </div>

</body>
</html>