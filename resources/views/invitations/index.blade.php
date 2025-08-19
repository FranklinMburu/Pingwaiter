@extends('layouts.app')

@section('content')
<div class="container max-w-2xl mx-auto py-6">
    <h2 class="mb-4 text-2xl font-bold">Pending Invitations</h2>
    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('invitations.store') }}" class="mb-6">
        @csrf
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="waiter">Waiter</option>
                    <option value="cashier">Cashier</option>
                    <option value="cook">Cook</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Invite</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Expires</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingInvitations as $invitation)
                <tr>
                    <td>{{ $invitation->email }}</td>
                    <td>{{ ucfirst($invitation->role) }}</td>
                    <td>{{ ucfirst($invitation->status) }}</td>
                    <td>{{ $invitation->expires_at ? $invitation->expires_at->format('M d, Y H:i') : '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('invitations.resend', $invitation->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">Resend</button>
                        </form>
                        <form method="POST" action="{{ route('invitations.revoke', $invitation->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Revoke</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No pending invitations.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
