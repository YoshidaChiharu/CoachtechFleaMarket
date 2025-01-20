<script setup>
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { reactive, ref, computed } from 'vue'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import PageTitle from "@/Components/PageTitle.vue"
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from '@/Components/InputError.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FlashMassageModal from "@/Components/FlashMassageModal.vue";

defineOptions({ layout: AdminLayout })

defineProps({
    users: Object,
})

const form = useForm({
    subject: null,
    mainText: null,
});

function submit() {
    form.post(route('admin.mail'));
};

const showFlashMessage = ref(false);
const message = computed(() => usePage().props.flash.message);
const status = computed(() => usePage().props.flash.status);
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
            <div class="pt-6 grow flex flex-col">
                <InputLabel>本文</InputLabel>
                <TextAreaInput v-model="form.mainText" class="grow" required />
                <InputError class="mt-2" :message="form.errors.mainText" />
            </div>
            <div class="pt-6">
                <PrimaryButton>送信する</PrimaryButton><br>
            </div>
        </form>

        <!-- フラッシュメッセージモーダル -->
        <FlashMassageModal v-model:status="status" v-model:show="showFlashMessage">
            {{ message }}
        </FlashMassageModal>

    </div>
</template>
