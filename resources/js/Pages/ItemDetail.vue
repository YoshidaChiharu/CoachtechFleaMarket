<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue'
import ItemImage from "@/Components/ItemImage.vue";
import LikeIcon from "@/Components/LikeIcon.vue";
import CommentIcon from "@/Components/CommentIcon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CategoryIcon from "@/Components/CategoryIcon.vue";
import FlashMessageModal from "@/Components/FlashMessageModal.vue";

const props = defineProps({
    item: Object,
});
const price = `￥${props.item.price.toLocaleString()}`;
const showFlashMessage = ref(true);
const message = computed(() => usePage().props.flash.message);
</script>

<template>
    <div class="max-w-5xl mx-auto py-10">
        <Head title="商品詳細" />
        <div class="flex w-full max-sm:flex-col">
            <!-- 商品画像(左側) -->
            <div class="max-sm:w-full max-sm:max-w-96 max-sm:mx-auto sm:w-1/2 p-6 md:p-16">
                <ItemImage :item="item" />
            </div>
            <!-- 詳細情報(右側) -->
            <div class="sm:w-1/2 p-6 md:p-16">
                <h2 class="text-2xl font-bold">{{ item.name }}</h2>
                <span class="text-sm">{{ item.brand }}</span>
                <div class="text-xl mt-4">
                    {{ price }}
                </div>
                <div class="flex gap-8 mt-4 px-2">
                    <LikeIcon :item="item">{{ item.likes_count }}</LikeIcon>
                    <Link :href="'/item/comment/' + item.id">
                        <CommentIcon>{{ item.comments_count }}</CommentIcon>
                    </Link>
                </div>
                <div class="mt-4">
                    <PrimaryButton v-if="item.is_sold === true" disabled>SOLD OUT</PrimaryButton>
                    <Link v-else :href="'/purchase/' + item.id">
                        <PrimaryButton>購入する</PrimaryButton>
                    </Link>
                </div>
                <div class=" mt-8">
                    <h3 class="text-xl font-bold">商品説明</h3>
                    <p class="break-words whitespace-pre-wrap">{{ item.description }}</p>
                </div>
                <div class=" mt-12">
                    <h3 class="text-xl font-bold">商品の情報</h3>
                    <div class="mt-8 flex items-center flex-wrap gap-4">
                        <span class="font-bold">カテゴリー</span>
                        <div class="flex flex-wrap gap-2">
                            <CategoryIcon v-for="name in item.category_names" :key="name">
                                {{ name }}
                            </CategoryIcon>
                        </div>
                    </div>
                    <div class="mt-8 gap-4 flex items-center flex-wrap ">
                        <span class="font-bold">商品の状態</span>
                        <span>{{ item.condition_name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- フラッシュメッセージモーダル -->
        <FlashMessageModal v-if="message" v-model:show="showFlashMessage" />
    </div>
</template>
