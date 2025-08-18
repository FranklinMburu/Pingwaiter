@extends('layouts.main')

@section('title', 'Pingwaiter - Restaurant Management Platform')

@push('styles')
    <style>
        .section-card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #f3f4f6;
        }

        .primary-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 16px 24px;
            border-radius: 0px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .restaurant-btn {
            background: linear-gradient(135deg, #8065ee 0%, #6d28d9 100%);
            color: white;
            border: none;
        }

        .restaurant-btn:hover {
            background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(128, 101, 238, 0.4);
        }

        .customer-btn {
            background: linear-gradient(135deg, #8065ee 0%, #6d28d9 100%);
            color: white;
            border: none;
        }

        .customer-btn:hover {
            background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .demo-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 14px 24px;
            border: 2px solid #e5e7eb;
            border-radius: 0px;
            background: white;
            color: #6b7280;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .demo-btn:hover {
            border-color: #9ca3af;
            color: #374151;
            background: #f9fafb;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 4px;
            border-radius: 2px;
        }

        .restaurant-title::after {
            background: linear-gradient(135deg, #8065ee 0%, #6d28d9 100%);
        }

        .customer-title::after {
            background: linear-gradient(135deg, #8065ee 0%, #6d28d9 100%);
        }

        .divider {
            width: 2px;
            background: linear-gradient(to bottom, transparent, #e5e7eb 20%, #e5e7eb 80%, transparent);
            margin: 0 3rem;
            min-height: 400px;
        }
    </style>
@endpush

@section('content')
    <div class="flex min-h-screen">
        <div class="w-full max-w-5xl mx-auto flex flex-col relative my-28">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Get Started</h2>
                <p class="text-gray-600 text-lg">Choose how you'd like to explore our platform</p>
            </div>

            <div class="w-full">
                @if (session('error'))
                    <div class="mb-6 bg-red-50 max-w-md mx-auto border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border max-w-md mx-auto border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <strong>There were some errors with your submission:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Professional Two Column Layout -->
            <div class="flex flex-col lg:flex-row items-start justify-center gap-12">

                <!-- Restaurant Owner Section -->
                <div class="flex-1 max-w-sm w-full mx-auto">
                    <div class="section-card">
                        <h3 class="section-title restaurant-title">Restaurant Owner</h3>

                        <div class="space-y-4">
                            <a href="{{ route('register') }}" class="primary-btn restaurant-btn">
                                <!-- Signup button icon -->
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                Sign Up as Restaurant Owner
                            </a>

                            <!-- OR Separator -->
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 h-px bg-gray-300"></div>
                                <span class="text-gray-500 text-sm font-medium">OR</span>
                                <div class="flex-1 h-px bg-gray-300"></div>
                            </div>

                            <a href="{{ route('demo.login') }}" class="demo-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                    <line x1="8" y1="21" x2="16" y2="21" />
                                    <line x1="12" y1="17" x2="12" y2="21" />
                                </svg>
                                Try Restaurant Owner Demo
                            </a>

                            <!-- OR Separator -->
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 h-px bg-gray-300"></div>
                                <span class="text-gray-500 text-sm font-medium">OR</span>
                                <div class="flex-1 h-px bg-gray-300"></div>
                            </div>

                            <a href="{{ route('google.login', ['role' => 'customer', 'table' => 'T001']) }}"
                                class="demo-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2" />
                                    <line x1="12" y1="18" x2="12.01" y2="18" />
                                </svg>
                                Try Customer Menu Demo
                            </a>

                            <div class="text-center pt-4">
                                <p class="text-gray-600">
                                    Already have an account?
                                    <a href="{{ route('restaurant.login') }}"
                                        class="font-semibold text-[#8065ee] hover:text-[#8065ee] underline transition-colors">
                                        Sign In
                                    </a>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Professional Divider -->
                <div class="divider hidden lg:block"></div>

                <!-- Customer Section -->
                <div class="flex-1 max-w-sm w-full mt-12 lg:mt-0 mx-auto">
                    <div class="section-card">
                        <h3 class="section-title customer-title">Customer</h3>

                        <div class="space-y-4">
                            <a href="{{ route('customer.login') }}" class="primary-btn customer-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                Sign Up as Customer
                            </a>

                            <!-- OR Separator -->
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 h-px bg-gray-300"></div>
                                <span class="text-gray-500 text-sm font-medium">OR</span>
                                <div class="flex-1 h-px bg-gray-300"></div>
                            </div>

                            <a href="{{ route('google.login', ['role' => 'customer', 'table' => 'T001']) }}"
                                class="demo-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="5" y="2" width="14" height="20" rx="2" ry="2" />
                                    <line x1="12" y1="18" x2="12.01" y2="18" />
                                </svg>
                                Try Customer Menu Demo
                            </a>

                            <div class="text-center pt-4">
                                <p class="text-gray-600">
                                    Already have an account?
                                    <a href="{{ route('customer.login') }}"
                                        class="font-semibold text-[#8065ee] hover:text-[#8065ee] underline transition-colors">
                                        Sign In
                                    </a>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
