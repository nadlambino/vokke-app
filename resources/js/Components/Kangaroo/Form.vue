<script lang="ts" setup>
import { computed, reactive, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import { AxiosError } from 'axios';
import { Kangaroo } from '@/types';
import InputLabel from '@/Components/Shared/InputLabel.vue';
import InputError from '@/Components/Shared/InputError.vue';
import TextInput from '@/Components/Shared/TextInput.vue';
import Select from '@/Components/Shared/Select.vue';
import PrimaryButton from '../Shared/PrimaryButton.vue';
import useKangarooApi from '@/utils/kangaroo';
import Confirm from '@/Components/Shared/Confirm.vue';
import FileUpload from '@/Components/Shared/FileUpload.vue';

const props = defineProps<{
    data?: Kangaroo|null;
}>();
const isCreate = computed(() => !props.data);
const emits = defineEmits(['close', 'saved']);
const { create, update } = useKangarooApi();
const toast = useToast();
const form = useForm<Kangaroo & { image: File|null }>({
    name: props.data?.name ?? '',
    nickname: props.data?.nickname ?? '',
    weight: props.data?.weight ?? undefined,
    height: props.data?.height ?? undefined,
    gender: props.data?.gender ?? 'male',
    color: props.data?.color ?? '',
    friendliness: props.data?.friendliness ?? 'friendly',
    birthday: props.data?.birthday ?? '',
    image: null,
});
const errors = reactive<{
    name?: string[];
    nickname?: string[];
    weight?: string[];
    height?: string[];
    gender?: string[];
    color?: string[];
    friendliness?: string[];
    birthday?: string[];
    image?: string[];
}>({});

const genderOptions = [
    { label: 'Male', value: 'male' },
    { label: 'Female', value: 'female' }
];

const friendlinessOptions = [
    { label: 'Friendly', value: 'friendly' },
    { label: 'Independent', value: 'independent' },
];

const showConfirm = ref(false);

const cancel = () => {
    form.reset();
    emits('close');
};

const submit = () => {
    if (isCreate.value) {
        create(form.data())
            .then(() => handleSuccessfulRequest('Kangaroo was created successfully'))
            .catch((error: AxiosError) => handleFailedRequest('Kangaroo creation failed', error));
    } else {
        update(props.data?.id as number, form.data())
            .then(() => handleSuccessfulRequest('Kangaroo was updated successfully'))
            .catch((error) => handleFailedRequest('Kangaroo update failed', error));
    }
}

const handleSuccessfulRequest = (message: string) => {
    form.reset();
    emits('saved');
    toast.add({ severity: 'success', summary: 'Success', detail: message, life: 3000 });
}

const handleFailedRequest = (message: string, error: any) => {
    if (error.response?.status === 422) {
        Object.assign(errors, error.response.data.errors);
    }
    toast.add({ severity: 'error', summary: 'Error', detail: message, life: 3000 });
}
</script>

<template>
    <div class="p-4 space-y-3">
        <Confirm
            group="form-confirm"
            id="form-confirm"
            key="form-confirm"
            v-model="showConfirm"
            message="Are you sure you want to add this kangaroo?"
            @proceed="submit"
        />

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ isCreate ? 'Add a Kangaroo' : 'Update a Kangaroo' }}</h2>

        <form class="space-y-5" @submit.prevent="showConfirm = true">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <InputLabel for="name" value="Name" required />
                    <TextInput
                        id="name"
                        name="name"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                    />
                    <InputError class="mt-2" :message="errors.name" />
                </div>
                <div>
                    <InputLabel for="nickname" value="Nickname" />
                    <TextInput
                        id="nickname"
                        name="nickname"
                        class="mt-1 block w-full"
                        v-model="form.nickname"
                    />
                    <InputError class="mt-2" :message="errors.nickname" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <InputLabel for="weight" value="Weight (kg)" required />
                    <TextInput
                        id="weight"
                        name="weight"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full"
                        v-model="form.weight"
                        required
                    />
                    <InputError class="mt-2" :message="errors.weight" />
                </div>
                <div>
                    <InputLabel for="height" value="Height (cm)" required />
                    <TextInput
                        id="height"
                        name="height"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full"
                        v-model="form.height"
                        required
                    />
                    <InputError class="mt-2" :message="errors.height" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <InputLabel for="gender" value="Gender" required />
                    <Select
                        id="gender"
                        name="gender"
                        v-model="form.gender"
                        :options="genderOptions"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError class="mt-2" :message="errors.gender" />
                </div>
                <div>
                    <InputLabel for="color" value="Color" />
                    <TextInput
                        id="color"
                        name="color"
                        class="mt-1 block w-full"
                        v-model="form.color"
                    />
                    <InputError class="mt-2" :message="errors.color" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <InputLabel for="friendliness" value="Friendliness" />
                    <Select
                        id="friendliness"
                        name="friendliness"
                        v-model="form.friendliness"
                        :options="friendlinessOptions"
                        class="mt-1 block w-full"
                    />
                    <InputError class="mt-2" :message="errors.friendliness" />
                </div>
                <div>
                    <InputLabel for="birthday" value="Birthday" required />
                    <TextInput
                        id="birthday"
                        name="birthday"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.birthday"
                        required
                    />
                    <InputError class="mt-2" :message="errors.birthday" />
                </div>
            </div>

            <div>
                <InputLabel for="image" class="w-full flex justify-between items-center">
                    <span>Image</span>
                    <span v-if="!isCreate" class="!text-xs !text-gray-500">Image will be replace with the new one.</span>
                </InputLabel>
                <FileUpload
                    class="mt-1 block w-full"
                    name="image"
                    id="image"
                    accept="image/*"
                    v-model="form.image"
                />
                <InputError class="mt-2" :message="errors.image" />
            </div>

            <div class="w-full flex flex-col md:flex-row md:justify-end gap-2">
                <PrimaryButton class="!justify-center order-2 md:order-1 !bg-gray-200 !text-gray-800 hover:!bg-gray-100" @click="cancel">Cancel</PrimaryButton>
                <PrimaryButton class="!justify-center order-1 md:order-2 " type="submit">Save</PrimaryButton>
            </div>
        </form>
    </div>
</template>
