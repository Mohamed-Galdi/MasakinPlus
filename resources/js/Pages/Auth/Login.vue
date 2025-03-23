<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

const loginForm = useForm({
    email: "",
    password: "",
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
                summary: "Erreur",
                detail: errorMessage,
                life: 3000,
            });
        },
    });
}
</script>

<template>
    <div class="fixed top-0 left-0 w-screen h-screen bg-teal-900 bg-cover bg-center" 
        style="background-image: url('/assets/images/bg-waves.png');">
        <Head title="| Log in" />
        <Toast position="top-center" />

        <div class="flex items-center justify-center h-full">
            <form @submit.prevent="submit" class="w-[22rem] flex flex-col gap-4 p-4 rounded-md bg-white/10 backdrop-blur-sm">
                <InputText
                    id="email"
                    v-model="loginForm.email"
                    type="email"
                    placeholder="Email"
                />
                <Password
                    id="password"
                    v-model="loginForm.password"
                    placeholder="Password"
                    :feedback="false"
                    toggleMask
                />

                <Button
                    severity="contrast"
                    type="button"
                    label="Login"
                    :loading="loginForm.processing"
                    @click="submit"
                />
                <div>
                    <p>Don't have an account? <Link :href="route('register')" class="text-gray-200 hover:text-gray-50 font-semibold">Register</Link></p>
                </div>
            </form>
        </div>
    </div>
</template>
