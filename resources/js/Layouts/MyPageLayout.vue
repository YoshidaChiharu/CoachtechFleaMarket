<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import SecondaryButton from "@/Components/SecondaryButton.vue";
import UserIcon from "@/Components/UserIcon.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

defineProps({
    user: Object,
})

function showItems(filter) {
    router.reload({
        data: {
            filter: filter,
            page: 1,
        },
        only: ['items'],
    });
}
</script>

<template>
    <div class="pt-10 pb-2 px-6 md:px-20 border-b border-gray-600">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-around items-center mb-10 max-sm:flex-col">
                <div class="flex gap-10 items-center max-sm:w-full">
                    <div class="w-24 md:w-32">
                        <UserIcon :path="user.image_url" />
                    </div>
                    <div class="font-bold text-2xl">{{ user.name }}</div>
                </div>
                <div class="max-sm:mt-5 max-sm:w-full">
                    <Link href="/mypage/profile">
                        <SecondaryButton class="w-max max-sm:w-full">プロフィールを編集</SecondaryButton>
                    </Link>
                </div>
            </div>
            <ul class="flex gap-10 md:gap-20">
                <li :class="{ 'text-red-500' : route().params.filter === 'sell' || route().params.filter === undefine }">
                    <button @click="showItems('sell')" class="font-bold">出品した商品</button>
                </li>
                <li :class="{ 'text-red-500' : route().params.filter === 'purchased' }">
                    <button @click="showItems('purchased')" class="font-bold">購入した商品</button>
                </li>
            </ul>
        </div>
    </div>
    <slot />
</template>