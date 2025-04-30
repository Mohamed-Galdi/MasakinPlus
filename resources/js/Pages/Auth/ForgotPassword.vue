<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import FloatLabel from "primevue/floatlabel";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import AuthLayout from "@/Layouts/AuthLayout.vue";

defineOptions({
    layout: AuthLayout,
});

const toast = useToast();

const forgotPasswordForm = useForm({
    email: "",
});

function submit() {
    forgotPasswordForm.post(route("password.email"), {
        onSuccess: () => {
            forgotPasswordForm.reset();
            toast.add({
                severity: "success",
                summary: "تم",
                detail: "تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني",
                life: 3000,
            });
        },
        onError: () => {
            const errorMessage = Object.values(forgotPasswordForm.errors)[0];
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: errorMessage,
                life: 3000,
            });
        },
    });
}
</script>

<template>
    <div
        class="flex items-center justify-center font-BeinNormal bg-gray-50 h-[90vh]"
    >
        <Head title="| نسيت كلمة المرور" />
        <Toast position="top-center" />

        <div class="relative w-full max-w-md">
            <!-- Background overlay with subtle pattern -->
            <div
                class="absolute inset-0 bg-teal-800/10 rounded-2xl transform -rotate-2 scale-105 -z-10"
            ></div>

            <!-- Forgot Password Card -->
            <div
                class="shadow-xl border border-teal-600 bg-white/95 backdrop-blur-sm rounded-2xl p-6"
            >
                <div>
                    <div class="text-center mb-6">
                        <h1
                            class="font-bold text-teal-800 font-Bein text-3xl mb-2"
                        >
                            نسيت كلمة المرور
                        </h1>
                        <p class="text-gray-600 text-sm">
                            أدخل بريدك الإلكتروني لتلقي رابط إعادة تعيين كلمة
                            المرور
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col gap-6">
                        <!-- Email Input -->
                        <FloatLabel variant="on" class="w-full">
                            <InputText
                                id="email"
                                v-model="forgotPasswordForm.email"
                                class="w-full border-gray-300 focus:border-teal-500 rounded-md"
                            />
                            <label for="email">البريد الإلكتروني</label>
                        </FloatLabel>

                        <Button
                            severity="success"
                            type="button"
                            label="إرسال رابط إعادة التعيين"
                            :loading="forgotPasswordForm.processing"
                            @click="submit"
                        />

                        <p class="text-center text-sm text-gray-600">
                            العودة إلى
                            <Link
                                :href="route('login')"
                                class="text-teal-600 hover:underline font-semibold"
                            >
                                تسجيل الدخول
                            </Link>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
