<script setup>
import { ref, onUpdated, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

import { usePage } from "@inertiajs/vue3";
const isLogined = usePage().props.auth.user !== null;
</script>

<template>
    <DefaultLayout>
    <div class="pt-10 pb-2 px-20 border-b border-gray-600">
        <div class="max-w-7xl mx-auto">
            <ul class="flex gap-20">
                <li :class="{ 'text-red-500' : $page.url === '/' }">
                    <Link href="/" class="font-bold">おすすめ</Link>
                </li>
                <li :class="{ 'text-red-500' : $page.url === '/mylist' }">
                    <Link href="/mylist" class="font-bold">マイリスト</Link>
                </li>
            </ul>
        </div>
    </div>
    <slot />
    </DefaultLayout>
</template>