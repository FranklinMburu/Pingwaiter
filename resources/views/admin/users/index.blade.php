@extends('layouts.main')
@section('title', 'User Management')
@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded shadow overflow-x-auto block">
    <h2 class="text-2xl font-bold mb-6">User Management</h2>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif
    <table class="min-w-full table-auto border text-base">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Role</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                    <td class="px-4 py-2">
                        @if($user->role !== 'admin')
                            <form action="{{ route('admin.users.promote', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-primary" style="min-width:44px;min-height:44px;">Promote to Admin</button>
                            </form>
                        @elseif($user->id !== auth()->id())
                            <form action="{{ route('admin.users.demote', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-danger" style="min-width:44px;min-height:44px;">Demote</button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400">(You)</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
