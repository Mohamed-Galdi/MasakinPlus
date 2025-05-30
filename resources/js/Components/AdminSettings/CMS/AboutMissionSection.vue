<script setup>
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

const props = defineProps({
    mission: Object,
});

// Initialize form with FAQ data
const form = useForm({
    section_id: props.mission.id,
    content: {
        title: props.mission.content.title,
        description: props.mission.content.description,
    },
});

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
            <div>
                <Textarea
                    v-model="form.content.title"
                    class="w-full"
                    rows="2"
                    autoResize
                    placeholder="عنوان الرسالة"
                />
            </div>

            <div>
                <Textarea
                    v-model="form.content.description"
                    class="w-full"
                    rows="4"
                    autoResize
                    placeholder="نص الرسالة"
                />
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
