<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - MyShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .details-img {
            max-height: 400px;
            object-fit: cover;
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">

    <!-- নেভবার -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">MyShop</a>
            <div>
                <a href="{{ route('cart.show') }}" class="btn btn-outline-light me-2">
                    🛒 Cart <span class="badge bg-danger">{{ count((array) session('cart')) }}</span>
                </a>
                <a href="/" class="btn btn-outline-light btn-sm">Home</a>
            </div>
        </div>
    </nav>

    <!-- প্রোডাক্ট ডিটেইলস সেকশন -->
    <div class="container my-5">
        <div class="bg-white p-4 shadow-sm rounded">
            <div class="row g-4">
                <!-- বড় ছবি -->
                <div class="col-md-6">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="rounded details-img" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/500x400?text=No+Image" class="rounded details-img" alt="No Image">
                    @endif
                </div>

                <!-- প্রোডাক্টের তথ্য -->
                <div class="col-md-6 d-flex flex-column justify-content-center">
                    <span class="badge bg-secondary w-auto align-self-start mb-2">{{ $product->category->name ?? 'Uncategorized' }}</span>
                    <h2 class="fw-bold mb-3">{{ $product->name }}</h2>
                    <h3 class="text-primary fw-bold mb-4">৳ {{ number_format($product->price, 2) }}</h3>

                    <p class="text-muted mb-4">
                        {{ $product->description ?? 'এই প্রোডাক্টটির কোনো বিস্তারিত বিবরণ এখনো দেওয়া হয়নি।' }}
                    </p>

                    <!-- Add to Cart বাটন -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100">🛒 Add to Cart</button>
                    </form>

                    <a href="/" class="btn btn-outline-secondary w-100">← কেনাকাটা চালিয়ে যান</a>
                </div>
            </div>
        </div>

        <!-- সম্পর্কিত প্রোডাক্টসমূহ (Related Products) -->
        @if($relatedProducts->count() > 0)
            <div class="mt-5">
                <h4 class="fw-bold mb-4">এই ক্যাটাগরির আরও প্রোডাক্ট</h4>
                <div class="row g-4">
                    @foreach($relatedProducts as $relProduct)
                        <div class="col-6 col-md-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <a href="{{ route('product.details', $relProduct->id) }}">
                                    @if($relProduct->image)
                                        <img src="{{ asset('storage/' . $relProduct->image) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/200" class="card-img-top" style="height: 150px;">
                                    @endif
                                </a>
                                <div class="card-body">
                                    <h6 class="card-title text-truncate">
                                        <a href="{{ route('product.details', $relProduct->id) }}" class="text-decoration-none text-dark">{{ $relProduct->name }}</a>
                                    </h6>
                                    <p class="text-primary fw-bold mb-0">৳ {{ number_format($relProduct->price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</body>
</html>