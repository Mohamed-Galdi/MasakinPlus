<script setup>
import { ref } from "vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import FloatLabel from "primevue/floatlabel";
import Password from "primevue/password";
import Button from "primevue/button";
import Checkbox from "primevue/checkbox";
import Select  from "primevue/select";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import Avatar from "primevue/avatar";
import AvatarGroup from "primevue/avatargroup";
import AuthLayout from "@/Layouts/AuthLayout.vue";

defineOptions({
    layout: AuthLayout,
});

const toast = useToast();

const registerForm = useForm({
    accountType: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const accountType = ref([
    { label: "حساب مالك ", value: "owner" },
    { label: "حساب مستثمر", value: "investor" },
    { label: "حساب مستأجر", value: "tenant" },
]);

function submit() {
    if (!registerForm.terms) {
        toast.add({
            severity: "error",
            summary: "خطأ",
            detail: "يجب الموافقة على الشروط والأحكام للمتابعة.",
            life: 3000,
        });
        return;
    }

    registerForm.post(route("register"), {
        onSuccess: () => {
            registerForm.reset();
        },
        onError: () => {
            const errorMessage = Object.values(registerForm.errors)[0];
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
    <div class="min-h-screen flex pt-8 justify-center font-BeinNormal">
        <Head title="| التسجيل" />
        <Toast position="top-center" />

        <div
            class="flex flex-col-reverse md:flex-row justify-center gap-1 w-full max-w-[90rem] mx-auto"
        >
            <!-- image section -->
            <div class="w-full md:w-1/3 flex justify-center py-2">
                <div
                    class="relative  w-[90%] max-w-md h-[37rem] rounded-b-[1rem] rounded-tr-[1rem] rounded-tl-[5rem] overflow-hidden"
                >
                    <img
                        src="/assets/register_image.jpg"
                        alt="تسجيل العقارات"
                        class="w-full h-full object-cover"
                    />
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent rounded-lg"
                    ></div>
                    <div class="absolute bottom-6 left-6 right-6 text-white">
                        <h2 class="text-2xl font-bold mb-2">
                            انضم إلى أفضل منصة عقارية
                        </h2>
                        <p class="text-sm mb-4">
                            ماسكن بلس توفر لك أسهل وأسرع طريقة لعرض عقارك أو
                            استثماره أو استئجاره بكل سهولة وأمان.
                        </p>
                        <div class="flex gap-2">
                            <AvatarGroup>
                                <Avatar
                                    image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png"
                                    shape="circle"
                                />
                                <Avatar
                                    image="https://primefaces.org/cdn/primevue/images/avatar/asiyajavayant.png"
                                    shape="circle"
                                />
                                <Avatar
                                    image="https://primefaces.org/cdn/primevue/images/avatar/onyamalimba.png"
                                    shape="circle"
                                />
                                <Avatar
                                    image="https://primefaces.org/cdn/primevue/images/avatar/ionibowcher.png"
                                    shape="circle"
                                />
                                <Avatar
                                    image="https://primefaces.org/cdn/primevue/images/avatar/xuxuefeng.png"
                                    shape="circle"
                                />
                            </AvatarGroup>
                            <div>
                                <div>
                                    <i
                                        v-for="i in 5"
                                        :key="i"
                                        class="pi pi-star-fill text-yellow-500 me-1"
                                        style="font-size: 0.8rem"
                                    ></i>
                                </div>
                                <p class="text-sm">أكثر من 250 عميل سعيد</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- قسم نموذج التسجيل -->
            <div class="w-full md:w-1/2 flex justify-center p-6">
                <div class="w-full max-w-md">
                    <h1 class="font-bold mb-2 text-teal-800 font-Bein text-4xl">
                        إنشاء حساب جديد
                    </h1>
                    <p class="text-gray-600 mb-6">
                        ابدأ الآن واستفد من أفضل العروض العقارية.
                    </p>

                    <form @submit.prevent="submit" class="flex flex-col gap-6">
                        <!-- account type -->
                        <Select
                            v-model="registerForm.accountType"
                            :options="accountType"
                            optionLabel="label"
                            placeholder="إختر نوع الحساب"
                            class="w-full"
                        />

                        <!-- username -->
                        <FloatLabel variant="on" class="w-full">
                            <InputText
                                id="username"
                                v-model="registerForm.username"
                                class="w-full"
                            />
                            <label for="username">اسم المستخدم</label>
                        </FloatLabel>

                        <!-- email -->
                        <FloatLabel variant="on" class="w-full">
                            <InputText
                                id="email"
                                v-model="registerForm.email"
                                class="w-full"
                            />
                            <label for="email">البريد الإلكتروني</label>
                        </FloatLabel>

                        <!-- password -->
                        <FloatLabel variant="on" class="w-full">
                            <Password
                                id="password"
                                v-model="registerForm.password"
                                :feedback="false"
                                toggleMask
                                class="w-full mt-1"
                            />
                            <label for="password">كلمة المرور</label>
                        </FloatLabel>

                        <!-- confirm password -->
                        <FloatLabel variant="on" class="w-full">
                            <Password
                                id="confirmPassword"
                                v-model="registerForm.password_confirmation"
                                :feedback="false"
                                toggleMask
                                class="w-full mt-1"
                            />
                            <label for="confirmPassword"
                                >تأكيد كلمة المرور</label
                            >
                        </FloatLabel>

                        <div class="flex items-center gap-2">
                            <Checkbox
                                v-model="registerForm.terms"
                                inputId="terms"
                                name="terms"
                                :binary="true"
                            />
                            <label for="terms" class="text-sm text-gray-600">
                                بالموافقة، أنت توافق على
                                <Link
                                    :href="route('privacy-policy')"
                                    class="text-teal-600 hover:underline"
                                    >الشروط والأحكام</Link
                                >
                            </label>
                        </div>

                        <Button
                            severity="contrast"
                            type="button"
                            label="إنشاء حساب"
                            :loading="registerForm.processing"
                            @click="submit"
                        />

                        <p class="text-center text-sm text-gray-600">
                            لديك حساب بالفعل؟
                            <Link
                                :href="route('login')"
                                class="text-teal-600 hover:underline font-semibold"
                                >تسجيل الدخول</Link
                            >
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
