<x-slot name="header">
    <div class=" flex items-center justify-between">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.users') }}
        </h2>
        @can('users_create')
            <x-button x-data="{}" x-on:click="window.livewire.emitTo('user-form','showingModal',null)"
                wire:loading.attr="disabled" class="py-0.5">{{ __('messages.add_user') }}</x-button>
        @endcan
    </div>
</x-slot>


<div class="p-6 lg:lg-8 {{ $users->hasPages() ? 'mb-10' : '' }}">
    @livewire('user-form')

    <div class="sm:rounded-lg">
        <div class="flex items-center justify-between py-4 bg-white dark:bg-gray-800">
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <input wire:model="search" type="text" id="table-search-users"
                    class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="{{ __('messages.search') }}">
            </div>
        </div>
    </div>

    {{-- small screen view --}}
    <div class=" lg:hidden">
        @forelse ($users as $user)
            <div
                class="flex mb-1 items-center gap-5 text-sm text-start text-gray-500 dark:text-gray-400 bg-white rounded-lg border p-4 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <div class="flex items-center">
                    <input id="checkbox-table-search-1" type="checkbox"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                </div>
                <div class=" flex flex-col flex-1 justify-start">
                    <div class="flex items-center text-start  text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="">
                            <div class="text-base font-semibold">{{ $user->name }}</div>
                            <div class="font-normal text-gray-500">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="">{{ $user->username }}</div>
                    <div class="">
                        @if ($user->active)
                            <div class="flex items-center justify-start">
                                <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>
                                {{ __('messages.active') }}
                            </div>
                        @else
                            <div class="flex items-center justify-start">
                                <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div>
                                {{ __('messages.inactive') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class=" text-end">
                    <div class="flex gap-3 justify-end">
                        @can('users_edit')
                            <button x-data="{}"
                                x-on:click="window.livewire.emitTo('user-form','showingModal',{{ $user->id }})">
                                <x-svgs.edit />
                            </button>
                        @endcan
                        @can('users_delete')
                            <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                wire:click="delete({{ $user }})" class="text-red-400">
                                <x-svgs.trash />
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
        @endforelse
        @if ($users->hasPages())
            <div class="absolute bottom-0 left-0 right-0 p-2 bg-white dark:bg-gray-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- large screen view --}}
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-sm text-start text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4" width="5%">
                        <div class="flex items-center">
                            <input id="checkbox-all-search" type="checkbox"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-all-search" class="sr-only">checkbox</label>
                        </div>
                    </th>
                    <th scope="col" width="20%" class="px-6 py-3 text-start">{{ __('messages.name') }}</th>
                    <th scope="col" width="20%" class="px-6 py-3 text-start">{{ __('messages.username') }}</th>
                    <th scope="col" width="10%" class="px-6 py-3 text-center">{{ __('messages.status') }}</th>
                    <th scope="col" width="10%" class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class=" divide-y">
                @forelse ($users as $user)
                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="w-4 p-4">
                            <div class="flex items-center">
                                <input id="checkbox-table-search-1" type="checkbox"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                            </div>
                        </td>
                        <th scope="row"
                            class="flex items-center text-start px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            {{-- <img class="w-10 h-10 rounded-full" src="/docs/images/people/profile-picture-1.jpg"
                            alt="Jese image"> --}}
                            <div class="">
                                <div class="text-base font-semibold">{{ $user->name }}</div>
                                <div class="font-normal text-gray-500">{{ $user->email }}</div>
                            </div>
                        </th>
                        <td class="px-6 py-4 text-start">{{ $user->username }}</td>
                        <td class="px-6 py-4 text-center">
                            @if ($user->active)
                                <div class="flex items-center justify-center">
                                    <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>
                                    {{ __('messages.active') }}
                                </div>
                            @else
                                <div class="flex items-center justify-center">
                                    <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div>
                                    {{ __('messages.inactive') }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-end">
                            <div class="flex gap-3 justify-end">
                                @can('users_edit')
                                    <button x-data="{}"
                                        x-on:click="window.livewire.emitTo('user-form','showingModal',{{ $user->id }})">
                                        <x-svgs.edit />
                                    </button>
                                @endcan
                                @can('users_delete')
                                    <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $user }})" class="text-red-400">
                                        <x-svgs.trash />
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        @if ($users->hasPages())
            <div class="absolute bottom-0 left-0 right-0 p-2 bg-white dark:bg-gray-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>


</div>
