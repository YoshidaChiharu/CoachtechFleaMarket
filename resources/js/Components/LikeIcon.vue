<script setup>
import { router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue';

const props = defineProps({
    item: Array,
})

function ChangeLike() {
    if (props.item.is_like) {
        // お気に入り削除
        router.delete(`api/like/${props.item.id}`, {
            preserveScroll: true,
            onError: (errors) => {console.log( errors ) },
        });
    } else {
        // お気に入り登録
        router.post(`api/like/${props.item.id}`, {}, {
            preserveScroll: true,
            onError: (errors) => {console.log( errors ) },
        });
    }
}
</script>

<template>
    <div class="inline-block text-center">
        <button @click="ChangeLike()" class="flex justify-center items-center">
            <img v-if="item.is_like" src="img/like_on.svg" alt="" class="w-8">
            <img v-else src="img/like_off.svg" alt="" class="w-8">
        </button>
        <span class="text-xs font-bold">
            <slot />
        </span>
    </div>
</template>