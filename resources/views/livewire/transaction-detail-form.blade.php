<form action="" wire:submit.prevent="save">
    <x-dialog-modal maxWidth='6xl' wire:model="showModal">
        <x-slot name="title">
            {{ $modalTitle }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col lg:grid lg:grid-cols-2 gap-4" x-data="{}"
                x-on:showingModal.window="setTimeout(() => $refs.name.focus(), 250)">
                @foreach ($errors->all() as $error)
                    <div class=" text-red-500">{{ $error }}</div>
                @endforeach
                <div class=" col-span-full lg:block overflow-x-auto">
                    <table class="w-full text-sm text-start text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="p-4">{{ __('messages.source_store') }}</th>
                                <th class="p-4">{{ __('messages.destination_store') }}</th>
                                <th class="p-4">{{ __('messages.item') }}</th>
                                <th class="p-4">{{ __('messages.quantity') }}</th>
                            </tr>
                        </thead>
                        <tbody class=" divide-y">
                            <tr
                                class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class=" align-top" width="25%">
                                    <select required
                                        class="mt-1 w-full h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        wire:model="row.source_store_id">
                                        <option disabled value="">---</option>
                                        @foreach ($source_stores as $store)
                                            <option value="{{ $store['id'] }}">{{ $store['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class=" align-top" width="25%">
                                    <select required
                                        class="mt-1 w-full h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        wire:model="row.destination_store_id">
                                        <option disabled value="">---</option>
                                        @foreach ($destination_stores as $store)
                                            <option value="{{ $store['id'] }}">{{ $store['name'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class=" align-top" width="25%">
                                    <select required
                                        class="mt-1 w-full h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        wire:model="row.item_id">
                                        <option disabled value="">---</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($row['item_id'] && $row['source_store_type'] == 'store')
                                        <div class=" text-green-500 text-xs">{{ $row['available'] }}
                                            {{ __('messages.available') }}</div>
                                    @endif

                                </td>
                                <td class=" align-top" width="15%">
                                    <input required wire:model="row.quantity" type="number"
                                        min="1"
                                        @if ($row['source_store_type'] == 'store') max="{{ $row['available'] }}" @endif
                                        class=" mt-1 w-full h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        placeholder="{{ __('messages.quantity') }}" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                {{ __('messages.cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" type="submit" wire:loading.attr="disabled">
                {{ __('messages.save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</form>
