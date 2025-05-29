<script setup>
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

const props = defineProps({
    faqs: Object,
});

// Initialize form with FAQ data
const form = useForm({
    section_id: props.faqs.id,
    content: {
        questions: JSON.parse(
            JSON.stringify(props.faqs.content.questions)
        ),
    },
});

// Add new FAQ item
const addFAQ = () => {
    form.content.questions.push({
        question: "",
        answer: "",
    });
};

// Remove FAQ item
const removeFAQ = (index) => {
    if (form.content.questions.length > 1) {
        form.content.questions.splice(index, 1);
    }
};

// Move FAQ up
const moveFAQUp = (index) => {
    if (index > 0) {
        const temp = form.content.questions[index];
        form.content.questions[index] = form.content.questions[index - 1];
        form.content.questions[index - 1] = temp;
    }
};

// Move FAQ down
const moveFAQDown = (index) => {
    if (index < form.content.questions.length - 1) {
        const temp = form.content.questions[index];
        form.content.questions[index] = form.content.questions[index + 1];
        form.content.questions[index + 1] = temp;
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
            <!-- Add FAQ Button -->
            <div class="flex justify-end items-center">
                <Button
                    icon="pi pi-plus"
                    label="إضافة سؤال جديد"
                    class="p-button-sm"
                    @click="addFAQ"
                    type="button"
                />
            </div>

            <!-- FAQ Items -->
            <div class="space-y-4">
                <div
                    v-for="(faq, index) in form.content.questions"
                    :key="index"
                    class="p-6 border rounded-xl bg-white shadow space-y-4"
                >
                    <!-- FAQ Header with Controls -->
                    <div class="flex justify-between items-center">
                        <h4 class="text-md font-semibold text-slate-700">
                            سؤال {{ index + 1 }}
                        </h4>
                        <div class="flex gap-2">
                            <!-- Move Up -->
                            <Button
                                icon="pi pi-chevron-up"
                                class="p-button-rounded p-button-text p-button-sm"
                                @click="moveFAQUp(index)"
                                type="button"
                                :disabled="index === 0"
                            />
                            <!-- Move Down -->
                            <Button
                                icon="pi pi-chevron-down"
                                class="p-button-rounded p-button-text p-button-sm"
                                @click="moveFAQDown(index)"
                                type="button"
                                :disabled="
                                    index === form.content.questions.length - 1
                                "
                            />
                            <!-- Remove -->
                            <Button
                                icon="pi pi-trash"
                                class="p-button-rounded p-button-text p-button-sm"
                                @click="removeFAQ(index)"
                                type="button"
                                :disabled="form.content.questions.length <= 1"
                            />
                        </div>
                    </div>

                    <!-- Question Input -->
                    <div>
                        <label
                            class="block mb-2 text-sm font-semibold text-slate-600"
                        >
                            السؤال
                        </label>
                        <Textarea
                            v-model="form.content.questions[index].question"
                            class="w-full"
                            rows="2"
                            autoResize
                            :placeholder="`اكتب السؤال ${index + 1}`"
                        />
                    </div>

                    <!-- Answer Input -->
                    <div>
                        <label
                            class="block mb-2 text-sm font-semibold text-slate-600"
                        >
                            الإجابة
                        </label>
                        <Textarea
                            v-model="form.content.questions[index].answer"
                            class="w-full"
                            rows="4"
                            autoResize
                            :placeholder="`اكتب الإجابة ${index + 1}`"
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
