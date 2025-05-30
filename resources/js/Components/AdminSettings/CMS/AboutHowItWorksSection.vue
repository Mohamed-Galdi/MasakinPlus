<script setup>
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

const props = defineProps({
    howItWorks: Object,
});

// Initialize form with cards data
const form = useForm({
    section_id: props.howItWorks.id,
    content: {
        cards: JSON.parse(JSON.stringify(props.howItWorks.content.cards)),
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
            <!-- Three Cards Grid -->
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-6">
                <div
                    v-for="(card, cardIndex) in form.content.cards"
                    :key="cardIndex"
                    class="space-y-4"
                >
                    <!-- Card Title -->
                    <div>
                        <InputText
                            v-model="form.content.cards[cardIndex].title"
                            class="w-full"
                        />
                    </div>

                    <!-- List Items -->
                    <div>
                        <Textarea
                            v-model="form.content.cards[cardIndex].description"
                            class="w-full"
                            rows="4"
                            autoResize
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
