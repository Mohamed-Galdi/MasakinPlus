<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import FloatLabel from "primevue/floatlabel";
import Password from "primevue/password";
import Button from "primevue/button";
import Checkbox from "primevue/checkbox";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import AuthLayout from "@/Layouts/AuthLayout.vue";

defineOptions({
    layout: AuthLayout,
});

const toast = useToast();

const loginForm = useForm({
    email: "",
    password: "",
    remember: false,
});

function submit() {
    loginForm.post(route("login"), {
        onSuccess: () => {
            loginForm.reset();
        },
        onError: () => {
            const errorMessage = Object.values(loginForm.errors)[0];
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
        class="flex items-center justify-center font-BeinNormal pt-40 "
    >
        <Head title="| تسجيل الدخول" />
        <Toast position="top-center" />

        <div class="relative w-full max-w-md">

            <!-- Login Card -->
            <div
                class="shadow-xl border border-teal-600 bg-white/95 backdrop-blur-sm rounded-2xl p-6"
            >
                <div>
                    <div class="text-center mb-6">
                        <h1
                            class="font-bold text-teal-800 font-Bein text-3xl mb-2"
                        >
                            تسجيل الدخول
                        </h1>
                        <p class="text-gray-600 text-sm">
                            مرحبًا بعودتك! أدخل بياناتك للوصول إلى حسابك
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col gap-6">
                        <!-- email -->
                        <FloatLabel variant="on" class="w-full">
                            <InputText
                                id="email"
                                v-model="loginForm.email"
                                class="w-full border-gray-300 focus:border-teal-500 rounded-md"
                            />
                            <label for="email">البريد الإلكتروني</label>
                        </FloatLabel>

                        <!-- password -->
                        <FloatLabel variant="on" class="w-full">
                            <Password
                                id="password"
                                v-model="loginForm.password"
                                :feedback="false"
                                toggleMask
                                class="w-full mt-1"
                                inputClass="border-gray-300 focus:border-teal-500 rounded-md"
                            />
                            <label for="password">كلمة المرور</label>
                        </FloatLabel>

                        <div
                            class="flex items-center justify-between flex-wrap gap-2"
                        >
                            <div class="flex items-center gap-2">
                                <Checkbox
                                    v-model="loginForm.remember"
                                    inputId="remember"
                                    name="remember"
                                    :binary="true"
                                />
                                <label
                                    for="remember"
                                    class="text-sm text-gray-600"
                                >
                                    تذكرني
                                </label>
                            </div>
                            <Link
                                :href="route('password.request')"
                                class="text-teal-600 hover:underline text-sm"
                            >
                                نسيت كلمة المرور؟
                            </Link>
                        </div>

                        <Button
                            severity="contrast"
                            type="button"
                            label="تسجيل الدخول"
                            :loading="loginForm.processing"
                            @click="submit"
                        />

                        <p class="text-center text-sm text-gray-600">
                            ليس لديك حساب؟
                            <Link
                                :href="route('register')"
                                class="text-teal-600 hover:underline font-semibold"
                            >
                                إنشاء حساب جديد
                            </Link>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
