<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

const registerForm = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

function submit() {
    registerForm.post(route("register"), {
        onSuccess: () => {
            registerForm.reset();
        },
        onError: () => {
            const errorMessage = Object.values(registerForm.errors)[0];
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
        <Head title="| Register" />
        <Toast position="top-center" />

        <div class="flex items-center justify-center h-full">
            <form @submit.prevent="submit" class="w-1/4 flex flex-col gap-4">
                <InputText

                    id="name"
                    v-model="registerForm.name"
                    type="name"
                    placeholder="Name"
                />
                <InputText
                    id="email"
                    v-model="registerForm.email"
                    type="email"
                    placeholder="Email"
                />
                <Password
                    id="password"
                    v-model="registerForm.password"
                    placeholder="Password"
                    :feedback="false"
                    toggleMask
                />
                <Password
                    id="confirmPassword"
                    v-model="registerForm.password_confirmation"
                    placeholder="Confirm Password"
                    :feedback="false"
                    toggleMask
                />

                <Button
                    severity="contrast"
                    type="button"
                    label="Register"
                    :loading="registerForm.processing"
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
