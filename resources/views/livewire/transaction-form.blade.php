<form action="" wire:submit.prevent="save">
    <x-dialog-modal maxWidth='6xl' wire:model="showModal">
        <x-slot name="title">
            {{ $modalTitle }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col lg:grid lg:grid-cols-2 gap-4" x-data="{}"
                x-on:showingModal.window="setTimeout(() => $refs.name.focus(), 250)">

                <div>
                    <x-label for="date">{{ __('messages.date') }}</x-label>
                    <x-input id="date" type="date" class="mt-1 w-full" autocomplete="date"
                        placeholder="{{ __('messages.date') }}" x-ref="date" wire:model.defer="date" />
                    <x-input-error for="date" class="mt-2" />
                </div>
                <div>
                    <x-label for="notes">{{ __('messages.notes') }}</x-label>
                    <x-input id="notes" type="text" class="mt-1 w-full" autocomplete="notes"
                        placeholder="{{ __('messages.notes') }}" x-ref="notes" wire:model.defer="notes" />
                    <x-input-error for="notes" class="mt-2" />
                </div>
                @foreach ($errors->all() as $error)
                    <div class=" text-red-500">{{ $error }}</div>
                @endforeach
                <div class=" col-span-full lg:block overflow-x-auto">
                    <table class="w-full text-sm text-start text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th></th>
                                <th class="p-4">{{ __('messages.source_store') }}</th>
                                <th class="p-4">{{ __('messages.destination_store') }}</th>
                                <th class="p-4">{{ __('messages.item') }}</th>
                                <th class="p-4">{{ __('messages.quantity') }}</th>
                                <th class="p-4"></th>
                            </tr>
                        </thead>
                        <tbody class=" divide-y">
                            @foreach ($rows as $index => $row)
                                <tr
                                    class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class=" align-middle text-center">
                                        @error('rows.' . $index . '.duplicated')
                                            <span class=" text-xs text-red-500">Dup</span>
                                        @enderror
                                    </td>
                                    <td class=" align-top w-60">
                                        <select required style="min-width: 15rem;"
                                            class="mt-1 w-full h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            wire:model="rows.{{ $index }}.source_store_id">
                                            <option disabled value="">---</option>
                                            @foreach ($row['source_stores'] as $store)
                                                <option value="{{ $store['id'] }}">{{ $store['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class=" align-top w-60">
                                        <select required style="min-width: 15rem;"
                                            class="mt-1 w-full h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            wire:model="rows.{{ $index }}.destination_store_id">
                                            <option disabled value="">---</option>
                                            @foreach ($row['destination_stores'] as $store)
                                                <option value="{{ $store['id'] }}">{{ $store['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class=" align-top w-60">
                                        <select required style="min-width: 15rem;"
                                            class="mt-1 w-full h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            wire:model="rows.{{ $index }}.item_id">
                                            <option disabled value="">---</option>
                                            @foreach ($row['items'] as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($rows[$index]['item_id'] && $rows[$index]['source_store_type'] == 'store')
                                            <div class=" text-green-500 text-xs">{{ $rows[$index]['available'] }}
                                                {{ __('messages.available') }}</div>
                                        @endif

                                    </td>
                                    <td class=" align-top w-32">
                                        <input style="min-width: 8rem;" required
                                            wire:model="rows.{{ $index }}.quantity" type="number"
                                            min="1"
                                            @if ($rows[$index]['source_store_type'] == 'store') max="{{ $rows[$index]['available'] }}" @endif
                                            class=" mt-1 w-full h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            placeholder="{{ __('messages.quantity') }}" />
                                    </td>
                                    <td class=" text-center align-middle" nowrap width="10%">
                                        <button type="button" class=" text-green-500 p-1"
                                            wire:click="duplicate_row({{ $index }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                                            </svg>
                                        </button>
                                        <button type="button" class=" text-red-500 p-1"
                                            wire:click="unset_row({{ $index }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class=" text-center p-2">
                                    <button class=" text-green-500" type="button"
                                        wire:click="add_row">{{ __('messages.add_row') }}</button>
                                </td>
                            </tr>
                        </tfoot>
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
