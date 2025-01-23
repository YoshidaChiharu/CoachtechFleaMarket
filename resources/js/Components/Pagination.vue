<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
})
</script>

<template>
    <div class="flex justify-center mt-6">
        <div v-if="links.length > 3" class="inline-block shadow">
            <ul class="border rounded flex items-center gap-2 sm:gap-5">
                <li v-for="link in links" :key="link"
                    :class="{'border border-black': link.active}"
                >
                    <div v-if="link.url === null"
                        v-html="link.label"
                        class="py-2 px-2 md:px-4"
                    />
                    <Link v-else
                        v-html="link.label"
                        :href="link.url"
                        class="py-2 px-2 md:px-4 block"
                    />
                </li>
            </ul>
        </div>
    </div>
</template>