<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageTitle from "@/Components/PageTitle.vue"
import Pagination from "@/Components/Pagination.vue";

defineOptions({ layout: AdminLayout })

defineProps({
    users: Object,
})
</script>

<template>
    <div class="max-w-5xl mx-auto py-10">
        <Head title="ユーザー管理ページ" />
        <PageTitle>ユーザー管理ページ</PageTitle>
    </div>
</template>
