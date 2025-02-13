<script setup>
const model = defineModel({
    type: Number,
    required: true,
});

const props = defineProps({
    options: Object,
});
</script>

<template>
    <select v-model="model" class="w-full rounded">
        <option v-for="(name, id) in options" :key="id" :value="id">
            {{ name }}
        </option>
    </select>
</template>
