@extends('layouts.app')

@section('title', 'API Key Management')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
@endpush

@section('content')
    <div class="card p-0 overflow-hidden">
        <!-- Header -->
        <div
            class="flex flex-col gap-2 sm:flex-row sm:justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
            <h6 class="card-title text-3xl font-bold text-heading dark:text-dark-text">API Key Management</h6>
            <button onclick="generateApiKey()" class="btn btn-primary-solid text-white dk-theme-card-square">
                <i class="ri-key-line mr-2 text-white"></i> Generate New API Key
            </button>
        </div>

        @php
            $baseUrl = auth()->user()?->restaurant?->website ?? 'domainroot.com';
        @endphp

        <!-- Instructions -->
        <div class="px-6 pt-6 text-sm text-gray-800 dark:text-gray-300">
            <p class="mb-2">Use these API functions to get CSV zipped files:</p>
            <ul class="list-disc pl-6 space-y-1">
                <li>
                    Get Unpaid Orders:
                    <code class="bg-gray-100 dark:bg-gray-800 text-gray-200 dark:text-gray-100 px-2 py-1 rounded">
                        {{ $baseUrl }}/get-data.php?<strong>%apikey%</strong>?unpaidorders
                    </code>
                    <button onclick="copyToClipboard('{{ $baseUrl }}/get-data.php?%apikey%?unpaidorders')"
                        class="ml-2 text-blue-600 dark:text-blue-400 text-xs hover:text-blue-800 dark:hover:text-blue-300">
                        <i class="ri-file-copy-line"></i> Copy
                    </button>
                </li>
                <li>
                    Get All Orders:
                    <code class="bg-gray-100 dark:bg-gray-800 text-gray-200 dark:text-gray-100 px-2 py-1 rounded">
                        {{ $baseUrl }}/get-data.php?<strong>%apikey%</strong>?allorders
                    </code>
                    <button onclick="copyToClipboard('{{ $baseUrl }}/get-data.php?%apikey%?allorders')"
                        class="ml-2 text-blue-600 dark:text-blue-400 text-xs hover:text-blue-800 dark:hover:text-blue-300">
                        <i class="ri-file-copy-line"></i> Copy
                    </button>
                </li>
            </ul>
        </div>

        <!-- API Keys Table -->
        <div class="p-6 overflow-x-auto">
            <table class="table-auto w-full text-left text-sm text-gray-600 dark:text-dark-text whitespace-nowrap">
                <thead class="border-b dark:border-dark-border text-heading font-semibold">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Token (visible only once)</th>
                        <th class="px-6 py-4">Last Used</th>
                        <th class="px-6 py-4">Created</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tokens as $token)
                        <tr class="border-b dark:border-dark-border-three">
                            <td class="px-6 py-4">{{ $token->name }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $preview = Str::limit($token->id . '-' . hash('sha256', $token->id), 10, '');
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs text-gray-500">{{ $preview }}••••••••</span>
                                    <button onclick="copyToClipboard('{{ $preview }}')"
                                        class="text-blue-500 hover:text-blue-700 text-xs">
                                        <i class="ri-file-copy-line text-sm"></i> Copy
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $token->last_used_at ?? 'Never' }}</td>
                            <td class="px-6 py-4">{{ $token->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <button onclick="revokeKey('{{ $token->id }}')"
                                    class="text-red-500 hover:text-red-700 flex items-center gap-1">
                                    <i class="ri-delete-bin-line"></i> Revoke
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">No API tokens found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function generateApiKey() {
            Swal.fire({
                title: 'Generate API Token?',
                input: 'text',
                inputLabel: 'Token name',
                inputPlaceholder: 'e.g., My App Integration',
                showCancelButton: true,
                confirmButtonText: 'Generate',
                inputValidator: (value) => {
                    if (!value) return 'Please enter a token name';
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    fetch('{{ route('api.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                name: result.value
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.api_key) {
                                Swal.fire({
                                    title: 'Token Created',
                                    html: 'Copy and store this token securely:<br><code style="word-break: break-all;">' +
                                        data.api_key + '</code>',
                                    icon: 'success'
                                }).then(() => location.reload());
                            } else {
                                Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                            }
                        }).catch(() => {
                            Swal.fire('Error', 'Failed to generate token.', 'error');
                        });
                }
            });
        }

        function revokeKey(id) {
            Swal.fire({
                title: 'Revoke this token?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, revoke'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api-keys/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    }).then(() => location.reload());
                }
            });
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-center',
                    icon: 'success',
                    title: 'Copied to clipboard',
                    showConfirmButton: false,
                    timer: 1500
                });
            }).catch(() => {
                Swal.fire('Error', 'Failed to copy.', 'error');
            });
        }
    </script>
@endpush
