<script setup>
import { nextTick, onMounted, watch } from 'vue';

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

const loadTable = () => {
    if (typeof window.$ === 'undefined' || typeof window.$.fn.dxDataGrid === 'undefined') {
        return;
    }

    window.$(`#${props.tableId}`)?.dxDataGrid({
        dataSource: props.data,
        keyExpr: props.keyExpr,
        columns: props.columns,
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
