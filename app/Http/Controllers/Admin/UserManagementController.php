<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        return view('admin.users.index', compact('users'));
    }

    public function promote(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'User is already an admin.');
        }
        $user->role = 'admin';
        $user->save();
        return back()->with('success', 'User promoted to admin.');
    }

    public function demote(Request $request, User $user)
    {
        if ($user->role !== 'admin') {
            return back()->with('error', 'User is not an admin.');
        }
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot demote yourself.');
        }
        $user->role = 'restaurant';
        $user->save();
        return back()->with('success', 'Admin demoted to restaurant user.');
    }
}
