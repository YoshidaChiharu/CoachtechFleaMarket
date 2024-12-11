<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <Head title="COACHTECHフリマ" />
        <div class="min-h-screen bg-gray-100">
            <header>
                <div class="py-2 px-10 flex justify-between items-center bg-black">
                    <Link href="/">
                        <ApplicationLogo/>
                    </Link>
                    <div class="flex gap-10">
                        <div>
                            <input type="text" class="py-1 rounded" placeholder="なにをお探しですか？">
                        </div>
                        <nav class="flex items-center gap-10 text-white">
                            <NavLink :href="route('login')">ログイン</NavLink>
                            <NavLink :href="route('register')">会員登録</NavLink>
                            <Link :href="route('login')">
                                <PrimaryButton>出品</PrimaryButton>
                            </Link>
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
