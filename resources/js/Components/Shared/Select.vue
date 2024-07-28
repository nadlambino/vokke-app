<script lang="ts" setup>
import { onMounted, ref } from 'vue';

const model = defineModel<string|number|null|undefined>({ required: true });
const props = defineProps<{
    options: { value: string | number; label: string }[];
}>();

const select = ref<HTMLSelectElement | null>(null);

onMounted(() => {
    if (select.value?.hasAttribute('autofocus')) {
        select.value?.focus();
    }
});

defineExpose({ focus: () => select.value?.focus() });
</script>

<template>
    <select
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="model"
        ref="input"
    >
        <option v-for="option in props.options" :value="option.value">{{ option.label }}</option>
    </select>
</template>
