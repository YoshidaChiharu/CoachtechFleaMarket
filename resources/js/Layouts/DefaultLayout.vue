<script setup>
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import SearchItems from "@/Components/SearchItems.vue";
import HamburgerMenu from "@/Components/UserHamburgerMenu.vue";

const isLogined = computed(() => (usePage().props.auth.user !== null));
const isAdmin = computed(() => (usePage().props.auth.user !== null) && (usePage().props.auth.user.role_id === 1));
</script>

<template>
    <div>
        <Head title="COACHTECHフリマ" />
        <div class="min-h-screen">
            <header>
                <div class="py-2 px-6 flex md:justify-between items-center gap-6 bg-black">
                    <div class="md:hidden">
                        <HamburgerMenu :isAdmin="isAdmin" :isLogined="isLogined" />
                    </div>
                    <Link href="/">
                        <ApplicationLogo class="w-52 lg:w-64" />
                    </Link>
                    <div class="hidden md:flex gap-3 lg:gap-5">
                        <div>
                            <SearchItems />
                        </div>
                        <nav class="flex items-center gap-3 lg:gap-5 text-white">
                            <!-- ログイン済み＆管理者ユーザーのみ表示 -->
                            <NavLink v-if="isAdmin" :href="route('admin.user')">管理画面</NavLink>

                            <!-- ログイン済みユーザーのみ表示 -->
                            <NavLink v-if="isLogined" :href="route('logout')" as="button" type="button" method="post">ログアウト</NavLink>
                            <NavLink v-if="isLogined" :href="route('mypage')">マイページ</NavLink>

                            <!-- 未ログインユーザーのみ表示 -->
                            <NavLink v-if="!isLogined" :href="route('login')">ログイン</NavLink>
                            <NavLink v-if="!isLogined" :href="route('register')">会員登録</NavLink>

                            <!-- 全ユーザー共通 -->
                            <Link :href="route('sell')" as="button" type="button" class="py-1 px-4 bg-white text-black text-sm font-bold rounded">出品</Link>
                        </nav>
                    </div>
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
