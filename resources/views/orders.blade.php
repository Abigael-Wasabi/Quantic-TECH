@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h1>Orders</h1>
            <ul>
                @foreach ($orders as $order)
                {{-- {{ $order }} --}}
                    <li>
                        Order ID: {{ $order->id }}
                        (<a href="/generate-invoice/{{ $order->id }}">Generate Invoice</a>)
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
