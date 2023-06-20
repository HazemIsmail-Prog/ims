<x-slot name="header">
    <div class="flex items-center justify-between">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.stores_report') }}
        </h2>
    </div>
</x-slot>

<div class="p-6 lg:lg-8">

    <div class=" grid grid-cols-12 gap-3">

        <div class=" col-span-9 print:col-span-12">
            <h2 class=" mb-5 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ @$selected_store->name }}
            </h2>
            <div class=" overflow-x-auto">
                <table class="w-full text-sm text-start text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-start">{{ __('messages.item') }}</th>
                            <th scope="col" class="px-6 py-3 text-center">{{ __('messages.available') }}</th>
                        </tr>
                    </thead>
                    <tbody class=" divide-y">
                        @forelse ($data as $row)
                            <tr
                                class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 text-start">{{ $row->name }}</td>
                                <td class="px-6 py-4 text-center">{{ $selected_store->availablePerItem($row->id) }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class=" print:hidden col-span-3 flex flex-col gap-4">
            <div>
                {{-- <x-label for="select_store">{{ __('messages.select_store') }}</x-label> --}}
                <x-searchable-single-select :list_items="$stores" :title="__('messages.select_store')" :model="'selected_store_id'"/>
            </div>
            <div>
                {{-- <x-label for="select_items">{{ __('messages.select_items') }}</x-label> --}}
                <x-searchable-multi-select :list_items="$items" :title="__('messages.select_items')" :model="'selected_items_ids'"/>
            </div>
        </div>
    </div>
</div>