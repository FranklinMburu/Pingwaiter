<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    // Require customer authentication
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show order form
    public function create()
    {
        $cart = Session::get('cart', []);
        $tableId = Session::get('table_id');
        $menuItems = MenuItem::whereIn('id', array_keys($cart))->get();
        return view('orders.create', compact('cart', 'menuItems', 'tableId'));
    }

    // Store new order
    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        $tableId = Session::get('table_id');
        if (!$cart || !$tableId) {
            return Redirect::back()->with('error', 'Cart or table not set.');
        }
        $subtotal = 0;
        foreach ($cart as $itemId => $qty) {
            $item = MenuItem::find($itemId);
            if ($item) {
                $subtotal += $item->price * $qty;
            }
        }
        $taxRate = config('app.tax_rate', 0.10); // 10% default
        $taxAmount = round($subtotal * $taxRate, 2);
        $totalAmount = round($subtotal + $taxAmount, 2);
        $orderData = [
            'customer_id' => Auth::id(),
            'table_id' => $tableId,
            'order_number' => Order::generateOrderNumber(),
            'status' => 'pending',
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'notes' => $request->input('notes'),
        ];
        $validated = validator($orderData, Order::rules())->validate();
        $order = Order::create($validated);
        foreach ($cart as $itemId => $qty) {
            $item = MenuItem::find($itemId);
            if ($item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item->id,
                    'quantity' => $qty,
                    'unit_price' => $item->price,
                    'total_price' => round($item->price * $qty, 2),
                    'special_instructions' => $request->input('instructions.' . $itemId),
                ]);
            }
        }
        Session::forget('cart');
        Session::forget('table_id');
        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
    }

    // Show order details
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items.menuItem', 'customer', 'table');
        return view('orders.show', compact('order'));
    }

    // Update order status (workflow transitions)
    public function updateStatus(Request $request, Order $order)
    {
        $newStatus = $request->input('status');
        if (!$order->canTransitionTo($newStatus)) {
            return Redirect::back()->with('error', 'Invalid status transition.');
        }
        $order->transitionStatus($newStatus);
        return Redirect::back()->with('success', 'Order status updated.');
    }

    public function approveBulk(Request $request)
    {
        $ids = $request->input('ids');
        if (! is_array($ids)) {
            return response()->json(['success' => false]);
        }

        \App\Models\Order::whereIn('id', $ids)->update(['status' => 'Approved']);

        return response()->json(['success' => 'Selected orders approved successfully!']);
    }

    public function bulkPrepare(Request $request)
    {
        Order::whereIn('id', $request->ids)->update([
            'status' => 'Prepared',
            'prepared_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function bulkDeliver(Request $request)
    {
        Order::whereIn('id', $request->ids)->update([
            'status' => 'Delivered',
            'delivered_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function bulkComplete(Request $request)
    {
        Order::whereIn('id', $request->ids)->update([
            'status' => 'Completed',
            'completed_by' => auth()->id(),
        ]);

        return response()->json(['success' => true]);
    }
}
