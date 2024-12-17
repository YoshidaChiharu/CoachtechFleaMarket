<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import PageTitle from "@/Components/PageTitle.vue"
import { Head, Link, useForm } from '@inertiajs/vue3';

defineOptions({ layout: GuestLayout })

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="max-w-xl mx-auto">
        <Head title="会員登録" />

        <PageTitle>会員登録</PageTitle>
        <form @submit.prevent="submit">
            <div class="mt-6">
                <InputLabel for="email" value="メールアドレス" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1"
                    v-model="form.email"
                    required
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-10">
                <InputLabel for="password" value="パスワード" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1"
                    v-model="form.password"
                    required
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-16">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    登録する
                </PrimaryButton>

                <div class="mt-6 text-center">
                    <Link
                        :href="route('login')"
                        class="text-sm text-blue-700 tracking-widest hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        ログインはこちら
                    </Link>
                </div>
            </div>
        </form>
    </div>
</template>
