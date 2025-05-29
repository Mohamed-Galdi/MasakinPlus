<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminSettingsLayout from "@/Layouts/AdminSettingsLayout.vue";
import CMSLayout from "@/Layouts/CMSLayout.vue";
import Editor from "primevue/editor";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

defineOptions({
    layout: (h, page) =>
        h(AdminLayout, {}, () =>
            h(AdminSettingsLayout, {}, () => h(CMSLayout, {}, () => page))
        ),
});

const props = defineProps({
    content: String,
});

const toast = useToast();

const form = useForm({
    page: 'privacy',
    content: props.content,
});

// Form submission
const submit = () => {
    form.put(route("admin.settings.content.updatePageContent"), {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "نجاح",
                detail: "تم تحديث الصفحة بنجاح",
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
    <div class="">
        <Toast position="top-center" />
        <form @submit.prevent="submit" class="space-y-2">
            <Editor v-model="form.content" height="500px" />
            <Button
                label="حفظ التغييرات"
                icon="pi pi-save"
                class="w-full mb-6"
                :disabled="form.processing"
                type="submit"
                severity="success"
            />
        </form>
    </div>
</template>
