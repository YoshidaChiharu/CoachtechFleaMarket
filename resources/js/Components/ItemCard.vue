<script setup>
import { Link } from '@inertiajs/vue3';
import LikeIcon from "@/Components/LikeIcon.vue";
import ItemImage from "@/Components/ItemImage.vue";

const props = defineProps({
    item: Object,
})
const price = `￥${props.item.price.toLocaleString()}`;
</script>

<template>
    <div>
        <div class="relative">
            <!-- 商品画像 -->
            <Link :href="'/item/' + item.id">
                <ItemImage :item="item" />
            </Link>
            <!-- お気に入りアイコン -->
            <div
                class="w-1/5 max-w-8 flex justify-center items-center aspect-square rounded-full bg-gray-200 opacity-80 shadow-xl absolute top-1 right-1"
            >
                <LikeIcon :item="item" class="w-2/3" />
            </div>
            <!-- 価格 -->
            <div
                class="py-1 pl-2 pr-4 bg-gray-600 opacity-80 inline-block rounded-r-full absolute bottom-0 left-0"
            >
                <span class="opacity-100 text-white font-bold">{{ price }}</span>
            </div>
        </div>
        <!-- 商品名 -->
        <div class="truncate">{{ item.name }}</div>
    </div>
</template>
