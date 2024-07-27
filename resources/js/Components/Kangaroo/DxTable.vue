<script setup>
import { computed, nextTick, onMounted, watch } from 'vue';

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

const columnsWithActions = computed(() => {
    return [
        ...props.columns,
        {
            caption: "Actions",
            cellTemplate: function(container, options) {
                $("<div class='flex gap-2'>")
                    .append($("<button class='bg-gray-800 px-2 py-1 rounded text-white hover:bg-gray-800/90'>")
                        .text("Edit")
                        .on("click", () => emits('edit', options.data)))
                    .append($("<button class='bg-red-500 rounded px-2 py-1 text-white hover:bg-red-500/90'>")
                        .text("Delete")
                        .on("click", () => emits('delete', options.data)))
                    .appendTo(container);
            }
        }
    ];
});

const loadTable = () => {
    if (typeof window.$ === 'undefined' || typeof window.$.fn.dxDataGrid === 'undefined') {
        return;
    }

    window.$(`#${props.tableId}`)?.dxDataGrid({
        dataSource: props.data,
        keyExpr: props.keyExpr,
        columns: columnsWithActions.value,
        paging: {
            pageSize: 5
        },
        pager: {
            showPageSizeSelector: true,
            allowedPageSizes: [5, 10, 20],
            showInfo: true
        }
    });
}

watch(() => props.data, loadTable);
onMounted(loadTable);
nextTick(loadTable);
</script>

<template>
    <div class="p-4">
        <div :id="tableId"></div>
    </div>
</template>
