<script setup>
import { router } from "@inertiajs/vue3";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";

const toast = useToast();

const resend = () => {
    router.post(route("verification.send"));
    setTimeout(() => {
        toast.add({
            severity: "success",
            summary: "تم إعادة إرسال رابط التفعيل",
            detail: "يرجى التحقق من صندوق الوارد ",
            life: 3000,
        });
    }, 1000);
};
</script>

<template>
    <div class="min-h-screen bg-slate-100 flex flex-col">
        <Toast position="top-center" />
        <!-- Main Content -->
        <div class="flex-grow flex items-center justify-center">
            <div
                class="max-w-3xl w-full bg-white rounded-xl shadow-lg text-right px-12 py-6 font-BeinNormal space-y-6"
            >
                <h2 class="text-3xl font-bold text-teal-800">
                    تأكيد بريدك الإلكتروني
                </h2>
                <p class="text-gray-600 text-lg leading-relaxed">
                    لقد أرسلنا رابط تفعيل إلى بريدك الإلكتروني. يرجى التحقق من
                    صندوق الوارد أو البريد المزعج لتفعيل حسابك في
                    <span class="font-semibold text-teal-800">مساكن بلس</span>.
                </p>

                <p class="text-sm text-gray-500 text-center">
                    لم تتلق البريد؟
                    <button
                        @click="resend"
                        class="text-teal-600 hover:underline"
                    >
                        إعادة إرسال رابط التفعيل
                    </button>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <footer
            class="bg-white py-4 text-center text-gray-500 text-sm shadow-inner font-BeinNormal"
        >
            <p>
                © {{ new Date().getFullYear() }} مساكن بلس. جميع الحقوق محفوظة.
            </p>
        </footer>
    </div>
</template>
