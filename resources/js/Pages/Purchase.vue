<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ItemImage from "@/Components/ItemImage.vue";
import StripeCheckoutButton from "@/Components/StripeCheckoutButton.vue";

const props = defineProps({
    item: Object,
});
</script>

<template>
    <div class="max-w-6xl mx-auto py-10">
        <Head title="購入ページ" />
        <div class="flex w-full">
            <!-- 商品情報／支払い方法／配送先(左側) -->
            <div class="w-3/5 p-10">
                <div class="flex gap-10 items-center">
                    <div class="w-32 p-4">
                        <ItemImage :path="item.image_url" />
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ item.name }}</h2>
                        <span class="text-sm">{{ item.brand }}</span>
                        <div class="text-xl mt-8">
                            {{ item.price }}
                        </div>
                    </div>
                </div>
                <div class="flex justify-between mt-2">
                    <span class="font-bold text-lg">支払い方法</span>
                    <button class="text-[#2085D2]">変更する</button>
                </div>
                <div class="flex justify-between mt-44">
                    <span class="font-bold text-lg">配送先</span>
                    <Link :href="route('top')" class="text-[#2085D2]">変更する</Link>
                </div>
            </div>
            <!-- 支払い情報／購入ボタン(右側) -->
            <div class="w-2/5 p-10">
                <div class="p-10 border border-gray-500">
                    <table class="w-full text-left">
                        <tbody>
                            <tr>
                                <th class="w-3/5 font-normal py-4">商品代金</th>
                                <td>{{ item.price }}</td>
                            </tr>
                            <tr>
                                <th class="w-3/5 font-normal py-4"><br></th>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="w-3/5 font-normal py-4">支払い金額</th>
                                <td>{{ item.price }}</td>
                            </tr>
                            <tr>
                                <th class="w-3/5 font-normal py-4">支払い方法</th>
                                <td>コンビニ払い</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-14">
                    <StripeCheckoutButton :itemId="item.id">購入する</StripeCheckoutButton>
                </div>
            </div>
        </div>
    </div>
</template>
