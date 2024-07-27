<script setup lang="ts">
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Form from '@/Components/Kangaroo/Form.vue';
import { ref } from 'vue';
import DxTable from '@/Components/Kangaroo/DxTable.vue';
import useKangarooApi from '@/utils/kangaroo';
import { Kangaroo } from '@/types';

const showForm = ref(false);
const kangaroo = ref<Kangaroo | null>(null);

const { kangaroos } = useKangarooApi();
const columns = ['name', 'weight', 'height', 'gender', 'friendliness', 'birthday'];

const handleEdit = (data: Kangaroo) => {
    showForm.value = true;
    kangaroo.value = data;
};

const handleDelete = (data: Kangaroo) => {
    console.log(data);
};
</script>

<template>
    <Head title="Kangaroo" />

    <Modal :show="showForm">
        <Form @close="showForm = false" :data="kangaroo" />
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
                    <DxTable table-id="kangaroos-table" :data="kangaroos" :columns="columns" @edit="handleEdit" @delete="handleDelete" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
