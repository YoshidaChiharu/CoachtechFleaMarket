<script setup>
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import ItemImage from "@/Components/ItemImage.vue";
import LikeIcon from "@/Components/LikeIcon.vue";
import CommentIcon from "@/Components/CommentIcon.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import SpeechBalloon from "@/Components/SpeechBalloon.vue";
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    item: Object,
});
const price = `￥${props.item.price.toLocaleString()}`;

const form = useForm({
    comment: null,
});

function submit() {
    form.post('/item/comment/' + props.item.id, {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <div class="max-w-5xl mx-auto py-10">
        <Head title="コメント" />
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
                    <form @submit.prevent="submit" >
                        <InputLabel>商品へのコメント</InputLabel>
                        <!-- <InputError class="mt-2" :message="form.errors.comment" /> -->
                        <TextAreaInput v-model="form.comment" required />
                        <InputError class="mb-2" :message="form.errors.comment" />
                        <PrimaryButton
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            コメントを送信する
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
