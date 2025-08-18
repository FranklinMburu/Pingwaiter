<?php

namespace App\Http\Controllers;

use App\Enums\WorkerDesignation;
use App\Models\Order;
use App\Models\TablePing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Turnover data
        $totalSales = Order::where('status', 'completed')
            ->selectRaw('SUM(price * quantity) as total')
            ->value('total');

        $totalCustomers = User::where('role', WorkerDesignation::CUSTOMER->value)->count();
        $totalOrders = Order::count();
        $totalPings = TablePing::count();

        // Open Orders (group by table)
        $openOrders = Order::where('status', 'Open')
            ->selectRaw('table_id, COUNT(*) as order_count')
            ->groupBy('table_id')
            ->get()
            ->map(function ($order) {
                return [
                    'table_id' => $order->table_id,
                    'order_count' => $order->order_count,
                    'orders' => Order::where('table_id', $order->table_id)
                        ->where('status', 'Open')
                        ->select('id', 'table_id')
                        ->get(),
                ];
            });

        // Orders Pending Approval
        $pendingApproval = Order::where('status', 'Pending')
            ->selectRaw('table_id, COUNT(*) as order_count')
            ->groupBy('table_id')
            ->get()
            ->map(function ($order) {
                return [
                    'table_id' => $order->table_id,
                    'order_count' => $order->order_count,
                    'orders' => Order::where('table_id', $order->table_id)
                        ->where('status', 'Pending')
                        ->select('id', 'table_id')
                        ->get(),
                ];
            });

        // Orders Pending Delivery
        $pendingDelivery = Order::where('status', 'Prepared')
            ->selectRaw('table_id, COUNT(*) as order_count')
            ->groupBy('table_id')
            ->get()
            ->map(function ($order) {
                return [
                    'table_id' => $order->table_id,
                    'order_count' => $order->order_count,
                    'orders' => Order::where('table_id', $order->table_id)
                        ->where('status', 'Prepared')
                        ->select('id', 'table_id')
                        ->get(),
                ];
            });

        // Orders Pending Payment
        $pendingPayment = Order::where('status', 'Delivered')
            ->selectRaw('table_id, COUNT(*) as order_count')
            ->groupBy('table_id')
            ->get()
            ->map(function ($order) {
                return [
                    'table_id' => $order->table_id,
                    'order_count' => $order->order_count,
                    'orders' => Order::where('table_id', $order->table_id)
                        ->where('status', 'Delivered')
                        ->select('id', 'table_id')
                        ->get(),
                ];
            });

        // Active Table Pings
        $activePings = TablePing::selectRaw('table_id, COUNT(*) as ping_count')
            ->groupBy('table_id')
            ->get()
            ->map(function ($ping) {
                return [
                    'table_id' => $ping->table_id,
                    'ping_count' => $ping->ping_count,
                    'pings' => TablePing::where('table_id', $ping->table_id)
                        ->select('id', 'table_id')
                        ->get(),
                ];
            });

        // Top 3 Foods by Sales Value
        $topFoodsByValue = Order::join('food_items', 'orders.item_id', '=', 'food_items.id')
            ->where('orders.status', 'completed')
            ->selectRaw('food_items.name, SUM(orders.price * orders.quantity) as total_value')
            ->groupBy('food_items.name')
            ->orderByDesc('total_value')
            ->take(3)
            ->get();

        // Top 3 Foods by Sales Quantity
        $topFoodsByQuantity = Order::join('food_items', 'orders.item_id', '=', 'food_items.id')
            ->where('orders.status', 'completed')
            ->selectRaw('food_items.name, SUM(orders.quantity) as total_quantity')
            ->groupBy('food_items.name')
            ->orderByDesc('total_quantity')
            ->take(3)
            ->get();

        // Top 3 Tables by Sales Value
        $topTablesByValue = Order::where('status', 'completed')
            ->selectRaw('table_id, SUM(price * quantity) as total_value')
            ->groupBy('table_id')
            ->orderByDesc('total_value')
            ->take(3)
            ->get();

        // Top 3 Tables by Sales Quantity
        $topTablesByQuantity = Order::where('status', 'completed')
            ->selectRaw('table_id, SUM(quantity) as total_quantity')
            ->groupBy('table_id')
            ->orderByDesc('total_quantity')
            ->take(3)
            ->get();

        // Top 3 Customers by Sales Value
        $topCustomersByValue = Order::join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->selectRaw('users.name, SUM(orders.price * orders.quantity) as total_value')
            ->groupBy('users.name')
            ->orderByDesc('total_value')
            ->take(3)
            ->get();

        // Top 3 Waiters by Sales Value
        $topWaitersByValue = Order::join('users', 'orders.delivered_by', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->selectRaw('users.name, SUM(orders.price * orders.quantity) as total_value')
            ->groupBy('users.name')
            ->orderByDesc('total_value')
            ->take(3)
            ->get();

        // Top 3 Waiters by Sales Quantity
        $topWaitersByQuantity = Order::join('users', 'orders.delivered_by', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->selectRaw('users.name, SUM(orders.quantity) as total_quantity')
            ->groupBy('users.name')
            ->orderByDesc('total_quantity')
            ->take(3)
            ->get();

        // Top 3 Cooks by Sales Value
        $topCooksByValue = Order::join('users', 'orders.prepared_by', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->selectRaw('users.name, SUM(orders.price * orders.quantity) as total_value')
            ->groupBy('users.name')
            ->orderByDesc('total_value')
            ->take(3)
            ->get();

        // Top 3 Cooks by Sales Quantity
        $topCooksByQuantity = Order::join('users', 'orders.prepared_by', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->selectRaw('users.name, SUM(orders.quantity) as total_quantity')
            ->groupBy('users.name')
            ->orderByDesc('total_quantity')
            ->take(3)
            ->get();

        // Top 3 Cashiers by Sales Value
        $topCashiersByValue = Order::join('users', 'orders.completed_by', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->selectRaw('users.name, SUM(orders.price * orders.quantity) as total_value')
            ->groupBy('users.name')
            ->orderByDesc('total_value')
            ->take(3)
            ->get();

        // Top 3 Cashiers by Sales Quantity
        $topCashiersByQuantity = Order::join('users', 'orders.completed_by', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->selectRaw('users.name, SUM(orders.quantity) as total_quantity')
            ->groupBy('users.name')
            ->orderByDesc('total_quantity')
            ->take(3)
            ->get();

        return view('dashboard', [
            'totalSales' => number_format($totalSales ?? 0, 2),
            'totalCustomers' => $totalCustomers,
            'totalOrders' => $totalOrders,
            'totalPings' => $totalPings,
            'openOrders' => $openOrders,
            'pendingApproval' => $pendingApproval,
            'pendingDelivery' => $pendingDelivery,
            'pendingPayment' => $pendingPayment,
            'activePings' => $activePings,
            'topFoodsByValue' => $topFoodsByValue,
            'topFoodsByQuantity' => $topFoodsByQuantity,
            'topTablesByValue' => $topTablesByValue,
            'topTablesByQuantity' => $topTablesByQuantity,
            'topCustomersByValue' => $topCustomersByValue,
            'topWaitersByValue' => $topWaitersByValue,
            'topWaitersByQuantity' => $topWaitersByQuantity,
            'topCooksByValue' => $topCooksByValue,
            'topCooksByQuantity' => $topCooksByQuantity,
            'topCashiersByValue' => $topCashiersByValue,
            'topCashiersByQuantity' => $topCashiersByQuantity,
        ]);
    }

    public function worker_index(Request $request)
    {
        $worker = $request->worker();

        // Proceed with the dashboard if the subscription is active
        return view('worker_dashboard');
    }

    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect('/');
    }
}
