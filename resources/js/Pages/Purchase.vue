<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed, reactive } from 'vue'
import ItemImage from "@/Components/ItemImage.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PurchaseButton from "@/Components/PurchaseButton.vue";
import FlashMessageModal from "@/Components/FlashMessageModal.vue";

const props = defineProps({
    item: Object,
    paymentMethods: Object,
    addresses: Object,
});
const price = `￥${props.item.price.toLocaleString()}`;

const paymentMethodModal = reactive({
    open: false,
    selectedId: 1,
});
const shipAddressModal = reactive({
    open: route().params.modalOpen ?? false,
    selectedId: 0,
});

const showFlashMessage = ref(true);
const message = computed(() => usePage().props.flash.message);
const status = computed(() => usePage().props.flash.status);

function deleteAddress(addressId) {
    router.delete(route('purchase.address.edit', addressId), {
        data: {'item_id':props.item.id},
    });
    shipAddressModal.selectedId = 0;
}
</script>

<template>
    <div class="max-w-6xl mx-auto py-10">
        <Head title="購入ページ" />
        <div class="flex w-full max-md:flex-col">
            <!-- 商品情報／支払い方法／配送先(左側) -->
            <div class="w-full sm:w-4/5 md:w-3/5 mx-auto p-6 lg:p-10">
                <!-- 商品情報表示 -->
                <div class="flex gap-10 items-center">
                    <div class="w-32 p-4">
                        <ItemImage :item="item" />
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ item.name }}</h2>
                        <span class="text-sm">{{ item.brand }}</span>
                        <div class="text-xl mt-8">
                            {{ price }}
                        </div>
                    </div>
                </div>
                <!-- 支払い方法設定 -->
                <div class="flex justify-between mt-2">
                    <span class="font-bold text-lg">支払い方法</span>
                    <button class="text-[#2085D2]" @click="paymentMethodModal.open = true">変更する</button>
                </div>
                <!-- 配送先設定 & 登録住所表示 -->
                <div class="flex justify-between mt-44 max-md:mt-12">
                    <span class="font-bold text-lg">配送先</span>
                    <button class="text-[#2085D2]" @click="shipAddressModal.open = true">変更する</button>
                </div>
                <div
                    v-if="addresses[shipAddressModal.selectedId].postcode && addresses[shipAddressModal.selectedId].address"
                    class="py-5 lg:px-6"
                >
                    {{ addresses[shipAddressModal.selectedId].name }}<br>
                    〒{{ addresses[shipAddressModal.selectedId].postcode }}<br>
                    {{ addresses[shipAddressModal.selectedId].address }}
                    {{ addresses[shipAddressModal.selectedId].building }}
                </div>
            </div>
            <!-- 支払い情報／購入ボタン(右側) -->
            <div class="w-full sm:w-4/5 md:w-2/5 mx-auto p-6 lg:p-10">
                <div class="p-6 lg:p-10 border border-gray-500">
                    <table class="w-full text-left">
                        <tbody>
                            <tr>
                                <th class="w-1/2 font-normal py-4">商品代金</th>
                                <td>{{ price }}</td>
                            </tr>
                            <tr>
                                <th class="w-1/2 font-normal py-4"><br></th>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="w-1/2 font-normal py-4">支払い金額</th>
                                <td>{{ price }}</td>
                            </tr>
                            <tr>
                                <th class="w-1/2 font-normal py-4">支払い方法</th>
                                <td>{{ paymentMethods[paymentMethodModal.selectedId] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-14">
                    <PrimaryButton v-if="item.is_sold === true" disabled>
                        SOLD OUT
                    </PrimaryButton>
                    <PurchaseButton v-else :itemId="item.id" :paymentMethodId="paymentMethodModal.selectedId" :addressId="addresses[shipAddressModal.selectedId].id" class="mt-5" />
                </div>
            </div>
        </div>
        <!-- 支払い方法変更モーダル -->
        <Teleport to="body">
            <div
                v-if="paymentMethodModal.open"
                class="fixed top-0 right-0 bottom-0 left-0 bg-[rgba(0,0,0,.3)]"
            >
                <div class="fixed top-1/2 left-1/2 z-50 bg-white translate-y-[-50%] translate-x-[-50%] drop-shadow-xl py-10 px-20 max-sm:px-10 max-h-[85vh] overflow-auto w-max">
                    <div v-for="(name, id) in paymentMethods" :key="id" class="flex items-center mb-2">
                        <input type="radio" v-model="paymentMethodModal.selectedId" :value="id" :id="name">
                        <label :for="name" class="ml-2 font-bold">{{ name }}</label>
                    </div>

                    <button @click="paymentMethodModal.open = false" class="block w-3/4 mx-auto mt-10">Close</button>
                </div>
            </div>
        </Teleport>

        <!-- 配送先変更モーダル -->
        <Teleport to="body">
            <div
                v-if="shipAddressModal.open"
                class="fixed top-0 right-0 bottom-0 left-0 bg-[rgba(0,0,0,.3)]"
            >
                <div class="fixed top-1/2 left-1/2 z-50 bg-white translate-y-[-50%] translate-x-[-50%] drop-shadow-xl py-10 px-20 max-sm:px-10 max-w-[90vw] max-h-[85vh] overflow-auto w-max">
                    <div v-for="(address, id) in addresses" :key="id" class="flex items-center gap-4 mb-4 rounded py-2 px-4 bg-gray-100">
                        <input type="radio" v-model="shipAddressModal.selectedId" :value="id">
                        <div class="px-2 font-bold grow">
                            <div class="break-all">{{ address.name }}<br></div>
                            <span v-if="address.postcode">〒{{ address.postcode }}<br></span>
                            <span v-if="address.address" class="break-all">{{ address.address }}</span>
                            <span v-if="address.building" class="ml-4 break-all">{{ address.building }}<br></span>
                            <!-- 住所登録が無い場合 -->
                            <span v-if="!address.postcode && !address.address">
                                <span class="text-red-500">※住所が登録されていません</span><br>
                            </span>
                            <div class="mt-2">
                                <div v-if="address.id == 0">
                                    <Link :href="route('mypage.profile')">
                                        <img src="/img/edit_icon.svg" class="w-5">
                                    </Link>
                                </div>
                                <div v-else class="flex items-center gap-4">
                                    <Link :href="route('purchase.address.edit', {'address_id':address.id, 'item_id':item.id})"><img src="/img/edit_icon.svg" class="w-5"></Link>
                                    <button @click="deleteAddress(address.id)"><img src="/img/delete_icon.svg" class="w-5"></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <Link :href="route('purchase.address.register', {'item_id':item.id})" class="text-[#2085D2]">＋ 新しい住所を登録する</Link>

                    <button @click="shipAddressModal.open = false" class="block w-3/4 mx-auto mt-10">Close</button>
                </div>
            </div>
        </Teleport>

        <!-- フラッシュメッセージモーダル -->
        <FlashMessageModal v-if="status" v-model:status="status" v-model:show="showFlashMessage">
            {{ message }}
        </FlashMessageModal>
    </div>
</template>
