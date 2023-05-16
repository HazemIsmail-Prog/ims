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
                        placeholder="{{ __('messages.name') }}" x-ref="name" wire:model.defer="item.name" />
                    <x-input-error for="item.name" class="mt-2" />
                </div>
                <div>
                    <x-label for="unit">{{ __('messages.unit') }}</x-label>
                    <x-input id="unit" type="text" class="mt-1 w-full" autocomplete="unit"
                        placeholder="{{ __('messages.unit') }}" x-ref="unit" wire:model.defer="item.unit" />
                    <x-input-error for="item.unit" class="mt-2" />
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
