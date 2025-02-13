<script setup>
import { ref } from 'vue'
import NavLinkHamburger from "@/Components/NavLinkHamburger.vue";

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
                        <nav class="flex flex-col items-start gap-5">
                            <button @click="hide()" class="fixed top-2 left-6">
                                <img src="/img/cross_black.svg" class="w-6">
                            </button>
                            <NavLinkHamburger :href="route('top')" @click="hide()">サイトトップへ</NavLinkHamburger>
                            <NavLinkHamburger :href="route('admin.user')" @click="hide()">ユーザー管理</NavLinkHamburger>
                            <NavLinkHamburger :href="route('admin.comment')" @click="hide()">コメント管理</NavLinkHamburger>
                            <NavLinkHamburger :href="route('admin.mail')" @click="hide()">メール送信</NavLinkHamburger>
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