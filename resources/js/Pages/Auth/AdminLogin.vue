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

const loginForm = useForm({
    email: "",
    password: "",
});

const submit = () => {
    console.log("hi");
};
</script>

<template>
    <div class="bg">
        <Head title="Admin Login" />
        <div
            class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 min-h-screen flex flex-col gap-3 items-center"
        >
            <img src="/assets/logos/logo_white.png" alt="logo" class="w-44" />

            <form @submit.prevent="submit" class="flex flex-col gap-6 bg-slate-500 w-[25rem] my-12 p-6 rounded-xl">
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

                <Button
                    severity="contrast"
                    type="button"
                    label="تسجيل الدخول"
                    :loading="loginForm.processing"
                    @click="submit"
                />
            </form>
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
