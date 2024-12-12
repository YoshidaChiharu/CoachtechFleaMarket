<script setup>
import { ref, onUpdated, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';

// ログイン状態によってレイアウトを切り替える
// import Layout from '../Layouts/AuthenticatedLayout.vue';
import Layout from '../Layouts/GuestLayout.vue';

const selected = ref(1)
</script>

<template>
    <Layout>
    <div class="pt-10 pb-2 px-20 border-b border-gray-600">
        <div class="max-w-7xl mx-auto">
            <ul class="flex gap-20">
                <li @click="selected = 1" :class="{ 'text-red-500' : selected == 1 }">
                    <Link href="/" class="font-bold">おすすめ</Link>
                </li>
                <li @click="selected = 2" :class="{ 'text-red-500' : selected == 2 }">
                    <Link href="/" class="font-bold">マイリスト</Link>
                </li>
            </ul>
        </div>
    </div>
    <slot />
    </Layout>
</template>