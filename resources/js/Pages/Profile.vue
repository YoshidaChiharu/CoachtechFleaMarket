<script setup>
import InputError from '@/Components/InputError.vue';
import TextInput from "../Components/TextInput.vue";
import InputLabel from "../Components/InputLabel.vue";
import ImageInput from "../Components/ImageInput.vue";
import PrimaryButton from "../Components/PrimaryButton.vue";
import PageTitle from "../Components/PageTitle.vue"
import UserIcon from "../Components/UserIcon.vue";
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue'

const props = defineProps({
    profile: Object,
})

const form = useForm({
    image: null,
    name: props.profile.name,
    postcode: props.profile.postcode,
    address: props.profile.address,
    building: props.profile.building,
});

const previewUrl = ref(props.profile.image_url);

function submit() {
    form.post(route('mypage.profile'));
};
</script>

<template>
    <div class="max-w-xl mx-auto px-6 pb-10">
        <Head title="Profile" />
        <PageTitle>プロフィール設定</PageTitle>
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="flex items-center gap-10 max-sm:gap-4">
                <div class="w-32 max-sm:w-24">
                    <UserIcon v-if="previewUrl" :path="previewUrl" />
                    <UserIcon v-else :path="profile.image_url" />
                </div>
                <ImageInput v-model:previewUrl="previewUrl" v-model:file="form.image">
                    画像を選択する
                </ImageInput>
            </div>
            <InputError class="mt-2" :message="form.errors.image" />
            <div class="pt-6">
                <InputLabel>ユーザー名</InputLabel>
                <TextInput v-model="form.name" required />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div class="pt-10">
                <InputLabel>郵便番号</InputLabel>
                <TextInput v-model="form.postcode" inputmode="numeric" placeholder="1234567 ※ハイフンなしの数字7桁" />
                <InputError class="mt-2" :message="form.errors.postcode" />
            </div>
            <div class="pt-10">
                <InputLabel>住所</InputLabel>
                <TextInput v-model="form.address" placeholder="東京都○○区○○町 0-00-00" />
                <InputError class="mt-2" :message="form.errors.address" />
            </div>
            <div class="pt-10">
                <InputLabel>建物名</InputLabel>
                <TextInput v-model="form.building" placeholder="○○ビル 101号室" />
                <InputError class="mt-2" :message="form.errors.building" />
            </div>
            <div class="pt-16">
                <PrimaryButton>更新する</PrimaryButton><br>
            </div>
        </form>
    </div>
</template>