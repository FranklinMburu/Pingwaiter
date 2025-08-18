<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\Table;
use App\Models\TablePing;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CustomerMenuController extends Controller
{
    public function create($tableId)
    {
        $table = Table::with('restaurant')->where('table_code', $tableId)->firstOrFail();

        $restaurant = $table->restaurant;

        $categories = FoodCategory::with(['foodItems' => function ($query) use ($restaurant) {
            $query->where('restaurant_id', $restaurant->id);
        }])
            ->get()
            ->filter(fn ($category) => $category->foodItems->isNotEmpty());

        return view('customer.place-order', [
            'categories' => $categories,
            'tableCode' => $table->table_code,
            'restaurant' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'logo_url' => $restaurant->logo ?? URL::asset('/logo-2.png'),
                'photos' => collect(json_decode($restaurant->picture ?? '[]'))->map(fn ($img) => asset('uploads/restaurant/pictures/'.$img))->toArray(),
                'video_url' => $restaurant->video ? asset('uploads/restaurant/videos/'.$restaurant->video) : null,
                'manager_whatsapp' => $restaurant->manager_whatsapp,
                'owner_whatsapp' => $restaurant->owner_whatsapp,
                'cashier_whatsapp' => $restaurant->cashier_whatsapp,
                'supervisor_whatsapp' => $restaurant->supervisor_whatsapp,
                'allow_call_manager' => $restaurant->allow_call_manager,
                'allow_call_owner' => $restaurant->allow_call_owner,
                'allow_call_cashier' => $restaurant->allow_call_cashier,
                'allow_call_supervisor' => $restaurant->allow_call_supervisor,
                'currency_symbol' => $restaurant->currency,
                'opening_hours' => $restaurant->opening_hours,
            ],
        ]);
    }

    public function store(Request $request, $tableId)
    {
        $orders = $request->orders;
        $table = Table::where('table_code', $tableId)->firstOrFail();

        if (! is_array($orders) || empty($orders)) {
            return response()->json(['error' => 'Invalid order data'], 422);
        }

        $cookieName = 'table_visit_'.$tableId;
        $cookie = Cookie::get($cookieName);

        if (! $cookie) {
            do {
                $cookie = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            } while (Order::where('cookie_code', $cookie)->exists());

            Cookie::queue($cookieName, $cookie, 60 * 24);
        }

        $ordersCount = Order::where('cookie_code', $cookie)->count();

        if ($ordersCount >= 5) {
            return response()->json(['cookie_error' => 'Order limit reached']);
        }

        foreach ($orders as $entry) {
            $food_item = FoodItem::where('item_code', $entry['item_id'])->first();

            Order::create([
                'item_id' => $food_item->id,
                'user_id' => auth()->id(),
                'price' => $food_item->price,
                'table_id' => $table->id,
                'quantity' => $entry['quantity'],
                'remark' => $entry['remark'] ?? '',
                'style' => $entry['style'] ?? 1,
                'cookie_code' => $cookie,
                'ip_address' => $request->ip(),
                'status' => \App\Enums\OrderStatus::PENDING->value,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function generateQr($tableId)
    {
        $url = URL::temporarySignedRoute(
            'table.menu',
            now()->addMinutes(20),
            ['table' => $tableId]
        );

        $qrCode = QrCode::size(300)->generate($url);

        return view('qr.preview', [
            'qrCode' => $qrCode,
            'link' => $url,
            'tableId' => $tableId,
        ]);
    }

    public function show(Request $request, $tableId)
    {
        // if (! $request->hasValidSignature()) {
        //     return view('customer.menu-expired');
        // }

        if (! auth()->check()) {
            return redirect()->route('customer.login', [
                'redirect' => url()->full(),
            ]);
        }

        $table = Table::with('restaurant')->where('table_code', $tableId)->firstOrFail();

        $restaurant = $table->restaurant;

        session(['table_id' => $table->table_code]);

        $categories = FoodCategory::with(['foodItems' => function ($query) use ($restaurant) {
            $query->where('restaurant_id', $restaurant->id);
        }])->get();

        $orders = Order::with('item')
            ->where('table_id', $table->id)
            ->whereIn('status', [
                \App\Enums\OrderStatus::PENDING->value,
                \App\Enums\OrderStatus::APPROVED->value,
                \App\Enums\OrderStatus::PREPARED->value,
            ])
            ->get();

        return view('customer.menu', [
            'tableCode' => $table->table_code,
            'restaurant' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'logo_url' => $restaurant->logo_url ?? asset('logo.png'),
                'photos' => collect(json_decode($restaurant->picture ?? '[]'))->map(fn ($img) => asset('uploads/restaurant/pictures/'.$img))->toArray(),
                'video_url' => $restaurant->video ? asset('uploads/restaurant/videos/'.$restaurant->video) : null,
                'manager_whatsapp' => $restaurant->manager_whatsapp,
                'owner_whatsapp' => $restaurant->owner_whatsapp,
                'cashier_whatsapp' => $restaurant->cashier_whatsapp,
                'supervisor_whatsapp' => $restaurant->supervisor_whatsapp,
                'allow_place_order' => $restaurant->allow_place_order,
                'allow_call_manager' => $restaurant->allow_call_manager,
                'allow_call_owner' => $restaurant->allow_call_owner,
                'allow_call_cashier' => $restaurant->allow_call_cashier,
                'allow_call_supervisor' => $restaurant->allow_call_supervisor,
                'currency_symbol' => $restaurant->currency,
                'google_maps_link' => $restaurant->google_maps_link,
                'google_review_link' => $restaurant->google_review_link,
                'opening_hours' => $restaurant->opening_hours,
                'video_url' => $restaurant->video,
                'instagram_link' => $restaurant->instagram_link,
                'facebook_link' => $restaurant->facebook_link,
                'twitter_link' => $restaurant->twitter_link,
            ],
            'categories' => $categories,
            'orders' => $orders,
        ]);
    }

    public function ping(Request $request, $tableCode)
    {
        $table = Table::where('table_code', $tableCode)->firstOrFail();

        // Check if client is logged in
        $user = auth()->user();

        if ($user && $user->isBanned()) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'You are banned from requesting waiter service.',
            ], 403);
        }

        // Optional: prevent multiple unacknowledged pings
        $existingPing = TablePing::where('table_id', $table->id)
            ->where('is_attended', false)
            ->where('seen', false)
            ->latest()
            ->first();

        if ($existingPing) {
            return response()->json([
                'status' => 'Ping already active',
                'message' => 'A waiter has already been called for this table.',
            ], 429);
        }

        // Create a new ping
        TablePing::create([
            'table_id' => $table->id,
            'restaurant_id' => $table->restaurant_id,
            'client_id' => $user?->id,
            'is_banned' => $user?->isBanned() ?? false,
            'ip_address' => $request->ip(),
        ]);

        \Log::info('PingWaiter dispatched for table '.$table->table_code);
        broadcast(new \App\Events\PingWaiter($table))->toOthers();

        return response()->json(['status' => 'Ping sent']);
    }

    public function repeat(Request $request, $tableCode, Order $order)
    {
        $table = Table::where('table_code', $tableCode)->firstOrFail();

        if ($order->table_id !== $table->id) {
            abort(403, 'Unauthorized to repeat this order.');
        }

        $cookieName = 'table_visit_'.$tableCode;
        $cookie = Cookie::get($cookieName);

        if (! $cookie) {
            do {
                $cookie = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            } while (Order::where('cookie_code', $cookie)->exists());

            Cookie::queue($cookieName, $cookie, 60 * 24);
        }

        $ordersCount = Order::where('cookie_code', $cookie)->count();

        if ($ordersCount >= 5) {
            return redirect()->back()->with('cookie_error', 'Order limit reached');
        }

        $newOrder = $order->replicate();

        $newOrder->table_id = $table->id;
        $newOrder->cookie_code = $cookie;
        $newOrder->status = \App\Enums\OrderStatus::PENDING->value;
        $newOrder->ip_address = $request->ip();
        $newOrder->save();

        return redirect()->back()->with('success', 'Order repeated successfully!');
    }
}
