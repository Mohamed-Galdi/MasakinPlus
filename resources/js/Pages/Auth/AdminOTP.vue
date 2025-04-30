<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputOtp from "primevue/inputotp";
import Button from "primevue/button";
import Checkbox from "primevue/checkbox";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

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
</script>

<template>
    <div class="bg">
        <Head title="Admin Login" />
        <Toast position="top-center" />
        <div
            class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 min-h-screen flex flex-col gap-3 items-center"
        >
            <img src="/assets/logos/logo_white.png" alt="logo" class="w-44" />

            <div
                class="flex flex-col gap-6 bg-gray-300 w-[25rem] my-12 p-6 rounded-xl"
            >
                <div class="text-center">
                    <h1 class="font-bold text-slate-800 font-Bein text-xl">
                        أدخل الرمز المرسل إلى بريدك الإلكتروني للتحقق
                    </h1>
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
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.bg {
    background-image: url("../../../../public/assets/login-bg.jpg");
    background-size: cover;
    background-position: center;
    height: 100vh;
}
</style>
