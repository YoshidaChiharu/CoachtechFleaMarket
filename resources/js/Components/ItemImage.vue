<script setup>
const props = defineProps({
    path: String
})
</script>

<template>
    <div>
        <img
            :src="path"
            alt="商品画像"
            class="w-full bg-gray-200 border border-gray-400 aspect-[3/4] object-contain"
        >
    </div>
</template>
