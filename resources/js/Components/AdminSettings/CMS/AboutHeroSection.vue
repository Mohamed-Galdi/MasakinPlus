<script setup>
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import FileUpload from "@/Components/PrimeVilt/FileUpload.vue";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

const props = defineProps({
    hero: Object,
});

// Initialize form with hero data
const form = useForm({
    section_id: props.hero.id,
    content: {
        title: props.hero.content.title,
        subtitle: props.hero.content.subtitle,
        primaryButton: {
            text: props.hero.content.primaryButton.text,
            link: props.hero.content.primaryButton.link,
        },
        secondaryButton: {
            text: props.hero.content.secondaryButton.text,
            link: props.hero.content.secondaryButton.link,
        },
        image: props.hero.content.image,
        terms: {
            term1: props.hero.content.terms.term1,
            term2: props.hero.content.terms.term2,
        },
    },
});

// File upload handling
function handleFileUploaded(fileFolder) {
    form.content.image = fileFolder;
}

function handleFileReverted() {
    form.content.image = null;
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
            <!-- Hero Content Grid -->
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Left Column - Text Content -->
                <div class="space-y-6">
                    <!-- Title -->
                    <div class="space-y-6">
                        <InputText
                            v-model="form.content.title"
                            class="w-full"
                            placeholder="العنوان الرئيسي "
                        />
                        <Textarea
                            v-model="form.content.subtitle"
                            class="w-full"
                            rows="4"
                            autoResize
                            placeholder="النص الوصفي"
                        />
                        <div class="flex gap-2">
                            <InputText
                                v-model="form.content.primaryButton.text"
                                class="w-1/3"
                                placeholder="  نص الزر الأول "
                            />
                            <InputText
                                v-model="form.content.primaryButton.link"
                                class="w-2/3"
                                placeholder="رابط الزر الأول"
                            />
                        </div>
                        <div class="flex gap-2">
                            <InputText
                                v-model="form.content.secondaryButton.text"
                                class="w-1/3"
                                placeholder=" نص الزر الثاني"
                            />
                            <InputText
                                v-model="form.content.secondaryButton.link"
                                class="w-2/3"
                                placeholder="رابط الزر الثاني"
                            />
                        </div>
                    </div>
                </div>

                <!-- Right Column - Image and Terms -->
                <div class="">
                    <FileUpload
                        :initial-files="form.content.image"
                        @file-uploaded="handleFileUploaded"
                        @file-reverted="handleFileReverted"
                    />
                    <div class="flex gap-4">
                        <InputText
                            v-model="form.content.terms.term2"
                            class="w-full"
                            placeholder="  العبارة الثانية (أعلى يمين)"
                        />
                        <InputText
                            v-model="form.content.terms.term1"
                            class="w-full"
                            placeholder="  العبارة الأولى (أسفل يسار)"
                        />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <Button
                    severity="success"
                    label="حفظ التغييرات"
                    icon="pi pi-save"
                    class="w-full"
                    :disabled="form.processing"
                    type="submit"
                />
            </div>
        </form>
    </div>
</template>
