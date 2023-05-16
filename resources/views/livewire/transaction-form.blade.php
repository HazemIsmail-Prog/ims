<x-slot name="header">
    <div class=" flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $pageTitle }}
        </h2>
    </div>
</x-slot>


<div class="p-6 lg:lg-8">
    <div class="sm:rounded-lg">


        <div class="flex flex-col lg:grid lg:grid-cols-2 gap-4" x-data="{}"
            x-on:showingModal.window="setTimeout(() => $refs.name.focus(), 250)">

            <div>
                <x-label for="date">{{ __('messages.date') }}</x-label>
                <x-input id="date" type="date" class="mt-1 w-full" autocomplete="date"
                    placeholder="{{ __('messages.date') }}" x-ref="date" wire:model.defer="transaction.date" />
                <x-input-error for="transaction.date" class="mt-2" />
            </div>
            <div>
                <x-label for="notes">{{ __('messages.notes') }}</x-label>
                <x-input id="notes" type="text" class="mt-1 w-full" autocomplete="notes"
                    placeholder="{{ __('messages.notes') }}" x-ref="notes" wire:model.defer="transaction.notes" />
                <x-input-error for="transaction.notes" class="mt-2" />
            </div>

            <div>
                <x-label for="source_store">{{ __('messages.source_store') }}</x-label>
                <select id="source_store"
                    class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    wire:model="transaction.source_store_id">
                    <option disabled value="">---</option>
                    @foreach ($source_stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>

            @if ($transaction['source_store_id'])
                <div>
                    <x-label for="destination_store">{{ __('messages.destination_store') }}</x-label>
                    <select id="destination_store"
                        class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        wire:model="transaction.destination_store_id">
                        <option value="">---</option>
                        @foreach ($destination_stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($transaction['source_store_id'] && $transaction['destination_store_id'])


                {{-- Items List --}}
                <div
                    class=" border rounded-lg flex flex-col justify-center items-center border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    <div class="py-2 px-4 h-28 border-b border-gray-300 dark:border-gray-700 w-full">
                        <h2 class=" py-4 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            Items
                        </h2>
                        <div class="relative ">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input wire:model="search" type="text" id="table-search-users"
                                class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('messages.search') }}">
                        </div>
                    </div>
                    <div class="h-96 overflow-y-auto w-full">
                        @foreach ($items as $index => $item)
                            <div
                                class="flex items-center gap-4 justify-between p-2 border rounded-lg my-2 mx-4 text-gray-900 dark:text-white bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <div class=" flex-1">{{ $item['name'] }}</div>
                                @if ($route != 'stockin')
                                    <div>
                                        <span
                                            class="bg-green-100 text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $item['total_in'] - $item['total_out'] }}
                                            {{ $item['unit'] }} {{ __('messages.available') }}</span>
                                    </div>
                                @endif
                                <button class=" text-green-500"
                                    wire:click="add_to_selected_items({{ $index }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>



                {{-- Selected Items --}}
                <div
                    class=" border rounded-lg flex flex-col justify-center items-center border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    <div class="py-2 px-4 h-28 border-b border-gray-300 dark:border-gray-700 w-full">
                        <h2 class=" py-4 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                            Select Items
                        </h2>

                    </div>
                    <div class="h-96 overflow-y-auto w-full">
                        @foreach ($selected_items as $index => $item)
                            <div
                                class="flex items-center gap-4 justify-between p-2 border rounded-lg my-2 mx-4 text-gray-900 dark:text-white bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <div class=" flex-1">{{ $item['item_name'] }}</div>
                                @if ($route != 'stockin')
                                    <div>
                                        <span
                                            class="bg-green-100 text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $item['total_in'] - $item['total_out'] }}
                                            {{ $item['item_unit'] }}
                                            {{ __('messages.available') }}</span>
                                    </div>
                                @endif
                                <input wire:model="selected_items.{{ $index }}.quantity" type="number"
                                    class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-32 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    placeholder="{{ __('messages.quantity') }}">
                                <button class=" text-red-500" wire:click="unset_item({{ $index }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>


                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class=" col-span-2 text-end">
                    <x-secondary-button wire:click="" wire:loading.attr="disabled">
                        {{ __('messages.cancel') }}
                    </x-secondary-button>
                    <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                        {{ __('messages.save') }}
                    </x-button>


                </div>

            @endif

        </div>







    </div>
</div>
