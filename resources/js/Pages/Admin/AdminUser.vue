<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import { reactive, ref, computed } from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageTitle from "@/Components/PageTitle.vue"
import Pagination from "@/Components/Pagination.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import FlashMassageModal from "@/Components/FlashMassageModal.vue";

defineOptions({ layout: AdminLayout })

defineProps({
    users: Object,
})

const searchParam = reactive({
    id: route().params.searchParam?.id,
    name: route().params.searchParam?.name,
    email: route().params.searchParam?.email,
    date: route().params.searchParam?.date,
});
const open = ref(false);
const selectedId = ref(null);
const selectedName = ref('');

const showFlashMessage = ref(false);
const message = computed(() => usePage().props.flash.message);
const status = computed(() => usePage().props.flash.status);

function searchUser() {
    router.reload({
        data: {
            searchParam: searchParam,
            page: 1,
        },
        only: ['users'],
    });
}

function openModal(id, name) {
    selectedId.value = id;
    selectedName.value = name;
    open.value = true;
}

function deleteUser(id) {
    router.post(route('admin.user'), {
        user_id: id,
    });
    open.value = false;
    showFlashMessage.value = true;
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
                <tr v-for="user in users.data" :key="user.id" class="even:bg-zinc-200">
                    <td class="text-center">
                        <button
                            v-if="user.role_id !== 1"
                            @click="openModal(user.id, user.name)"
                            class="bg-red-500 text-sm text-white px-2 rounded"
                        >
                            削除
                        </button>
                    </td>
                    <td class="px-2">{{ user.id }}</td>
                    <td class="px-2">{{ user.name }}</td>
                    <td class="px-2">{{ user.email }}</td>
                    <td class="px-2">{{ user.created_at }}</td>
                </tr>
            </tbody>
        </table>

        <Pagination :links="users.links" />

        <!-- 削除確認モーダル -->
        <Teleport to="body">
            <div
                v-if="open"
                class="fixed top-0 right-0 bottom-0 left-0 bg-[rgba(0,0,0,.3)]"
            >
                <div class="fixed top-1/2 left-1/2 z-50 bg-white rounded translate-y-[-50%] translate-x-[-50%] drop-shadow-xl max-h-[50vh] overflow-auto">
                    <div class="py-6 px-10">
                        <p class="text-center mb-4 font-bold">【ユーザー削除】</p>
                        <p>
                            <span class="mr-2">ユーザーID : </span>
                            <span class="font-bold">{{ selectedId }}</span><br>
                            <span class="mr-2">ユーザー名 : </span>
                            <span class="font-bold">{{ selectedName }}</span>
                        </p>
                        <p class="mt-5">このユーザーを削除してよろしいですか？</p>
                    </div>
                    <div class="grid grid-cols-2 border-t">
                        <div class="border-r text-center">
                            <button @click="open = false" class="py-2 px-4 font-bold text-blue-500">キャンセル</button>
                        </div>
                        <div class="text-center">
                            <button @click="deleteUser(selectedId)" class="py-2 px-4 font-bold text-red-500">削除</button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- フラッシュメッセージモーダル -->
        <FlashMassageModal v-model:status="status" v-model:show="showFlashMessage">
            {{ message }}
        </FlashMassageModal>

    </div>
</template>
