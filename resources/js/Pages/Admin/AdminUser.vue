<script setup>
import { Head, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageTitle from "@/Components/PageTitle.vue"
import Pagination from "@/Components/Pagination.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

defineOptions({ layout: AdminLayout })

defineProps({
    users: Object,
})

const searchParam = reactive({
    id: null,
    name: null,
    email: null,
    date: null,
});

function searchUser() {
    router.reload({
        data: { searchParam },
        only: ['users'],
    });
}

function deleteUser(id) {
    console.log('[削除] ユーザーID:' + id);
}
</script>

<template>
    <div class="max-w-5xl mx-auto p-5">
        <Head title="ユーザー管理ページ" />
        <PageTitle>ユーザー管理ページ</PageTitle>
        <!-- ユーザー検索 -->
        <div class="p-4 border rounded bg-gradient-to-t from-zinc-300 to-zinc-100">
            <div class="flex items-center gap-2 py-1">
                <InputLabel class="w-24">ID:</InputLabel>
                <div>
                    <TextInput
                        v-model="searchParam.id"
                        type="number"
                        min="1"
                        class="py-0"
                        @change="searchUser()"
                    />
                </div>
            </div>
            <div class="flex items-center gap-2 py-1">
                <InputLabel class="w-24">ユーザー名:</InputLabel>
                <div>
                    <TextInput v-model="searchParam.name" @change="searchUser()" class="py-0" />
                </div>
            </div>
            <div class="flex items-center gap-2 py-1">
                <InputLabel class="w-24">メールアドレス:</InputLabel>
                <div>
                    <TextInput v-model="searchParam.email" type="email" @change="searchUser()" class="py-0" />
                </div>
            </div>
            <div class="flex items-center gap-2 py-1">
                <InputLabel class="w-24">登録日:</InputLabel>
                <div>
                    <TextInput v-model="searchParam.date" type="date" @change="searchUser()" class="py-0" />
                </div>
            </div>
        </div>

        <!-- ユーザー一覧 -->
        <table class="w-full mt-10">
            <thead class="text-sm text-left bg-gradient-to-t from-zinc-400 to-zinc-100">
                <tr>
                    <th class="px-2 border-r border-white">操作</th>
                    <th class="px-2 border-r border-white">ID</th>
                    <th class="px-2 border-r border-white">ユーザー名</th>
                    <th class="px-2 border-r border-white">メールアドレス</th>
                    <th class="px-2">登録日時</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id" class="even:bg-zinc-200">
                    <td class="text-center">
                        <button @click="deleteUser(user.id)" class="bg-red-500 text-sm text-white px-2 rounded">削除</button>
                    </td>
                    <td class="px-2">{{ user.id }}</td>
                    <td class="px-2">{{ user.name }}</td>
                    <td class="px-2">{{ user.email }}</td>
                    <td class="px-2">{{ user.created_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
