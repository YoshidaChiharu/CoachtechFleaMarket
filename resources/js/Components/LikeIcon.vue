<script setup>
import { router } from '@inertiajs/vue3'
import { onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    item: Array,
})

const isLike = ref(false);

onMounted(() => {
    isLike.value = props.item.is_like;
})

function ChangeLike() {
    if (isLike.value) {
        // お気に入り削除
        router.delete(`api/like/${props.item.id}`, {
            preserveScroll: true,
            onSuccess: () => { isLike.value = !isLike.value },
            onError: (errors) => {console.log( errors ) },
            onFinish: visit => {console.log('お気に入り削除完了 item_id:'+props.item.id)},
        });
        // アイコン画像切り替え
        isLike.value = !isLike.value;
    } else {
        // お気に入り登録
        // router.post(`api/like/${props.item.id}`, {}, {
        router.post(`api/like/100`, {}, {
            preserveScroll: true,
            onSuccess: () => { isLike.value = !isLike.value },
            onError: (errors) => {console.log( errors ) },
            onFinish: visit => {console.log('お気に入り登録完了 item_id:'+props.item.id)},
        });
        // アイコン画像切り替え
        
    }
}
</script>

<template>
    <div class="inline-block text-center">
        <button @click="ChangeLike()" class="flex justify-center items-center">
            <img v-if="isLike" src="img/like_on.svg" alt="" class="w-8">
            <img v-else src="img/like_off.svg" alt="" class="w-8">
        </button>
        <span class="text-xs font-bold">
            <slot />
        </span>
    </div>
</template>