<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: Array,
    required: true,
});

const props = defineProps({
    options: Object,
});
</script>

<template>
    <div class="flex flex-wrap gap-x-6 p-4 border rounded border-gray-600 shadow-sm">
        <div v-for="(name, id) in options" :key="id" class="flex items-center gap-1">
            <input type="checkbox" :id="name" :value="id" v-model="model">
            <label :for="name">{{ name }}</label>
        </div>
    </div>
</template>
