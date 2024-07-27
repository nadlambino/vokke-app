<script lang="ts" setup>
import { ref } from 'vue';
import { Kangaroo } from '@/types';
import { useToast } from 'primevue/usetoast';
import DxTable from '@/Components/DxTable.vue';
import useKangarooApi from '@/utils/kangaroo';
import Confirm from '@/Components/Confirm.vue';

const emits = defineEmits(['edit']);
const toast = useToast();
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
            $("<div class='capitalize px-4'>")
                .text(options.value)
                .appendTo(container);
        }
    },
    {
        dataField: 'friendliness',
        cellTemplate: function(container: any, options: any) {
            $("<div class='capitalize px-4'>")
                .text(options.value)
                .appendTo(container);
        }
    },
    'birthday'
];

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
    <Confirm
        group="delete-confirm"
        id="delete-confirm"
        key="delete-confirm"
        v-model="showConfirm"
        message="Are you sure you want to delete this kangaroo?"
        @proceed="proceedDelete"
    />

    <DxTable
        table-id="kangaroos-table"
        :data="kangaroos"
        :columns="columns"
        @edit="(data) => $emit('edit', data)"
        @delete="handleDelete"
    />
</template>
