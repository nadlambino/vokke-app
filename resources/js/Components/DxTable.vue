<script setup>
import { useWindowSize } from '@vueuse/core';
import { computed, nextTick, onMounted, ref, watch } from 'vue';

const emits = defineEmits(['edit', 'delete']);
const props = defineProps({
    tableId: {
        type: String,
        required: true
    },
    data: {
        type: Array,
        required: true
    },
    columns: {
        type: Array,
        required: true
    },
    keyExpr: {
        type: String,
        default: 'id'
    }
});

const { width } = useWindowSize();
const isLargeDesktop = computed(() => width.value > 1280);
const columnsWithActions = computed(() => {
    return [
        ...props.columns,
        {
            cellTemplate: function(container, options) {
                $("<div class='flex gap-2 h-10'>")
                    .append($("<button class='flex justify-center items-center bg-gray-800 px-2 py-1 rounded text-white hover:bg-gray-800/90 min-w-16'>")
                        .text("Edit")
                        .on("click", () => emits('edit', options.data)))
                    .append($("<button class='flex justify-center items-center bg-red-500 rounded px-2 py-1 text-white hover:bg-red-500/90 min-w-16'>")
                        .text("Delete")
                        .on("click", () => emits('delete', options.data)))
                    .appendTo(container);
            }
        }
    ];
});

const isLoading = ref(true);
const initializeTable = () => {
    isLoading.value = true;
    if (typeof window.$ === 'undefined' || typeof window.$.fn.dxDataGrid === 'undefined') {
        return;
    }

    window.$(`#${props.tableId}`)?.dxDataGrid({
        dataSource: props.data,
        keyExpr: props.keyExpr,
        columns: columnsWithActions.value,
        columnHidingEnabled: !isLargeDesktop.value,
        paging: {
            pageSize: 5
        },
        pager: {
            showPageSizeSelector: true,
            allowedPageSizes: [5, 10, 20],
            showInfo: true
        }
    });

    isLoading.value = false;
}

const loadTable = () => {
    const interval = setInterval(() => {
        initializeTable();
        if (!isLoading.value) clearInterval(interval);
    }, 100);
}
watch(() => props.data, loadTable);
watch(width, loadTable);
onMounted(loadTable);
nextTick(loadTable);
</script>

<template>
    <div class="p-4">
        <div v-if="isLoading" class="flex justify-center items-center h-64">
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
