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
                        placeholder="{{ __('messages.name') }}" x-ref="name" wire:model.defer="user.name" />
                    <x-input-error for="user.name" class="mt-2" />
                </div>
                <div>
                    <x-label for="username">{{ __('messages.username') }}</x-label>
                    <x-input id="username" type="text" class="mt-1 w-full" autocomplete="username"
                        placeholder="{{ __('messages.username') }}" x-ref="username" wire:model.defer="user.username" />
                    <x-input-error for="user.username" class="mt-2" />
                </div>
                <div>
                    <x-label for="password">{{ __('messages.password') }}</x-label>
                    <x-input id="password" type="password" class="mt-1 w-full" autocomplete="password"
                        placeholder="{{ __('messages.password') }}" x-ref="password" wire:model.defer="user.password" />
                    <x-input-error for="user.password" class="mt-2" />
                </div>
                <div class=" flex items-end gap-2">
                    <x-checkbox class=" inline-block" id="active" x-ref="active" wire:model.defer="user.active" />
                    <x-label for="active">{{ __('messages.active') }}</x-label>
                </div>
                <div wire:ignore id="rolesList" class=" border col-span-2 rounded p-2 dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">{{ __('messages.roles') }}</h3>
                    @foreach ($roles as $role)
                        <div class="flex items-center">
                            <input id="role{{ $role->id }}" type="checkbox" value="{{ $role->id }}"
                                wire:model="selected_roles"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="role{{ $role->id }}"
                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $role->name }}</label>
                        </div>
                    @endforeach
                </div>
                <x-input-error for="selected_roles" class="mt-2" />
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
