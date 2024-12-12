<script setup>
import MyPageLayoutVue from '@/Layouts/MyPageLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({ layout: MyPageLayoutVue })
</script>

<template>
    <div>
        <Head title="Top Page" />
        MyPage
    </div>
</template>
