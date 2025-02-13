<script setup>
import { Head, Link } from '@inertiajs/vue3';
import TopPageLayoutVue from '@/Layouts/TopPageLayout.vue';
import ItemCard from "@/Components/ItemCard.vue";
import Pagination from "@/Components/Pagination.vue";

defineOptions({ layout: TopPageLayoutVue })

defineProps({
    items: Object,
})
</script>

<template>
    <div class="max-w-5xl mx-auto py-10 px-6">
        <Head title="Top Page" />

        <!-- 検索条件表示 -->
        <div v-if="route().params.searchWord" class="mb-4 px-2">
            <div class="font-bold py-1 px-4 bg-gradient-to-t from-zinc-300 to-zinc-100">
                検索条件 : "{{ route().params.searchWord }}"
            </div>
        </div>

        <!-- 商品一覧 -->
        <div class="flex flex-wrap">
            <div v-for="item in items.data" :key="item.id" class="w-1/2 sm:w-1/4 md:w-1/5 p-2">
                <ItemCard :item="item" />
            </div>
        </div>
        <Pagination :links="items.links" />
    </div>
</template>
