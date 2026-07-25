<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">MyShop</a>
            <a href="/" class="btn btn-outline-light btn-sm">Home</a>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4 fw-bold">আপনার শপিং কার্ট</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)
            <div class="row g-4">
                <!-- কার্ট টেবিল -->
                <div class="col-lg-7">
                    <div class="table-responsive bg-white p-4 shadow-sm rounded">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>ছবি</th>
                                    <th>প্রোডাক্ট</th>
                                    <th>দাম</th>
                                    <th>পরিমাণ</th>
                                    <th>মোট</th>
                                    <th>অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <tr>
                                        <td>
                                            @if($details['image'])
                                                <img src="{{ asset('storage/' . $details['image']) }}" width="50" height="50" class="rounded" style="object-fit:cover;">
                                            @else
                                                <img src="https://via.placeholder.com/50" width="50" class="rounded">
                                            @endif
                                        </td>
                                        <td class="fw-bold">{{ $details['name'] }}</td>
                                        <td>৳ {{ number_format($details['price'], 2) }}</td>
                                        <!-- ✅ অটো-আপডেট কোড (Update বাটন ছাড়া) -->
                                        <td>
                                          <form action="{{ route('cart.update', $id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                             <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" onchange="this.form.submit()" class="form-control form-control-sm text-center mx-auto" style="width: 65px;">
                                          </form>
                                        </td>
                                        <td>৳ {{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <h4 class="fw-bold text-end mt-3">সর্বমোট: ৳ {{ number_format($total, 2) }}</h4>
                    </div>
                </div>

                <!-- কাস্টমার শিপিং অ্যাড্রেস ফর্ম -->
                <div class="col-lg-5">
                    <div class="bg-white p-4 shadow-sm rounded">
                        <h4 class="fw-bold mb-3">শিপিং তথ্য দিন (Cash on Delivery)</h4>
                        
                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">আপনার নাম *</label>
                                <input type="text" name="name" class="form-control" required placeholder="যেমন: রহিম আহমেদ">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">মোবাইল নম্বর *</label>
                                <input type="text" name="phone" class="form-control" required placeholder="যেমন: 01700000000">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">পূর্ণাঙ্গ ঠিকানা *</label>
                                <textarea name="address" class="form-control" rows="3" required placeholder="রোড/বাড়ি নম্বর, এলাকা, জেলা"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100 btn-lg">অর্ডার কনফার্ম করুন</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5 bg-white shadow-sm rounded">
                <h4>আপনার কার্ট বর্তমানে খালি আছে!</h4>
                <a href="/" class="btn btn-primary mt-3">শপিং করুন</a>
            </div>
        @endif
    </div>

</body>
</html>