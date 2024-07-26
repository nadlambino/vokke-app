<script lang="ts" setup>
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import Select from '@/Components/Select.vue';
import PrimaryButton from '../PrimaryButton.vue';
import useKangarooApi from '@/utils/kangaroo';
import { Kangaroo } from '@/utils/kangaroo/types';
import { reactive } from 'vue';

const emits = defineEmits(['close']);
const { create } = useKangarooApi();
const form = useForm<Kangaroo>({
    name: '',
    nickname: '',
    weight: undefined,
    height: undefined,
    gender: undefined,
    color: '',
    friendliness: undefined,
    birthday: '',
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
}>({});

const genderOptions = [
    { label: 'Male', value: 'male' },
    { label: 'Female', value: 'female' }
];

const friendlinessOptions = [
    { label: 'Friendly', value: 'friendly' },
    { label: 'Independent', value: 'independent' },
];

const cancel = () => {
    form.reset();
    emits('close');
};

const submit = () => {
    create(form.data())
        .then(() => {
            form.reset();
            alert('Kangaroo created successfully');
        })
        .catch((error) => {
            if (error.response.status === 422) {
                Object.assign(errors, error.response.data.errors);
            }

            alert('Failed to create kangaroo');
        });
}
</script>

<template>
    <div class="p-4 space-y-3">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Form</h2>

        <form class="space-y-5" @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <InputLabel for="name" value="Name" required />
                    <TextInput
                        id="name"
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
                        class="mt-1 block w-full"
                        v-model="form.nickname"
                    />
                    <InputError class="mt-2" :message="errors.nickname" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <InputLabel for="weight" value="Weight" required />
                    <TextInput
                        id="weight"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full"
                        v-model="form.weight"
                        required
                    />
                    <InputError class="mt-2" :message="errors.weight" />
                </div>
                <div>
                    <InputLabel for="height" value="Height" required />
                    <TextInput
                        id="height"
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
                        v-model="form.gender"
                        :options="genderOptions"
                        class="w-full"
                        required
                    />
                    <InputError class="mt-2" :message="errors.gender" />
                </div>
                <div>
                    <InputLabel for="color" value="Color" />
                    <TextInput
                        id="color"
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
                        v-model="form.friendliness"
                        :options="friendlinessOptions"
                        class="w-full"
                    />
                    <InputError class="mt-2" :message="errors.friendliness" />
                </div>
                <div>
                    <InputLabel for="birthday" value="Birthday" required />
                    <TextInput
                        id="birthday"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.birthday"
                        required
                    />
                    <InputError class="mt-2" :message="errors.birthday" />
                </div>
            </div>

            <div class="w-full flex flex-col md:flex-row md:justify-end gap-2">
                <PrimaryButton class="!justify-center order-2 md:order-1 !bg-gray-200 !text-gray-800 hover:!bg-gray-100" @click="cancel">Cancel</PrimaryButton>
                <PrimaryButton class="!justify-center order-1 md:order-2 " type="submit">Save</PrimaryButton>
            </div>
        </form>
    </div>
</template>
