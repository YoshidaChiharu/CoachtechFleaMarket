<script setup>
const props = defineProps({
  path: String
})
</script>

<template>
    <img :src="path" alt="user" class="rounded-full">
</template>
