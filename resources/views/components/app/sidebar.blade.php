<div>
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="
        flex 
        flex-col 
        absolute 
        z-40 
        {{ app()->getLocale() == 'ar' ? 'dark:border-l' : 'dark:border-r' }} 
        dark:border-gray-700 
        start-0 
        top-0 
        lg:static 
        lg:start-auto 
        lg:top-auto 
        lg:translate-x-0 
        lg:overflow-y-auto 
        h-screen 
        overflow-y-auto 
        no-scrollbar 
        w-64 
        shrink-0 
        bg-gray-800 
        p-3 
        transition-all 
        duration-200 
        ease-in-out"
        :class="sidebarOpen ? 'translate-x-0' : '{{ app()->getLocale() == 'ar' ? 'translate-x-64' : '-translate-x-64' }}'"
        @click.outside="sidebarOpen = false" @keydown.escape.window="sidebarOpen = false" x-cloak="lg">

        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pe-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-slate-500 hover:text-slate-400" @click.stop="sidebarOpen = !sidebarOpen"
                aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
            <!-- Logo -->
            <x-application-mark class="w-6" />
        </div>

        <!-- Links -->
        <div class="space-y-8">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs uppercase text-slate-500 font-semibold ps-3">{{ __('messages.pages') }}</h3>
                <ul class="mt-3">
                    <!-- Dashboard -->
                    @can('dashboard_menu')
                        <li
                            class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if (request()->routeIs('dashboard')) {{ 'bg-slate-900' }} @endif">
                            <a class="block truncate transition duration-150" href="{{ route('dashboard') }}">
                                <div
                                    class="flex items-center {{ request()->routeIs('dashboard') ? 'text-indigo-500' : 'text-slate-400 hover:text-white' }}">
                                    <x-svgs.dashboard />
                                    <span
                                        class="text-sm font-medium ms-3 duration-200">{{ __('messages.dashboard') }}</span>
                                </div>
                            </a>
                        </li>
                    @endcan

                    @canany(['roles_menu', 'permissions_menu', 'users_menu'])
                        <!-- Setting -->
                        <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if (in_array(Route::current()->getName(), ['roles.index', 'permissions.index', 'users.index'])) {{ 'bg-slate-900' }} @endif"
                            x-data="{ open: {{ in_array(Route::current()->getName(), ['roles.index', 'permissions.index', 'users.index']) ? 1 : 0 }} }">
                            <a class="block text-slate-400 hover:text-white truncate transition duration-150 @if (in_array(Route::current()->getName(), ['roles.index', 'permissions.index', 'users.index'])) {{ 'hover:text-slate-200' }} @endif"
                                href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <x-svgs.settings />
                                        <span
                                            class="text-sm font-medium ms-3 duration-200">{{ __('messages.settings') }}</span>
                                    </div>
                                    <x-svgs.chevron />
                                </div>
                            </a>
                            <ul class="ps-9 mt-1 @if (!in_array(Route::current()->getName(), ['roles.index', 'permissions.index', 'users.index'])) {{ 'hidden' }} @endif"
                                :class="open ? '!block' : 'hidden'">
                                @can('roles_menu')
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if (request()->routeIs('roles.index')) {{ '!text-indigo-500' }} @endif"
                                            href="{{ route('roles.index') }}">
                                            <span class="text-sm font-medium duration-200">{{ __('messages.roles') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('permissions_menu')
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if (request()->routeIs('permissions.index')) {{ '!text-indigo-500' }} @endif"
                                            href="{{ route('permissions.index') }}">
                                            <span
                                                class="text-sm font-medium duration-200">{{ __('messages.permissions') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('users_menu')
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if (request()->routeIs('users.index')) {{ '!text-indigo-500' }} @endif"
                                            href="{{ route('users.index') }}">
                                            <span class="text-sm font-medium duration-200">{{ __('messages.users') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    <!-- Stores -->
                    @can('stores_menu')
                        <li
                            class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if (request()->routeIs('stores.index')) {{ 'bg-slate-900' }} @endif">
                            <a class="block truncate transition duration-150" href="{{ route('stores.index') }}">
                                <div
                                    class="flex items-center {{ request()->routeIs('stores.index') ? 'text-indigo-500' : 'text-slate-400 hover:text-white' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                    </svg>
                                    <span class="text-sm font-medium ms-3 duration-200">{{ __('messages.stores') }}</span>
                                </div>
                            </a>
                        </li>
                    @endcan

                    <!-- Items -->
                    @can('items_menu')
                        <li
                            class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if (request()->routeIs('items.index')) {{ 'bg-slate-900' }} @endif">
                            <a class="block truncate transition duration-150" href="{{ route('items.index') }}">
                                <div
                                    class="flex items-center {{ request()->routeIs('items.index') ? 'text-indigo-500' : 'text-slate-400 hover:text-white' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                                    </svg>
                                    <span class="text-sm font-medium ms-3 duration-200">{{ __('messages.items') }}</span>
                                </div>
                            </a>
                        </li>
                    @endcan

                    <!-- Transactions -->
                    @can('transactions_menu')
                        <li
                            class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if (request()->routeIs('transactions.index')) {{ 'bg-slate-900' }} @endif">
                            <a class="block truncate transition duration-150" href="{{ route('transactions.index') }}">
                                <div
                                    class="flex items-center {{ request()->routeIs('transactions.index') ? 'text-indigo-500' : 'text-slate-400 hover:text-white' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium ms-3 duration-200">{{ __('messages.transactions') }}</span>
                                </div>
                            </a>
                        </li>
                    @endcan


                    @canany(['items_report_menu', 'stores_report_menu'])
                        <!-- Setting -->
                        <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 @if (in_array(Route::current()->getName(), ['items.report', 'stores.report'])) {{ 'bg-slate-900' }} @endif"
                            x-data="{ open: {{ in_array(Route::current()->getName(), ['items.report', 'stores.report']) ? 1 : 0 }} }">
                            <a class="block text-slate-400 hover:text-white truncate transition duration-150 @if (in_array(Route::current()->getName(), ['items.report', 'stores.report'])) {{ 'hover:text-slate-200' }} @endif"
                                href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <x-svgs.report />
                                        <span
                                            class="text-sm font-medium ms-3 duration-200">{{ __('messages.reports') }}</span>
                                    </div>
                                    <x-svgs.chevron />
                                </div>
                            </a>
                            <ul class="ps-9 mt-1 @if (!in_array(Route::current()->getName(), ['items_report_menu', 'stores_report_menu'])) {{ 'hidden' }} @endif"
                                :class="open ? '!block' : 'hidden'">



                                @can('items_report_menu')
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if (request()->routeIs('items.report')) {{ '!text-indigo-500' }} @endif"
                                            href="{{ route('items.report') }}">
                                            <span class="text-sm font-medium duration-200">{{ __('messages.items_report') }}</span>
                                        </a>
                                    </li>
                                @endcan



                                @can('stores_report_menu')
                                    <li class="mb-1 last:mb-0">
                                        <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate @if (request()->routeIs('stores.report')) {{ '!text-indigo-500' }} @endif"
                                            href="{{ route('stores.report') }}">
                                            <span
                                                class="text-sm font-medium duration-200">{{ __('messages.stores_report') }}</span>
                                        </a>
                                    </li>
                                @endcan



                            </ul>
                        </li>
                    @endcanany


                </ul>
            </div>
        </div>
    </div>
</div>
