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
import { useToast } from 'primevue/usetoast';
import Confirm from '@/Components/Confirm.vue';

const toast = useToast();
const showForm = ref(false);
const kangaroo = ref<Kangaroo | null>(null);
const { kangaroos, destroy, refetch } = useKangarooApi();
const columns = [
    {
        dataField: 'image_url',
        caption: 'Image',
        cellTemplate: function(container: any, options: any) {
            $("<img class='rounded-full'>")
                .attr("src", options.value || 'https://via.placeholder.com/50')
                .attr("alt", options.data.name)
                .css("width", "50px")
                .appendTo(container);
        }
    },
    'name',
    {
        dataField: 'weight',
        caption: 'Weight (kg)',
    },
    {
        dataField: 'height',
        caption: 'Height (cm)',
    },
    {
        dataField: 'gender',
        cellTemplate: function(container: any, options: any) {
            $("<div class='capitalize'>")
                .text(options.value)
                .appendTo(container);
        }
    },
    {
        dataField: 'friendliness',
        cellTemplate: function(container: any, options: any) {
            $("<div class='capitalize'>")
                .text(options.value)
                .appendTo(container);
        }
    },
    'birthday'
];

const handleEdit = (data: Kangaroo) => {
    showForm.value = true;
    kangaroo.value = data;
};

const showConfirm = ref(false);
const handleDelete = (data: Kangaroo) => {
    kangaroo.value = data;
    showConfirm.value = true;
};
const proceedDelete = async () => {
    await destroy(kangaroo.value?.id as number)
        .then(() => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Kangaroo was deleted successfully', life: 3000 });
            showConfirm.value = false;
            kangaroo.value = null;
            refetch();
        })
        .catch(() => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to delete the kangaroo', life: 3000 });
        });
};
</script>

<template>
    <Head title="Kangaroo" />

    <Modal :show="showForm">
        <Form @close="showForm = false; kangaroo = null" :data="kangaroo" />
    </Modal>


    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kangaroos</h2>
                <PrimaryButton @click="showForm = true">Add a Kangaroo</PrimaryButton>
            </div>
        </template>

        <Confirm
            group="delete-confirm"
            id="delete-confirm"
            key="delete-confirm"
            v-model="showConfirm"
            message="Are you sure you want to delete this kangaroo?"
            @proceed="proceedDelete"
        />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DxTable
                        table-id="kangaroos-table"
                        :data="kangaroos"
                        :columns="columns"
                        @edit="handleEdit"
                        @delete="handleDelete"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
