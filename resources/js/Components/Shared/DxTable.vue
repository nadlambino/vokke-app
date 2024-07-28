<script setup lang="ts">
import { useWindowSize } from '@vueuse/core';
import { computed, nextTick, onMounted, watch } from 'vue';
import useDxTable from '@/utils/dx-table';

const emits = defineEmits(['edit', 'delete']);
const props = withDefaults(defineProps<{
        tableId: string,
        dataSource: Array<any>|Function,
        columns: Array<any>,
        perPage?: number,
        pageSizes?: number[],
        refreshToggle?: boolean,
    }>(),
    {
        perPage: 5,
        pageSizes: () => [5, 10, 20] as number[],
        refreshToggle: false,
    }
);

const columnsWithActions = computed(() => {
    return [
        ...props.columns,
        {
            cellTemplate: function(container: any, options: any) {
                $("<div class='flex gap-2 h-10'>")
                    .append(
                        $("<button class='flex justify-center items-center rounded-full text-white bg-gray-800 hover:bg-gray-800/90 w-10 h-10 aspect-square'>")
                            .append("<i class='pi pi-pencil' />")
                            .attr("title", "Edit")
                            .on("click", () => emits('edit', options.data))
                    )
                    .append(
                        $("<button class='flex justify-center items-center rounded-full !text-gray-800 !bg-gray-200 hover:!bg-gray-100 w-10 h-10 aspect-square'>")
                            .append("<i class='pi pi-trash' />")
                            .attr("title", "Delete")
                            .on("click", () => emits('delete', options.data))
                    )
                    .appendTo(container);
            }
        }
    ];
});
const { initialize, initialized } = useDxTable({
    dataSource: props.dataSource,
    tableId: props.tableId,
    columns: columnsWithActions.value,
    perPage: props.perPage,
    pageSizes: props.pageSizes
});

const loadTable = () => {
    const interval = setInterval(() => {
        initialize();
        if (!initialized.value) clearInterval(interval);
    }, 100);
}

const { width } = useWindowSize();
watch(width, loadTable);
watch(() => props.refreshToggle, loadTable);
onMounted(loadTable);
nextTick(loadTable);
</script>

<template>
    <div class="p-4">
        <div v-if="initialized" class="flex justify-center items-center h-64">
            <i class="pi pi-spin pi-spinner !text-4xl text-gray-700"></i>
        </div>
        <div v-else :id="tableId" class="dx-table"></div>
    </div>
</template>

<style scoped lang="scss">
.dx-table {
    :deep(.dx-datagrid) {
        .dx-datagrid-pager {
            .dx-selection {
                @apply bg-gray-800 text-white;
            }
        }
    }
}
</style>
