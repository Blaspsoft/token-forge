<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Create API Token') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("API tokens allow third-party services to authenticate with our
        application on your behalf.") }}
        </p>
    </header>

    <form method="post" action="{{ route('api-tokens.store') }}" class="mt-6 space-y-6">
        @csrf
        @method('post')
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="block w-full mt-1" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div>
            @if (count($availablePermissions) > 0)
                <x-input-label for="permissions" :value="__('Permissions')" />
                <div class="grid grid-cols-1 gap-4 mt-2 md:grid-cols-2">
                    @foreach ($availablePermissions as $index => $availablePermission)
                        <label for="permission" class="">
                            <input id="permissions" type="checkbox" class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="permissions[]" value="{{ $availablePermission }}" @if (in_array($availablePermission, $defaultPermissions)) checked @endif />
                            <span class="text-sm text-gray-600 ms-2 dark:text-gray-400">{{ $availablePermission }}</span>
                        </label>
                    @endforeach
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('permissions')" />
            @endif
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'token-created')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <x-modal name="show-created-api-token" :show="session('status') === 'token-created'" focusable>
        <div class="p-6">
            <div class="text-lg font-medium text-gray-900">API Token</div>
            <div
                x-data="{ copied: false }"
                class="mt-4 text-sm text-gray-600">
                <div>
                    Please copy your new API token. For your security, it won't be shown
                    again.
                </div>
                <div class="flex items-center justify-between px-4 py-2 mt-4 font-mono text-sm text-gray-500 break-all bg-gray-100 rounded">
                    {{ session('token-forge') }}
                    <button
                        x-on:click="navigator.clipboard.writeText('{{ session('token-forge') }}')
                            .then(() => copied = true)
                            .then(setTimeout(() => copied = false, 2000))"
                        type="button"
                        class="flex items-center px-3 py-1 text-xs bg-gray-300 rounded cursor-pointer hover:opacity-75"
                        >
                        Copy
                    </button>
                </div>
                <p
                    x-cloak
                    x-show="copied"
                    x-transition:enter="transition ease-in-out"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-0"
                    x-transition:leave="transition ease-in-out"
                    class="flex justify-end mt-2 mr-2 text-xs text-gray-600 dark:text-gray-400"
                    >
                    Copied to clipboard!
                </p>
            </div>
            <div class="flex justify-end mt-6">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</section>

<style>
    [x-cloak] {
        display: none;
    }
</style>
