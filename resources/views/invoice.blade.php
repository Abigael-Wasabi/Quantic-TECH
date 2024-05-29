@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">Invoice</div>
                <div class="card-body">
                    <!-- Invoice details -->
                    <p><strong>Order ID:</strong> {{ $order->id }}</p>
                    <p><strong>Customer Name:</strong> {{ $customer->name }}</p>
                    <p><strong>Customer Email:</strong> {{ $customer->email }}</p>
                    <p><strong>Products:</strong></p>
                    <ul class="list-group">
                        @foreach($products as $product)
                        <li class="list-group-item">{{ $product->name }} - Quantity: {{ $product->pivot->quantity }}</li>
                        @endforeach
                    </ul>
                    <p><strong>Total Amount:</strong> ${{ $order->total_amount }}</p>
                    
                    <!-- Payment details -->
                    <p><strong>Payment Status:</strong> {{ $payment->ResultCode == 0 ? 'Completed' : 'Failed' }}</p>
                    <p><strong>Transaction Date:</strong> {{ date('d M Y, h:i A', $payment->TransactionDate) }}</p>
                    <p><strong>Mpesa Receipt Number:</strong> {{ $payment->MpesaReceiptNumber }}</p>
                    <p><strong>Payment Amount:</strong> ${{ $payment->Amount }}</p>

                    <!-- Button to generate and download PDF -->
                    <a href="{{ route('generate.invoice.pdf', $order->id) }}" class="btn btn-primary">Generate PDF Invoice</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
