<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:cashier']);
    }

    // View delivered orders for payment
    public function delivered()
    {
        $orders = Order::delivered()->get();
        return view('cashier.orders.delivered', compact('orders'));
    }

    // Process payment (mark as paid) for delivered orders only
    public function pay(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if (!Auth::user()->hasRole('cashier')) {
                return back()->with('error', 'Unauthorized.');
            }
            if (!$order->canTransitionTo('paid') || $order->status !== 'delivered') {
                return back()->with('error', 'Order cannot be marked as paid.');
            }
            $order->transitionStatus('paid', Auth::id());
            return back()->with('success', 'Order marked as paid.');
        });
    }
    public function cancel(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if (!Auth::user()->hasRole('cashier')) {
                return back()->with('error', 'Unauthorized.');
            }
            if (!$order->canTransitionTo('cancelled')) {
                return back()->with('error', 'Order cannot be cancelled.');
            }
            $order->transitionStatus('cancelled', Auth::id());
            return back()->with('success', 'Order cancelled.');
        });
    }
}
