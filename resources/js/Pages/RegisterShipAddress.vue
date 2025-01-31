<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue'
import InputError from '@/Components/InputError.vue';
import TextInput from "../Components/TextInput.vue";
import InputLabel from "../Components/InputLabel.vue";
import PrimaryButton from "../Components/PrimaryButton.vue";
import PageTitle from "../Components/PageTitle.vue"

const props = defineProps({
    itemId: Number,
})

const form = useForm({
    name: null,
    postcode: null,
    address: null,
    building: null,
});

function submit() {
    form.post(route('purchase.address.register', {'item_id':props.itemId}));
};
</script>

<template>
    <div class="max-w-xl mx-auto px-6">
        <Head title="Profile" />
        <PageTitle>住所の登録</PageTitle>
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div>
                <InputLabel>宛名</InputLabel>
                <TextInput v-model="form.name" />
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
                <PrimaryButton>登録する</PrimaryButton><br>
            </div>
        </form>
    </div>
</template>