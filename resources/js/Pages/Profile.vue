<script setup>
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
    <div class="max-w-xl mx-auto">
        <Head title="Profile" />
        <PageTitle>プロフィール設定</PageTitle>
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="flex items-center gap-10">
                <div class="w-32">
                    <UserIcon v-if="previewUrl" :path="previewUrl" />
                    <UserIcon v-else :path="profile.image_url" />
                </div>
                <ImageInput v-model:previewUrl="previewUrl" v-model:file="form.image">
                    画像を選択する
                </ImageInput>
            </div>
            <div class="pt-6">
                <InputLabel>ユーザー名</InputLabel>
                <TextInput v-model="form.name" required />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div class="pt-10">
                <InputLabel>郵便番号</InputLabel>
                <TextInput v-model="form.postcode" />
                <InputError class="mt-2" :message="form.errors.postcode" />
            </div>
            <div class="pt-10">
                <InputLabel>住所</InputLabel>
                <TextInput v-model="form.address" />
                <InputError class="mt-2" :message="form.errors.address" />
            </div>
            <div class="pt-10">
                <InputLabel>建物名</InputLabel>
                <TextInput v-model="form.building" />
                <InputError class="mt-2" :message="form.errors.building" />
            </div>
            <div class="pt-16">
                <PrimaryButton>更新する</PrimaryButton><br>
            </div>
        </form>
    </div>
</template>