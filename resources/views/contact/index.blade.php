@extends('layouts.app')

@section('title', 'Contact Customers')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
@endpush

@section('content')
    <div class="grid grid-cols-12 gap-6">
        {{-- <!-- Contact Form -->
        <div class="col-span-full xl:col-span-6">
            <div class="card px-0 py-8">
                <div class="max-w-xl mx-auto">
                    <h2 class="text-3xl font-semibold text-center mb-6">Customer Contacts</h2>

                    @if (session('success'))
                        <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" required class="form-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" required class="form-input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea name="message" rows="6" required class="form-input w-full h-auto resize-none"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="btn b-solid btn-primary-solid dk-theme-card-square">
                                <i class="ri-send-plane-line mr-2"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}

        <!-- Customer Mailer -->
        <div class="col-span-full">
            <div class="card p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Contact Customers</h3>
                    <button onclick="document.getElementById('mailModal').showModal()" class="btn b-solid btn-primary-solid">
                        <i class="ri-mail-line text-white"></i> Compose Mail
                    </button>
                </div>

                <p class="text-gray-600 mb-4 text-sm">Send promotional messages or announcements to selected customers.</p>

                <table class="table-auto w-full text-left border text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-dark-card">
                            <th class="p-3"><input type="checkbox" id="select-all" onclick="toggleSelectAll(this)"></th>
                            <th class="p-3">Customer Name</th>
                            <th class="p-3">Customer Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr class="border-b">
                                <td class="p-3">
                                    <input type="checkbox" class="customer-checkbox" value="{{ $customer->email }}">
                                </td>
                                <td class="p-3">{{ $customer->name }}</td>
                                <td class="p-3">{{ $customer->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Compose Modal -->
    <dialog id="mailModal" class="modal p-0 w-full max-w-2xl rounded-lg shadow-xl">
        <form method="POST" action="{{ route('contact.bulk') }}" enctype="multipart/form-data"
            class="bg-white dark:bg-dark-bg p-6 space-y-4">
            @csrf
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Compose Mail</h3>
                <button type="button" onclick="document.getElementById('mailModal').close()" class="text-red-500">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">From (Admin Email)</label>
                <input type="email" name="from" required value="support@pingwaiter.com" class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">BCC (Customer Emails)</label>
                <input type="text" name="bcc" id="bccEmails" class="form-input w-full" readonly
                    placeholder="Will be filled automatically">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Subject</label>
                <input type="text" name="subject" required class="form-input w-full">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Message</label>
                <textarea name="body" rows="6" required class="form-input h-auto w-full resize-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Attachments</label>
                <input type="file" name="attachments[]" multiple class="form-input w-full">
            </div>

            <div class="text-right">
                <button type="submit" class="btn b-solid btn-primary-solid">
                    <i class="ri-send-plane-2-line text-white"></i> Send Email
                </button>
            </div>
        </form>
    </dialog>
@endsection

@push('scripts')
    <script>
        function toggleSelectAll(source) {
            const checkboxes = document.querySelectorAll('.customer-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
        }

        // Autofill BCC when modal opens
        document.getElementById('mailModal').addEventListener('show', () => {
            const emails = [...document.querySelectorAll('.customer-checkbox:checked')]
                .map(cb => cb.value)
                .join(',');
            document.getElementById('bccEmails').value = emails;
        });

        // fallback for browsers not supporting dialog.show event
        document.getElementById('mailModal').addEventListener('click', function(e) {
            if (e.target.tagName === 'DIALOG') {
                this.close();
            }
        });

        // update BCC when modal is opened manually
        document.querySelector('[onclick*="mailModal"]').addEventListener('click', () => {
            const emails = [...document.querySelectorAll('.customer-checkbox:checked')]
                .map(cb => cb.value)
                .join(',');
            document.getElementById('bccEmails').value = emails;
        });
    </script>
@endpush
