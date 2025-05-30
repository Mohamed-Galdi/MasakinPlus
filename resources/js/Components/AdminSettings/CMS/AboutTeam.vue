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
    team: Object,
});

// Initialize form with team data
const form = useForm({
    section_id: props.team.id,
    content: {
        title: props.team.content.title,
        team: JSON.parse(JSON.stringify(props.team.content.team)),
    },
});

// File upload handling
function handleFileUploaded(fileFolder, index) {
    form.content.team[index].image = fileFolder;
}

function handleFileReverted(index) {
    form.content.team[index].image = "";
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
           
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="(member, index) in form.content.team"
                        :key="index"
                        class="border rounded-lg p-4 bg-gray-50 space-y-4"
                    >
                        <!-- Member Image -->
                        <div>
                            <FileUpload
                                :key="`member-image-${index}`"
                                :initial-files="member.image"
                                @file-uploaded="
                                    (file) => handleFileUploaded(file, index)
                                "
                                @file-reverted="() => handleFileReverted(index)"
                            />
                        </div>

                        <!-- Member Name -->
                        <div>
                            <InputText
                                v-model="form.content.team[index].name"
                                class="w-full"
                                placeholder="اسم العضو"
                            />
                        </div>

                        <!-- Member Position -->
                        <div>
                            <InputText
                                v-model="form.content.team[index].position"
                                class="w-full"
                                placeholder="المنصب الوظيفي"
                            />
                        </div>

                        <!-- Member Quote -->
                        <div>
                            <Textarea
                                v-model="form.content.team[index].quote"
                                class="w-full"
                                rows="3"
                                autoResize
                                placeholder="اقتباس العضو"
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
