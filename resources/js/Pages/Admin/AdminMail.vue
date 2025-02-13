<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { reactive, ref } from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageTitle from "@/Components/PageTitle.vue"
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from '@/Components/InputError.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FlashMessageModal from "@/Components/FlashMessageModal.vue";

defineOptions({ layout: AdminLayout })

defineProps({
    users: Object,
})

const form = useForm({
    subject: null,
    mainText: null,
});

const showFlashMessage = ref(false);

function submit() {
    form.post(route('admin.mail'));
    showFlashMessage.value = true;
};
</script>

<template>
    <div class="max-w-3xl mx-auto p-5 pb-20 h-full">
        <Head title="Adminメール送信ページ" />

        <!-- メール作成フォーム -->
        <form @submit.prevent="submit" class="h-full flex flex-col">
            <PageTitle>Adminメール送信</PageTitle>

            <div>
                <InputLabel>件名</InputLabel>
                <TextInput v-model="form.subject" rows="10" required />
                <InputError class="mt-2" :message="form.errors.subject" />
            </div>
            <div class="mt-6 grow flex flex-col">
                <InputLabel>本文</InputLabel>
                <p class="my-2 text-lg">○○様</p>
                <TextAreaInput v-model="form.mainText" class="grow" required />
                <InputError class="mt-2" :message="form.errors.mainText" />
                <div class="my-2">
                    <p>※本メールは送信専用です</p>
                    <p class="pt-1 border-t border-black">配信元&nbsp;:&nbsp;COACHTECHフリーマーケット</p>
                </div>
            </div>
            <div class="pt-8">
                <PrimaryButton>送信する</PrimaryButton><br>
            </div>
        </form>

        <!-- フラッシュメッセージモーダル -->
        <FlashMessageModal v-model:show="showFlashMessage" />

    </div>
</template>
