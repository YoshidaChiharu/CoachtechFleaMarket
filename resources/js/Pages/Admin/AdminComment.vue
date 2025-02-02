<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import { reactive, ref, computed } from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageTitle from "@/Components/PageTitle.vue"
import Pagination from "@/Components/Pagination.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import FlashMessageModal from "@/Components/FlashMessageModal.vue";

defineOptions({ layout: AdminLayout })

defineProps({
    comments: Object,
})

const searchParam = reactive({
    id: route().params.searchParam?.id,
    itemName: route().params.searchParam?.itemName,
    userName: route().params.searchParam?.userName,
    comment: route().params.searchParam?.comment,
    date: route().params.searchParam?.date,
});
const open = ref(false);
const selectedId = ref(null);
const selectedItemName = ref('');
const selectedUserName = ref('');
const selectedComment = ref('')

const showFlashMessage = ref(false);

function searchComment() {
    router.reload({
        data: {
            searchParam: searchParam,
            page: 1,
        },
        only: ['comments'],
    });
}

function openModal(id, itemName, userName, comment) {
    selectedId.value = id;
    selectedItemName.value = itemName,
    selectedUserName.value = userName;
    selectedComment.value = comment;
    open.value = true;
}

function deleteComment(id) {
    router.post(route('admin.comment'), {
        comment_id: id,
    });
    open.value = false;
    showFlashMessage.value = true;
}
</script>

<template>
    <div class="max-w-5xl mx-auto p-5">
        <Head title="コメント管理ページ" />
        <PageTitle>コメント管理ページ</PageTitle>

        <!-- コメント検索 -->
        <div class="p-4 border rounded bg-gradient-to-t from-zinc-300 to-zinc-100">
            <div class="flex items-center gap-2 py-1">
                <InputLabel class="w-24">ID:</InputLabel>
                <div>
                    <TextInput
                        v-model="searchParam.id"
                        type="number"
                        min="1"
                        class="py-0"
                        @change="searchComment()"
                    />
                </div>
            </div>
            <div class="flex items-center gap-2 py-1">
                <InputLabel class="w-24">商品名:</InputLabel>
                <div>
                    <TextInput v-model="searchParam.itemName" @change="searchComment()" class="py-0" />
                </div>
            </div>
            <div class="flex items-center gap-2 py-1">
                <InputLabel class="w-24">投稿者名:</InputLabel>
                <div>
                    <TextInput v-model="searchParam.userName" @change="searchComment()" class="py-0" />
                </div>
            </div>
            <div class="flex items-center gap-2 py-1">
                <InputLabel class="w-24">コメント:</InputLabel>
                <div>
                    <TextInput v-model="searchParam.comment" type="email" @change="searchComment()" class="py-0" />
                </div>
            </div>
            <div class="flex items-center gap-2 py-1">
                <InputLabel class="w-24">投稿日:</InputLabel>
                <div>
                    <TextInput v-model="searchParam.date" type="date" @change="searchComment()" class="py-0" />
                </div>
            </div>
        </div>

        <!-- コメント一覧 -->
        <div class="overflow-x-auto">
            <table class="w-full mt-10 min-w-[600px] ">
                <thead class="text-sm text-left bg-gradient-to-t from-zinc-400 to-zinc-100">
                    <tr>
                        <th class="px-2 border-r border-white">操作</th>
                        <th class="px-2 border-r border-white">ID</th>
                        <th class="px-2 border-r border-white">コメント</th>
                        <th class="px-2 border-r border-white">商品名</th>
                        <th class="px-2 border-r border-white">投稿者</th>
                        <th class="px-2">投稿日時</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="comment in comments.data" :key="comment.id" class="even:bg-zinc-200">
                        <td class="text-center min-w-12">
                            <button
                                @click="openModal(comment.id, comment.item_name, comment.user_name, comment.comment)"
                                class="bg-red-500 text-sm text-white px-2 rounded"
                            >
                                削除
                            </button>
                        </td>
                        <td class="px-2">{{ comment.id }}</td>
                        <td class="px-2 max-w-60 truncate">{{ comment.comment }}</td>
                        <td class="px-2 max-w-60 truncate">{{ comment.item_name }}</td>
                        <td class="px-2 max-w-60 truncate">{{ comment.user_name }}</td>
                        <td class="px-2 whitespace-nowrap">{{ comment.created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="comments.links" />

        <!-- 削除確認モーダル -->
        <Teleport to="body">
            <div
                v-if="open"
                class="fixed top-0 right-0 bottom-0 left-0 bg-[rgba(0,0,0,.3)]"
            >
                <div class="fixed top-1/2 left-1/2 z-50 bg-white rounded translate-y-[-50%] translate-x-[-50%] drop-shadow-xl max-md:w-[90vw] md:max-w-[70vw] lg:max-w-xl max-h-[80vh] overflow-auto">
                    <div class="py-6 px-10">
                        <div class="text-center mb-4 font-bold">【コメント削除】</div>
                        <div>
                            <span class="mr-2">ID : </span>
                            <span class="font-bold">{{ selectedId }}</span>
                        </div>
                        <div>
                            <span class="mr-2">商品名 : </span>
                            <span class="font-bold">{{ selectedItemName }}</span>
                        </div>
                        <div>
                            <span class="mr-2">投稿者 : </span>
                            <span class="font-bold">{{ selectedUserName }}</span>
                        </div>
                        <div class="mt-1 p-2 bg-gray-200 rounded break-words whitespace-pre-wrap">
                            {{ selectedComment }}
                        </div>
                        <div class="mt-5">このコメントを削除してよろしいですか？</div>
                    </div>
                    <div class="grid grid-cols-2 border-t">
                        <div class="border-r text-center">
                            <button @click="open = false" class="py-2 px-4 font-bold text-blue-500">キャンセル</button>
                        </div>
                        <div class="text-center">
                            <button @click="deleteComment(selectedId)" class="py-2 px-4 font-bold text-red-500">削除</button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- フラッシュメッセージモーダル -->
        <FlashMessageModal v-model:show="showFlashMessage" />

    </div>
</template>
