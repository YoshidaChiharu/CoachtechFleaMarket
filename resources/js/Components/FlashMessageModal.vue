<script setup>
import { ref, onUpdated, onMounted, computed } from 'vue'
import { usePage } from '@inertiajs/vue3';

const show = defineModel('show', {
    type: Boolean,
    required: true,
    default: false,
});

const message = computed(() => usePage().props.flash.message);
const status = computed(() => usePage().props.flash.status);
const timeId = ref(0);

onMounted(() => {
    if (show.value === true) {
        timeId.value = setTimeout(() => {
            show.value = false;
        }, 3000);
    }
});

onUpdated(() => {
    if (show.value === true) {
        timeId.value = setTimeout(() => {
            show.value = false;
        }, 3000);
    }
});

function closeModal() {
    show.value = false;
    clearTimeout(timeId.value);
}
</script>

<template>
    <div>
        <Teleport to="body">
            <Transition>
                <div
                    v-if="show && message"
                    class="fixed top-14 right-6 md:right-20 z-50 drop-shadow-xl py-4 px-8 rounded bg-gray-600"
                    :class = "{
                        'bg-red-500' : status == 'error',
                        'bg-green-600' : status == 'success'
                    }"
                >
                    <div class="flex items-center text-white font-bold">
                        <img src="/img/error_icon.svg" class="w-5 mr-2">
                        {{ message }}
                        <button @click="closeModal()" class="ml-6 p-2 rounded-full hover:bg-red-400">
                            <img src="/img/cross_icon.svg" class="w-3">
                        </button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>