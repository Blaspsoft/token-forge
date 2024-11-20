<section
    x-data="{
        confirmingApiTokenDeletion: null,
        tokenToDelete: null,
        permissions: [],
        availablePermissions: [],
        manageApiTokenPermissions(availablePermissions, token) {
            this.tokenToDelete = token;
            this.availablePermissions = availablePermissions.map(permission => ({
                name: permission,
                selected: token.abilities.includes(permission)
            }));
            $dispatch('open-modal', 'manage-api-token-permissions');
        },
        confirmApiTokenDeletion(token) {
            this.tokenToDelete = token;
            this.confirmingApiTokenDeletion = true;
            $dispatch('open-modal', 'confirm-api-token-deletion');
        },
    }"
>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Manage API Tokens') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("You may delete any of your existing tokens if they are no longer needed.") }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        @foreach ($tokens as $token)
            <div class="flex items-center justify-between">
                <div class="break-all">
                    {{ $token->name }}
                </div>
                <div class="flex items-center ms-2">
                    @if ($token->last_used_ago)
                        <div class="text-sm text-gray-400">
                            Last used {{ $token->last_used_ago }}
                        </div>
                    @endif
                    <button
                        class="text-sm text-gray-400 underline cursor-pointer ms-6"
                        x-on:click="manageApiTokenPermissions({{ json_encode($availablePermissions) }}, {{ json_encode($token) }})"
                    >
                        Permissions
                    </button>
                    <button
                        class="text-sm text-red-500 cursor-pointer ms-6"
                        x-on:click="confirmApiTokenDeletion({{ json_encode($token) }})"
                    >
                        Delete
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <x-modal name="manage-api-token-permissions">
        <div class="p-6">
            <div class="text-lg font-medium text-gray-900">
                API Token Permissions
            </div>

            <form
                method="post"
                :action="`{{ url('api-tokens') }}/${tokenToDelete?.id}`"
                >
                @csrf
                @method('put')
                <div class="mt-4 text-sm text-gray-600">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <template x-for="permission in availablePermissions" :key="permission.name">
                            <div>
                                <label class="flex items-center">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                        :value="permission.name"
                                        x-model="permission.selected"
                                    />
                                    <span class="text-sm text-gray-600 ms-2 dark:text-gray-400" x-text="permission.name"></span>
                                </label>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Cancel
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                            Save
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal name="confirm-api-token-deletion">
        <div class="p-6">
            <div class="text-lg font-medium text-gray-900">
                Delete API Token
            </div>
            <div class="mt-4 text-sm text-gray-600">
                Are you sure you want to delete the API token?
            </div>
            <div class="flex justify-end mt-6">
                <x-secondary-button
                    x-on:click="$dispatch('close')"
                >
                    Cancel
                </x-secondary-button>
                <form
                    method="post"
                    :action="`{{ url('api-tokens') }}/${tokenToDelete?.id}`"
                >
                    @csrf
                    @method('delete')
                    <x-danger-button class="ms-3">
                        Delete
                    </x-danger-button>
                </form>
            </div>
        </div>
    </x-modal>
</section>
