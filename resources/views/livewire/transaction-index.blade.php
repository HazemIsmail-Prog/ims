<x-slot name="header">
    <div class=" flex transactions-center justify-between">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.transactions') }}
        </h2>
        @can('transactions_create')
            <x-button x-data="{}" x-on:click="window.livewire.emitTo('transaction-form','showingModal',null)"
                wire:loading.attr="disabled" class="py-0.5">{{ __('messages.add_transaction') }}</x-button>
        @endcan
    </div>
</x-slot>


<div class="p-6 lg:lg-8 {{ $transaction_details->hasPages() ? 'mb-10' : '' }}">
    @livewire('transaction-form')
    @livewire('transaction-detail-form')

    <div class="sm:rounded-lg">
        <div class="flex flex-col lg:flex-row lg:w-1/2 items-start gap-2 py-4 bg-white dark:bg-gray-800">
            <select wire:model="item_id"
                class="h-10 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('messages.all_items') }}</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            <select wire:model="store_id"
                class="h-10 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('messages.all_stores') }}</option>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- small screen view --}}
    <div class=" lg:hidden">
        @forelse ($transaction_details as $transaction)
            <div
                class="flex mb-1 items-center gap-5 text-sm text-start text-gray-500 dark:text-gray-400 bg-white rounded-lg border p-4 dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <div class=" flex flex-col flex-1 space-y-2 justify-start">
                    <div>{{ $transaction->transaction->date->format('d-m-Y') }}</div>
                    <div>
                        <span
                            class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $transaction->quantity }}</span>
                        {{ $transaction->item->name }}
                    </div>
                    <div class=" flex gap-2">
                        <span
                            class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $transaction->source_store->name }}</span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>

                        <span
                            class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $transaction->destination_store->name }}</span>
                    </div>
                </div>
                <div class=" text-end">
                    <div class="flex gap-3 justify-end">
                        @can('transactions_view')
                            <button wire:click="pdf({{ $transaction->transaction->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        @endcan
                        @can('transactions_edit')
                            <button x-data="{}"
                                x-on:click="window.livewire.emitTo('transaction-detail-form','showingModal',{{ $transaction->id }})">
                                <x-svgs.edit />
                            </button>
                        @endcan
                        @can('transactions_delete')
                            <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                wire:click="delete({{ $transaction }})" class="text-red-400">
                                <x-svgs.trash />
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
        @endforelse
        @if ($transaction_details->hasPages())
            <div class="absolute bottom-0 left-0 right-0 p-2 bg-white dark:bg-gray-800">
                {{ $transaction_details->links() }}
            </div>
        @endif
    </div>

    {{-- large screen view --}}
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-sm text-start text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" width="20%" class="px-6 py-3 text-center">
                        {{ __('messages.transaction_number') }}</th>
                    <th scope="col" width="20%" class="px-6 py-3 text-start">{{ __('messages.date') }}</th>
                    <th scope="col" width="20%" class="px-6 py-3 text-start">{{ __('messages.source_store') }}
                    <th scope="col" width="20%" class="px-6 py-3 text-center">{{ __('messages.quantity') }}</th>
                    <th scope="col" width="20%" class="px-6 py-3 text-start">{{ __('messages.item') }}</th>
                    </th>
                    <th scope="col" width="20%" class="px-6 py-3 text-start">
                        {{ __('messages.destination_store') }}</th>
                    <th scope="col" width="10%" class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class=" divide-y">
                @forelse ($transaction_details as $row)
                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 text-center">{{ $row->transaction->id }}</td>
                        <td class="px-6 py-4 text-start">{{ $row->transaction->date->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 text-start" nowrap>
                            @if ($store_id == $row->source_store_id)
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $row->source_store->name }}</span>
                            @else
                                {{ $row->source_store->name }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">{{ $row->quantity }}</td>
                        <td class="px-6 py-4 text-start">
                            @if ($item_id == $row->item_id)
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $row->item->name }}</span>
                            @else
                                {{ $row->item->name }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-start" nowrap>
                            @if ($store_id == $row->destination_store_id)
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $row->destination_store->name }}</span>
                            @else
                                {{ $row->destination_store->name }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-end">
                            <div class="flex gap-3 justify-end">
                                @can('transactions_view')
                                    <button wire:click="pdf({{ $row->transaction->id }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                @endcan
                                @can('transactions_edit')
                                    <button x-data="{}"
                                        x-on:click="window.livewire.emitTo('transaction-detail-form','showingModal',{{ $row->id }})">
                                        <x-svgs.edit />
                                    </button>
                                @endcan
                                @can('transactions_delete')
                                    <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $row }})" class="text-red-400">
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
        @if ($transaction_details->hasPages())
            <div class="absolute bottom-0 left-0 right-0 p-2 bg-white dark:bg-gray-800">
                {{ $transaction_details->links() }}
            </div>
        @endif
    </div>


</div>
