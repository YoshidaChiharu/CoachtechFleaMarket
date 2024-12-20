<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
})
</script>

<template>
    <div class="flex justify-center mt-6">
        <div v-if="links.length > 3" class="inline-block">
            <ul class="border rounded flex item-center gap-5">
                <li v-for="link in links" :key="link"
                    class="py-2 px-4"
                    :class="{'border border-black': link.active}"
                >
                    <div v-if="link.url === null"
                        v-html="link.label"
                    />
                    <Link v-else
                        v-html="link.label"
                        :href="link.url"
                    />
                </li>
            </ul>
        </div>
    </div>
</template>