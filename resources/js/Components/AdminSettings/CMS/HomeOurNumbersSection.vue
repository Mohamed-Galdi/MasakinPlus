<script setup>
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

const props = defineProps({
    ourNumbers: Object,
});

// Initialize form with stats data
const form = useForm({
    section_id: props.ourNumbers.id,
    content: {
        stats: JSON.parse(JSON.stringify(props.ourNumbers.content.stats)),
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
            const errorMessage = Object.values(errors)[0] || "حدث خطأ أثناء التحديث";
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
            <!-- Three Stats Grid -->
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-6">
                <div
                    v-for="(stat, statIndex) in form.content.stats"
                    :key="statIndex"
                    class="p-4 border rounded-xl bg-white shadow space-y-4"
                >

                    <!-- Number -->
                    <div>
                        <InputNumber
                            v-model="form.content.stats[statIndex].number"
                            class="w-full"
                            :min="0"
                            :max="999999"
                        />
                    </div>

                    <!-- Label -->
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-600">
                            النص الوصفي
                        </label>
                        <Textarea
                            v-model="form.content.stats[statIndex].label"
                            class="w-full"
                            rows="3"
                            autoResize
                            :placeholder="`وصف الإحصائية ${statIndex + 1}`"
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