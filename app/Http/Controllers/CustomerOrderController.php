<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Events\OrderStatusUpdated;
use App\Models\FoodCategory;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\TableAccessToken;
use App\Models\TablePing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class CustomerOrderController extends Controller
{
    public function status($tableId, $orderId)
    {
        $order = Order::with('item', 'table')
            ->where('id', $orderId)
            ->firstOrFail();

        $restaurant = $order->table->restaurant;

        return view('customer.order-status', [
            'order' => $order,
            'tableCode' => $order->table->table_code,
            'restaurant' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'logo_url' => $restaurant->logo ?? asset('/logo-2.png'),
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
            ],
        ]);
    }

    public function history($tableId)
    {
        $table = Table::with('restaurant')->where('table_code', $tableId)->firstOrFail();

        $restaurant = $table->restaurant;

        $orders = Order::with('item', 'table')
            ->where('table_id', $table->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.order-history', [
            'orders' => $orders,
            'tableCode' => $table->table_code,
            'restaurant' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'logo_url' => $restaurant->logo ?? asset('/logo-2.png'),
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
            ],
        ]);
    }

    public function create(Request $request, $restaurantId, $tableId)
    {
        if (! $request->hasValidSignature()) {
            return view('customer.menu-expired');
        }

        $table = Table::findOrFail($tableId);
        $restaurant = Restaurant::findOrFail($restaurantId);

        $categories = FoodCategory::with(['foodItems' => function ($query) use ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        }])->get();

        return view('customer.place-order', [
            'restaurant' => $restaurant,
            'tableCode' => $table->table_code,
            'categories' => $categories,
        ]);
    }

    public function showMenu(Request $request, $id)
    {
        $table = Table::with('restaurant')->findOrFail($id);
        $token = $request->token;
        $accessToken = TableAccessToken::where('table_id', $id)
            ->where('token', $token)
            ->first();

        if (! $accessToken || $accessToken->isExpired()) {
            return redirect()->route('expired.link');
        }

        $cookieName = 'table_visit_'.$id;
        $cookieValue = Cookie::get($cookieName);

        if (! $cookieValue) {
            do {
                $randomCookie = mt_rand(10000000, 99999999);
            } while (Order::where('cookie_code', $randomCookie)->exists());

            $cookieValue = $randomCookie;
            Cookie::queue($cookieName, $cookieValue, 180);
        }

        $categories = FoodCategory::all();
        $items = FoodItem::all();

        return view('orders.menu', compact('table', 'items', 'categories', 'cookieValue'));
    }

    public function getFoodsByCategory($id)
    {
        $foods = FoodItem::where('category_id', $id)->get();

        return response()->json(['foods' => $foods]);
    }

    public function itemDetail($id)
    {
        $item = FoodItem::with('styles')->find($id);

        return response()->json(['item' => $item]);
    }

    public function banCustomer($id)
    {
        $order = Order::find($id);

        if (! $order || ! $order->email) {
            return response()->json(['error' => 'Order or email not found'], 404);
        }

        $user = User::where('email', $order->email)->first();

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->isBanned()) {
            $user->unban();

            return response()->json(['success' => 'Customer unbanned successfully']);
        } else {
            $user->ban();

            return response()->json(['success' => 'Customer banned successfully']);
        }
    }

    public function toggleTablePingBan($id)
    {
        $ping = TablePing::find($id);

        if (! $ping || ! $ping->user) {
            return response()->json(['error' => 'Ping or email not found'], 404);
        }

        $user = User::where('email', $ping->email)->first();

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $ping->seen = 1;
        $ping->save();

        if ($user->isBanned()) {
            $user->unban();

            return response()->json(['success' => 'Customer unbanned successfully']);
        } else {
            $user->ban();

            return response()->json(['success' => 'Customer banned successfully']);
        }
    }

    public function getrequests()
    {
        $order = Order::with('item', 'table')->orderBy('created_at', 'desc');

        return datatables()->of($order)->addIndexColumn()->make(true);
    }

    public function index()
    {
        return view('orders.index');
    }

    public function storeOrUpdate(Request $request, $id)
    {
        $cookieName = 'table_visit_'.$request->table_id;
        $cookie = Cookie::get($cookieName);

        // If cookie doesn't exist or doesn't match any orders, create a new one
        if (! $cookie) {
            do {
                $cookie = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            } while (Order::where('cookie_code', $cookie)->exists());

            // Set the cookie for 1 day (or adjust as needed)
            Cookie::queue($cookieName, $cookie, 60 * 24); // 60 minutes * 24 = 1 day
        }

        // Check if the user has already placed 5 or more orders with the same cookie
        $orderLimit = 5;  // Set the order limit
        $ordersCount = Order::where('cookie_code', $cookie)->count();

        if ($ordersCount >= $orderLimit) {
            return response()->json(['cookie_error' => 'You have reached the maximum limit of orders. Please try again later.']);
        }

        $rules = [
            'item_id' => 'required',
            'quantity' => 'required',
            'style' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        // Handle order creation or update
        $order = $id == 'add' ? new Order : Order::findOrFail($id);
        $food_item = FoodItem::findOrFail($request->item_id);

        $order->item_id = $request->item_id;
        $order->price = $food_item->price;
        $order->table_id = $request->table_id;
        $order->quantity = $request->quantity;
        $order->remark = $request->remark;
        $order->style = $request->style;
        $order->cookie_code = $cookie;
        $order->ip_address = $request->ip();
        $order->save();

        return response()->json(['success' => 'Order '.($id == 'add' ? 'created' : 'updated').' successfully']);
    }

    public function getorderlist($id)
    {
        $cookieName = 'table_visit_'.$id;
        $cookie = Cookie::get($cookieName);

        // If cookie doesn't exist or doesn't match any orders, create a new one
        if (! $cookie) {
            do {
                $cookie = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            } while (Order::where('cookie_code', $cookie)->exists());

            // Set the cookie for 1 day (or adjust as needed)
            Cookie::queue($cookieName, $cookie, 60 * 24); // 60 minutes * 24 = 1 day
        }

        $order = Order::with('item', 'table')->where('cookie_code', $cookie)->orderBy('created_at', 'desc');

        return datatables()->of($order)->addColumn('status', function ($row) {
            return $row->status->label();
        })->addIndexColumn()->make(true);
    }

    public function getapproveorderlist()
    {
        $order = Order::with('item', 'table')
            ->where('status', OrderStatus::PENDING->value)
            ->orderBy('created_at', 'desc');

        return datatables()->of($order)
            ->addColumn('status', function ($row) {
                return $row->status->label();
            })
            ->editColumn('style', function ($row) {
                return $row->style_name;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function getapprovedorderlist()
    {
        $order = Order::with('item', 'table')
            ->whereNot('status', OrderStatus::PENDING->value)
            ->whereNot('status', OrderStatus::EDITABLE->value)
            ->orderBy('created_at', 'desc');

        return datatables()->of($order)
            ->addColumn('status', function ($row) {
                return $row->status->label();
            })
            ->addColumn('approved_by', function ($row) {
                return $row->approvedBy->name ?? '';
            })
            ->addColumn('prepared_by', function ($row) {
                return $row->preparedBy->name ?? '';
            })
            ->addColumn('delivered_by', function ($row) {
                return $row->deliveredBy->name ?? '';
            })
            ->addColumn('completed_by', function ($row) {
                return $row->completedBy->name ?? '';
            })
            ->editColumn('style', function ($row) {
                return $row->style_name;
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit($id)
    {
        $order = Order::with('item.styles')->find($id);

        // broadcast(new OrderStatusUpdated($order->id, $order->status));

        return response()->json(['order' => $order]);
    }

    public function approveOrder($id)
    {
        $order = Order::find($id);

        $order->update(['status' => OrderStatus::APPROVED, 'approved_by' => auth()->user()->id]);

        // broadcast(new OrderStatusUpdated($order->id, $order->status));

        return response()->json(['success' => 'Order approved successfully']);
    }

    public function prepareOrder($id)
    {
        $order = Order::find($id);

        $order->update(['status' => OrderStatus::PREPARED, 'prepared_by' => auth()->user()->id]);

        // broadcast(new OrderStatusUpdated($order->id, $order->status));

        return response()->json(['success' => 'Order Prepared successfully']);
    }

    public function deliverOrder($id)
    {
        $order = Order::find($id);

        $order->update(['status' => OrderStatus::DELIVERED, 'delivered_by' => auth()->user()->id]);

        // broadcast(new OrderStatusUpdated($order->id, $order->status));

        return response()->json(['success' => 'Order Delivered successfully']);
    }

    public function completeOrder($id)
    {
        $order = Order::find($id);

        $order->update([
            'status' => OrderStatus::COMPLETED,
            'paid_status' => '1',
            'completed_by' => auth()->user()->id,
        ]);

        // broadcast(new OrderStatusUpdated($order->id, $order->status));

        return response()->json(['success' => 'Order completed successfully']);

    }

    public function delete($id)
    {
        $order = Order::find($id);

        $delete = $order->delete();

        return response()->json(['success' => 'Order Setup deleted successfully!']);
    }

    public function submittokithcen($id)
    {
        // Get all editable orders with this cookie
        $orders = Order::where('cookie_code', $id)->get();

        // Get the table_id from the first order (all orders in the same cookie should have the same table_id)
        $tableId = $orders->first()->table_id;

        // Find the highest group_number for this specific table and increment by 1
        $maxGroupNumber = Order::where('table_id', $tableId)->max('group_number') ?? 0;
        $newGroupNumber = $maxGroupNumber + 1;

        foreach ($orders as $order) {
            if ($order->status === OrderStatus::EDITABLE) {
                // Update status and assign the same group_number to all orders in this batch
                $order->update([
                    'status' => OrderStatus::PENDING,
                    'group_number' => $newGroupNumber,
                ]);
            }
        }

        return response()->json(['success' => 'Order sent to kitchen successfully!']);
    }

    public function approve()
    {
        return view('orders.approve');
    }

    public function listen()
    {
        return view('orders.listen');
    }

    public function tablePings()
    {
        return view('orders.table_pings');
    }

    public function getTablePings()
    {
        $user = auth()->user();

        $pings = TablePing::with(['waiter', 'table', 'restaurant'])
            ->when($user->role === 'waiter', function ($query) use ($user) {
                $query->whereExists(function ($subQuery) use ($user) {
                    $subQuery->select(DB::raw(1))
                        ->from('waiter_table_assignments')
                        ->whereColumn('waiter_table_assignments.table_id', 'table_pings.table_id')
                        ->where('waiter_table_assignments.user_id', $user->id);
                });
            })
            ->when($user->role === 'restaurant', function ($query) use ($user) {
                $query->where('table_pings.restaurant_id', $user->restaurant_id);
            })
            ->orderByDesc('table_pings.created_at')
            ->get();

        return datatables()->of($pings)
            ->addColumn('table_code', fn ($row) => $row->table->table_code)
            ->addColumn('location', fn ($row) => $row->table->location ?? 'N/A')
            ->addColumn('time_elapsed', fn ($row) => $row->created_at->diffForHumans())
            ->addColumn('action', function ($row) {
                $html = '<div class="btn-group">';

                if (! $row->seen && ! $row->is_attended) {
                    $html .= '<button class="btn btn-sm btn-success mr-1" onclick="markAsAttended('.$row->id.')" '.
                        'title="Mark as attended" data-id="'.$row->id.'">'.
                        '<i class="fa fa-check"></i></button>';
                }

                $banIcon = $row->is_banned ? 'fa-user-check' : 'fa-user-slash';
                $banClass = $row->is_banned ? 'btn-info' : 'btn-warning';
                $banTitle = $row->is_banned ? 'Unban customer' : 'Ban customer';

                $html .= '<button class="btn btn-sm '.$banClass.'" onclick="toggleBanCustomer('.$row->id.')" '.
                    'title="'.$banTitle.'" data-id="'.$row->id.'">'.
                    '<i class="fa '.$banIcon.'"></i></button>';

                $html .= '</div>';

                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function markTablePingAttended($pingId)
    {
        $ping = TablePing::findOrFail($pingId);

        $ping->update([
            'is_attended' => ! $ping->is_attended,
            $ping->seen = 1,
            'attended_at' => now(),
        ]);

        return response()->json(['success' => 'Ping marked as attended']);
    }

    public function orderPendingDetail($id)
    {

        $order = Order::with('item', 'table')->where('table_id', $id)->where('status', '2')->orderBy('created_at', 'desc');

        return datatables()->of($order)->addColumn('status', function ($row) {
            $status = $row->status == 0 ? 'Editable' : 'Pending';

            return $status;
        })->addColumn('created_at', function ($row) {
            $formattedDate = Carbon::parse($row->created_at)->format('Y-m-d h:i a');

            return $formattedDate;
        })->addColumn('style', function ($row) {
            return $row->style_name;
        })->addColumn('total', function ($row) {
            $price = $row->item->price ? $row->item->price : '0';
            $total = $price * $row->quantity;

            return number_format($total, 3, '.', '');
        })->addIndexColumn()->make(true);
    }

    public function orderCompletedDetail($id)
    {
        $order = Order::with('item', 'table')
            ->where('table_id', $id)
            ->where('status', OrderStatus::COMPLETED->value)
            ->orderBy('created_at', 'desc');

        return datatables()->of($order)
            ->addColumn('status', function ($row) {
                return $row->status->label();
            })
            ->addColumn('created_at', function ($row) {
                $formattedDate = Carbon::parse($row->created_at)->format('Y-m-d h:i a');

                return $formattedDate;
            })
            ->addColumn('total', function ($row) {
                $price = $row->item->price ? $row->item->price : '0';
                $total = $price * $row->quantity;

                return number_format($total, 3, '.', '');
            })
            ->addIndexColumn()
            ->make(true);
    }
}
