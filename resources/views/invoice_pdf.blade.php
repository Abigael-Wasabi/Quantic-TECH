<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS for the PDF */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Invoice</h3>
            </div>
            <div class="card-body">
                <!-- Invoice details -->
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Customer Name:</strong> {{ $customer->name }}</p>
                <p><strong>Customer Email:</strong> {{ $customer->email }}</p>
                <p><strong>Products:</strong></p>
                <ul>
                    @foreach($products as $product)
                    <li>{{ $product->name }} - Quantity: {{ $product->pivot->quantity }}</li>
                    @endforeach
                </ul>
                <p><strong>Total Amount:</strong> ${{ $order->total_amount }}</p>

                <!-- Payment details -->
                <p><strong>Payment Status:</strong> {{ $payment->ResultCode == 0 ? 'Completed' : 'Failed' }}</p>
                <p><strong>Transaction Date:</strong> {{ date('d M Y, h:i A', $payment->TransactionDate) }}</p>
                <p><strong>Mpesa Receipt Number:</strong> {{ $payment->MpesaReceiptNumber }}</p>
                <p><strong>Payment Amount:</strong> ${{ $payment->Amount }}</p>
                
            </div>
        </div>
    </div>
</body>
</html>
