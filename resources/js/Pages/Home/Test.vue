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
import HomeLayout from "@/Layouts/HomeLayout.vue";
import { gsap } from "gsap";

defineOptions({
    layout: HomeLayout,
});

const toast = useToast();

const loginForm = useForm({
    email: "",
    password: "",
    remember: false,
});

// Login form submission
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

// Generate paths data function
const generatePaths = (position) => {
    return Array.from({ length: 36 }, (_, i) => ({
        id: i,
        d: `M-${380 - i * 5 * position} -${189 + i * 6}C-${
            380 - i * 5 * position
        } -${189 + i * 6} -${312 - i * 5 * position} ${216 - i * 6} ${
            152 - i * 5 * position
        } ${343 - i * 6}C${616 - i * 5 * position} ${470 - i * 6} ${
            684 - i * 5 * position
        } ${875 - i * 6} ${684 - i * 5 * position} ${875 - i * 6}`,
        width: 0.5 + i * 0.03,
        opacity: 0.1 + i * 0.03,
    }));
};

// Create paths for both positions
const pathsPositive = ref(generatePaths(1));
const pathsNegative = ref(generatePaths(-1));

// Animate paths on mount
onMounted(() => {
    // Animate positive position paths
    pathsPositive.value.forEach((path) => {
        const pathElement = document.getElementById(`path-pos-${path.id}`);
        animatePath(pathElement);
    });

    // Animate negative position paths
    pathsNegative.value.forEach((path) => {
        const pathElement = document.getElementById(`path-neg-${path.id}`);
        animatePath(pathElement);
    });
});

// Helper function to animate a path
function animatePath(pathElement) {
    if (pathElement) {
        gsap.set(pathElement, {
            strokeDasharray: pathElement.getTotalLength(),
            strokeDashoffset: pathElement.getTotalLength() * 0.7,
            opacity: 0.6,
        });

        gsap.to(pathElement, {
            strokeDashoffset: -pathElement.getTotalLength(),
            opacity: [0.3, 0.6, 0.3],
            duration: 20 + Math.random() * 10,
            repeat: -1,
            ease: "none",
        });
    }
}
</script>

<template>
    <div class="relative min-h-screen w-full overflow-hidden bg-gray-50">
        <!-- Background paths -->
        <div class="absolute inset-0 pointer-events-none">
            <svg
                class="w-full h-full text-teal-700/40"
                viewBox="0 0 696 316"
                fill="none"
            >
                <title>Background Paths</title>
                <path
                    v-for="path in pathsPositive"
                    :key="`pos-${path.id}`"
                    :id="`path-pos-${path.id}`"
                    :d="path.d"
                    stroke="currentColor"
                    :stroke-width="path.width"
                    :stroke-opacity="path.opacity"
                    fill="none"
                />
                <path
                    v-for="path in pathsNegative"
                    :key="`neg-${path.id}`"
                    :id="`path-neg-${path.id}`"
                    :d="path.d"
                    stroke="currentColor"
                    :stroke-width="path.width"
                    :stroke-opacity="path.opacity"
                    fill="none"
                />
            </svg>
        </div>

        <!-- Login content -->
        <div class="flex items-center justify-center font-BeinNormal h-[90vh]">
            <Head title="| TEST " />
            <Toast position="top-center" />

            <div class="relative w-full max-w-md z-10">
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

                        <form
                            @submit.prevent="submit"
                            class="flex flex-col gap-6"
                        >
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
                                    :href="route('forgot-password')"
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
    </div>
</template>

<style scoped>
/* Optional: Add any additional styling here if needed */
</style>
