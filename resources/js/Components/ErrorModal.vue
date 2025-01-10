<script setup>
import { ref, onUpdated } from 'vue'

const show = defineModel({
    type: Boolean,
})

const timeId = ref(0);

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
                    v-if="show"
                    class="fixed top-14 right-20 z-50 bg-red-500 drop-shadow-xl py-4 px-8 rounded"
                >
                    <div class="flex items-center text-white font-bold">
                        <img src="/img/error_icon.svg" class="w-5 mr-2">
                        <slot />
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