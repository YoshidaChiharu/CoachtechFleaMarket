<script setup>
const previewUrl = defineModel("previewUrl", {
    type: String,
})
const file = defineModel('file', {
    type: Object,
})

function setPreviewUrl(e) {
    const image = e.target.files[0];
    previewUrl.value = URL.createObjectURL(image);
    file.value = image;
}
</script>

<template>
    <label
        for="input_file"
        class="inline-flex items-center rounded-md border border-red-500 bg-white px-8 py-2 font-bold uppercase tracking-widest text-red-500 shadow-sm transition duration-150 ease-in-out hover:bg-red-500 hover:text-white disabled:opacity-25"
    >
        <slot />
    </label>
    <input
        type="file"
        accept="image/*"
        @change="setPreviewUrl"
        id="input_file"
        class="hidden"
   >
</template>
