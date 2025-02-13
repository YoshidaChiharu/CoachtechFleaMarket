<template>
    <div class="inline-block text-center">
        <img :src="'/img/comments.svg'" alt="Likes" class="w-8">
        <span class="text-xs font-bold">
            <slot />
        </span>
    </div>
</template>