<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;

class AccountSetupController extends Controller
{
    public function showAccountType()
    {
        return view('account.type-select');
    }

    public function updateAccountType(Request $request)
    {
        $allowed = ['restaurant', 'waiter', 'cashier', 'cook'];
        $validator = Validator::make($request->all(), [
            'account_type' => 'required|string|in:' . implode(',', $allowed),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $accountType = $request->input('account_type');

        \DB::transaction(function () use ($user, $accountType) {
            $user->account_type = $accountType;
            $user->role = $accountType;
            $user->save();
            // If restaurant, create customer record idempotently
            if ($accountType === 'restaurant') {
                \App\Models\Customer::firstOrCreate([
                    'user_id' => $user->id
                ], [
                    'phone' => $user->phone,
                    'preferences' => [],
                ]);
            }
            // If you have role logic, fire event/service here
        });

        // Redirect to appropriate dashboard per role
        switch ($accountType) {
            case 'restaurant':
                return Redirect::route('restaurant.dashboard')->with('success', __('account.success'));
            case 'waiter':
                return Redirect::route('waiter.dashboard')->with('success', __('account.success'));
            case 'cashier':
                return Redirect::route('cashier.dashboard')->with('success', __('account.success'));
            case 'cook':
                return Redirect::route('cook.dashboard')->with('success', __('account.success'));
            default:
                return Redirect::route('dashboard');
        }
    }
}
