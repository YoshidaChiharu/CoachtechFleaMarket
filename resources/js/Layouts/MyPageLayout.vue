<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import SecondaryButton from "../Components/SecondaryButton.vue";
import UserIcon from "../Components/UserIcon.vue";

// ログイン状態によってレイアウトを切り替える
// import Layout from '../Layouts/AuthenticatedLayout.vue';
import Layout from '../Layouts/GuestLayout.vue';

const selected = ref(1)
</script>

<template>
    <Layout>
    <div class="pt-10 pb-2 px-20 border-b border-gray-600">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-around items-center mb-10">
                <div class="flex gap-10 items-center">
                    <UserIcon path="img/dummy_user_icon.png" />
                    <span class="font-bold text-2xl">ユーザー名</span>
                </div>
                <div>
                    <Link href="/mypage">
                        <SecondaryButton>プロフィールを編集</SecondaryButton>
                    </Link>
                </div>
            </div>
            <ul class="flex gap-20">
                <li @click="selected = 1" :class="{ 'text-red-500' : selected == 1 }">
                    <Link href="/mypage" class="font-bold">出品した商品</Link>
                </li>
                <li @click="selected = 2" :class="{ 'text-red-500' : selected == 2 }">
                    <Link href="/mypage" class="font-bold">購入した商品</Link>
                </li>
            </ul>
        </div>
    </div>
    <slot />
    </Layout>
</template>