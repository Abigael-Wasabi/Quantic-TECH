<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->has('search')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            })->orWhere('order_status', 'LIKE', '%' . $request->search . '%');
        }

        return $query->with('customer', 'products')->paginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'phone' => 'required|string'
        ]);

        $total_amount = 0;

        foreach ($validated['products'] as $product) {
            $productModel = Product::find($product['product_id']);
            if ($productModel) {
                $total_amount += $productModel->price * $product['quantity'];
            }
        }

        DB::beginTransaction();
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'total_amount' => $total_amount,
            'phone' => $validated['phone'],
            'order_status' => 'pending'
        ]);

        foreach ($validated['products'] as $product) {
            $order->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
        }

        // Commit the transaction to ensure the order is saved
        DB::commit();

        //get inserted order
        $order = Order::find($order->id);
        $lastInsertedId = $order->id;
        try {

            // Trigger MPesa STK push
            $mpesaController = new MpesaController();
            $mpesaResponse = $mpesaController->stkPush(new Request([
                'phone' => $validated['phone'],
                'amount' => 1,
                // 'amount' => $order->total_amount,
                'order_id' => $lastInsertedId
            ]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MPesa STK Push Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to initiate MPesa STK Push'], 500);
        }

        // Load relationships
        $order->load('customer', 'products');

        return response()->json([
            'order' => $order,
            'mpesa_response' => $mpesaResponse
        ]);
    }

    public function callback(Request $request)
    {
        $validated = $request->validate([
            'MerchantRequestID' => 'required|string',
            'CheckoutRequestID' => 'required|string',
            'ResultCode' => 'required|string',
            'ResultDesc' => 'required|string',
            'Amount' => 'required|numeric',
            'MpesaReceiptNumber' => 'required|string',
            'Balance' => 'nullable|string',
            'TransactionDate' => 'required|string',
            'PhoneNumber' => 'required|string',
        ]);

        $payment = Payment::where('CheckoutRequestID', $validated['CheckoutRequestID'])->first();

        if ($payment) {
            $payment->update([
                'ResultCode' => $validated['ResultCode'],
                'ResultDesc' => $validated['ResultDesc'],
                'Amount' => $validated['Amount'],
                'MpesaReceiptNumber' => $validated['MpesaReceiptNumber'],
                'Balance' => $validated['Balance'] ?? null,
                'TransactionDate' => $validated['TransactionDate'],
                'PhoneNumber' => $validated['PhoneNumber'],
            ]);
        }

        return response()->json(['message' => 'Payment callback received and processed'], 200);
    }

    public function show(Order $order)
    {
        return $order->load('customer', 'products');
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,completed',
        ]);

        $order->update($validated);

        return $order->load('customer', 'products');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->noContent();
    }
}
