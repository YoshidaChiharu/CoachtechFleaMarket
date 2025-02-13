<script setup>
import { ref } from 'vue'
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
            <img src="/img/hamburger_menu.svg" class="w-6">
        </button>

        <Teleport to="body">
            <Transition>
                <div
                    v-if="open"
                    class="fixed top-0 right-0 bottom-0 left-0 bg-[rgba(0,0,0,.3)]"
                >
                    <div class="fixed top-0 left-0 bottom-0 z-50 min-w-52 py-14 px-6 bg-white drop-shadow-xl border-r">
                        <div>
                            <SearchItems @change="hide()" />
                        </div>
                        <nav class="mt-5 flex flex-col items-start gap-5">
                            <button @click="hide()" class="fixed top-2 left-6">
                                <img src="/img/cross_black.svg" class="w-6">
                            </button>
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
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.2s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>