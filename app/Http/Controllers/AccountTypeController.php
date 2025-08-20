<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AccountTypeController extends Controller
{
    public function show()
    {
        return view('account.type-select');
    }

    public function save(Request $request)
    {
        $allowed = ['restaurant', 'waiter', 'cashier', 'cook'];
        $validator = Validator::make($request->all(), [
            'account_type' => 'required|string|in:' . implode(',', $allowed),
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = Auth::user();
        $user->account_type = $request->input('account_type');
        $user->save();
        return Redirect::route('dashboard')->with('success', 'Account type selected successfully.');
    }
}
