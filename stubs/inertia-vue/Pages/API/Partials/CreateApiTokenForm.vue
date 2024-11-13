<script setup>
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
  availablePermissions: Array,
  defaultPermissions: Array,
});

const page = usePage();

const displayingToken = ref(false);

const recentlyCopied = ref(false);

const form = useForm({
  name: "",
  permissions: props.defaultPermissions,
});

const createApiToken = () => {
  form.post(route("api-tokens.store"), {
    preserveScroll: true,
    onSuccess: () => {
      displayingToken.value = true;
      form.reset();
    },
  });
};

const copyToken = () => {
  recentlyCopied.value = true;

  navigator.clipboard.writeText(page.props.flash.breezeToken.token);

  setTimeout(() => {
    recentlyCopied.value = false;
  }, 2000);
};
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Create API Token
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        API tokens allow third-party services to authenticate with our
        application on your behalf.
      </p>
    </header>

    <form @submit.prevent="createApiToken" class="mt-6 space-y-6">
      <div>
        <InputLabel for="name" value="Name" />

        <TextInput
          id="name"
          type="text"
          class="block w-full mt-1"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
        />

        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div>
        <!-- Token Permissions -->
        <div v-if="availablePermissions.length > 0" class="col-span-6">
          <InputLabel for="permissions" value="Permissions" />

          <div class="grid grid-cols-1 gap-4 mt-2 md:grid-cols-2">
            <div v-for="permission in availablePermissions" :key="permission">
              <label class="flex items-center">
                <Checkbox
                  v-model:checked="form.permissions"
                  :value="permission"
                />
                <span class="text-sm text-gray-600 ms-2">{{ permission }}</span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Create
        </PrimaryButton>
      </div>
    </form>
    <Modal :show="displayingToken" @close="displayingToken = false">
      <div class="p-6">
        <div class="text-lg font-medium text-gray-900">API Token</div>

        <div class="mt-4 text-sm text-gray-600">
          <div>
            Please copy your new API token. For your security, it won't be shown
            again.
          </div>

          <div
            v-if="$page.props.flash.tokenForge.token"
            class="flex items-center justify-between px-4 py-2 mt-4 font-mono text-sm text-gray-500 break-all bg-gray-100 rounded"
          >
            {{ $page.props.flash.tokenForge.token }}
            <button
              @click="copyToken"
              type="button"
              class="flex items-center px-3 py-1 text-xs bg-gray-300 rounded cursor-pointer hover:opacity-75"
            >
              Copy
            </button>
          </div>
          <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
          >
            <p
              v-if="recentlyCopied"
              class="flex justify-end mt-2 mr-2 text-xs text-gray-600 dark:text-gray-400"
            >
              Copied to clipboard!
            </p>
          </Transition>
        </div>

        <div class="flex justify-end mt-6">
          <SecondaryButton @click="displayingToken = false">
            Close
          </SecondaryButton>
        </div>
      </div>
    </Modal>
  </section>
</template>
