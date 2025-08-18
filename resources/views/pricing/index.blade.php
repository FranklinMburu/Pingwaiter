@extends('layouts.app')

@section('title', 'Pricing')

@section('content')
    @php
        $isPremium = $subscription && $subscription->status === 'ACTIVE';
    @endphp

    <div class="card p-0 overflow-hidden">
        <!-- Header -->
        <div
            class="flex flex-col gap-2 sm:flex-center-between sm:flex-row px-4 py-5 sm:p-7 bg-gray-200/30 dark:bg-dark-card-two">
            <div>
                <h6 class="card-title">Plans & Pricing</h6>
            </div>
        </div>

        <div class="card-body px-8">
            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Pricing Cards -->
            <div class="p-7 xl:p-15">
                <div class="grid grid-cols-2 gap-5">
                    <!-- Free Plan -->
                    <div
                        class="col-span-full sm:col-auto dk-border-one p-5 xl:p-10 rounded-20 h-max hover:border-primary-500 group/pc dk-theme-card-square ac-transition">
                        <h6 class="sm:text-lg !leading-none text-heading font-semibold">Free</h6>
                        <div class="text-[42px] leading-none text-heading dark:text-dark-text font-extrabold mt-3 mb-8">
                            $0.00
                            <sub class="text-base font-medium text-gray-900 dark:text-dark-text relative bottom-1">/
                                Month</sub>
                        </div>

                        <button
                            class="dk-border-one text-heading dark:text-dark-text flex-center w-full py-3.5 rounded-full
                        {{ !$isPremium ? 'bg-primary-500 text-white' : 'hover:!bg-primary-500 group-hover/pc:text-white group-hover/pc:bg-primary-400' }}
                        dk-theme-card-square ac-transition">
                            {{ !$isPremium ? 'Current Plan' : 'Choose Plan' }}
                        </button>
                    </div>

                    <!-- Premium Plan -->
                    <div
                        class="col-span-full sm:col-auto dk-border-one p-5 xl:p-10 rounded-20 h-max hover:border-primary-500 group/pc dk-theme-card-square ac-transition">
                        <div
                            class="sm:text-lg text-primary-500 dark:text-dark-text font-semibold bg-primary-200 dark:bg-dark-icon px-5 py-1.5 rounded-full w-max mb-5 dk-theme-card-square">
                            Premium
                        </div>
                        <h6 class="sm:text-lg text-heading font-semibold">Premium</h6>
                        <div class="text-[42px] leading-none text-heading dark:text-dark-text font-extrabold mt-3 mb-8">
                            $20.00
                            <sub class="text-base font-medium text-gray-900 dark:text-dark-text relative bottom-1">/
                                Month</sub>
                        </div>

                        @if ($isPremium)
                            <button
                                class="dk-border-one bg-green-100 text-green-700 font-semibold flex-center w-full py-3.5 rounded-full dk-theme-card-square ac-transition cursor-default">
                                Current Plan
                            </button>
                        @else
                            <form action="{{ route('subscription.create') }}" method="GET">
                                <button type="submit"
                                    class="dk-border-one text-heading dark:text-dark-text flex-center w-full py-3.5 rounded-full hover:!bg-primary-500 group-hover/pc:text-white group-hover/pc:bg-primary-400 dk-theme-card-square ac-transition">
                                    Choose Plan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
