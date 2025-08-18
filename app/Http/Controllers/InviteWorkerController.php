<?php

namespace App\Http\Controllers;

use App\Enums\WorkerDesignation;
use App\Mail\InviteWorkerMail;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InviteWorkerController extends Controller
{
    public function index()
    {
        return view('workers.index');
    }

    public function getWorkers()
    {
        $workers = User::role(WorkerDesignation::values())->select(['id', 'name', 'email'])
            ->with('roles');

        return datatables()->of($workers)
            ->addIndexColumn()
            ->addColumn('designation', fn ($user) => $user->roles->pluck('name')->first())
            ->addColumn('action', function ($user) {
                return '<button onclick="deleteWorker('.$user->id.')" class="btn btn-sm btn-danger">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'designation' => 'required|in:'.implode(',', WorkerDesignation::values()),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Worker::where('email', $request->email)->exists()) {
            return response()->json(['email_duplication' => 'This email is already in use.'], 200);
        }

        $worker = Worker::create([
            'name' => $request->name,
            'email' => $request->email,
            'designation' => $request->designation,
            'restaurant_id' => auth()->user()->restaurant->id,
        ]);

        $token = Str::uuid()->toString();

        cache()->put("worker_invite_{$token}", [
            'email' => $request->email,
            'role' => $request->designation,
            'restaurant_id' => auth()->user()->restaurant_id,
        ], now()->addHours(48));

        $inviteLink = URL::temporarySignedRoute(
            'workers.accept-invite',
            now()->addHours(48),
            ['token' => $token]
        );

        $restaurantName = auth()->user()->restaurant->name;

        Mail::to($request->email)->send(new InviteWorkerMail($inviteLink, $request->designation, $restaurantName));

        return response()->json(['success' => 'Invitation sent successfully.']);
    }

    public function acceptInvite(Request $request, $token)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invitation link expired or invalid.');
        }

        $invite = cache("worker_invite_{$token}");
        if (! $invite) {
            abort(403, 'This invitation has expired.');
        }

        return redirect()->route('google.login', ['invite_token' => $token]);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'Worker deleted successfully.']);
    }
}
