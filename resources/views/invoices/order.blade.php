<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; padding: 20px; }
        .invoice-box { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header { display: table; width: 100%; border-bottom: 2px solid #3b82f6; padding-bottom: 20px; margin-bottom: 20px; }
        .header-left { display: table-cell; vertical-align: top; }
        .header-right { display: table-cell; text-align: right; vertical-align: top; }
        .shop-title { font-size: 26px; font-weight: bold; color: #1e3a8a; margin: 0; }
        .invoice-title { font-size: 22px; font-weight: bold; color: #4b5563; margin: 0; }
        .details-table { width: 100%; margin-bottom: 30px; }
        .details-table td { vertical-align: top; }
        .order-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .order-table th { background-color: #3b82f6; color: white; padding: 10px; text-align: left; }
        .order-table td { padding: 12px 10px; border-bottom: 1px solid #ddd; }
        .total-row { font-weight: bold; font-size: 16px; background-color: #f9fafb; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; text-transform: uppercase; font-weight: bold; background-color: #e5e7eb; color: #374151; }
        .footer { margin-top: 40px; text-align: center; color: #9ca3af; font-size: 12px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <!-- হেডার অংশ -->
        <table style="width: 100%;">
            <tr>
                <td>
                    <h1 class="shop-title">{{ config('app.name', 'Ecommerce App') }}</h1>
                    <p style="margin: 5px 0; color: #6b7280;">Official Customer Invoice</p>
                </td>
                <td style="text-align: right;">
                    <h2 class="invoice-title">INVOICE</h2>
                    <p style="margin: 5px 0;"><strong>Invoice No:</strong> #INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p style="margin: 0;"><strong>Date:</strong> {{ $order->created_at->format('d M, Y') }}</p>
                </td>
            </tr>
        </table>

        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">

        <!-- কাস্টমার ডিটেইলস -->
        <table class="details-table">
            <tr>
                <td style="width: 50%;">
                    <strong style="color: #1e3a8a;">Billed To:</strong><br>
                    <strong>Name:</strong> {{ $order->name }}<br>
                    <strong>Phone:</strong> {{ $order->phone }}<br>
                    <strong>Address:</strong> {{ $order->address }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong style="color: #1e3a8a;">Order Status:</strong><br>
                    <span class="badge">{{ strtoupper($order->status) }}</span>
                </td>
            </tr>
        </table>

        <!-- অর্ডারের পণ্যের তথ্য -->
        <table class="order-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Order Total Value (Standard Delivery Included)</td>
                    <td style="text-align: right;">${{ number_format($order->total_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td style="text-align: right; color: #1e3a8a;">Grand Total:</td>
                    <td style="text-align: right; color: #1e3a8a;">${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Thank you for shopping with us!</p>
        </div>
    </div>
</body>
</html>