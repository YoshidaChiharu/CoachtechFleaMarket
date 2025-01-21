<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import NavLinkHamburger from "@/Components/NavLinkHamburger.vue";
import SearchItems from "@/Components/SearchItems.vue";

defineProps({
    isAdmin: Boolean,
    isLogined: Boolean,
});

const open = ref(false);

async function hide() {
    open.value = false;
}
</script>

<template>
    <div>
        <button @click="open = true" class="flex items-center">
            <img src="/img/hamburger_menu.svg" class="w-8">
        </button>

        <Teleport to="body">
            <div
                v-if="open"
                class="fixed top-0 right-0 bottom-0 left-0 bg-white"
            >
                <div class="fixed top-1/2 left-1/2 z-50 bg-white translate-y-[-50%] translate-x-[-50%] drop-shadow-xl py-10 px-20 border rounded">
                    <div>
                        <SearchItems @change="hide()" />
                    </div>
                    <nav class="mt-8 flex flex-col text-center gap-5">
                        <!-- ログイン済み＆管理者ユーザーのみ表示 -->
                        <NavLinkHamburger v-if="isAdmin" :href="route('admin.user')" @click="hide()">管理画面</NavLinkHamburger>

                        <!-- ログイン済みユーザーのみ表示 -->
                        <NavLinkHamburger v-if="isLogined" :href="route('logout')" as="button" type="button" method="post" @click="hide()">ログアウト</NavLinkHamburger>
                        <NavLinkHamburger v-if="isLogined" :href="route('mypage')" @click="hide()">マイページ</NavLinkHamburger>

                        <!-- 未ログインユーザーのみ表示 -->
                        <NavLinkHamburger v-if="!isLogined" :href="route('login')" class="text-black text-lg font-bold" @click="hide()">ログイン</NavLinkHamburger>
                        <NavLinkHamburger v-if="!isLogined" :href="route('register')" class="text-black text-lg font-bold" @click="hide()">会員登録</NavLinkHamburger>

                        <!-- 全ユーザー共通 -->
                        <NavLinkHamburger :href="route('sell')" as="button" type="button" class="text-black text-lg font-bold" @click="hide()">出品</NavLinkHamburger>
                    </nav>
                </div>
                <button @click="hide()" class="fixed top-2 right-6">
                    <img src="/img/cross_black.svg" class="w-8">
                </button>
            </div>
        </Teleport>
    </div>
</template>