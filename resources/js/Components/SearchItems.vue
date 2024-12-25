<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'

const searchWord = ref('')

function search() {
    router.get('/', { searchWord: searchWord.value })
}
</script>


<template>
    <input
        type="text"
        class="py-1 rounded"
        placeholder="なにをお探しですか？"
        v-model="searchWord"
        @change="search()"
    >
</template>