<script setup>
import HomeLayout from "@/Layouts/HomeLayout.vue";

defineOptions({
    layout: HomeLayout,
});

const props = defineProps({
    content: String,
});
</script>

<template>
    <div class="max-w-7xl mx-auto p-6 space-y-12 py-12">
        <h1
            class="lg:text-3xl text-lg font-bold text-teal-800 mb-6 font-BlueOcean text-center my-12"
        >
            سياسة الخصوصية وشروط الاستخدام
        </h1>
        <div
            class="content font-BeinNormal w-full max-w-full break-words overflow-wrap-anywhere"
            v-html="content"
        ></div>
    </div>
</template>

<style>
.content h2 {
    @apply text-3xl font-Bein text-teal-800 mt-6 mb-2;
}

.content p {
    @apply text-slate-700 mb-2;
}

.content ul {
    @apply list-disc space-y-2 ms-12 my-4;
}
</style>
