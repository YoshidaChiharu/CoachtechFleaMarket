<script setup>
const props = defineProps({
    item: Object
})
</script>

<template>
    <div class="relative">
        <img
            :src="item.image_url"
            alt="商品画像"
            class="w-full bg-gray-800 border border-gray-400 aspect-[3/4] object-contain"
        >
        <!-- SOLD OUTアイコン -->
        <div v-if="item.is_sold" class="w-2/5 absolute top-0 left-0">
            <img src="/img/sold_out.png" alt="">
        </div>
    </div>
</template>
