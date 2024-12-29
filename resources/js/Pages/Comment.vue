<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ItemImage from "@/Components/ItemImage.vue";
import LikeIcon from "@/Components/LikeIcon.vue";
import CommentIcon from "@/Components/CommentIcon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import SpeechBalloon from "@/Components/SpeechBalloon.vue";

import { onMounted } from 'vue'

const props = defineProps({
    item: Object,
});

onMounted(() => {
    console.log(props.item);
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
                    <CommentIcon>{{ item.comments_count }}</CommentIcon>
                </div>
                <div class="mt-14">
                    <SpeechBalloon
                        v-for="comment in item.comments"
                        :key="comment.id"
                        :comment="comment"
                    />
                    <p v-if="item.comments_count == 0" class="font-bold text-gray-400">
                        コメントなし
                    </p>
                </div>
                <div class="mt-14">
                    <form action="">
                        <InputLabel>商品へのコメント</InputLabel>
                        <TextAreaInput></TextAreaInput>
                        <PrimaryButton>コメントを送信する</PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
