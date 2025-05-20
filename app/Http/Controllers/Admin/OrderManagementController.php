<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    /**
     * Display the orders management page.
     */
    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display specific order details.
     */
    public function showOrder(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of a specific order.
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,completed,canceled,processing,shipped,delivered',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Статус заказа успешно обновлен.');
    }

    /**
     * Generate and print invoice for an order.
     */
    public function printInvoice($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.print-invoice', compact('order'));
    }

    /**
     * Generate and print packing slip for an order.
     */
    public function printPackingSlip($id) 
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.print-packing-slip', compact('order'));
    }
}