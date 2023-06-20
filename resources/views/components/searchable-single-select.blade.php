@props(['list_items', 'title', 'model'])

<div class="w-full">
    <!-- Start Component -->
    <div x-data="select({
        items: {{ @json_encode($list_items) }},
        size: 6,
    })" x-init="onInit" class="relative" @click.away="expanded = false">
        <!-- Start Item Tags And Input Field -->
        <div
            class="flex items-center justify-between px-1 border rounded-md relative pr-8 bg-white dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700">
            <div @click="expanded = !expanded" class="flex flex-col justify-center items-center h-10 w-full">

                <button type="button" x-text="title"
                    class=" text-start cursor-pointer py-1 px-1 outline-none focus:outline-none focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 ring-inset transition-all rounded-md w-full">
                </button>

                <!-- Arrow Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" stroke-width="0" fill="#ccc"
                    :class="expanded && 'rotate-180'"
                    class="absolute right-2 top-1/2 -translate-y-1/2 cursor-pointer focus:outline-none" tabindex="-1">
                    <path d="M12 17.414 3.293 8.707l1.414-1.414L12 14.586l7.293-7.293 1.414 1.414L12 17.414z" />
                </svg>
            </div>
        </div>
        <!-- End Item Tags And Input Field -->

        <!-- Start Items List -->
        <template x-if="expanded">
            <div x-ref="listBox"
                class=" absolute z-50 flex flex-col items-center overflow-x-hidden w-full mt-1 p-1 list-none border-2 border-t-0 dark:border-gray-700 rounded-md overflow-y-auto outline-none focus:outline-none bg-white dark:bg-gray-900 dark:text-gray-300  left-0 bottom-100"
                tabindex="0" {{-- :style="listBoxStyle" --}}>
                <!-- Search Input -->
                <input x-ref="searchInput" x-model="search" @click="expanded = true" @focusin="expanded = true"
                    @input="expanded = true" @keyup.arrow-down="expanded = true; selectNextItem()"
                    @keyup.arrow-up="expanded = true; selectPrevItem()" @keyup.escape="reset"
                    @keyup.enter="addActiveItem" placeholder="{{ __('messages.search') }}" type="text"
                    class=" w-full py-2 px-2 mx-1 outline-none focus:outline-none focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 ring-inset transition-all rounded-md" />
                <!-- Item Element -->
                <ul class=" flex-1 self-start w-full max-h-80 overflow-y-auto">
                    <template x-if="filteredItems.length">
                        <template x-for="(filteredItem, idx) in filteredItems">
                            <li x-text="shortenedName(filteredItem.name, maxItemChars)"
                                @click="handleItemClick(filteredItem)" :class="idx === activeIndex && 'bg-amber-200'"
                                :title="filteredItem.name"
                                class="hover:bg-indigo-200 dark:hover:bg-gray-600 cursor-pointer px-2 py-2"></li>
                        </template>
                    </template>
                </ul>
            </div>
        </template>
        <!-- End Items List -->
    </div>
    <!-- End Component -->
</div>

@push('scripts')
<script>
    function select(config) {
        return {
            items: config.items ?? [],
            allItems: null,
            selectedItems: null,
            title: config.title ?? '{{ $title }}',
            search: config.search ?? "",
            searchPlaceholder: config.searchPlaceholder ?? '{{ $title }}',
            expanded: config.expanded ?? false,
            allowDuplicates: config.allowDuplicates ?? true,
            size: config.size ?? 4,
            itemHeight: config.itemHeight ?? 40,
            maxItemChars: config.maxItemChars ?? 50,
            maxTagChars: config.maxTagChars ?? 25,
            activeIndex: -1,
            onInit() {
                // Set the allItems array since we want to filter later on and keep the original (items) array as reference
                this.allItems = [...this.items];
                this.$watch('expanded', (newValue, oldValue) => {
                    if (newValue) {
                        this.$refs.searchInput.focus();
                    }
                })

                // Scroll to active element whenever activeIndex changes (if expanded is true and we have a value)
                this.$watch("activeIndex", (newValue, oldValue) => {
                    if (
                        this.activeIndex == -1 ||
                        !this.filteredItems[this.activeIndex] ||
                        !this.expanded
                    )
                        return;
                    this.scrollToActiveElement();
                });

                // Check whether there are selected values or not and set them
                this.selectedItems = this.items ?
                    this.items.filter((item) => item.selected) : [];
            },

            reset() {
                this.search = "";
                this.expanded = false;
                this.activeIndex = -1;
            },

            handleItemClick(item) {
                this.title = item.name;
                @this.set('{{ $model }}', item.id);
                this.reset();
            },

            selectNextItem() {
                if (!this.filteredItems.length) return;
                // Array count starts at 0, so we abstract 1
                if (this.filteredItems.length - 1 == this.activeIndex) {
                    return (this.activeIndex = 0);
                }
                this.activeIndex++;
            },

            selectPrevItem() {
                if (!this.filteredItems.length) return;
                if (this.activeIndex == 0 || this.activeIndex == -1)
                    return (this.activeIndex = this.filteredItems.length - 1);
                this.activeIndex--;
            },

            addActiveItem() {
                if (!this.filteredItems[this.activeIndex]) return;
                item = this.filteredItems[this.activeIndex];
                this.title = item.name;
                @this.set('{{ $model }}', item.id);
                this.reset();
            },

            scrollToActiveElement() {
                // Remove the first two child elements since they are <template> tags
                const availableListElements = [...this.$refs.listBox.children].slice(
                    2,
                    -1
                );
                // Scroll to active <li> element
                availableListElements[this.activeIndex].scrollIntoView({
                    block: "end",
                });
            },

            shortenedName(name, maxChars) {
                return !maxChars || name.length <= maxChars ?
                    name :
                    `${name.substr(0, maxChars)}...`;
            },

            get filteredItems() {
                return this.allItems.filter((item) =>
                    item.name.toLowerCase().includes(this.search?.toLowerCase())
                );
            },

            get listBoxStyle() {
                // We add 2 since there is border that takes space
                return {
                    maxHeight: `${this.size * this.itemHeight + 2}px`,
                };
            },
        };
    }
</script>
    
@endpush

