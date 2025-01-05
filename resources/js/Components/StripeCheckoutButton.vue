<script setup>
import { ref } from 'vue'
import axios from 'axios'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { loadStripe } from '@stripe/stripe-js'

const props = defineProps({
    itemId: Number,
});

const stripeKey = "pk_test_51QBad1Bli9nlS8GV0wskk4eK8OoTM6vLGUuQ7igRELuoB3B4YlbN4ubnUKFPaFeXeTqju80TN1vyXrMS7LWFY4zb00Pd2mQYT5";
const checkout = ref(null);
const open = ref(false);

async function submit() {
    open.value = true;

    const stripe = await loadStripe(stripeKey);

    await axios
        .post('/api/purchase/' + props.itemId)
        .then(async (res) => {
            const tempCheckout = await stripe.initEmbeddedCheckout({
                clientSecret: res.data.client_secret,
            });
            checkout.value = tempCheckout;
            tempCheckout?.mount('#checkout');
        });
}

async function hide() {
    await checkout.value?.destroy();
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