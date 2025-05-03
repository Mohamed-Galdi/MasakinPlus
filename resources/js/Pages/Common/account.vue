<script setup>
import DynamicLayout from "@/Layouts/DynamicLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import FileUpload from "@/Components/PrimeVilt/FileUpload.vue";
import InputText from "primevue/inputtext";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import Dialog from "primevue/dialog";
import Password from "primevue/password";
import Image from "primevue/image";
import Button from "primevue/button";
import Message from "primevue/message";
import  Header from "@/Components/Header.vue";

defineOptions({
    layout: DynamicLayout,
});

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

let userType;

switch (props.user.type) {
    case "owner":
        userType = "حساب مالك";
        break;
    case "investor":
        userType = "حساب مستثمر";
        break;
    case "tenant":
        userType = "حساب مستأجر";
        break;
    default:
        userType = "حساب غير معروف";
        break;
}

const toast = useToast();
const activeSection = ref("profile");

// Username Form
const usernameForm = useForm({
    name: props.user.name,
});
const editUsernameMode = ref(false);

function updateUsername() {
    usernameForm.post(route("account.editUsername"), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "نجاح",
                detail: "تم تحديث اسم المستخدم بنجاح",
                life: 3000,
            });
            editUsernameMode.value = false;
        },
        onError: () => {
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: Object.values(usernameForm.errors)[0],
                life: 3000,
            });
        },
    });
}

function cancelUsernameEdit() {
    usernameForm.name = props.user.name;
    editUsernameMode.value = false;
}

// Email Form
const emailForm = useForm({
    email: props.user.email,
});
const editEmailMode = ref(false);

function updateEmail() {
    emailForm.post(route("account.editEmail"), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "نجاح",
                detail: "تم تحديث البريد الإلكتروني بنجاح",
                life: 3000,
            });
            editEmailMode.value = false;
        },
        onError: () => {
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: Object.values(emailForm.errors)[0],
                life: 3000,
            });
        },
    });
}

function cancelEmailEdit() {
    emailForm.email = props.user.email;
    editEmailMode.value = false;
}

// Password Form
const updatePasswordModal = ref(false);
const updatePasswordForm = useForm({
    current_password: null,
    password: null,
    password_confirmation: null,
});

function updatePassword() {
    updatePasswordForm.post(route("account.updatePassword"), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "نجاح",
                detail: "تم تحديث كلمة المرور بنجاح",
                life: 3000,
            });
            updatePasswordForm.reset();
            updatePasswordModal.value = false;
        },
        onError: () => {
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: Object.values(updatePasswordForm.errors)[0],
                life: 3000,
            });
        },
    });
}

// Profile Image
const profileImage = ref(props.user.image);
const updateImageModal = ref(false);
const tempFile = ref(null);

function handleFileUploaded(fileFolder) {
    tempFile.value = fileFolder;
}

function handleFileReverted() {
    tempFile.value = null;
}

function openUpdateImageModal() {
    tempFile.value = profileImage.value;
    updateImageModal.value = true;
}

const updateImageForm = useForm({
    image: null,
});

function updateImage() {
    updateImageForm.image = tempFile.value;
    updateImageForm.post(route("account.changeImage"), {
        preserveState: false,
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "نجاح",
                detail: "تم تحديث الصورة بنجاح",
                life: 3000,
            });
            updateImageForm.reset();
            updateImageModal.value = false;
        },
        onError: () => {
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: Object.values(updateImageForm.errors)[0],
                life: 3000,
            });
        },
    });
}

// Navigation items
const menuItems = [
    { id: "profile", label: "الملف الشخصي", icon: "pi pi-user" },
    { id: "security", label: "الأمان", icon: "pi pi-shield" },
];

// Logout
const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div class="min-h-screen">
        <Head title="| إعدادات الحساب" />
        <Toast position="top-center" />

        <!-- Image Update Modal -->
        <Dialog
            v-model:visible="updateImageModal"
            modal
            header="تحديث صورة الملف الشخصي"
            :style="{ width: '32rem' }"
            class="m-2"
        >
            <FileUpload
                :initial-file="tempFile"
                :allowedFileTypes="['image/png', 'image/jpeg', 'image/jpg']"
                @file-uploaded="handleFileUploaded"
                @file-reverted="handleFileReverted"
                class="mb-4"
            />
            <Button
                type="button"
                label="تحديث"
                :loading="updateImageForm.processing"
                @click="updateImage"
                class="w-full"
            />
        </Dialog>

        <!-- Password Update Modal -->
        <Dialog
            v-model:visible="updatePasswordModal"
            modal
            header="تحديث كلمة المرور"
            :style="{ width: '32rem' }"
            class="m-2"
        >
            <form @submit.prevent="updatePassword" class="space-y-4 p-4">
                <Password
                    v-model="updatePasswordForm.current_password"
                    placeholder="كلمة المرور الحالية"
                    toggleMask
                    :feedback="false"
                    class="w-full"
                />
                <Password
                    v-model="updatePasswordForm.password"
                    placeholder="كلمة المرور الجديدة"
                    toggleMask
                    :feedback="false"
                    class="w-full"
                />
                <Password
                    v-model="updatePasswordForm.password_confirmation"
                    placeholder="تأكيد كلمة المرور الجديدة"
                    toggleMask
                    :feedback="false"
                    class="w-full"
                />
                <Button
                    type="submit"
                    label="تحديث"
                    class="w-full"
                    :loading="updatePasswordForm.processing"
                />
            </form>
        </Dialog>

        <!-- Header -->
        <Header
            icon="fa-solid fa-user"
            title="إعدادات الحساب"
            subtitle="إدارة إعدادات الحساب وإعدادات الأمان"
        >
            <button
                @click="logout"
                class="btn bg-slate-200 hover:bg-slate-100 text-slate-800 md:w-fit w-full mt-2 md:mt-0"
            >
                <span>تسجيل الخروج</span>
                <i class="pi pi-sign-out scale-x-[-1]"></i>
            </button>            
        </Header>

        <!-- Main Content -->
        <div class="container mx-auto max-w-7xl font-BeinNormal">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar -->
                <div class="lg:w-1/4">
                    <div
                        class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6"
                    >
                        <div
                            class="bg-gradient-to-r from-teal-600 to-teal-700 h-32 relative"
                        >
                            <div
                                class="absolute -bottom-16 left-1/2 transform -translate-x-1/2"
                            >
                                <div class="relative group">
                                    <div
                                        class="rounded-full h-32 w-32 overflow-hidden border-4 border-white bg-white"
                                    >
                                        <Image
                                            :src="profileImage"
                                            alt="Profile Image"
                                            imageClass="object-cover h-full w-full"
                                            preview
                                        />
                                    </div>

                                    <div
                                        class="absolute bottom-2 border border-gray-500 right-0 bg-gray-50 rounded-full p-2 shadow-md cursor-pointer hover:bg-gray-300 transition-colors flex items-center justify-center"
                                        @click="openUpdateImageModal"
                                    >
                                        <i
                                            class="pi pi-pencil text-gray-800"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-20 pb-6 px-6 text-center">
                            <h2 class="text-2xl font-bold text-teal-800">
                                {{ usernameForm.name }}
                            </h2>
                            <p class="text-gray-500 mt-1 font-sans">
                                {{ emailForm.email }}
                            </p>

                            <div class="mt-2 flex justify-center">
                                <p
                                    class="p-1 bg-slate-800 text-teal-50 text-sm rounded-md font-sans font-bold"
                                >
                                    {{ userType }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-4">
                            <h3
                                class="text-lg font-semibold text-gray-700 mb-4"
                            >
                                إعدادات الحساب
                            </h3>
                            <div class="space-y-2">
                                <div
                                    v-for="item in menuItems"
                                    :key="item.id"
                                    @click="activeSection = item.id"
                                    :class="[
                                        'flex items-center p-3 rounded-lg cursor-pointer transition-colors',
                                        activeSection === item.id
                                            ? 'bg-teal-50 text-teal-600'
                                            : 'hover:bg-gray-50 text-gray-700 hover:text-gray-900',
                                    ]"
                                >
                                    <i
                                        :class="[
                                            item.icon,
                                            'ml-3',
                                            activeSection === item.id
                                                ? 'text-teal-600'
                                                : 'text-gray-500',
                                        ]"
                                    ></i>
                                    <span class="font-medium">{{
                                        item.label
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="lg:w-3/4">
                    <!-- Profile Section -->
                    <div
                        v-if="activeSection === 'profile'"
                        class="bg-white rounded-2xl shadow-sm overflow-hidden"
                    >
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-2xl font-bold text-gray-800">
                                الملف الشخصي
                            </h2>
                            <p class="text-gray-500 mt-1">
                                إدارة معلومات حسابك الشخصي
                            </p>
                        </div>

                        <!-- Username Section -->
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    اسم المستخدم
                                </h3>
                                <Button
                                    v-if="!editUsernameMode"
                                    icon="pi pi-pencil"
                                    text
                                    rounded
                                    aria-label="تعديل"
                                    @click="editUsernameMode = true"
                                />
                            </div>

                            <div
                                v-if="!editUsernameMode"
                                class="flex items-center"
                            >
                                <p class="text-gray-700">
                                    {{ usernameForm.name }}
                                </p>
                            </div>

                            <div v-else class="space-y-4">
                                <div class="p-float-label">
                                    <InputText
                                        id="username"
                                        v-model="usernameForm.name"
                                        class="w-full"
                                    />
                                </div>
                                <div class="flex gap-2">
                                    <Button
                                        label="حفظ"
                                        icon="pi pi-check"
                                        @click="updateUsername"
                                        :loading="usernameForm.processing"
                                        severity="primary"
                                    />
                                    <Button
                                        label="إلغاء"
                                        icon="pi pi-times"
                                        severity="secondary"
                                        text
                                        @click="cancelUsernameEdit"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Email Section -->
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    البريد الإلكتروني
                                </h3>
                                <Button
                                    v-if="!editEmailMode"
                                    icon="pi pi-pencil"
                                    text
                                    rounded
                                    aria-label="تعديل"
                                    @click="editEmailMode = true"
                                />
                            </div>

                            <Message severity="info" class="mb-4">
                                <span
                                    >سيتم استخدام البريد الإلكتروني لاستلام رمز
                                    التحقق (OTP) عند تسجيل الدخول</span
                                >
                            </Message>

                            <div
                                v-if="!editEmailMode"
                                class="flex items-center"
                            >
                                <p
                                    class="text-gray-800 font-sans font-semibold"
                                >
                                    {{ emailForm.email }}
                                </p>
                            </div>

                            <div v-else class="space-y-4">
                                <div class="p-float-label">
                                    <InputText
                                        id="email"
                                        v-model="emailForm.email"
                                        class="w-full"
                                    />
                                </div>
                                <div class="flex gap-2">
                                    <Button
                                        label="حفظ"
                                        icon="pi pi-check"
                                        @click="updateEmail"
                                        :loading="emailForm.processing"
                                        severity="primary"
                                    />
                                    <Button
                                        label="إلغاء"
                                        icon="pi pi-times"
                                        severity="secondary"
                                        text
                                        @click="cancelEmailEdit"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Section -->
                    <div
                        v-if="activeSection === 'security'"
                        class="bg-white rounded-2xl shadow-sm overflow-hidden"
                    >
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-2xl font-bold text-gray-800">
                                إعدادات الأمان
                            </h2>
                            <p class="text-gray-500 mt-1">
                                إدارة إعدادات أمان حسابك
                            </p>
                        </div>

                        <!-- Password Section -->
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-start gap-4">
                                <div class="flex-grow">
                                    <h3
                                        class="text-lg font-semibold text-gray-800"
                                    >
                                        كلمة المرور
                                    </h3>

                                    <p class="text-gray-600 mb-4">
                                        لحماية حسابك، ننصح بتغيير كلمة المرور
                                        بانتظام واستخدام كلمة مرور قوية تجمع بين
                                        الأحرف والأرقام والرموز.
                                    </p>

                                    <Button
                                        label="تغيير كلمة المرور"
                                        icon="pi pi-key"
                                        @click="updatePasswordModal = true"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
