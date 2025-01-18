<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SearchItems from "../Components/SearchItems.vue";
import { Head, Link } from '@inertiajs/vue3';

import { usePage } from "@inertiajs/vue3";
const isLogined = (usePage().props.auth.user !== null);
</script>

<template>
    <div>
        <Head title="COACHTECHフリマ" />
        <div class="min-h-screen flex flex-col">
            <header>
                <div class="py-2 px-10 flex items-center bg-black">
                    <Link href="/">
                        <ApplicationLogo/>
                    </Link>
                    <span class="text-white text-3xl font-bold ml-4">管理画面</span>
                </div>
            </header>

            <main class="flex grow">
                <!-- サイドメニュー -->
                <div class="w-1/5 max-w-72 p-4 text-lg text-white font-bold bg-slate-800">
                    <ul>
                        <li class="py-2">
                            <Link href="/">サイトトップへ</Link>
                        </li>
                        <li class="py-2">
                            <Link href="/admin/user">ユーザー管理</Link>
                        </li>
                        <li class="py-2">
                            <Link href="/admin/comment">コメント管理</Link>
                        </li>
                        <li class="py-2">
                            <Link href="/admin/mail">メール送信</Link>
                        </li>
                    </ul>
                </div>
                <!-- メインコンテンツ -->
                <div class="grow">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
