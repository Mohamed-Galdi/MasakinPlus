<script setup>
import { ref, onMounted } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import FloatLabel from "primevue/floatlabel";
import Password from "primevue/password";
import Button from "primevue/button";
import Checkbox from "primevue/checkbox";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import intlTelInput from "intl-tel-input";
import { ar } from "intl-tel-input/i18n"; 

const iti = ref(null);

onMounted(() => {
    const input = document.querySelector("#phone");
    iti.value = intlTelInput(input, {
        i18n: ar,
        initialCountry: "sa",
        onlyCountries: ["sa", "ma", "ae", "kw", "eg", "bh", "qa", "om", "ae", "ye"],
        countryOrder: ["sa", "ma", "ae", "kw","eg", "bh", "qa", "om", "ae", "ye"],
        loadUtils: () => import("intl-tel-input/utils"),
    });
});

defineOptions({
    layout: AuthLayout,
});

const toast = useToast();

const loginForm = useForm({
    phone: "",
});

function submit() {

    if (!iti.value.isValidNumber()) {
        toast.add({
            severity: "error",
            summary: "خطأ",
            detail: "رقم الهاتف غير صحيح",
            life: 3000,
        });
        return;
    }
    
    loginForm.phone = iti.value.getNumber();

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
    <div class="flex items-center justify-center font-BeinNormal pt-40">
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
                            مرحبًا بعودتك! أدخل رقم الهاتف الخاص بك للوصول إلى حسابك
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col gap-6">
                          <!-- phone -->
                          <input
                            type="phone"
                            id="phone"
                            class="w-full"
                        />

                        <Button
                            severity="success"
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
