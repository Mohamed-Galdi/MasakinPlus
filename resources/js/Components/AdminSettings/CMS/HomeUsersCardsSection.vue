<script setup>
import { useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

const props = defineProps({
    usersCards: Object,
});

// Initialize form with cards data
const form = useForm({
    section_id: props.usersCards.id,
    content: {
        cards: JSON.parse(JSON.stringify(props.usersCards.content.cards)),
    },
});

// Add new list item to specific card
const addListItem = (cardIndex) => {
    form.content.cards[cardIndex].listItems.push("");
};

// Remove list item from specific card
const removeListItem = (cardIndex, itemIndex) => {
    if (form.content.cards[cardIndex].listItems.length > 1) {
        form.content.cards[cardIndex].listItems.splice(itemIndex, 1);
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
            <!-- Three Cards Grid -->
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-6">
                <div
                    v-for="(card, cardIndex) in form.content.cards"
                    :key="cardIndex"
                    class="p-4 border rounded-xl bg-white shadow space-y-4"
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
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-sm font-semibold text-slate-600">
                                نقاط المميزات
                            </label>
                            <Button
                                icon="pi pi-plus"
                                class="p-button-rounded p-button-text p-button-sm"
                                @click="addListItem(cardIndex)"
                                type="button"
                            />
                        </div>

                        <div class="space-y-3">
                            <div
                                v-for="(item, itemIndex) in card.listItems"
                                :key="itemIndex"
                                class="flex gap-2 items-start"
                            >
                                <Textarea
                                    v-model="
                                        form.content.cards[cardIndex].listItems[
                                            itemIndex
                                        ]
                                    "
                                    class="flex-1"
                                    rows="2"
                                    autoResize
                                    :placeholder="`النقطة ${itemIndex + 1}`"
                                />
                                <Button
                                    icon="pi pi-trash"
                                    class="p-button-rounded p-button-text p-button-sm"
                                    @click="
                                        removeListItem(cardIndex, itemIndex)
                                    "
                                    type="button"
                                    :disabled="card.listItems.length <= 1"
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
