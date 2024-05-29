<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('orders', compact('orders'));
    }

    public function generateInvoice($orderId)
    {
        try {
            // Fetch the order details from the database, including the customer and products
            $order = Order::with('customer', 'products', 'payment')->findOrFail($orderId);

            // Pass the fetched order data to the invoice view
            $data = [
                'order' => $order,
                'customer' => $order->customer,
                'products' => $order->products,
                'payment' => $order->payment,
            ];
            /* echo '<pre>';
            print_r($data);
            echo '</pre>';
            die(); */

            // Return the invoice view with the data
            return view('invoice', $data);
        } catch (\Exception $e) {
            // Handle any errors (e.g., order not found)
            echo $e->getMessage();
            //return redirect()->back()->with('error', 'Invoice generation failed. ' . $e->getMessage());
        }
    }

    public function generateInvoicePDF($orderId)
    {
        try {
            // Fetch the order details from the database, including the customer and products
            $order = Order::with('customer', 'products', 'payment')->findOrFail($orderId);

            // Pass the fetched order data to the invoice view
            $data = [
                'order' => $order,
                'customer' => $order->customer,
                'products' => $order->products,
                'payment' => $order->payment,
            ];

            // Generate PDF from the invoice view
            $pdf = PDF::loadView('invoice_pdf', $data);

            // Download the PDF
            return $pdf->download('invoice.pdf');
        } catch (\Exception $e) {
            // Handle any errors (e.g., order not found)
            return redirect()->back()->with('error', 'Invoice generation failed. ' . $e->getMessage());
        }
    }
}
