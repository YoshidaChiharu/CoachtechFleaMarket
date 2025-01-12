<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ImageInput from "@/Components/ImageInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PageTitle from "@/Components/PageTitle.vue"
import SectionTitle from "@/Components/SectionTitle.vue";

const props = defineProps({
    categories: Object,
    conditions: Object,
});

const form = useForm({
    image: null,
    name: '',
    brand: '',
    price: 0,
    description: '',
    condition_id: 0,
    categories: [],
});

const previewUrl = ref('');

function submit() {
    form
    // .transform((data) => {
    //     console.log(data);
    // })
    .post(route('sell'));
};
</script>

<template>
    <div class="max-w-xl mx-auto mb-20">
        <Head title="出品ページ" />
        <PageTitle>商品の出品</PageTitle>
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="max-w-2xl">
                <InputLabel>商品画像</InputLabel>
                <img v-if="previewUrl" :src="previewUrl" alt="no image" class="w-full">
                <div v-else class="w-full h-40 border border-black border-dashed flex justify-center items-center">
                    <ImageInput v-model:previewUrl="previewUrl" v-model:file="form.image">
                        画像を選択する
                    </ImageInput>
                </div>
            </div>
            <SectionTitle>商品の詳細</SectionTitle>
            <div class="pt-6">
                <InputLabel>カテゴリー</InputLabel>
                <div class="flex flex-wrap gap-x-6 p-4 border rounded border-gray-600 shadow-sm">
                    <div v-for="(name, id) in categories" :key="id" class="flex items-center gap-1">
                        <input type="checkbox" :id="name" :value="id" v-model="form.categories">
                        <label :for="name">{{ name }}</label>
                    </div>
                </div>
                <InputError class="mt-2" :message="form.errors.categories" />
            </div>
            <div class="pt-6">
                <InputLabel>商品の状態</InputLabel>
                <select v-model="form.condition_id" class="w-full rounded">
                    <option v-for="(name, id) in conditions" :key="id" :value="id">
                        {{ name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.condition_id" />
            </div>
            <SectionTitle>商品名と説明</SectionTitle>
            <InputError class="mt-2" :message="form.errors.image" />
            <div class="pt-6">
                <InputLabel>商品名</InputLabel>
                <TextInput v-model="form.name" required />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div class="pt-6">
                <InputLabel>ブランド</InputLabel>
                <TextInput v-model="form.brand" />
                <InputError class="mt-2" :message="form.errors.brand" />
            </div>
            <div class="pt-6">
                <InputLabel>商品の説明</InputLabel>
                <TextAreaInput v-model="form.description" required />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>
            <SectionTitle>販売価格</SectionTitle>
            <div class="pt-6">
                <InputLabel>販売価格</InputLabel>
                <TextInput type="number" v-model="form.price" required />
                <InputError class="mt-2" :message="form.errors.price" />
            </div>
            <div class="pt-16">
                <PrimaryButton>出品する</PrimaryButton><br>
            </div>
        </form>
    </div>
</template>