<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue'
import TopPageLayoutVue from '@/Layouts/TopPageLayout.vue';
import TextInput from "../Components/TextInput.vue";
import TextAreaInput from "../Components/TextAreaInput.vue";
import InputLabel from "../Components/InputLabel.vue";
import PrimaryButton from "../Components/PrimaryButton.vue";
import SecondaryButton from "../Components/SecondaryButton.vue";
import ImageInput from "../Components/ImageInput.vue";
import PageTitle from "../Components/PageTitle.vue"
import LikeIcon from "../Components/LikeIcon.vue";
import CommentIcon from "../Components/CommentIcon.vue";
import UserIcon from "../Components/UserIcon.vue";
import ItemImage from "../Components/ItemImage.vue";
import SearchItems from "../Components/SearchItems.vue";

defineOptions({ layout: TopPageLayoutVue })

const input = ref('')
const textArea = ref('')
const previewUrl = ref('')
</script>

<template>
    <div class="max-w-2xl mx-auto">
        <Head title="Top Page" />
        <PageTitle>Top Page</PageTitle>
        <InputLabel>お名前</InputLabel>
        <TextInput v-model="input" name="name" />
        <InputLabel>詳細</InputLabel>
        <TextAreaInput v-model="textArea" name="description" />
        <PrimaryButton>Submit</PrimaryButton><br>
        <SecondaryButton>Button</SecondaryButton><br>

        <div class="max-w-2xl p-10">
            <img v-if="previewUrl" :src="previewUrl" alt="no image" class="w-full">
            <div v-else class="w-full h-40 border border-black border-dashed flex justify-center items-center">
                <ImageInput v-model="previewUrl">画像を選択する</ImageInput>
            </div>
        </div>
        <LikeIcon>3</LikeIcon>
        <CommentIcon>14</CommentIcon>
        <div class="w-20 p-2">
            <UserIcon path="img/dummy_user_icon.png" />
        </div>
        <div class="w-80 p-2">
            <ItemImage path="img/logo.svg" />
        </div>
    </div>
</template>
