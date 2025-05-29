<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import FileUpload from "@/Components/PrimeVilt/FileUpload.vue";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

const props = defineProps({
    features: Object,
});

// Initialize form with features data
const form = useForm({
    section_id : props.features.id,
    content: {
        features: JSON.parse(JSON.stringify(props.features.content.features)),
    },
});

// Current slide index for carousel navigation
const currentSlideIndex = ref(0);

// Watch for changes in currentSlideIndex to ensure image field is set
watch(currentSlideIndex, () => {
    form.content.features[currentSlideIndex.value].image =
        form.content.features[currentSlideIndex.value].image || null;
});

// Carousel navigation
const nextSlide = () => {
    currentSlideIndex.value =
        (currentSlideIndex.value + 1) % form.content.features.length;
};

const prevSlide = () => {
    currentSlideIndex.value =
        (currentSlideIndex.value - 1 + form.content.features.length) %
        form.content.features.length;
};

// File upload handling
function handleFileUploaded(fileFolder) {
    form.content.features[currentSlideIndex.value].image = fileFolder;
}

function handleFileReverted() {
    form.content.features[currentSlideIndex.value].image = null;
}

// Form submission
const submit = () => {
    form.put(route("admin.settings.content.updateSectionContent"), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "نجاح",
                detail: "تم تحديث القسم بنجاح",
                life: 3000,
            });
        },
        onError: (errors) => {
            const errorMessage =
                Object.values(errors)[0] || "حدث خطأ أثناء التحديث";
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: errorMessage,
                life: 3000,
            });
        },
    });
};
</script>

<template>
   <div>
        <Toast position="top-center" />
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
                            {{ form.content.features.length }}
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
                    v-if="form.content.features.length > 0"
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
                                    v-model="
                                        form.content.features[currentSlideIndex].title
                                    "
                                    class="w-full"
                                />
                            </div>

                            <div>
                                <label
                                    class="block mb-1 text-sm font-semibold text-slate-600"
                                    >الوصف</label
                                >
                                <Textarea
                                    v-model="
                                        form.content.features[currentSlideIndex]
                                            .subtitle
                                    "
                                    class="w-full"
                                    rows="3"
                                    autoResize
                                />
                            </div>
    
                            <div>
                                <label
                                    class="block mb-1 text-sm font-semibold text-slate-600"
                                    >نص الزر</label
                                >
                                <InputText
                                    v-model="
                                        form.content.features[currentSlideIndex]
                                            .cta
                                    "
                                    class="w-full"
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
                                :initial-files="
                                    form.content.features[currentSlideIndex].image
                                "
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
                    :disabled="form.processing"
                    type="submit"
                />
            </div>
        </form>
   </div>
</template>
