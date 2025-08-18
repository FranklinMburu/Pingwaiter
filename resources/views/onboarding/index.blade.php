@extends('layouts.auth')

@section('title', 'Onboarding')

@section('content')
    <div class="mx-w-[475px] mx-auto">
        <div class="text-center mb-6">
            <h2 class="text-4xl font-bold text-gray-900 font-playfair mb-2">Onboarding</h2>
            <p class="text-gray-600 text-lg mb-6">Enter your Restaurant Retails to continue</p>
        </div>

        {{-- Custom Steps --}}
        <div class="custom-steps mb-16">
            <div class="step-item">
                <div class="step-circle active" id="step-1">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="step-label active">Register</div>
            </div>
            <div class="step-line" id="line-1"></div>
            <div class="step-item">
                <div class="step-circle active" id="step-2">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="step-label active">Restaurant Details</div>
            </div>
            <div class="step-line" id="line-2"></div>
            <div class="step-item">
                <div class="step-circle" id="step-3">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="step-label">Complete</div>
            </div>
        </div>

        <div class="text-center mb-4">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="form-container">
            {{-- <div class="form-header">
                <h3 class="text-2xl font-semibold text-gray-900 font-playfair" id="step-title">Restaurant Details</h3>
                <p class="text-gray-600 mt-1" id="step-description">Please provide your restaurant details to get started
                </p>
            </div> --}}

            <form id="registration-form" method="POST" action="{{ route('onboarding.store') }}">
                @csrf

                <section>
                    <div class="form-content">
                        {{-- General Error Display --}}
                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                                <strong>There were some errors with your submission:</strong>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="space-y-6">
                            {{-- Restaurant Name --}}
                            <div>
                                <label for="restaurantName" class="block text-sm font-medium text-gray-700 mb-2">
                                    Restaurant Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="restaurantName" name="restaurantName"
                                    value="{{ old('restaurantName') }}" required
                                    class="form-input w-full px-4 py-3 border {{ $errors->has('restaurantName') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                    placeholder="Enter restaurant name">
                                @error('restaurantName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}*</p>
                                @enderror
                            </div>

                            {{-- Currency + Rewards Points --}}
                            {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="currencySymbol" class="block text-sm font-medium text-gray-700 mb-2">
                                        Currency Symbol <span class="text-red-500">*</span>
                                    </label>
                                    <select id="currencySymbol" name="currency_symbol" required
                                        class="form-input w-full px-4 py-3 border {{ $errors->has('currency_symbol') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                                        <option value="" disabled {{ old('currency_symbol') ? '' : 'selected' }}>
                                            Select currency</option>
                                        @foreach ($currencies as $code => $currency)
                                            <option value="{{ $code }}"
                                                {{ old('currency_symbol') === $code ? 'selected' : '' }}>
                                                {{ $currency['symbol'] }} ({{ $code }} - {{ $currency['name'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('currency_symbol')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}*</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="rewardsPoints" class="block text-sm font-medium text-gray-700 mb-2">
                                        Rewards Points
                                    </label>
                                    <input type="number" id="rewardsPoints" name="rewards_points" step="0.1"
                                        min="0" value="{{ old('rewardsPoints') }}" required
                                        class="form-input w-full px-4 py-3 border {{ $errors->has('rewardsPoints') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                        placeholder="e.g., 1.5">
                                    @error('rewardsPoints')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}*</p>
                                    @enderror
                                </div>
                            </div> --}}

                            {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="googleMapsLink" class="block text-sm font-medium text-gray-700 mb-2">
                                        Google Maps Link
                                    </label>
                                    <input type="url" id="googleMapsLink" name="googleMapsLink"
                                        value="{{ old('googleMapsLink') }}"
                                        class="form-input w-full px-4 py-3 border {{ $errors->has('googleMapsLink') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                        placeholder="https://maps.google.com/...">
                                    @error('googleMapsLink')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}*</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="googleReviewLink" class="block text-sm font-medium text-gray-700 mb-2">
                                        Google Review Link
                                    </label>
                                    <input type="url" id="googleReviewLink" name="googleReviewLink"
                                        value="{{ old('googleReviewLink') }}"
                                        class="form-input w-full px-4 py-3 border {{ $errors->has('googleReviewLink') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                        placeholder="https://g.page/...">
                                    @error('googleReviewLink')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}*</p>
                                    @enderror
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </section>

                <div class="mt-8">
                    <button type="submit"
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 px-6 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <i class="bi bi-check-lg mr-2"></i> Continue
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const form = $("#registration-form");

            form.on("submit", function(e) {
                if (!form.valid()) {
                    e.preventDefault();
                }
            });

            form.validate({
                rules: {
                    restaurantName: {
                        required: true,
                        minlength: 2
                    },
                    currencySymbol: {
                        required: true
                    },
                    rewardsPoints: {
                        required: true,
                        number: true,
                        min: 0,
                        max: 100
                    },
                    googleMapsLink: {
                        url: true
                    },
                    googleReviewLink: {
                        url: true
                    }
                },
                messages: {
                    restaurantName: "Please enter a valid restaurant name",
                    currencySymbol: "Select a currency symbol",
                    rewardsPoints: {
                        required: "Enter reward points per dollar",
                        number: "Enter a valid number",
                        min: "Must be 0 or greater",
                        max: "Cannot exceed 100"
                    },
                    googleMapsLink: "Enter a valid URL",
                    googleReviewLink: "Enter a valid URL"
                },
                errorElement: "label",
                errorPlacement: function(error, element) {
                    error.addClass("mt-1 text-sm text-red-600");
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass("border-red-500").removeClass("border-gray-300");
                },
                unhighlight: function(element) {
                    $(element).removeClass("border-red-500").addClass("border-gray-300");
                }
            });
        });
    </script>
@endpush
