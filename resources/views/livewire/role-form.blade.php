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
                        placeholder="{{ __('messages.name') }}" x-ref="name" wire:model.defer="role.name" />
                    <x-input-error for="role.name" class="mt-2" />
                </div>

                <div wire:ignore>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        {{ __('messages.permissions') }}</h3>
                    <div>
                        <table class=" min-w-full text-start text-sm font-light">
                            <tbody>
                                @foreach ($permissions as $section => $section_permissions)
                                    <tr class="border-b transition duration-300 ease-in-out dark:border-gray-600">
                                        <td class=" align-middle">{{ Str::ucfirst($section) }}</td>
                                        <td class=" align-middle">
                                            @foreach ($section_permissions as $permission)
                                                <div class="flex items-center">
                                                    <input id="{{ $permission->id }}" type="checkbox"
                                                        value="{{ $permission->id }}" wire:model="selected_permissions"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                    <label for="{{ $permission->id }}"
                                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $permission->description }}</label>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <x-input-error for="selected_permissions" class="mt-2" />


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
