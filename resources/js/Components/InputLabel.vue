<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block text-sm font-medium text-gray-700 mt-2">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
