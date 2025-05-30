<script setup>
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

const props = defineProps({
    timeline: Object,
});

// Initialize form with journey data
const form = useForm({
    section_id: props.timeline.id,
    content: {
        title: props.timeline.content.title,
        milestones: JSON.parse(
            JSON.stringify(props.timeline.content.milestones)
        ),
    },
});

// Add new milestone
const addMilestone = () => {
    form.content.milestones.push({
        year: "",
        title: "",
        description: "",
    });
};

// Remove milestone
const removeMilestone = (index) => {
    if (form.content.milestones.length > 1) {
        form.content.milestones.splice(index, 1);
    }
};


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
            <!-- Milestones Header -->
            <div class="flex justify-end items-center">
                
                <Button
                    icon="pi pi-plus"
                    label="إضافة محطة جديدة"
                    class="p-button-sm"
                    @click="addMilestone"
                    type="button"
                />
            </div>

            <!-- Milestones Timeline -->
            <div class="space-y-6">
                <div
                    v-for="(milestone, index) in form.content.milestones"
                    :key="index"
                    class="relative"
                >

                    <div class="flex gap-4">
                       

                        <!-- Milestone Content -->
                        <div
                            class="flex-1 p-4 border rounded-lg bg-gray-50 space-y-4"
                        >
                            <div class="flex justify-between items-center gap-4 mb-3">
                                <span
                                    class="text-sm font-semibold text-slate-600"
                                >
                                    محطة {{ index + 1 }}
                                </span>
                                <Button
                                icon="pi pi-trash"
                                class="p-button-rounded p-button-text p-button-sm"
                                @click="removeMilestone(index)"
                                type="button"
                                :disabled="
                                        form.content.milestones.length <= 1
                                        "
                                />
                            </div>

                            <div class="grid md:grid-cols-3 gap-4">
                                <!-- Year -->
                                <div>
                                    <label
                                        class="block mb-1 text-sm font-medium text-slate-600"
                                    >
                                        السنة
                                    </label>
                                    <InputText
                                        v-model="
                                            form.content.milestones[index].year
                                        "
                                        class="w-full"
                                        placeholder="2024"
                                    />
                                </div>

                                <!-- Title -->
                                <div class="md:col-span-2">
                                    <label
                                        class="block mb-1 text-sm font-medium text-slate-600"
                                    >
                                        العنوان
                                    </label>
                                    <InputText
                                        v-model="
                                            form.content.milestones[index].title
                                        "
                                        class="w-full"
                                        placeholder="عنوان المحطة"
                                    />
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label
                                    class="block mb-1 text-sm font-medium text-slate-600"
                                >
                                    الوصف
                                </label>
                                <Textarea
                                    v-model="
                                        form.content.milestones[index]
                                            .description
                                    "
                                    class="w-full"
                                    rows="3"
                                    autoResize
                                    placeholder="وصف المحطة الزمنية"
                                />
                            </div>
                        </div>
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
