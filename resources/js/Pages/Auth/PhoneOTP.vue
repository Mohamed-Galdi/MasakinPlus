<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputOtp from "primevue/inputotp";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import AuthLayout from "@/Layouts/AuthLayout.vue";

defineOptions({
    layout: AuthLayout,
});

const props = defineProps({
    phone: {
        type: String,
    },
});

const toast = useToast();

const otpForm = useForm({
    otp: "",
});

function submit() {
    otpForm.post(route("otp.verify"), {
        onSuccess: () => {
            otpForm.reset();
        },
        onError: () => {
            const errorMessage = Object.values(otpForm.errors)[0];
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: errorMessage,
                life: 3000,
            });
        },
    });
}

function resendOtp() {
    // Logic to resend OTP (e.g., POST to a resend route)
    useForm({}).post(route("otp.resend"), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "تم",
                detail: "تم إرسال رمز جديد إلى بريدك الإلكتروني",
                life: 3000,
            });
        },
        onError: () => {
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: "فشل إرسال الرمز، حاول مرة أخرى",
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
        <Head title="| التحقق من رمز OTP" />
        <Toast position="top-center" />

        <div class="relative w-full max-w-md">
            <!-- Background overlay with subtle pattern -->
            <div
                class="absolute inset-0 bg-teal-800/10 rounded-2xl transform -rotate-2 scale-105 -z-10"
            ></div>

            <!-- OTP Card -->
            <div
                class="shadow-xl border border-teal-600 bg-white/95 backdrop-blur-sm rounded-2xl p-6"
            >
                <div>
                    <div class="text-center">
                        <div class="mb-6">
                            <h1
                            class="font-bold text-teal-800 font-Bein text-xl mb-2"
                        >
                            أدخل الرمز المرسل إلى بريدك الإلكتروني للتحقق
                        </h1>

                            <div
                                v-if="phone"
                            >
                                <span class="text-sm text-gray-500 font-sans mb-1">
                                    تم الإرسال إلى:
                                </span>
                                <span
                                    class="font-mono text-lg text-gray-800 tracking-wider"
                                    dir="ltr"
                                >
                                    {{ phone }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col gap-6">
                        <!-- OTP Input -->
                        <div class="w-full flex justify-center" dir="ltr">
                            <InputOtp
                                v-model="otpForm.otp"
                                :length="6"
                                integerOnly
                            />
                        </div>

                        <Button
                            severity="success"
                            type="button"
                            label="التحقق"
                            :loading="otpForm.processing"
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
