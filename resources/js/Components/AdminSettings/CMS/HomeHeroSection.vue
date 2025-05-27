<script setup>
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import FileUpload from "@/Components/PrimeVilt/FileUpload.vue";

const props = defineProps({
    hero: Object,
});

// Editable state for Hero
const slides = ref(JSON.parse(JSON.stringify(props.hero.content.slides)));
const currentSlideIndex = ref(0);

// Watch for changes in currentSlideIndex to ensure reactivity
watch(currentSlideIndex, () => {
    slides.value[currentSlideIndex.value].image =
        slides.value[currentSlideIndex.value].image || null;
});

// Carousel navigation
const nextSlide = () => {
    currentSlideIndex.value =
        (currentSlideIndex.value + 1) % slides.value.length;
};

const prevSlide = () => {
    currentSlideIndex.value =
        (currentSlideIndex.value - 1 + slides.value.length) %
        slides.value.length;
};

// File upload handling
function handleFileUploaded(fileFolder) {
    slides.value[currentSlideIndex.value].image = fileFolder;
}

function handleFileReverted() {
    slides.value[currentSlideIndex.value].image = null;
}

const submit = () => {
    router.put(
        route("admin.cms.page-sections.update", props.hero.id),
        {
            content: {
                slides: slides.value,
            },
        },
        {
            preserveScroll: true,
        }
    );
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="relative">
            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center mb-4">
                <Button
                    icon="pi pi-chevron-right"
                    class="p-button-rounded p-button-text"
                    @click="prevSlide"
                />
                <!-- Slide Indicator -->
                <div class="text-center">
                    <span class="text-sm text-slate-600">
                        {{ currentSlideIndex + 1 }} /
                        {{ slides.length }}
                    </span>
                </div>
                <Button
                    icon="pi pi-chevron-left"
                    class="p-button-rounded p-button-text"
                    @click="nextSlide"
                />
            </div>

            <!-- Current Slide -->
            <div
                v-if="slides.length > 0"
                class="p-6 border rounded-xl bg-white shadow space-y-4"
            >
                <div class="flex gap-4 items-center">
                    <div class="w-1/2 flex flex-col gap-2 justify-between">
                        <div>
                            <label
                                class="block mb-1 text-sm font-semibold text-slate-600"
                                >العنوان</label
                            >
                            <InputText
                                v-model="slides[currentSlideIndex].title"
                                class="w-full"
                            />
                        </div>

                        <div>
                            <label
                                class="block mb-1 text-sm font-semibold text-slate-600"
                                >نص الزر</label
                            >
                            <InputText
                                v-model="slides[currentSlideIndex].buttonText"
                                class="w-full"
                            />
                        </div>

                        <div>
                            <label
                                class="block mb-1 text-sm font-semibold text-slate-600"
                                >الوصف</label
                            >
                            <Textarea
                                v-model="slides[currentSlideIndex].description"
                                class="w-full"
                                rows="3"
                                autoResize
                            />
                        </div>
                    </div>

                    <div class="w-1/2">
                        <label
                            class="block mb-1 text-sm font-semibold text-slate-600"
                        >
                            الصورة
                        </label>
                        <FileUpload
                            :key="currentSlideIndex"
                            :initial-files="slides[currentSlideIndex].image"
                            @file-uploaded="handleFileUploaded"
                            @file-reverted="handleFileReverted"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <Button
                severity="success"
                label="حفظ التغييرات"
                icon="pi pi-save"
                class="w-full mb-6"
            />
        </div>
    </form>
</template>
