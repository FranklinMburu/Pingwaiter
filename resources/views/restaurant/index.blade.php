@extends('layouts.app')

@section('title', 'Setup Restaurant')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
@endpush

@section('content')
    <div class="card p-0 shadow-lg bg-white dark:bg-dark-card">
        <div
            class="flex flex-col gap-2 sm:flex-row sm:justify-between px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
            <h3 class="card-title text-3xl font-bold text-heading dark:text-dark-text">Restaurant Setup</h6>
        </div>

        <div class="card-body p-6 md:p-8">

            {{-- Form Errors --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-semibold">Please fix the following errors:</p>
                            <ul class="list-disc list-inside mt-2">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <form id="restaurantForm" enctype="multipart/form-data" method="POST"
                action="{{ isset($restaurant) ? route('restaurants.update', $restaurant->id) : route('restaurants.store') }}"
                class="space-y-8">
                @csrf
                @if (isset($restaurant))
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-dark-text border-b pb-2">General Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label for="name"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Restaurant Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('name', $restaurant->name ?? '') }}" placeholder="Enter restaurant name" />
                            @error('name')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="location"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Restaurant Location
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="location" name="location" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('location', $restaurant->location ?? '') }}" placeholder="Enter location" />
                            @error('location')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="phone_number"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Phone Number <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="phone_number" name="phone_number" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('phone_number', $restaurant->phone_number ?? '') }}"
                                placeholder="Enter phone number" />
                            @error('phone_number')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="whatsapp_number"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">WhatsApp Number
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="whatsapp_number" name="whatsapp_number" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('whatsapp_number', $restaurant->whatsapp_number ?? '') }}"
                                placeholder="Enter WhatsApp number" />
                            @error('whatsapp_number')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="email"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('email', $restaurant->email ?? '') }}" placeholder="Enter email" />
                            @error('email')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="website"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Website <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="website" name="website" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('website', $restaurant->website ?? '') }}" placeholder="Enter website URL" />
                            @error('website')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-dark-text border-b pb-2">Contact Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="owner_name"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Owner Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="owner_name" name="owner_name" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('owner_name', $restaurant->owner_name ?? '') }}"
                                placeholder="Enter owner name" />
                            @error('owner_name')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="owner_whatsapp"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Owner WhatsApp
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="owner_whatsapp" name="owner_whatsapp" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('owner_whatsapp', $restaurant->owner_whatsapp ?? '') }}"
                                placeholder="Enter owner WhatsApp" />
                            @error('owner_whatsapp')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="manager_name"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Manager Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="manager_name" name="manager_name" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('manager_name', $restaurant->manager_name ?? '') }}"
                                placeholder="Enter manager name" />
                            @error('manager_name')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="manager_whatsapp"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Manager WhatsApp
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="manager_whatsapp" name="manager_whatsapp" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('manager_whatsapp', $restaurant->manager_whatsapp ?? '') }}"
                                placeholder="Enter manager WhatsApp" />
                            @error('manager_whatsapp')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="cashier_name"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Cashier Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="cashier_name" name="cashier_name" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('cashier_name', $restaurant->cashier_name ?? '') }}"
                                placeholder="Enter cashier name" />
                            @error('cashier_name')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="cashier_whatsapp"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Cashier WhatsApp
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="cashier_whatsapp" name="cashier_whatsapp" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('cashier_whatsapp', $restaurant->cashier_whatsapp ?? '') }}"
                                placeholder="Enter cashier WhatsApp" />
                            @error('cashier_whatsapp')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="supervisor_name"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Supervisor Name
                                <span class="text-red-500">*</span></label>
                            <input type="text" id="supervisor_name" name="supervisor_name" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('supervisor_name', $restaurant->supervisor_name ?? '') }}"
                                placeholder="Enter supervisor name" />
                            @error('supervisor_name')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label for="supervisor_whatsapp"
                                class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Supervisor
                                WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" id="supervisor_whatsapp" name="supervisor_whatsapp" required
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('supervisor_whatsapp', $restaurant->supervisor_whatsapp ?? '') }}"
                                placeholder="Enter supervisor WhatsApp" />
                            @error('supervisor_whatsapp')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-dark-text border-b pb-2">Media Uploads</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Restaurant Logo
                                <span class="text-red-500">*</span></label>
                            @if (isset($restaurant->logo))
                                <div class="mb-3 flex items-center">
                                    <img src="{{ asset('uploads/restaurant/logos/' . $restaurant->logo) }}"
                                        class="h-20 rounded border" />
                                    <button type="button"
                                        class="ml-3 bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition"
                                        onclick="removeLogo()">Remove</button>
                                </div>
                            @endif
                            <input type="file" name="logo" accept="image/*"
                                @if (!isset($restaurant->logo)) required @endif onchange="previewLogo(this)"
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition" />
                            <input type="hidden" name="remove_logo" id="remove_logo" value="0">
                            <small class="text-gray-500 dark:text-dark-text-muted">Required dimensions: 250 x 100
                                pixels</small>
                            <div id="logo-preview" class="mt-3 flex flex-wrap gap-2"></div>
                            <div id="logo-error" class="text-red-500 text-sm mt-1" style="display: none;"></div>
                            @error('logo')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Restaurant
                                Pictures</label>
                            @if (isset($restaurant->picture))
                                <div class="mb-3 flex flex-wrap gap-2">
                                    @foreach (json_decode($restaurant->picture) as $index => $picture)
                                        <div class="relative" id="picture-{{ $index }}">
                                            <img src="{{ asset('uploads/restaurant/pictures/' . $picture) }}"
                                                class="h-20 rounded border" />
                                            <button type="button"
                                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition"
                                                onclick="removePicture({{ $index }})">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="picture[]" multiple accept="image/*"
                                onchange="previewPictures(this)"
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition" />
                            <input type="hidden" name="remove_pictures" id="remove_pictures" value="">
                            <small class="text-gray-500 dark:text-dark-text-muted">You can select multiple images</small>
                            <div id="picture-preview" class="mt-3 flex flex-wrap gap-2"></div>
                            @error('picture')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label text-sm font-medium text-gray-700 dark:text-dark-text">Restaurant
                                Video</label>
                            @if (isset($restaurant->video))
                                <div class="mb-3">
                                    <iframe width="200" height="113" src="{{ $restaurant->video }}"
                                        class="rounded border" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                    <button type="button"
                                        class="mt-2 bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition"
                                        onclick="removeVideo()">Remove Video</button>
                                </div>
                            @endif
                            <input type="url" name="video"
                                class="form-input w-full px-4 py-2 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('video', $restaurant->video ?? '') }}" placeholder="Enter YouTube URL" />
                            <input type="hidden" name="remove_video" id="remove_video" value="0">
                            @error('video')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-dark-text border-b pb-2">Additional Details
                    </h3>
                    <div class="grid grid-cols-12 gap-x-4">
                        <div class="col-span-12 md:col-span-6 mb-4">
                            <label class="form-label"><strong>Currency Symbol <span
                                        class="text-danger">*</span></strong></label>
                            <input type="text" id="currencySymbol" name="currency_symbol" required
                                class="form-input w-full px-4 py-3 border {{ $errors->has('currency_symbol') ? 'border-red-500' : 'border-gray-300' }} focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('currency_symbol', $restaurant->currency_symbol ?? config('currency.default')) }}"
                                placeholder="Enter currency symbol (e.g. $, €, £)">
                            @error('currency_symbol')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}*</p>
                            @enderror
                        </div>

                        <div class="col-span-12 md:col-span-6 mb-4">
                            <label class="form-label flex items-center">
                                <strong>Customer Reward Points</strong>
                                <span class="relative ml-1" data-tooltip-target="rewards-tooltip"
                                    data-tooltip-placement="top">
                                    <i class="bi bi-patch-question-fill mr-2 cursor-pointer"></i>
                                </span>
                            </label>
                            <div id="rewards-tooltip" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-4 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 shadow-xs opacity-0 dark:bg-gray-700">
                                <i class="bi bi-info-circle-fill text-white"></i>
                                Using customer rewards points, you can allow your customers to accumulate points and<br />
                                redeem it for a discount or any other offerings you may decide
                            </div>
                            <input type="number" step="0.1" min="0" name="rewards_points" required
                                class="form-input w-full px-4 py-3 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('rewards_points', $restaurant->rewards_per_dollar ?? '') }}">
                            @error('rewards_points')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-span-12 md:col-span-6 mb-4">
                            <label class="form-label"><strong>Google Maps Link</strong></label>
                            <input type="url" name="google_maps_link"
                                class="form-input w-full px-4 py-3 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('google_maps_link', $restaurant->google_maps_link ?? '') }}">
                            @error('google_maps_link')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-span-12 md:col-span-6 mb-4">
                            <label class="form-label"><strong>Google Review Link</strong></label>
                            <input type="url" name="google_review_link"
                                class="form-input w-full px-4 py-3 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('google_review_link', $restaurant->google_review_link ?? '') }}">
                            @error('google_review_link')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-span-12 md:col-span-6 mb-4">
                            <label class="form-label"><strong>X (Twitter) Link</strong></label>
                            <input type="url" name="twitter_link"
                                class="form-input w-full px-4 py-3 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('twitter_link', $restaurant->twitter_link ?? '') }}"
                                placeholder="Enter X (Twitter) URL">
                            @error('twitter_link')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-span-12 md:col-span-6 mb-4">
                            <label class="form-label"><strong>Instagram Link</strong></label>
                            <input type="url" name="instagram_link"
                                class="form-input w-full px-4 py-3 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('instagram_link', $restaurant->instagram_link ?? '') }}"
                                placeholder="Enter Instagram URL">
                            @error('instagram_link')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-span-12 md:col-span-6 mb-4">
                            <label class="form-label"><strong>Facebook Link</strong></label>
                            <input type="url" name="facebook_link"
                                class="form-input w-full px-4 py-3 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition"
                                value="{{ old('facebook_link', $restaurant->facebook_link ?? '') }}"
                                placeholder="Enter Facebook URL">
                            @error('facebook_link')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-span-12 mb-4">
                            <label class="form-label"><strong>Opening Hours</strong></label>
                            <textarea name="opening_hours" rows="3"
                                class="form-input w-full px-4 py-3 border focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition h-auto resize-none">{{ old('opening_hours', $restaurant->opening_hours ?? '') }}</textarea>
                            @error('opening_hours')
                                <small class="text-red-500 text-sm mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-dark-text border-b pb-2">Permissions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach ([
            'allow_place_order' => 'Allow Place Orders',
            'allow_call_owner' => 'Allow Call Owner',
            'allow_call_manager' => 'Allow Call Manager',
            'allow_call_cashier' => 'Allow Call Cashier',
            'allow_call_supervisor' => 'Allow Call Supervisor',
        ] as $name => $label)
                            <div class="flex items-center gap-2">
                                <input id="{{ $name }}" type="checkbox"
                                    class="check check-primary-solid h-5 w-5 text-blue-500 focus:ring-blue-500 border-gray-300 rounded"
                                    name="{{ $name }}" value="1"
                                    {{ old($name, $restaurant->$name ?? false) ? 'checked' : '' }}>
                                <label for="{{ $name }}"
                                    class="text-sm font-medium text-gray-700 dark:text-dark-text">{{ $label }}</label>
                            </div>
                        @endforeach

                        <div class="col-span-12 mb-4">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="notify_customer" class="check check-primary-solid"
                                    {{ old('notify_customer', $restaurant->notify_customer ?? false) ? 'checked' : '' }}>
                                <span>Notify Customer to Collect Order</span>
                            </label>
                            <p class="text-sm text-gray-500">Send notifications when orders are ready</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-dark-border mt-2">
                    <button type="submit" class="btn b-solid btn-primary-solid dk-theme-card-square mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#admin-emails').select2({
                tags: true,
                placeholder: "Select or type admin emails",
                tokenSeparators: [',', ' '],
                allowClear: true,
            });
        });

        function previewLogo(input) {
            const previewContainer = document.getElementById('logo-preview');
            previewContainer.innerHTML = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        if (this.width !== 250 || this.height !== 100) {
                            input.value = '';
                            document.getElementById('logo-error').style.display = 'block';
                            document.getElementById('logo-error').textContent =
                                'Logo must be exactly 250 x 100 pixels';
                        } else {
                            document.getElementById('logo-error').style.display = 'none';
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'position-relative mr-2 mb-2';

                            const previewImg = document.createElement('img');
                            previewImg.src = e.target.result;
                            previewImg.className = 'img-thumbnail';
                            previewImg.style.height = '80px';

                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'btn btn-sm btn-danger position-absolute';
                            removeBtn.style.top = '0';
                            removeBtn.style.right = '0';
                            removeBtn.innerHTML = '×';
                            removeBtn.onclick = function() {
                                previewDiv.remove();
                                input.value = '';
                            };

                            previewDiv.appendChild(previewImg);
                            previewDiv.appendChild(removeBtn);
                            previewContainer.appendChild(previewDiv);
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function removeLogo() {
            document.getElementById('remove_logo').value = '1';
            const logoContainer = event.target.closest('div');
            logoContainer.style.display = 'none';
            document.getElementById('logo-preview').innerHTML = '';
        }

        function previewPictures(input) {
            const previewContainer = document.getElementById('picture-preview');

            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'position-relative mr-2 mb-2';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        img.style.height = '80px';

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'btn btn-sm btn-danger position-absolute';
                        removeBtn.style.top = '0';
                        removeBtn.style.right = '0';
                        removeBtn.innerHTML = '×';
                        removeBtn.onclick = function() {
                            previewDiv.remove();
                        };

                        previewDiv.appendChild(img);
                        previewDiv.appendChild(removeBtn);
                        previewContainer.appendChild(previewDiv);
                    };

                    reader.readAsDataURL(input.files[i]);
                }
            }
        }

        function removePicture(index) {
            const removedPictures = document.getElementById('remove_pictures').value;
            const updatedPictures = removedPictures ?
                removedPictures.split(',').concat([index]).join(',') :
                index.toString();
            document.getElementById('remove_pictures').value = updatedPictures;
            document.getElementById(`picture-${index}`).style.display = 'none';
        }

        function removeVideo() {
            document.getElementById('remove_video').value = '1';
            const videoContainer = event.target.closest('div');
            videoContainer.style.display = 'none';
        }
    </script>
@endpush
