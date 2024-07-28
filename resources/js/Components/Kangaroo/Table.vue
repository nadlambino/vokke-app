<script lang="ts" setup>
import { ref } from 'vue';
import { Kangaroo } from '@/types';
import { useToast } from 'primevue/usetoast';
import DxTable from '@/Components/Shared/DxTable.vue';
import useKangarooApi from '@/utils/kangaroo';
import Confirm from '@/Components/Shared/Confirm.vue';
import TextInput from '../Shared/TextInput.vue';
import useDxDataSource from '@/utils/dx-datasource';

const emits = defineEmits(['edit']);
const toast = useToast();
const kangaroo = ref<Kangaroo | null>(null);
const { destroy, search } = useKangarooApi();
const dataSource = useDxDataSource({
    api: route('api.kangaroos.index') as string,
    dataId: 'id'
});
const filterOptions = {
    filterOperations: ["contains"],
}
const columns = [
    {
        dataField: 'image_url',
        caption: '',
        width: 100,
        allowFiltering: false,
        cellTemplate: function(container: any, options: any) {
            $("<img class='rounded-full'>")
                .attr("src", options.value || 'https://via.placeholder.com/50')
                .attr("alt", options.data.name)
                .css("width", "50px")
                .appendTo(container);
        }
    },
    {
        dataField: 'name',
        ...filterOptions
    },
    {
        dataField: 'weight',
        caption: 'Weight (kg)',
        ...filterOptions
    },
    {
        dataField: 'height',
        caption: 'Height (cm)',
        ...filterOptions
    },
    {
        dataField: 'gender',
        allowFiltering: false,
        cellTemplate: function(container: any, options: any) {
            $("<div class='capitalize px-4 md:px-0'>")
                .text(options.value)
                .appendTo(container);
        }
    },
    {
        dataField: 'friendliness',
        allowFiltering: false,
        cellTemplate: function(container: any, options: any) {
            $("<div class='capitalize px-4 md:px-0'>")
                .text(options.value)
                .appendTo(container);
        }
    },
    {
        dataField: 'birthday',
        allowFiltering: false,
    }
];

const showConfirm = ref(false);
const handleDelete = (data: Kangaroo) => {
    kangaroo.value = data;
    showConfirm.value = true;
};
const refreshToggle = defineModel('refreshToggle', { type: Boolean });
const proceedDelete = async () => {
    await destroy(kangaroo.value?.id as number)
        .then(() => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Kangaroo was deleted successfully', life: 3000 });
            showConfirm.value = false;
            kangaroo.value = null;
            refreshToggle.value = !refreshToggle.value;
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

    <div class="flex justify-end items-center p-4">
        <TextInput
            v-model="search"
            placeholder="Search kangaroo..."
            class="w-96"
        />
    </div>

    <KeepAlive>
        <DxTable
            table-id="kangaroos-table"
            :data-source="dataSource"
            :per-page="5"
            :columns="columns"
            :refresh-toggle="refreshToggle"
            @edit="(data: Kangaroo) => emits('edit', data)"
            @delete="handleDelete"
        />
    </KeepAlive>
</template>
