<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue'
import TopPageLayoutVue from '@/Layouts/TopPageLayout.vue';
import TextInput from "../Components/TextInput.vue";
import TextAreaInput from "../Components/TextAreaInput.vue";
import InputLabel from "../Components/InputLabel.vue";
import PrimaryButton from "../Components/PrimaryButton.vue";
import SecondaryButton from "../Components/SecondaryButton.vue";
import ImageInput from "../Components/ImageInput.vue";
import PageTitle from "../Components/PageTitle.vue"
import NumberLikes from "../Components/NumberLikes.vue";
import NumberComments from "../Components/NumberComments.vue";
import UserIcon from "../Components/UserIcon.vue";
import ItemImage from "../Components/ItemImage.vue";
import SearchItems from "../Components/SearchItems.vue";
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineOptions({ layout: TopPageLayoutVue })

const input = ref('test')
const textArea = ref('test')
const previewUrl = ref('')
</script>

<template>
    <div class="max-w-2xl mx-auto">
        test.vue
    </div>
</template>
