<script setup>
import { ref, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { loadStripe } from '@stripe/stripe-js'
import ErrorModal from "@/Components/ErrorModal.vue";

const props = defineProps({
    itemId: Number,
    paymentMethodId: Number,
});

const stripeKey = "pk_test_51QBad1Bli9nlS8GV0wskk4eK8OoTM6vLGUuQ7igRELuoB3B4YlbN4ubnUKFPaFeXeTqju80TN1vyXrMS7LWFY4zb00Pd2mQYT5";
const stripeInstance = ref(null);
const stripeElements = ref(null);
const stripePaymentIntentId = ref(null);

const open = ref(false);

const showErrorModal = ref(false);
const errorMessage = ref('');

async function showForm() {
    const stripe = await loadStripe(stripeKey);

    await axios
        .post('/api/purchase/' + props.itemId, {
            paymentMethodId: props.paymentMethodId,
        })
        .then(async (res) => {
            open.value = true;

            const options = {
                clientSecret: res.data.client_secret,
                appearance: {/*...*/},
            };
            const elements = stripe.elements(options);
            const paymentElementOptions = { layout: 'accordion'};
            const paymentElement = await elements.create('payment', paymentElementOptions);
            paymentElement.mount('#checkout');

            // 作成済みStripeインスタンス、エレメントを一時保存
            stripeInstance.value = stripe;
            stripeElements.value = elements;
            stripePaymentIntentId.value = res.data.id;
        })
        .catch(function (error) {
            router.reload();
            showError(error.response.data.message)
        })
}

async function submit() {
    try {
        // 売り切れ確認
        await axios
            .get('/api/purchase/sold_item/' + props.itemId)
            .then(async (res) => {
                if (res.data.isSold) {
                    throw new Error('この商品は既に売却済みです');
                }
            })
            .catch(function (error) {
                hide();
                throw new Error(error.message);
            })

        // sold_itemへ登録
        const soldItemId = ref(null);
        await axios
            .post('/api/purchase/sold_item/' + props.itemId, {
                paymentMethodId: props.paymentMethodId,
                paymentIntentId: stripePaymentIntentId.value,
            })
            .then(async (res) => {
                soldItemId.value = res.data.soldItemId;
            })
            .catch(function (error) {
                throw new Error('エラーが発生しました');
            })

        // PaymentIntentの確認
        const stripe = stripeInstance.value;
        const elements = stripeElements.value;
        const {error} = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: route('purchase.complete', props.itemId),
            },
        });

        // PaymentIntent確認失敗時のエラー処理
        if (error) {
            // sold_itemレコード削除
            await axios
                .delete('/api/purchase/sold_item/' +  soldItemId.value)
                .catch(function (error) {
                    throw new Error('エラーが発生しました');
                })

            throw new Error(error.message);
        }

    } catch (e) {
        showError(e.message);
    }
}

async function hide() {
    open.value = false;
}

async function showError(message) {
    errorMessage.value = message;
    showErrorModal.value = true;
}
</script>

<template>
    <div>
        <div>
            <PrimaryButton @click="showForm()">購入する</PrimaryButton>
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

        <ErrorModal v-model="showErrorModal">{{ errorMessage }}</ErrorModal>

    </div>
</template>