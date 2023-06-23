<div x-data="{ expanded: false }">
    <div @click.away="expanded = false" class=" relative">
        <div @click="expanded = !expanded; $nextTick(() => { $refs.search.focus(); });"
            class="cursor-pointer flex justify-between items-center p-2 mt-1 w-full h-10 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            <button type="button">
                {{ $selected_name ?? '---' }}
            </button>
            <!-- Arrow Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                :class="expanded && 'rotate-180 duration-150'" class="w-4 h-4 dark:text-gray-500" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </div>
        <div x-show="expanded"
            class=" duration-150 z-50 absolute flex flex-col p-2 mt-1 w-full max-h-60 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            <input x-ref="search" type="text" wire:model="search"
                class="w-full py-1 px-2 outline-none focus:outline-none border border-gray-300 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 ring-inset transition-all rounded-md">
            <ul class="w-full flex-1 overflow-y-auto mt-2">
                @foreach ($filtered_list as $row)
                    <li @click="expanded = false" class="cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $row['id'] == $selected_id ? 'bg-gray-50 dark:bg-gray-600' : '' }}"
                        wire:click="select({{ $row['id'] }})">{{ $row['name'] }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>


