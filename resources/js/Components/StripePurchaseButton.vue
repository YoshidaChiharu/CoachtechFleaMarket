<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { loadStripe } from '@stripe/stripe-js'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FlashMassageModal from "@/Components/FlashMassageModal.vue";

const props = defineProps({
    item: Object,
    paymentMethodId: Number,
});

const stripeKey = "pk_test_51QBad1Bli9nlS8GV0wskk4eK8OoTM6vLGUuQ7igRELuoB3B4YlbN4ubnUKFPaFeXeTqju80TN1vyXrMS7LWFY4zb00Pd2mQYT5";
const stripeInstance = ref(null);
const stripeElements = ref(null);

const open = ref(false);

const showFlashMessage = ref(false);
const message = computed(() => usePage().props.flash.message);
const status = computed(() => usePage().props.flash.status);

async function showForm() {
    open.value = true;

    const stripe = await loadStripe(stripeKey);

    const options = {
        mode: 'payment',
        amount: props.item.price,
        currency: 'jpy',
        paymentMethodCreation: 'manual',
        // Fully customizable with appearance API.
        appearance: {/*...*/},
    };
    const elements = stripe.elements(options);
    const paymentElementOptions = {
        layout: 'tabs',
        paymentMethodOrder: ['konbini'],
    };
    const paymentElement = await elements.create('payment', paymentElementOptions);
    paymentElement.mount('#checkout');

    // 作成済みStripeインスタンス、エレメントを一時保存
    stripeInstance.value = stripe;
    stripeElements.value = elements;
}

async function submit() {
    const stripe = stripeInstance.value;
    const elements = stripeElements.value;

    // stripeElementsのsubmit処理
    const {error: submitError} = await elements.submit();
    // エラー処理
    if (submitError) {
        console.log(error);
        return;
    }

    // ConfirmationTokenの作成
    const {error, confirmationToken} = await stripe.createConfirmationToken({
        elements,
    });
    // エラー処理
    if (error) {
        console.log(error);
        return;
    }

    console.log(confirmationToken);

    // ConfirmationTokenをサーバーへ送信
    router.post(route('purchase', props.item.id), { confirmationTokenId: confirmationToken.id }, {
        onError: (errors) => {console.log( errors ) },
        onFinish: visit => {
            open.value = false;
            showFlashMessage.value = true;
        },
    });
}

async function hide() {
    open.value = false;
}
</script>

<template>
    <div>
        <div>
            <PrimaryButton @click="showForm()">購入する（サーバー決済対応）</PrimaryButton>
        </div>

        <Teleport to="body">
            <div
                v-if="open"
                class="fixed top-0 right-0 bottom-0 left-0 bg-[rgba(0,0,0,.3)]"
            >
                <div class="fixed top-1/2 left-1/2 z-50 bg-white translate-y-[-50%] translate-x-[-50%] drop-shadow-xl py-10 px-20 max-sm:px-6 max-h-[85vh] overflow-auto w-max">
                    <form @submit.prevent="submit()">
                        <div id="checkout"></div>
                        <PrimaryButton class="mt-10">支払う</PrimaryButton>
                    </form>
                    <button @click="hide()" class="block w-3/4 mx-auto mt-10">Close</button>
                </div>
            </div>
        </Teleport>

        <!-- フラッシュメッセージモーダル -->
        <FlashMassageModal v-model:status="status" v-model:show="showFlashMessage">
            {{ message }}
        </FlashMassageModal>

    </div>
</template>