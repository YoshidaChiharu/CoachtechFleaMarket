<script setup>
import MyPageLayout from '@/Layouts/MyPageLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import ItemCard from "@/Components/ItemCard.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    items: Object,
    user: Object,
})
</script>

<template>
    <MyPageLayout :user="user">
        <div class="max-w-5xl mx-auto py-10 px-6">
            <Head title="MyPage" />
            <div class="flex flex-wrap">
                <!-- 商品カード -->
                <div v-for="item in items.data" :key="item.id" class="w-1/2 sm:w-1/4 md:w-1/5 p-2">
                    <ItemCard :item="item" />
                </div>
            </div>
            <Pagination :links="items.links" />
        </div>
    </MyPageLayout>
</template>
