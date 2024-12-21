<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SearchItems from "../Components/SearchItems.vue";
import { Head, Link } from '@inertiajs/vue3';

import { onMounted, onUnmounted, ref } from 'vue';

onMounted(() => {
    console.log('mounted layout')
})
onUnmounted(() => {
    console.log("UnMount layout")
})
</script>

<template>
    <div>
        <Head title="COACHTECHフリマ" />
        <div class="min-h-screen">
            <header>
                <div class="py-2 px-10 flex justify-between items-center bg-black">
                    <Link href="/">
                        <ApplicationLogo/>
                    </Link>
                    <div class="flex gap-10">
                        <div>
                            <!-- <SearchItems /> -->
                        </div>
                        <nav class="flex items-center gap-10 text-white">
                            <NavLink :href="route('logout')" as="button" type="button" method="post">ログアウト</NavLink>
                            <NavLink :href="route('mypage')">マイページ</NavLink>
                            <Link :href="route('login')" as="button" type="button" class="py-1 px-4 bg-white text-black text-sm font-bold rounded">出品</Link>
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
