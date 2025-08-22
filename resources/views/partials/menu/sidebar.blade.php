
@php
    $error = session('error');
    $success = session('success');
    $info = session('info');
    $user = auth()->user();
    $role = $user->role ?? null;
@endphp

<!-- Modern Global Feedback Alerts -->
<div class="px-4 pt-4">
    @if ($error)
        <div class="flex items-center gap-2 p-3 rounded-lg bg-gradient-to-r from-red-400/20 to-red-100 border border-red-300 text-red-800 text-sm font-semibold shadow-sm animate-fade-in">
            <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
            <span>{{ $error }}</span>
        </div>
    @endif
    @if ($success)
        <div class="flex items-center gap-2 p-3 rounded-lg bg-gradient-to-r from-green-400/20 to-green-100 border border-green-300 text-green-800 text-sm font-semibold shadow-sm animate-fade-in">
            <i class="fas fa-check-circle text-green-500 text-lg"></i>
            <span>{{ $success }}</span>
        </div>
    @endif
    @if ($info)
        <div class="flex items-center gap-2 p-3 rounded-lg bg-gradient-to-r from-blue-400/20 to-blue-100 border border-blue-300 text-blue-800 text-sm font-semibold shadow-sm animate-fade-in">
            <i class="fas fa-info-circle text-blue-500 text-lg"></i>
            <span>{{ $info }}</span>
        </div>
    @endif
</div>

<!-- Sidebar Navigation -->
<nav class="mt-6 fixed top-0 left-0 h-full w-[280px] z-40 bg-body-light dark:bg-dark-body shadow-lg overflow-y-auto">
    <ul class="space-y-2">
        <!-- Dashboard Section -->
        <li class="px-4 mt-3">
            <h5 class="text-heading text-base font-bold mb-2 tracking-wide text-gray-700 dark:text-gray-200 flex items-center gap-2">
                <i class="fas fa-home text-primary-500"></i> Dashboard
            </h5>
        </li>
        <li>
            <a href="{{ url('/dashboard') }}" class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Admin Section -->
        @if ($user && $role === 'admin')
            <li class="px-4 mt-6">
                <h5 class="text-heading text-base font-bold mb-2 tracking-wide text-gray-700 dark:text-gray-200 flex items-center gap-2">
                    <i class="fas fa-user-shield text-primary-500"></i> Admin Tools
                </h5>
            </li>
            <li>
                <a href="{{ route('invitations.index') }}" class="sidebar-link {{ request()->routeIs('invitations.index') ? 'active' : '' }}">
                    <i class="fas fa-user-plus"></i>
                    <span>Invitations</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i>
                    <span>User Management</span>
                </a>
            </li>
            @if ($user->is_first_user ?? false)
                <li>
                    <a href="{{ route('onboarding.admin') }}" class="sidebar-link text-blue-600 {{ request()->routeIs('onboarding.admin') ? 'active' : '' }}">
                        <i class="fas fa-crown"></i>
                        <span>Admin Onboarding</span>
                    </a>
                </li>
            @endif
        @endif

        <!-- Invitation Recovery: visible to all users -->
        <li class="px-4 mt-6">
            <h5 class="text-heading text-base font-bold mb-2 tracking-wide text-gray-700 dark:text-gray-200 flex items-center gap-2">
                <i class="fas fa-key text-primary-500"></i> Invitations
            </h5>
        </li>
        <li>
            <a href="{{ route('invitations.recover') }}" class="sidebar-link {{ request()->routeIs('invitations.recover') ? 'active' : '' }}">
                <i class="fas fa-key"></i>
                <span>Recover Invitation</span>
            </a>
        </li>

        <!-- Restaurant Section -->
        @if ($user && $user->isRestaurant())
            <li class="px-4 mt-6">
                <h5 class="text-heading text-base font-bold mb-2 tracking-wide text-gray-700 dark:text-gray-200 flex items-center gap-2">
                    <i class="fas fa-store text-primary-500"></i> Restaurant Management
                </h5>
            </li>
            <li>
                <a href="{{ url('/setup-restaurant') }}" class="sidebar-link {{ request()->is('setup-restaurant') ? 'active' : '' }}">
                    <i class="fas fa-fw fa-cogs"></i>
                    <span>Setup Restaurant</span>
                </a>
            </li>
            @if (isset($user->restaurant->id))
                <li>
                    <a href="{{ route('tables.index') }}" class="sidebar-link {{ request()->routeIs('tables.index') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Setup Tables</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('listentable') }}" class="sidebar-link {{ request()->is('listentable') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <span>Select Tables To Serve</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('food.categories.index') }}" class="sidebar-link {{ request()->routeIs('food.categories.index') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <span>Food Categories</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('food.styles.index') }}" class="sidebar-link {{ request()->routeIs('food.styles.index') ? 'active' : '' }}">
                        <i class="fas fa-utensils"></i>
                        <span>Food Styles</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('food.menu.index') }}" class="sidebar-link {{ request()->routeIs('food.menu.index') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <span>Setup Food Menu</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('workers.index') }}" class="sidebar-link {{ request()->routeIs('workers.index') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-users"></i>
                        <span>Invite Workers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('table_pings') }}" class="sidebar-link {{ request()->is('table_pings') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <span>View Table Pings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('approve_orders') }}" class="sidebar-link {{ request()->is('approve_orders') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <span>Approve Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('view_table_requests') }}" class="sidebar-link {{ request()->is('view_table_requests') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <span>View Table Orders</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/reports') }}" class="sidebar-link {{ request()->is('reports') ? 'active' : '' }}">
                        <i class="fas fa-fw fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('api.index') }}" class="sidebar-link {{ request()->routeIs('api.index') ? 'active' : '' }}">
                        <i class="fas fa-key"></i>
                        <span>API Keys</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/customer/rewards') }}" class="sidebar-link {{ request()->is('contact') ? 'active' : '' }}">
                        <i class="fas fa-gift"></i>
                        <span>Customer Rewards</span>
                    </a>
                </li>
            @endif
        @endif

        <!-- Waiter Section -->
        @if ($user && $user->isWaiter())
            <li class="px-4 mt-6">
                <h5 class="text-heading text-base font-bold mb-2 tracking-wide text-gray-700 dark:text-gray-200 flex items-center gap-2">
                    <i class="fas fa-user-tie text-primary-500"></i> Waiter Tools
                </h5>
            </li>
            <li>
                <a href="{{ url('listentable') }}" class="sidebar-link {{ request()->is('listentable') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>Select Tables To Serve</span>
                </a>
            </li>
            <li>
                <a href="{{ url('table_pings') }}" class="sidebar-link {{ request()->is('table_pings') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>View Table Pings</span>
                </a>
            </li>
            <li>
                <a href="{{ url('approve_orders') }}" class="sidebar-link {{ request()->is('approve_orders') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>Approve Orders</span>
                </a>
            </li>
            <li>
                <a href="{{ url('view_table_requests') }}" class="sidebar-link {{ request()->is('view_table_requests') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>View Table Orders</span>
                </a>
            </li>
        @endif

        <!-- Other Roles Section -->
        @if ($user && !$user->isRestaurant() && !$user->isWaiter())
            <li class="px-4 mt-6">
                <h5 class="text-heading text-base font-bold mb-2 tracking-wide text-gray-700 dark:text-gray-200 flex items-center gap-2">
                    <i class="fas fa-user text-primary-500"></i> User Tools
                </h5>
            </li>
            <li>
                <a href="{{ url('approve_orders') }}" class="sidebar-link {{ request()->is('approve_orders') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>Approve Orders</span>
                </a>
            </li>
            <li>
                <a href="{{ url('view_table_requests') }}" class="sidebar-link {{ request()->is('view_table_requests') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span>View Table Orders</span>
                </a>
            </li>
        @endif
    </ul>
</nav>

<!-- Upgrade Callout for Restaurant -->
@if ($user && $user->isRestaurant() && !$user->hasActiveSubscription())
    <div class="px-6 mt-8">
        <div class="w-full pt-4 pb-6 px-4 bg-gradient-to-r from-purple-400/20 to-[#F2ECFF] dark:bg-dark-card-two rounded-xl shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div class="flex-1">
                    <h6 class="text-sm font-semibold text-primary-600 dark:text-primary-400">Unlock More Features</h6>
                </div>
                <a href="/pricing" class="shrink-0 px-4 py-2 text-xs font-semibold rounded bg-primary-500 hover:bg-primary-600 text-white transition shadow">Upgrade</a>
            </div>
        </div>
    </div>
@endif

<!-- Modern Sidebar Link Styles -->
<style>
.sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem;
    font-size: 1rem;
    font-weight: 500;
    color: #4B5563;
    border-radius: 0.75rem;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    background: transparent;
    text-decoration: none;
}
.sidebar-link:hover, .sidebar-link.active {
    background: linear-gradient(90deg, #6366F1 0%, #A5B4FC 100%);
    color: #fff;
    box-shadow: 0 2px 8px 0 rgba(99,102,241,0.08);
}
.sidebar-link i {
    font-size: 1.2rem;
    min-width: 1.5rem;
    text-align: center;
}
.animate-fade-in {
    animation: fadeIn 0.5s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

@if ($user && $user->role === 'admin')
                    <li class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                        <a href="{{ route('invitations.index') }}"
                           class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->routeIs('invitations.index') ? 'active' : '' }}">
                            <span class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                <i class="fas fa-user-plus"></i>
                            </span>
                            <span class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                Invitations
                            </span>
                        </a>
                    </li>
                    <li class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                        <a href="{{ route('admin.users.index') }}"
                           class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <span class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                <i class="fas fa-users-cog"></i>
                            </span>
                            <span class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                User Management
                            </span>
                        </a>
                    </li>
                @endif

                <!-- Invitation Recovery: visible to all users -->
                <li class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                    <a href="{{ route('invitations.recover') }}"
                       class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->routeIs('invitations.recover') ? 'active' : '' }}">
                        <span class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                            <i class="fas fa-key"></i>
                        </span>
                        <span class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                            Recover Invitation
                        </span>
                    </a>
                </li>
                @if ($user && $user->role === 'admin' && ($user->is_first_user ?? false))
                    <li class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                        <a href="{{ route('onboarding.admin') }}"
                           class="relative text-blue-600 font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-blue-100 group-data-[sidebar-size=sm]:hover:bg-blue-200 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->routeIs('onboarding.admin') ? 'active' : '' }}">
                            <span class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                <i class="fas fa-crown"></i>
                            </span>
                            <span class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                Admin Onboarding
                            </span>
                        </a>
                    </li>
                @endif
 <!-- Start App Menu -->
 <div id="app-menu-drawer"
     class="app-menu flex flex-col gap-y-2.5 bg-white dark:bg-dark-card w-app-menu fixed top-0 left-0 bottom-0 -translate-x-full group-data-[sidebar-size=sm]:min-h-screen group-data-[sidebar-size=sm]:h-max xl:translate-x-0 xl:group-data-[sidebar-size=lg]:w-app-menu xl:group-data-[sidebar-size=sm]:w-app-menu-sm xl:group-data-[sidebar-size=sm]:absolute xl:group-data-[sidebar-size=lg]:fixed xl:top-4 xl:left-4 xl:bottom-4 z-backdrop xl:group-data-[theme-width=box]:left-auto dk-theme-card-square ac-transition pb-6"
     tabindex="-1">
     <div
         class="px-4 h-header flex items-center shrink-0 group-data-[sidebar-size=sm]:px-2 group-data-[sidebar-size=sm]:justify-center">
         <a href="{{ route('dashboard') }}" class="group-data-[sidebar-size=lg]:block hidden mx-auto"
             style="font-family: 'Comfortaa', sans-serif;">
             @if (isset(auth()->user()->restaurant->logo))
                 <img src="{{ asset('uploads/restaurant/logos/' . auth()->user()->restaurant->logo) }}" alt="Logo"
                     class="group-[.dark]:hidden" style="max-height: 40px;">
                 <img src="{{ asset('uploads/restaurant/logos/' . auth()->user()->restaurant->logo) }}" alt="Logo"
                     class="group-[.light]:hidden" style="max-height: 40px;">
             @else
                 <img src="{{ asset('/logo-2.png') }}" alt="Logo" class="group-[.dark]:hidden"
                     style="max-height: 40px;">
             @endif
         </a>
         <a href="{{ route('dashboard') }}" class="group-data-[sidebar-size=lg]:hidden block mx-auto">
             @if (isset(auth()->user()->restaurant->logo))
                 <img src="{{ asset('uploads/restaurant/logos/' . auth()->user()->restaurant->logo) }}" alt="Logo"
                     style="max-height: 40px;">
             @else
                 <img src="{{ asset('/logo-2.png') }}" alt="Logo" class="group-[.dark]:hidden"
                     style="max-height: 40px;">
             @endif
         </a>
     </div>
     <div id="app-menu-scrollbar" data-scrollbar
         class="pl-4 group-data-[sidebar-size=sm]:pl-0 group-data-[sidebar-size=sm]:!overflow-visible !overflow-x-hidden smooth-scrollbar">
         <div class="group-data-[sidebar-size=lg]:max-h-full">
             <ul id="navbar-nav"
                 class="text-[14px] !leading-none space-y-1 group-data-[sidebar-size=sm]:space-y-2.5 group-data-[sidebar-size=sm]:flex group-data-[sidebar-size=sm]:flex-col group-data-[sidebar-size=sm]:items-start group-data-[sidebar-size=sm]:mx-2 group-data-[sidebar-size=sm]:overflow-visible">

                 <li class="px-4 mt-3 group-data-[sidebar-size=sm]:hidden">
                     <h5 class="text-heading text-lg leading-none font-semibold mb-3">Quick Access</h5>
                 </li>

                 <li
                     class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                     <a href="{{ url('/dashboard') }}"
                         class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('dashboard') ? 'active' : '' }}">
                         <span
                             class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                             <i class="fas fa-fw fa-tachometer-alt"></i>
                         </span>
                         <span
                             class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                             Dashboard
                         </span>
                     </a>
                 </li>
                 @if (auth()->user()->isRestaurant())
                     <li
                         class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                         <a href="{{ url('/setup-restaurant') }}"
                             class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('setup-restaurant') ? 'active' : '' }}">
                             <span
                                 class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                 <i class="fas fa-fw fa-cogs"></i>
                             </span>
                             <span
                                 class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                 Setup Restaurant
                             </span>
                         </a>
                     </li>
                 @endif

                 @if (auth()->user()->isRestaurant())
                     <li
                         class="px-4 mt-10 mb-6 pt-5 border-t border-gray-200 dark:border-dark-border-two group-data-[sidebar-size=sm]:hidden">
                         {{-- <h5 class="text-heading text-lg leading-none font-semibold mb-3">Restaurant Management</h5> --}}
                     </li>

                     @if (isset(auth()->user()->restaurant->id))
                         <!-- Setup Tables -->
                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ route('tables.index') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->routeIs('tables.index') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-fw fa-table"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     Setup Tables
                                 </span>
                             </a>
                         </li>
                         <!-- Select Tables To Serve -->
                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ url('listentable') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('listentable') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-receipt"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     Select Tables To Serve
                                 </span>
                             </a>
                         </li>

                         <!-- Food Categories -->
                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ route('food.categories.index') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->routeIs('food.categories.index') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-list"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     Food Categories
                                 </span>
                             </a>
                         </li>

                         <!-- Food Styles -->
                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ route('food.styles.index') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->routeIs('food.styles.index') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-utensils"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     Food Styles
                                 </span>
                             </a>
                         </li>

                         <!-- Setup Food Menu -->
                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ route('food.menu.index') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->routeIs('food.menu.index') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-receipt"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     Setup Food Menu
                                 </span>
                             </a>
                         </li>

                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ route('workers.index') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->routeIs('workers.index') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-fw fa-users"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     Invite Workers
                                 </span>
                             </a>
                         </li>

                         <li
                             class="px-4 mt-10 mb-6 pt-5 border-t border-gray-200 dark:border-dark-border-two group-data-[sidebar-size=sm]:hidden">
                             {{-- <h5 class="text-heading text-lg leading-none font-semibold mb-3">Restaurant Management</h5> --}}
                         </li>

                         <!-- View Table Pings -->
                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ url('table_pings') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('table_pings') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-receipt"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     View Table Pings
                                 </span>
                             </a>
                         </li>

                         <!-- Approve Orders -->
                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ url('approve_orders') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('approve_orders') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-receipt"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     Approve Orders
                                 </span>
                             </a>
                         </li>

                         <!-- View Table Orders -->
                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ url('view_table_requests') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('view_table_requests') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-receipt"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     View Table Orders
                                 </span>
                             </a>
                         </li>

                         <li
                             class="px-4 mt-6 pt-5 border-t border-gray-200 dark:border-dark-border-two group-data-[sidebar-size=sm]:hidden">
                             {{-- <h5 class="text-heading text-lg leading-none font-semibold mb-3">Reports</h5> --}}
                         </li>

                         <!-- Reports -->
                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ url('/reports') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('reports') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-fw fa-chart-bar"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     Reports
                                 </span>
                             </a>
                         </li>


                         <li
                             class="px-4 mt-6 pt-5 border-t border-gray-200 dark:border-dark-border-two group-data-[sidebar-size=sm]:hidden">
                             {{-- <h5 class="text-heading text-lg leading-none font-semibold mb-3">Team & Access</h5> --}}
                         </li>
                         <li
                             class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                             <a href="{{ route('api.index') }}"
                                 class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->routeIs('api.index') ? 'active' : '' }}">
                                 <span
                                     class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                     <i class="fas fa-key"></i>
                                 </span>
                                 <span
                                     class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                     API Keys
                                 </span>
                             </a>
                         </li>
                     @endif
                 @elseif(auth()->user()->isWaiter())
                     <!-- Waiter Menu Items -->
                     <li
                         class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                         <a href="{{ url('listentable') }}"
                             class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('listentable') ? 'active' : '' }}">
                             <span
                                 class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                 <i class="fas fa-receipt"></i>
                             </span>
                             <span
                                 class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                 Select Tables To Serve
                             </span>
                         </a>
                     </li>

                     <li
                         class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                         <a href="{{ url('table_pings') }}"
                             class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('table_pings') ? 'active' : '' }}">
                             <span
                                 class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                 <i class="fas fa-receipt"></i>
                             </span>
                             <span
                                 class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                 View Table Pings
                             </span>
                         </a>
                     </li>

                     <li
                         class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                         <a href="{{ url('approve_orders') }}"
                             class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=lg]:rounded-l-full group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('approve_orders') ? 'active' : '' }}">
                             <span
                                 class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                 <i class="fas fa-receipt"></i>
                             </span>
                             <span
                                 class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                 Approve Orders
                             </span>
                         </a>
                     </li>

                     <li
                         class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                         <a href="{{ url('view_table_requests') }}"
                             class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('view_table_requests') ? 'active' : '' }}">
                             <span
                                 class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                 <i class="fas fa-receipt"></i>
                             </span>
                             <span
                                 class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                 View Table Orders
                             </span>
                         </a>
                     </li>
                 @else
                     <!-- Other Roles Menu Items -->
                     <li
                         class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                         <a href="{{ url('approve_orders') }}"
                             class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('approve_orders') ? 'active' : '' }}">
                             <span
                                 class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                 <i class="fas fa-receipt"></i>
                             </span>
                             <span
                                 class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                 Approve Orders
                             </span>
                         </a>
                     </li>

                     <li
                         class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                         <a href="{{ url('view_table_requests') }}"
                             class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('view_table_requests') ? 'active' : '' }}">
                             <span
                                 class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                 <i class="fas fa-receipt"></i>
                             </span>
                             <span
                                 class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                 View Table Orders
                             </span>
                         </a>
                     </li>
                 @endif

                 @if (auth()->user()->isRestaurant())
                     <!-- Help Section -->
                     {{-- <li
                         class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                         <a href="{{ url('/contact') }}"
                             class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('contact') ? 'active' : '' }}">
                             <span
                                 class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                 <i class="fas fa-headset"></i>
                             </span>
                             <span
                                 class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                 Contact Customers
                             </span>
                         </a>
                     </li> --}}

                     <li
                         class="relative group/sm w-full group-data-[sidebar-size=sm]:hover:w-[calc(theme('spacing.app-menu-sm')_*_3.4)] group-data-[sidebar-size=sm]:flex-center">
                         <a href="{{ url('/customer/rewards') }}"
                             class="relative text-gray-500 dark:text-dark-text-two font-medium leading-none px-3.5 py-3 h-[42px] flex items-center group/menu-link ac-transition group-data-[sidebar-size=sm]:bg-gray-100 dark:group-data-[sidebar-size=sm]:bg-dark-icon group-data-[sidebar-size=sm]:hover:bg-primary-500/95 group-data-[sidebar-size=sm]:[&.active]:bg-primary-500/95 hover:text-white [&.active]:text-white hover:!bg-primary-500/95 [&.active]:bg-primary-500/95 group-data-[sidebar-size=sm]:rounded-lg group-data-[sidebar-size=sm]:group-hover/sm:!rounded-br-none group-data-[sidebar-size=sm]:p-3 group-data-[sidebar-size=sm]:w-full {{ request()->is('contact') ? 'active' : '' }}">
                             <span
                                 class="shrink-0 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.app-menu-sm')_*_0.43)] group-data-[sidebar-size=sm]:flex-center">
                                 <i class="fas fas fa-gift"></i>
                             </span>
                             <span
                                 class="group-data-[sidebar-size=sm]:hidden group-data-[sidebar-size=sm]:ml-6 group-data-[sidebar-size=sm]:group-hover/sm:block ml-3 shrink-0">
                                 Customer Rewards
                             </span>
                         </a>
                     </li>
                 @endif
             </ul>
         </div>
     </div>
     @if (auth()->user()->isRestaurant() && !auth()->user()->hasActiveSubscription())
         <div class="px-6">
             <div
                 class="w-full pt-4 pb-6 px-4 lg:py-5 xl:py-6 bg-[#F2ECFF] dark:bg-dark-card-two dk-theme-card-square">
                 <!-- Upgrade Callout -->
                 <div class="mt-6 px-4">
                     <div class="flex items-start justify-between gap-3">
                         <div class="flex-1">
                             <h6 class="text-sm font-semibold text-primary-600 dark:text-primary-400">Unlock More
                                 Features</h6>
                         </div>
                         <a href="/pricing"
                             class="shrink-0 px-4 py-2 text-xs font-semibold rounded-none bg-primary-500 hover:bg-primary-600 text-white transition">Upgrade</a>
                     </div>
                 </div>
             </div>
         </div>
     @endif
 </div>
