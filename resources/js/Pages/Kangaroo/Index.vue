<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Kangaroo } from '@/types';
import Modal from '@/Components/Shared/Modal.vue';
import PrimaryButton from '@/Components/Shared/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Form from '@/Components/Kangaroo/Form.vue';
import KangarooTable from '@/Components/Kangaroo/Table.vue';

const showForm = ref(false);
const kangaroo = ref<Kangaroo | null>(null);
const refreshToggle = ref(false);
const handleEdit = (data: Kangaroo) => {
    showForm.value = true;
    kangaroo.value = data;
};
const handleClose = () => {
    showForm.value = false;
    kangaroo.value = null;
};
const handleSaved = () => {
    handleClose();
    refreshToggle.value = !refreshToggle.value;
};
</script>

<template>
    <Head title="Kangaroo" />

    <Modal :show="showForm">
        <Form @close="handleClose" @saved="handleSaved" :data="kangaroo" />
    </Modal>


    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kangaroos</h2>
                <PrimaryButton @click="showForm = true">Add a Kangaroo</PrimaryButton>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <KangarooTable v-model:refresh-toggle="refreshToggle" @edit="handleEdit" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
