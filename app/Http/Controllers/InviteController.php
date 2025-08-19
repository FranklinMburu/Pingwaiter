<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use App\Models\Invitation;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['accept']);
        // 'admin' middleware is already applied in routes/web.php
    }

    /**
     * Show the invitation form.
     */
    public function index()
    {
        $pendingInvitations = \App\Models\Invitation::where('status', 'pending')
            ->where('expires_at', '>', now())
            ->orderByDesc('created_at')
            ->get();
        return view('invitations.index', compact('pendingInvitations'));
    }
    /**
     * Resend an invitation email.
     */
    public function resend(Request $request, Invitation $invitation)
    {
        if ($invitation->isExpired() || $invitation->isAccepted() || $invitation->isRevoked()) {
            return back()->with('error', 'Cannot resend this invitation.');
        }
        try {
            Mail::to($invitation->email)->send(new \App\Mail\UserInvitationMail($invitation));
        } catch (\Exception $e) {
            $msg = 'Failed to resend invitation. Please check your mail configuration or contact support.';
            if (app()->environment('local')) {
                $msg .= ' Error: ' . $e->getMessage();
            }
            return back()->with('error', $msg);
        }
        return back()->with('success', 'Invitation resent successfully!');
    }

    /**
     * Revoke an invitation.
     */
    public function revoke(Request $request, Invitation $invitation)
    {
        if ($invitation->isAccepted() || $invitation->isRevoked()) {
            return back()->with('error', 'Cannot revoke this invitation.');
        }
        $invitation->status = 'revoked';
        $invitation->revoked_at = now();
        $invitation->save();
        return back()->with('success', 'Invitation revoked.');
    }

    /**
     * Send an invitation email.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'role' => ['required', Rule::in(['waiter', 'cashier', 'cook'])],
        ]);

        $user = Auth::user();
        $key = 'invite:' . $user->id . ':' . now()->format('YmdH');
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => 'You have reached the invite limit. Try again later.']);
        }
        RateLimiter::hit($key, 3600);

        $token = Str::random(40);
        $expiresAt = now()->addHours(48);
        $invitation = Invitation::create([
            'email' => $request->email,
            'role' => $request->role,
            'token' => $token,
            'inviter_id' => $user->id,
            'expires_at' => $expiresAt,
        ]);

        // Send invitation email
        try {
            Mail::to($request->email)->send(new \App\Mail\UserInvitationMail($invitation));
        } catch (\Exception $e) {
            $msg = 'Failed to send invitation. Please check your mail configuration or contact support.';
            if (app()->environment('local')) {
                $msg .= ' Error: ' . $e->getMessage();
            }
            return back()->withErrors(['email' => $msg]);
        }

        return back()->with('success', 'Invitation sent successfully!');
    }

    /**
     * Accept an invitation.
     */
    public function accept(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->first();
        if (! $invitation) {
            abort(404);
        }
        if ($invitation->isExpired()) {
            return view('table.expired-link');
        }
        if ($invitation->isAccepted()) {
            return redirect()->route('login')->with('error', 'Invitation already accepted.');
        }
        if ($invitation->isRevoked()) {
            return redirect()->route('login')->with('error', 'Invitation has been revoked.');
        }
        // Show registration or onboarding view, passing invite data
        return view('auth.accept-invitation', [
            'invite' => $invitation->toArray(),
            'token' => $token
        ]);
    }
}
