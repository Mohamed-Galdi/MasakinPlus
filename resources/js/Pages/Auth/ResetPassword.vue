<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import Password from "primevue/password";
import FloatLabel from "primevue/floatlabel";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import AuthLayout from "@/Layouts/AuthLayout.vue";

defineOptions({
    layout: AuthLayout,
});

const props = defineProps({
    email: String,
    token: String,
});

const toast = useToast();

const resetPasswordForm = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

function submit() {
    resetPasswordForm.post(route("password.store"), {
        onSuccess: () => {
            resetPasswordForm.reset();
            toast.add({
                severity: "success",
                summary: "تم",
                detail: "تم إعادة تعيين كلمة المرور بنجاح",
                life: 3000,
            });
        },
        onError: () => {
            const errorMessage = Object.values(resetPasswordForm.errors)[0];
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
        <Head title="| إعادة تعيين كلمة المرور" />
        <Toast position="top-center" />

        <div class="relative w-full max-w-md">
            <!-- Background overlay with subtle pattern -->
            <div
                class="absolute inset-0 bg-teal-800/10 rounded-2xl transform -rotate-2 scale-105 -z-10"
            ></div>

            <!-- Reset Password Card -->
            <div
                class="shadow-xl border border-teal-600 bg-white/95 backdrop-blur-sm rounded-2xl p-6"
            >
                <div>
                    <div class="text-center mb-6">
                        <h1
                            class="font-bold text-teal-800 font-Bein text-3xl mb-2"
                        >
                            إعادة تعيين كلمة المرور
                        </h1>
                        <p class="text-gray-600 text-sm">
                            أدخل كلمة المرور الجديدة لحسابك
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col gap-6">
                        <!-- New Password Input -->

                        <FloatLabel variant="on" class="w-full">
                            <Password
                                id="password"
                                v-model="resetPasswordForm.password"
                                :feedback="false"
                                toggleMask
                                class="w-full mt-1"
                                inputClass="border-gray-300 focus:border-teal-500 rounded-md"
                            />
                            <label for="password">كلمة المرور الجديدة</label>
                        </FloatLabel>

                        <!-- Confirm Password Input -->
                        <FloatLabel variant="on" class="w-full">
                            <Password
                                id="password_confirmation"
                                v-model="
                                    resetPasswordForm.password_confirmation
                                "
                                :feedback="false"
                                toggleMask
                                class="w-full mt-1"
                                inputClass="border-gray-300 focus:border-teal-500 rounded-md"
                            />
                            <label for="password_confirmation"
                                >تأكيد كلمة المرور</label
                            >
                        </FloatLabel>

                        <Button
                            severity="contrast"
                            type="button"
                            label="حفظ كلمة المرور الجديدة"
                            :loading="resetPasswordForm.processing"
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
