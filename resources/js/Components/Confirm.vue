<script lang="ts" setup>
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm';
import { watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const emits = defineEmits(['cancel', 'proceed']);
const props = withDefaults(defineProps<{
    message?: string;
    header?: string;
    icon?: string;
}>(), {
    message: 'Are you sure you want to proceed?',
    header: 'Confirmation',
    icon: 'pi pi-exclamation-triangle',
});
const confirm = useConfirm();
const show = defineModel({ default: false });
watch(show, (value) => {
    if (value) {
        confirm.require({});
    } else {
        confirm.close();
    }
});

const cancel = () => {
    emits('cancel');
    show.value = false;
};

const proceed = () => {
    emits('proceed');
    show.value = false;
};
</script>

<template>
    <ConfirmDialog>
        <template #container>
            <div class="flex flex-col items-center p-8 bg-surface-0 dark:bg-surface-900 rounded">
                <div class="rounded-full bg-gray-800 text-white inline-flex justify-center items-center h-24 w-24 -mt-20">
                    <i class="pi pi-question text-5xl"></i>
                </div>
                <span class="font-bold text-2xl block mb-2 mt-6">{{ header }}</span>
                <p class="mb-0">{{ message }}</p>
                <div class="flex items-center gap-2 mt-6">
                    <PrimaryButton class="!justify-center order-2 md:order-1 !bg-gray-200 !text-gray-800 hover:!bg-gray-100" @click="cancel">Cancel</PrimaryButton>
                    <PrimaryButton class="!justify-center order-1 md:order-2 " @click="proceed">Proceed</PrimaryButton>
                </div>
            </div>
        </template>
    </ConfirmDialog>
</template>
