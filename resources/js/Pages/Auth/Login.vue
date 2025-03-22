<script setup>
import { Head, useForm } from "@inertiajs/vue3";
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
    <div class="container h-screen bg-teal-900">
        <Head title="| Log in" />
        <Toast position="top-center" />

        <div class="flex items-center justify-center h-full">
            <form @submit.prevent="submit" class="w-1/4 flex flex-col gap-4">
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
            </form>
        </div>
    </div>
</template>
<style scoped>
.container {
    background-image: url("../../../../public/assets/images/bg-waves.png");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
}
</style>
