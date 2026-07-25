<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অর্ডার ট্র্যাকিং - MyShop</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- হেডার / নেভবার -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">MyShop</a>
            <div>
                <a href="/" class="btn btn-outline-light btn-sm me-2">Home</a>
                <a href="/cart" class="btn btn-outline-light btn-sm">Cart</a>
            </div>
        </div>
    </nav>

    <!-- ট্র্যাকিং কনটেন্ট -->
    <div class="container my-5" style="max-width: 800px;">
        <h2 class="text-center mb-4 fw-bold">অর্ডার ট্র্যাকিং (Track Your Order)</h2>

        <!-- সার্চ ফর্ম -->
        <div class="card p-4 shadow-sm mb-4 border-0">
            <form action="{{ route('order.track.search') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-lg" 
                           placeholder="আপনার মোবাইল নম্বর বা অর্ডার আইডি দিন..." 
                           value="{{ $search ?? '' }}" required>
                    <button class="btn btn-primary btn-lg px-4" type="submit">ট্র্যাক করুন</button>
                </div>
                @error('search')
                    <span class="text-danger mt-2 d-block">{{ $message }}</span>
                @enderror
            </form>
        </div>

        <!-- ফলাফল প্রদর্শন -->
        @if(isset($orders))
            @if($orders->count() > 0)
                @foreach($orders as $order)
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                            <span><strong>অর্ডার #{{ $order->id }}</strong> ({{ $order->created_at->format('d M, Y') }})</span>
                            
                            <!-- স্ট্যাটাস ব্যাজ -->
                            @if($order->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info text-dark">Processing</span>
                            @elseif($order->status == 'completed' || $order->status == 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <p class="mb-1"><strong>গ্রাহকের নাম:</strong> {{ $order->name }}</p>
                            <p class="mb-1"><strong>মোবাইল নম্বর:</strong> {{ $order->phone }}</p>
                            <p class="mb-3"><strong>ঠিকানা:</strong> {{ $order->address }}</p>
                            
                            <hr>
                            <h6 class="fw-bold mb-3">অর্ডারকৃত আইটেমসমূহ:</h6>
                            <ul class="list-group list-group-flush mb-3">
                                @foreach($order->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>{{ $item->product->name ?? 'Product' }} (x{{ $item->quantity }})</span>
                                        <strong>৳ {{ number_format($item->price * $item->quantity, 2) }}</strong>
                                    </li>
                                @endforeach
                            </ul>
                            <h5 class="text-end text-primary fw-bold mt-3">
                                সর্বমোট: ৳ {{ number_format($order->total_price ?? $order->items->sum(fn($i) => $i->price * $i->quantity), 2) }}
                            </h5>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-danger text-center shadow-sm">
                    কোনো অর্ডার পাওয়া যায়নি! মোবাইল নম্বর বা অর্ডার আইডিটি পুনরায় চেক করুন।
                </div>
            @endif
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>