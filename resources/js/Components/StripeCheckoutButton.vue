<script setup>
import { ref, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { loadStripe } from '@stripe/stripe-js'

const props = defineProps({
    itemId: Number,
});

onUnmounted(() => {
    // 決済モーダルのcloseボタンを押さずにブラウザバックした時の為の処理
    checkout.value?.destroy();
});

const stripeKey = "pk_test_51QBad1Bli9nlS8GV0wskk4eK8OoTM6vLGUuQ7igRELuoB3B4YlbN4ubnUKFPaFeXeTqju80TN1vyXrMS7LWFY4zb00Pd2mQYT5";
const checkout = ref(null);
const open = ref(false);

async function submit() {
    const stripe = await loadStripe(stripeKey);

    await axios
        .post('/api/purchase/' + props.itemId)
        .then(async (res) => {
            open.value = true;

            const handleComplete = async function() {
                open.value = false;
                router.post(
                    `/api/purchase/completed/${tempCheckout.embeddedCheckout.checkoutSessionId}`,
                    {
                        preserveScroll: true,
                        onError: (errors) => {console.log( errors ) },
                    }
                );
            }

            const tempCheckout = await stripe.initEmbeddedCheckout({
                clientSecret: res.data.client_secret,
                onComplete: handleComplete
            });
            checkout.value = tempCheckout;
            tempCheckout?.mount('#checkout');
        })
        .catch(function (error) {
            console.log(error.response.data.status);
            console.log(error.response.data.message);
        })
}

async function hide() {
    router.delete(`/api/purchase/${checkout.value.embeddedCheckout.checkoutSessionId}`, {
        preserveScroll: true,
        onError: (errors) => {console.log( errors ) },
    });
    await checkout.value?.destroy();
    checkout.value = null;
    open.value = false;
}
</script>

<template>
    <div>
        <div>
            <PrimaryButton @click="submit()">購入する</PrimaryButton>
        </div>

        <Teleport to="body">
            <div
                v-if="open"
                class="fixed top-0 right-0 bottom-0 left-0 bg-[rgba(0,0,0,.3)]"
            >
                <div class="fixed top-1/2 left-1/2 z-50 bg-white translate-y-[-50%] translate-x-[-50%] drop-shadow-xl py-10 px-20 max-h-[85vh] overflow-auto">
                    <div id="checkout"></div>
                    <button @click="hide()" class="block w-3/4 mx-auto mt-10">Close</button>
                </div>
            </div>
        </Teleport>
    </div>
</template>