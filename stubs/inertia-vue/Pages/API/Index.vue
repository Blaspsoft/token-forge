<script setup>
import { Head } from "@inertiajs/vue3";
import ManageApiTokens from "./Partials/ManageApiTokens.vue";
import CreateApiTokenForm from "./Partials/CreateApiTokenForm.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
  tokens: Array,
  availablePermissions: Array,
  defaultPermissions: Array,
});
</script>

<template>
  <Head title="API Tokens" />

  <AuthenticatedLayout>
    <template #header>
      <h2
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
      >
        API Tokens
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
        <div class="p-4 bg-white shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
          <CreateApiTokenForm
            :available-permissions="availablePermissions"
            :default-permissions="defaultPermissions"
            class="max-w-xl"
          />
        </div>

        <div
          v-if="tokens.length > 0"
          class="p-4 bg-white shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
        >
          <ManageApiTokens
            :tokens="tokens"
            :available-permissions="availablePermissions"
            :default-permissions="defaultPermissions"
            class="max-w-xl"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
