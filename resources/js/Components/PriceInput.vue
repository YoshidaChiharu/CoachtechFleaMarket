<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: Number,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="relative">
        <input
            type="number"
            class="w-full pl-8 rounded border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            v-model="model"
            ref="input"
        />
        <span class="absolute top-1/2 left-3 bg-white translate-y-[-50%]">￥</span>
    </div>
</template>
