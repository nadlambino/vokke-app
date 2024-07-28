<script lang="ts" setup>
import { computed, ref } from 'vue';
import PrimaryButton from '../Shared/PrimaryButton.vue';

const props = withDefaults(defineProps<{
    accept?: string;
    multiple?: boolean;
}>(), {
    accept: '*',
    multiple: false,
});
const model = defineModel<File|FileList|null>({ default: null})
const fileInput = ref<HTMLInputElement | null>(null);
const displayFiles = computed(() => {
    if (model.value instanceof FileList) {
        return Array.from(model.value).map(file => ({
            url: URL.createObjectURL(file),
            is_image: file.type.startsWith('image/'),
        }));
    } else if (model.value instanceof File) {
        return [{
            url: URL.createObjectURL(model.value),
            is_image: model.value.type.startsWith('image/'),
        }];
    } else {
        return [];
    }
});

const chooseFile = () => {
    fileInput.value?.click();
};

const handleFileSelect = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files) {
        model.value = props.multiple ? input.files : input.files[0];
    }
};

const handleFileRemove = (index: number) => {
    if (model.value instanceof FileList) {
        const files = Array.from(model.value);
        files.splice(index, 1);
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        model.value = dataTransfer.files;
    } else {
        model.value = null;
    }
};
</script>

<template>
    <div class="flex gap-3 flex-wrap">
        <div class="h-auto flex items-center">
            <PrimaryButton class="!text-[10px] !w-24 !px-2" type="button" @click="chooseFile">
                <i class="pi pi-plus pr-2" />
                <span>Browse</span>
            </PrimaryButton>
            <input ref="fileInput" type="file" class="hidden" @change="handleFileSelect" :accept :multiple />
        </div>
        <template v-for="(file, index) in displayFiles">
            <div class="flex flex-col items-center">
                <img v-if="file.is_image" :src="file.url" class="w-24 h-24 object-cover border shadow" />
                <div v-else class="w-24 h-24 flex items-center justify-center border shadow">
                    <i class="pi pi-file !text-5xl text-gray-700" />
                </div>
                <button type="button" class="mt-2 text-sm text-gray-600 underline" @click="() => handleFileRemove(index)">Remove</button>
            </div>
        </template>
    </div>
</template>
