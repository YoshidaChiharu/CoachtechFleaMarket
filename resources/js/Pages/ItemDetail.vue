<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ItemImage from "@/Components/ItemImage.vue";
import LikeIcon from "@/Components/LikeIcon.vue";
import CommentIcon from "@/Components/CommentIcon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CategoryIcon from "@/Components/CategoryIcon.vue";

const props = defineProps({
    item: Object,
});
</script>

<template>
    <div class="max-w-5xl mx-auto py-10">
        <Head title="商品詳細" />
        <div class="flex w-full">
            <!-- 商品画像(左側) -->
            <div class="w-1/2 p-20">
                <ItemImage :path="item.image_url" />
            </div>
            <!-- 詳細情報(右側) -->
            <div class="w-1/2 p-20">
                <h2 class="text-2xl font-bold">{{ item.name }}</h2>
                <span class="text-sm">{{ item.brand }}</span>
                <div class="text-xl mt-4">
                    {{ item.price }}
                </div>
                <div class="flex gap-8 mt-4 px-2">
                    <LikeIcon :item="item">{{ item.likes_count }}</LikeIcon>
                    <Link :href="'/item/comment/' + item.id">
                        <CommentIcon>{{ item.comments_count }}</CommentIcon>
                    </Link>
                </div>
                <div class="mt-4">
                    <Link :href="route('top')">
                        <PrimaryButton>購入する</PrimaryButton>
                    </Link>
                </div>
                <div class=" mt-8">
                    <h3 class="text-xl font-bold">商品説明</h3>
                    <p class="whitespace-pre">{{ item.description }}</p>
                </div>
                <div class=" mt-12">
                    <h3 class="text-xl font-bold">商品の情報</h3>
                    <div class="mt-8 flex items-center flex-wrap gap-4">
                        <span class="font-bold">カテゴリー</span>
                        <div class="flex flex-wrap gap-2">
                            <CategoryIcon v-for="name in item.category_names">
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
    </div>
</template>
