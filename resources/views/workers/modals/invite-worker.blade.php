<!-- Invite Worker Modal -->
<div id="invite-modal" class="fixed inset-0 z-modal flex-center !hidden bg-black bg-opacity-50 modal">
    <div
        class="modal-content bg-white rounded-lg shadow-lg w-full max-w-lg transform transition-all duration-300 opacity-0 -translate-y-10 m-4 border-l-8 border-primary">
        <form id="Register" class="space-y-4 p-6">
            @csrf
            <h2 class="text-xl font-semibold" id="workerlbl">Invite Worker</h2>

            <div>
                <label class="form-label"><strong>Worker Name</strong> <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" placeholder="Enter worker name"
                    class="form-input mt-1 w-full" required />
                <small id="name-error" class="text-danger hidden mt-1"></small>
            </div>

            <div>
                <label class="form-label"><strong>Worker Email</strong> <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter worker email"
                    class="form-input mt-1 w-full" required />
                <small id="email-error" class="text-danger hidden mt-1"></small>
            </div>

            <div>
                <label class="form-label"><strong>Worker Designation</strong> <span class="text-danger">*</span></label>
                <select id="designation" name="designation" class="form-input mt-1 w-full select2-designation" required>
                    <option value="">Select Designation</option>
                    @foreach (\App\Enums\WorkerDesignation::cases() as $designation)
                        @if ($designation->value === 'admin' || $designation->value === 'restaurant')
                            @continue
                        @endif
                        <option value="{{ $designation->value }}">{{ ucfirst($designation->value) }}</option>
                    @endforeach
                </select>
                <small id="designation-error" class="text-danger hidden mt-1"></small>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" class="btn b-light btn-secondary-light close-modal-btn">Close</button>
                <button type="button" id="savebutton" class="btn b-solid btn-primary-solid">Send Now</button>
            </div>
        </form>
    </div>
</div>
