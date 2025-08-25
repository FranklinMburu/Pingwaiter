<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class CookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:cook']);
    }

    // View confirmed orders
    public function confirmed()
    {
        $orders = Order::confirmed()->get();
        return view('cook.orders.confirmed', compact('orders'));
    }

    // Mark order as preparing
    public function preparing(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if (!Auth::user()->hasRole('cook')) {
                return back()->with('error', 'Unauthorized.');
            }
            if (!$order->canTransitionTo('preparing')) {
                return back()->with('error', 'Order cannot be marked as preparing.');
            }
            $order->transitionStatus('preparing', Auth::id());
            return back()->with('success', 'Order marked as preparing.');
        });
    }

    // Mark order as ready
    public function ready(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if (!Auth::user()->hasRole('cook')) {
                return back()->with('error', 'Unauthorized.');
            }
            if (!$order->canTransitionTo('ready')) {
                return back()->with('error', 'Order cannot be marked as ready.');
            }
            $order->transitionStatus('ready', Auth::id());
            return back()->with('success', 'Order marked as ready.');
        });
    }

    // Cancel order
    public function cancel(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if (!Auth::user()->hasRole('cook')) {
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
