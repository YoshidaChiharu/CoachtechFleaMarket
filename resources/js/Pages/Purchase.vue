<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue'
import ItemImage from "@/Components/ItemImage.vue";
import StripeCheckoutButton from "@/Components/StripeCheckoutButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    item: Object,
    paymentMethods: Object,
    shipAddress: Object,
});

const open = ref(false);
const selectedId = ref(1);
</script>

<template>
    <div class="max-w-6xl mx-auto py-10">
        <Head title="購入ページ" />
        <div class="flex w-full">
            <!-- 商品情報／支払い方法／配送先(左側) -->
            <div class="w-3/5 p-10">
                <!-- 商品情報表示 -->
                <div class="flex gap-10 items-center">
                    <div class="w-32 p-4">
                        <ItemImage :item="item" />
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ item.name }}</h2>
                        <span class="text-sm">{{ item.brand }}</span>
                        <div class="text-xl mt-8">
                            {{ item.price }}
                        </div>
                    </div>
                </div>
                <!-- 支払い方法設定 -->
                <div class="flex justify-between mt-2">
                    <span class="font-bold text-lg">支払い方法</span>
                    <button class="text-[#2085D2]" @click="open = true">変更する</button>
                </div>
                <!-- 配送先設定 & 登録住所表示 -->
                <div class="flex justify-between mt-44">
                    <span class="font-bold text-lg">配送先</span>
                    <Link :href="route('top')" class="text-[#2085D2]">変更する</Link>
                </div>
                <div v-if="shipAddress.postcode && shipAddress.address" class="py-5 px-10">
                    <table class="text-left">
                        <tbody>
                            <tr>
                                <th class="font-normal p-1">郵便番号</th>
                                <td>: {{ shipAddress.postcode }}</td>
                            </tr>
                            <tr>
                                <th class="font-normal p-1">住所</th>
                                <td>: {{ shipAddress.address }}</td>
                            </tr>
                            <tr>
                                <th class="font-normal p-1">建物名</th>
                                <td>: {{ shipAddress.building }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 支払い情報／購入ボタン(右側) -->
            <div class="w-2/5 p-10">
                <div class="p-10 border border-gray-500">
                    <table class="w-full text-left">
                        <tbody>
                            <tr>
                                <th class="w-1/2 font-normal py-4">商品代金</th>
                                <td>{{ item.price }}</td>
                            </tr>
                            <tr>
                                <th class="w-1/2 font-normal py-4"><br></th>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="w-1/2 font-normal py-4">支払い金額</th>
                                <td>{{ item.price }}</td>
                            </tr>
                            <tr>
                                <th class="w-1/2 font-normal py-4">支払い方法</th>
                                <td>{{ paymentMethods[selectedId] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-14">
                    <PrimaryButton v-if="item.is_sold === true" disabled>
                        SOLD OUT
                    </PrimaryButton>
                    <StripeCheckoutButton v-else :itemId="item.id" :paymentMethodId="selectedId">
                        購入する
                    </StripeCheckoutButton>
                </div>
            </div>
        </div>
        <!-- 支払い方法変更モーダル -->
        <Teleport to="body">
            <div
                v-if="open"
                class="fixed top-0 right-0 bottom-0 left-0 bg-[rgba(0,0,0,.3)]"
            >
                <div class="fixed top-1/2 left-1/2 z-50 bg-white translate-y-[-50%] translate-x-[-50%] drop-shadow-xl py-10 px-20 max-h-[85vh] overflow-auto">
                    <div v-for="(name, id) in paymentMethods" :key="id" class="flex items-center mb-2">
                        <input type="radio" v-model="selectedId" :value="id" :id="name">
                        <label :for="name" class="ml-2 font-bold">{{ name }}</label>
                    </div>

                    <button @click="open = false" class="block w-3/4 mx-auto mt-10">Close</button>
                </div>
            </div>
        </Teleport>
    </div>
</template>
