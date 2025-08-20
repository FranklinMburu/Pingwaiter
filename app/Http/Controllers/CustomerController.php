<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class CustomerController extends Controller
{
    // Ban customer by email
    public function banByEmail(Request $request)
    {
        $this->authorize('ban', Customer::class);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'reason' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = User::where('email', $request->input('email'))->first();
        if (!$user || !$user->customer) {
            return back()->with('error', 'Customer not found.');
        }
        DB::transaction(function () use ($user, $request) {
            $customer = $user->customer;
            $customer->ban_reason = $request->input('reason');
            $customer->banned_at = Carbon::now();
            $customer->save();
        });
        return back()->with('success', 'Customer banned successfully.');
    }

    // Unban customer by email
    public function unbanByEmail(Request $request)
    {
        $this->authorize('ban', Customer::class);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = User::where('email', $request->input('email'))->first();
        if (!$user || !$user->customer) {
            return back()->with('error', 'Customer not found.');
        }
        DB::transaction(function () use ($user) {
            $customer = $user->customer;
            $customer->ban_reason = null;
            $customer->banned_at = null;
            $customer->save();
        });
        return back()->with('success', 'Customer unbanned successfully.');
    }

    // Get all banned customers
    public function getBannedCustomers()
    {
        $this->authorize('viewAny', Customer::class);
        $banned = Customer::whereNotNull('banned_at')->with('user')->get();
        return view('admin.customers.banned', compact('banned'));
    }
}
