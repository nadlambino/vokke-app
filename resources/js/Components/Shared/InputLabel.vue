<script setup lang="ts">
defineProps<{
    value?: string;
    required?: boolean;
}>();
</script>

<template>
    <label class="block font-medium text-sm text-gray-700">
        <span v-if="value">
            {{ value }}
            <span v-if="required" class="text-red-500">*</span>
        </span>
        <template v-else><slot /></template>
    </label>
</template>
