<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['restaurantLogin', 'restaurantLogin', 'adminLoginStore', 'adminLogin']);
    }

    /**
     * Show the worker login form.
     *
     * @return \Illuminate\View\View
     */
    public function workerLogin()
    {
        return view('auth.admin');
    }

    /**
     * Handle worker login form submission.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function workerLoginStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->isStaff() || $user->isAdmin()) {
                return redirect()->route('dashboard');
            } else {
                Auth::logout();

                return redirect()->route('home')->with('error', 'Access restricted to workers only.');
            }
        }

        return redirect()->route('home')
            ->with('error', 'Invalid credentials.')
            ->withInput();
    }

    public function restaurantLogin()
    {
        return view('auth.login');
    }

    public function customerLogin(Request $request)
    {
        $tableCode = $request->route('table') ?? session('table_id');

        if (! $tableCode) {
            return redirect()->route('home')->with('error', 'Table information is required to log in. Please scan the QR from your table to login.');
        }

        return view('auth.customer-login', [
            'redirect' => $request->get('redirect', route('home')),
            'tableCode' => $tableCode,
        ]);
    }
}
