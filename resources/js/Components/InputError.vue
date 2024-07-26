<script setup lang="ts">
defineProps<{
    message?: string|string[];
}>();
</script>

<template>
    <div v-show="message">
        <template v-if="Array.isArray(message)">
            <p class="text-sm text-red-600" v-for="msg in message" :key="msg">
                {{ msg }}
            </p>
        </template>
        <p v-else class="text-sm text-red-600">
            {{ message }}
        </p>
    </div>
</template>
