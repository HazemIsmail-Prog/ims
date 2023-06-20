@props(['list_items', 'title', 'model'])

<div class="w-full">
    <!-- Start Component -->
    <div x-data="multiselect({
        items: {{ @json_encode($list_items) }},
        size: 6,
    })" x-init="onInit" @focusout="handleBlur" class="relative">
        <!-- Start Item Tags And Input Field -->
        <div
            class="flex items-center justify-between px-1 border rounded-md relative pr-8 bg-white dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700">
            <ul class="flex flex-col items-center w-full">
                <!-- Search Input -->
                <input x-ref="searchInput" x-model="search" @click="expanded = true" @focusin="expanded = true"
                    @input="expanded = true" @keyup.arrow-down="expanded = true; selectNextItem()"
                    @keyup.arrow-up="expanded = true; selectPrevItem()" @keyup.escape="reset"
                    @keyup.enter="addActiveItem" :placeholder="searchPlaceholder" type="text"
                    class="flex-grow py-2 px-2 mx-1 my-1.5 border-none outline-none focus:outline-none dark:placeholder:text-gray-300 placeholder:text-black focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 ring-inset transition-all rounded-md w-full" />
                <!-- Tags (Selected) -->
                <div class=" w-full overflow-y-hidden overflow-x-auto">
                    <div class="flex">
                        <template x-for="(selectedItem, idx) in selectedItems">
                            <button type="button" x-text="shortenedName(selectedItem.name, maxTagChars)" @click="removeElementByIdx(idx)"
                                @keyup.backspace="removeElementByIdx(idx)" @keyup.delete="removeElementByIdx(idx)"
                                tabindex="0"
                                class=" text-xs min-h-min min-w-max m-1 px-2 py-1.5 border-indigo-500 dark:text-indigo-800 bg-indigo-200 border rounded-md cursor-pointer after:content-['x'] after:ml-1.5 after:text-indigo-500 outline-none focus:outline-none ring-0 focus:ring-2 focus:ring-indigo-500 ring-inset transition-all">
                            </button>
                        </template>
                    </div>
                </div>


                <!-- Arrow Icon -->
                <svg @click="expanded = !expanded; expanded && $refs.searchInput.focus()"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" stroke-width="0" fill="#ccc"
                    :class="expanded && 'rotate-180'"
                    class="absolute right-2 top-1/2 -translate-y-1/2 cursor-pointer focus:outline-none" tabindex="-1">
                    <path d="M12 17.414 3.293 8.707l1.414-1.414L12 14.586l7.293-7.293 1.414 1.414L12 17.414z" />
                </svg>
            </ul>
        </div>
        <!-- End Item Tags And Input Field -->

        <!-- Start Items List -->
        <template x-if="expanded">
            <ul x-ref="listBox"
                class="  absolute z-50 w-full mt-1 list-none border-2 border-t-0  dark:border-gray-700 rounded-md overflow-y-auto outline-none focus:outline-none bg-white dark:bg-gray-900 dark:text-gray-300  left-0 bottom-100"
                tabindex="0" :style="listBoxStyle">
                <!-- Item Element -->
                <template x-if="filteredItems.length">
                    <template x-for="(filteredItem, idx) in filteredItems">
                        <li x-text="shortenedName(filteredItem.name, maxItemChars)"
                            @click="handleItemClick(filteredItem)" :class="idx === activeIndex && 'bg-amber-200'"
                            :title="filteredItem.name"
                            style="user-select: none;"
                            class="hover:bg-indigo-200 dark:hover:bg-gray-600 cursor-pointer px-2 py-2"></li>
                    </template>
                </template>

                <!-- Empty Text -->
                <template x-if="!filteredItems.length">
                    <li x-text="emptyText" class="cursor-pointer px-2 py-2 text-gray-400"></li>
                </template>
            </ul>
        </template>
        <!-- End Items List -->
    </div>
    <!-- End Component -->
</div>

<script>
    function multiselect(config) {
        return {
            items: config.items ?? [],
            allItems: null,
            selectedItems: null,
            search: config.search ?? "",
            searchPlaceholder: config.searchPlaceholder ?? "{{ $title }}",
            expanded: config.expanded ?? false,
            emptyText: config.emptyText ?? "No items found...",
            allowDuplicates: config.allowDuplicates ?? false,
            size: config.size ?? 4,
            itemHeight: config.itemHeight ?? 40,
            maxItemChars: config.maxItemChars ?? 50,
            maxTagChars: config.maxTagChars ?? 25,
            activeIndex: -1,

            onInit() {
                // Set the allItems array since we want to filter later on and keep the original (items) array as reference
                this.allItems = [...this.items];

                this.$watch("filteredItems", (newValues, oldValues) => {
                    // Reset the activeIndex whenever the filteredItems array changes
                    if (newValues.length !== oldValues.length) this.activeIndex = -1;
                });

                this.$watch("selectedItems", (newValues, oldValues) => {
                    if (this.allowDuplicates) return;

                    // Remove already selected items from the items (allItems) array (if allowDuplicates is false)
                    this.allItems = this.items.filter((item, idx, all) =>
                        newValues.every((n) => n.id !== item.id)
                    );
                });

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

            handleBlur(e) {
                // If the current active element (relatedTarget) is a child element of the component itself, return
                // Note: The current active element must have a tabindex attribute set in order to appear as a relatedTarget
                if (this.$el.contains(e.relatedTarget)) {
                    return;
                }
                this.reset();
            },

            reset() {
                this.search = "";
                this.expanded = false;
                this.activeIndex = -1;
            },

            handleItemClick(item) {
                // 1) Add the item
                this.selectedItems.push(item);
                data = this.selectedItems.map(a => a['id'])
                @this.set('{{ $model }}', data);
                this.search = "";
                this.$refs.searchInput.focus();
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
                this.selectedItems.push(this.filteredItems[this.activeIndex]);
                data = this.selectedItems.map(a => a['id'])
                @this.set('{{ $model }}', data);
                this.search = "";
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

            removeElementByIdx(itemIdx) {
                this.selectedItems.splice(itemIdx, 1);
                // Focus the input element to keep the blur functionlity
                // otherwise @focusout on the root element will not be triggered
                if (!this.selectedItems.length) this.$refs.searchInput.focus();
                data = this.selectedItems.map(a => a['id'])
                @this.set('{{ $model }}', data);
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
