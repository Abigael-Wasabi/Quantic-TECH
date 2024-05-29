<!DOCTYPE html>
<html>
<head>
    <title>Order Status Changed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 5px;
        }
        h1 {
            color: #333333;
            font-size: 24px;
            border-bottom: 2px solid #eeeeee;
            padding-bottom: 10px;
        }
        p {
            color: #666666;
            line-height: 1.6;
        }
        .order-details {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #999999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Status Changed</h1>
        <p>Dear {{ $order->customer->name }},</p>
        <p>Your order with ID <strong>{{ $order->id }}</strong> has been updated to the status: <strong>{{ $order->order_status }}</strong>.</p>
        <div class="order-details">
            <p><strong>Order Details:</strong></p>
            <p><strong>Order ID:</strong> {{ $order->id }}</p>
            <p><strong>Status:</strong> {{ $order->order_status }}</p>
            <p><strong>Total Amount:</strong> ${{ $order->total_amount }}</p>
            <p><strong>Ordered on:</strong> {{ $order->created_at->format('F j, Y') }}</p>
        </div>
        <p>Thank you for shopping with us.</p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
