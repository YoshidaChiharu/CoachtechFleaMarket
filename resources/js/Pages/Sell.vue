<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import PriceInput from "@/Components/PriceInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import CheckboxInput from "@/Components/CheckboxInput.vue";
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
    price: null,
    description: '',
    condition_id: 0,
    categories: [],
});

const previewUrl = ref('');

function submit() {
    form.post(route('sell'));
};

function imageReset() {
    form.image = null;
    previewUrl.value = null;
}
</script>

<template>
    <div class="max-w-xl mx-auto mb-20">
        <Head title="出品ページ" />
        <PageTitle>商品の出品</PageTitle>
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="max-w-2xl">
                <InputLabel>商品画像</InputLabel>
                <div v-if="previewUrl">
                    <img :src="previewUrl" alt="no image" class="w-full">
                    <button @click="imageReset()" class="w-full text-center rounded-md border border-red-500 bg-white mt-2 px-8 font-bold tracking-widest text-red-500 shadow-sm transition duration-150 ease-in-out hover:bg-red-500 hover:text-white disabled:opacity-25">
                        画像をリセットする
                    </button>
                </div>
                <div v-else class="w-full h-40 border border-black border-dashed flex justify-center items-center">
                    <ImageInput v-model:previewUrl="previewUrl" v-model:file="form.image">
                        画像を選択する
                    </ImageInput>
                </div>
                <InputError class="mt-2" :message="form.errors.image" />
            </div>
            <SectionTitle>商品の詳細</SectionTitle>
            <div class="pt-6">
                <InputLabel>カテゴリー</InputLabel>
                <CheckboxInput :options="categories" v-model="form.categories" />
                <InputError class="mt-2" :message="form.errors.categories" />
            </div>
            <div class="pt-6">
                <InputLabel>商品の状態</InputLabel>
                <SelectInput :options="conditions" v-model="form.condition_id" />
                <InputError class="mt-2" :message="form.errors.condition_id" />
            </div>
            <SectionTitle>商品名と説明</SectionTitle>
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
                <PriceInput v-model="form.price" required />
                <InputError class="mt-2" :message="form.errors.price" />
            </div>
            <div class="pt-16">
                <PrimaryButton>出品する</PrimaryButton><br>
            </div>
        </form>
    </div>
</template>