<x-slot name="header">
    <div class=" flex items-center justify-between">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.items') }}
        </h2>
        @can('items_create')
            <x-button x-data="{}" x-on:click="window.livewire.emitTo('item-form','showingModal',null)"
                wire:loading.attr="disabled" class="py-0.5">{{ __('messages.add_item') }}</x-button>
        @endcan
    </div>
</x-slot>


<div class="p-6 lg:lg-8 {{ $items->hasPages() ? 'mb-10' : '' }}">
    @livewire('item-form')


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
                <input wire:model="search" type="text" id="table-search-items"
                    class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="{{ __('messages.search') }}">
            </div>
        </div>
    </div>

    {{-- small screen view --}}
    <div class=" lg:hidden">
        @forelse ($items as $item)
            <div
                class="flex mb-1 items-center gap-5 text-sm text-start text-gray-500 dark:text-gray-400 bg-white rounded-lg border p-4 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <div class=" flex flex-col flex-1 space-y-2 justify-start">
                    <div>{{ $item->name }}</div>
                    <div>{{ $item->unit }}</div>
                    <div>
                        <span
                            class="bg-green-100 text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ __('messages.available') }}
                            : {{ $item->available_quantity }}</span>
                    </div>
                    <div>
                        <span
                            class="bg-red-100 text-red-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{{ __('messages.deprecated') }}
                            : {{ $item->deprecated_quantity }}</span>
                    </div>
                    <div>{{ $item->section_name }}</div>
                </div>
                <div class=" text-end">
                    <div class="flex gap-3 justify-end">
                        @can('items_view')
                            <button wire:click="pdf({{ $item->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        @endcan
                        @can('items_edit')
                            <button x-data="{}"
                                x-on:click="window.livewire.emitTo('item-form','showingModal',{{ $item->id }})">
                                <x-svgs.edit />
                            </button>
                        @endcan
                        @can('items_delete')
                            <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                wire:click="delete({{ $item }})" class="text-red-400">
                                <x-svgs.trash />
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
        @endforelse
        @if ($items->hasPages())
            <div class="absolute bottom-0 left-0 right-0 p-2 bg-white dark:bg-gray-800">
                {{ $items->links() }}
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
                    <th scope="col" width="20%" class="px-6 py-3 text-center">{{ __('messages.unit') }}</th>
                    <th scope="col" width="10%" class="px-6 py-3 text-center">{{ __('messages.available') }}</th>
                    <th scope="col" width="10%" class="px-6 py-3 text-center">{{ __('messages.deprecated') }}
                    </th>
                    <th scope="col" width="10%" class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class=" divide-y">
                @forelse ($items as $item)
                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="w-4 p-4">
                            <div class="flex items-center">
                                <input id="checkbox-table-search-1" type="checkbox"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-start">{{ $item->name }}</td>
                        <td class="px-6 py-4 text-center">{{ $item->unit }}</td>
                        <td class="px-6 py-4 text-center">
                            @if ($item->available_quantity > 0)
                                <span
                                    class="bg-green-100 text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $item->available_quantity }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($item->deprecated_quantity > 0)
                                <span
                                    class="bg-red-100 text-red-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{{ $item->deprecated_quantity }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-end">
                            <div class="flex gap-3 justify-end">
                                @can('items_view')
                                    <button wire:click="pdf({{ $item->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                @endcan
                                @can('items_edit')
                                    <button x-data="{}"
                                        x-on:click="window.livewire.emitTo('item-form','showingModal',{{ $item->id }})">
                                        <x-svgs.edit />
                                    </button>
                                @endcan
                                @can('items_delete')
                                    <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $item }})" class="text-red-400">
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
        @if ($items->hasPages())
            <div class="absolute bottom-0 left-0 right-0 p-2 bg-white dark:bg-gray-800">
                {{ $items->links() }}
            </div>
        @endif
    </div>


</div>
