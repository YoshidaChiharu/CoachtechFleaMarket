<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue'
import InputError from '@/Components/InputError.vue';
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PageTitle from "@/Components/PageTitle.vue"

const props = defineProps({
    address: Object,
    itemId: Number,
})

const form = useForm({
    name: props.address.name,
    postcode: props.address.postcode,
    address: props.address.address,
    building: props.address.building,
});

function submit() {
    form.post(route('purchase.address.edit', {'address_id':props.address.id, 'item_id':props.itemId}));
};
</script>

<template>
    <div class="max-w-xl mx-auto px-6">
        <Head title="住所変更ページ" />
        <PageTitle>住所の変更</PageTitle>
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
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    更新する
                </PrimaryButton><br>
            </div>
        </form>
    </div>
</template>