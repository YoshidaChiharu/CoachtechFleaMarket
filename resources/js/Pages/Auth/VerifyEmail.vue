<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <div class="max-w-lg mx-auto mt-20 px-6">
        <Head title="Email Verification" />

        <div class="p-6 border rounded">
            <div class="mb-4 text-lg font-bold text-gray-600">
                ご登録頂きありがとうございます。<br>
                メールアドレス確認のリンクを送付しましたので、メールに記載のボタンをクリックして、会員登録を完了してください。
            </div>

            <div class="mb-4 text-red-500">
                <span>＜注意事項＞</span><br>
                ボタン押下時、別のブラウザやメールアプリ等でページ表示される場合、認証処理が完了出来ません。その場合はメール最下部のURLをコピーし、このブラウザに直接貼り付けて認証を行って下さい。
            </div>

            <div
                class="mb-4 text-green-600"
                v-if="verificationLinkSent"
            >
                メールアドレス確認リンクを再送しました<br>
                メールが届いていない場合はユーザ登録時に入力頂いたメールアドレスを再度ご確認下さい
            </div>

            <form @submit.prevent="submit">
                <div class="mt-8 flex items-center justify-around flex-wrap gap-y-8">
                    <PrimaryButton
                        class="w-fit"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        メールアドレス確認リンクの再送
                    </PrimaryButton>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm font-bold text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >ログアウト</Link
                    >
                </div>
            </form>
        </div>
    </div>
</template>
