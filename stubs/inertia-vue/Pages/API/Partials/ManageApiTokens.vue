<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";

const props = defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});

const managingPermissionsFor = ref(null);

const apiTokenBeingDeleted = ref(null);

const deleteApiTokenForm = useForm({});

const updateApiTokenForm = useForm({
    permissions: [],
});

const manageApiTokenPermissions = (token) => {
    console.log(token);
    updateApiTokenForm.permissions = token.abilities;
    managingPermissionsFor.value = token;
};

const updateApiToken = () => {
    updateApiTokenForm.put(
        route("api-tokens.update", managingPermissionsFor.value),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (managingPermissionsFor.value = null),
        }
    );
};

const confirmApiTokenDeletion = (token) => {
    apiTokenBeingDeleted.value = token;
};

const deleteApiToken = () => {
    deleteApiTokenForm.delete(
        route("api-tokens.destroy", apiTokenBeingDeleted.value),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => (apiTokenBeingDeleted.value = null),
        }
    );
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Manage API Tokens
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                You may delete any of your existing tokens if they are no longer
                needed.
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <div
                v-for="token in tokens"
                :key="token.id"
                class="flex items-center justify-between"
            >
                <div class="break-all">
                    {{ token.name }}
                </div>

                <div class="flex items-center ms-2">
                    <div
                        v-if="token.last_used_ago"
                        class="text-sm text-gray-400"
                    >
                        Last used {{ token.last_used_ago }}
                    </div>

                    <button
                        v-if="availablePermissions.length > 0"
                        class="text-sm text-gray-400 underline cursor-pointer ms-6"
                        @click="manageApiTokenPermissions(token)"
                    >
                        Permissions
                    </button>

                    <button
                        class="text-sm text-red-500 cursor-pointer ms-6"
                        @click="confirmApiTokenDeletion(token)"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- API Token Permissions Modal -->
        <Modal
            :show="managingPermissionsFor != null"
            @close="managingPermissionsFor = null"
        >
            <div class="p-6">
                <div class="text-lg font-medium text-gray-900">
                    API Token Permissions
                </div>

                <div class="mt-4 text-sm text-gray-600">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div
                            v-for="permission in availablePermissions"
                            :key="permission"
                        >
                            <label class="flex items-center">
                                <Checkbox
                                    v-model:checked="
                                        updateApiTokenForm.permissions
                                    "
                                    :value="permission"
                                />
                                <span class="text-sm text-gray-600 ms-2">{{
                                    permission
                                }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <SecondaryButton @click="managingPermissionsFor = null">
                        Cancel
                    </SecondaryButton>

                    <PrimaryButton
                        class="ms-3"
                        :class="{ 'opacity-25': updateApiTokenForm.processing }"
                        :disabled="updateApiTokenForm.processing"
                        @click="updateApiToken"
                    >
                        Save
                    </PrimaryButton>
                </div>
            </div>
        </Modal>

        <!-- Delete Token Confirmation Modal -->
        <Modal
            :show="apiTokenBeingDeleted != null"
            @close="apiTokenBeingDeleted = null"
        >
            <div class="p-6">
                <div class="text-lg font-medium text-gray-900">
                    Delete API Token
                </div>

                <div class="mt-4 text-sm text-gray-600">
                    Are you sure you would like to delete this API token?
                </div>

                <div class="flex justify-end mt-6">
                    <SecondaryButton @click="apiTokenBeingDeleted = null">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': deleteApiTokenForm.processing }"
                        :disabled="deleteApiTokenForm.processing"
                        @click="deleteApiToken"
                    >
                        Delete
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
