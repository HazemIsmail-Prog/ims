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
                        placeholder="{{ __('messages.name') }}" x-ref="name" wire:model.defer="permission.name" />
                    <x-input-error for="permission.name" class="mt-2" />
                </div>
                <div>
                    <x-label for="description">{{ __('messages.description') }}</x-label>
                    <x-input id="description" type="text" class="mt-1 w-full" autocomplete="description"
                        placeholder="{{ __('messages.description') }}" x-ref="description" wire:model.defer="permission.description" />
                    <x-input-error for="permission.description" class="mt-2" />
                </div>
                <div>
                    <x-label for="section_name">{{ __('messages.section') }}</x-label>
                    <x-input id="section_name" type="text" class="mt-1 w-full" autocomplete="section_name"
                        placeholder="{{ __('messages.section') }}" x-ref="section_name" wire:model.defer="permission.section_name" />
                    <x-input-error for="permission.section_name" class="mt-2" />
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
