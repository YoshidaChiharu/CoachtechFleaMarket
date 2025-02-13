<script setup>
const props = defineProps({
    path: String
})
</script>

<template>
    <div>
        <img
            :src="path"
            alt="ユーザーアイコン"
            class="w-full aspect-square rounded-full bg-gray-200 border border-gray-400 object-cover"
        >
    </div>
</template>
