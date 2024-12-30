<script setup>
import UserIcon from "@/Components/UserIcon.vue";

const props = defineProps({
    comment: Object
})

const direction = props.comment.is_mine ? "flex-row-reverse" : "flex-row" ;

</script>

<template>
    <div class="mb-4">
        <div class="flex items-center gap-2" :class="direction">
            <div class="w-8">
                <UserIcon :path="comment.image_url" />
            </div>
            <span class="font-bold">{{ comment.name }}</span>
        </div>
        <div class="mt-1 p-2 bg-gray-200 rounded break-words">{{ comment.comment }}</div>
    </div>
</template>
