<form action="" wire:submit.prevent="save">
    <x-dialog-modal wire:model="showModal">
        <x-slot name="title">
            {{ $modalTitle }}
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col lg:grid lg:grid-cols-2 gap-4" x-data="{}"
                x-on:showingModal.window="setTimeout(() => $refs.name.focus(), 250)">

                <div>
                    <x-label for="name">{{ __('messages.name') }}</x-label>
                    <x-input id="name" type="text" class="mt-1 w-full" autocomplete="name"
                        placeholder="{{ __('messages.name') }}" x-ref="name" wire:model.defer="store.name" />
                    <x-input-error for="store.name" class="mt-2" />
                </div>
                <div>
                    <x-label for="type">{{ __('messages.type') }}</x-label>
                    <select id="type"
                    class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    wire:model="store.type">
                    <option disabled value="">---</option>
                    <option value="store">{{ __('messages.store') }}</option>
                    <option value="supplier">{{ __('messages.supplier') }}</option>
                </select>
                <x-input-error for="store.type" class="mt-2" />
                </div>
                <div class=" flex items-end gap-2">
                    <x-checkbox class=" inline-block" id="active" x-ref="active" wire:model.defer="store.active" />
                    <x-label for="active">{{ __('messages.active') }}</x-label>
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
