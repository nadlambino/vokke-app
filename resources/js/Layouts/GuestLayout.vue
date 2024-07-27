<script setup lang="ts">
import image from './../../images/zoo.svg';

</script>

<template>
    <div class="min-h-screen bg-gray-50 grid grid-cols-2">
        <div class="col-span-1 hidden lg:block">
            <img :src="image" />
        </div>

        <div class="col-span-2 lg:col-span-1 bg-white flex justify-center items-center">
            <div class="w-full mx-4 md:w-2/3 shadow-lg bg-gray-50 rounded p-4 relative z-10">
                <slot />
            </div>
        </div>
    </div>
</template>
