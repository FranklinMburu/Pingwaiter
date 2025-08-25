<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class WaiterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:waiter']);
    }

    // View pending orders
    public function pending()
    {
        $orders = Order::pending()->get();
        return view('waiter.orders.pending', compact('orders'));
    }

    // Approve order (transition to confirmed)
    public function approve(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if (!Auth::user()->hasRole('waiter')) {
                return back()->with('error', 'Unauthorized.');
            }
            if (!$order->canTransitionTo('confirmed')) {
                return back()->with('error', 'Order cannot be approved.');
            }
            $order->transitionStatus('confirmed', Auth::id());
            Event::dispatch('order.status.changed', [$order, 'confirmed']);
            return back()->with('success', 'Order approved.');
        });
    }

    // Handoff to cook (transition to preparing)
    public function handoffToCook(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if (!Auth::user()->hasRole('waiter')) {
                return back()->with('error', 'Unauthorized.');
            }
            if (!$order->canTransitionTo('preparing')) {
                return back()->with('error', 'Order cannot be handed off to cook.');
            }
            $order->transitionStatus('preparing', Auth::id());
            Event::dispatch('order.status.changed', [$order, 'preparing']);
            return back()->with('success', 'Order handed off to cook.');
        });
    }

    // Mark order as served (transition to delivered)
    public function serve(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if (!Auth::user()->hasRole('waiter')) {
                return back()->with('error', 'Unauthorized.');
            }
            if (!$order->canTransitionTo('delivered')) {
                return back()->with('error', 'Order cannot be marked as served.');
            }
            $order->transitionStatus('delivered', Auth::id());
            Event::dispatch('order.status.changed', [$order, 'delivered']);
            return back()->with('success', 'Order marked as served.');
        });
    }

    // Cancel order (transition to cancelled)
    public function cancel(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if (!Auth::user()->hasRole('waiter')) {
                return back()->with('error', 'Unauthorized.');
            }
            if (!$order->canTransitionTo('cancelled')) {
                return back()->with('error', 'Order cannot be cancelled.');
            }
            $order->transitionStatus('cancelled', Auth::id());
            Event::dispatch('order.status.changed', [$order, 'cancelled']);
            return back()->with('success', 'Order cancelled.');
        });
    }

    // View ready orders for serving
    public function readyToServe()
    {
        $orders = Order::ready()->get();
        return view('waiter.orders.ready', compact('orders'));
    }
}
