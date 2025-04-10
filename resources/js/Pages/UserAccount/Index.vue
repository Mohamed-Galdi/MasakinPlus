<script setup>
import DynamicLayout from "@/Layouts/DynamicLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import FileUpload from "@/Components/PrimeVilt/FileUpload.vue";
import InputText from "primevue/inputtext";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import Dialog from "primevue/dialog";
import Password from "primevue/password";
import Image from "primevue/image";
import Button from "primevue/button";
import Tabs from "primevue/tabs";
import TabList from "primevue/tablist";
import Tab from "primevue/tab";
import TabPanels from "primevue/tabpanels";
import TabPanel from "primevue/tabpanel";

defineOptions({
    layout: DynamicLayout,
});

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const toast = useToast();

// Profile Info Form
const infosForm = useForm({
    name: props.user.name,
    email: props.user.email,
});
const editFormDisabled = ref(true);

function handleEditInfos() {
    if (!editFormDisabled.value) {
        infosForm.post(route("account.editAccount"), {
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: " نجاح",
                    detail: "تم تحديث الحساب بنجاح",
                    life: 3000,
                });
                editFormDisabled.value = true;
            },
            onError: () => {
                toast.add({
                    severity: "error",
                    summary: "خطأ",
                    detail: Object.values(infosForm.errors)[0],
                    life: 3000,
                });
            },
        });
    } else {
        editFormDisabled.value = false;
    }
}

function handleCancel() {
    infosForm.name = props.user.name;
    infosForm.email = props.user.email;
    editFormDisabled.value = true;
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
                summary: " نجاح",
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
</script>

<template>
    <div class="container mx-auto px-4 py-8 max-w-7xl font-BeinNormal">
        <Head title="| Account" />

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

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar Profile Card -->
            <div class="lg:w-1/4 w-full">
                <div
                    class="bg-white border border-slate-500/50 shadow-sm p-6 h-[22rem] flex flex-col justify-between rounded-lg overflow-hidden"
                >
                    <div class="flex flex-col items-center">
                        <Image
                            v-if="profileImage"
                            :src="profileImage"
                            alt="Profile Image"
                            class="w-32 h-32 rounded-md overflow-hidden mb-4 border-2 border-gray-500"
                            preview
                        />
                        <div
                            v-else
                            class="flex items-center bg-teal-700 justify-center w-32 h-32 rounded-md overflow-hidden mb-4 border-2 border-gray-500"
                        >
                            <i
                                class="pi pi-user text-white"
                                style="font-size: 3.5rem"
                            ></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ infosForm.name }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            {{ infosForm.email }}
                        </p>
                    </div>
                    <Button
                                                severity="contrast"

                        label="تحديث الصورة"
                        class="w-full"
                        @click="openUpdateImageModal"
                    />
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:w-3/4 w-full">
                <div
                    class="bg-white border border-slate-500/50 shadow-sm h-[22rem] rounded-lg overflow-hidden"
                >
                    <Tabs value="0">
                        <TabList>
                            <Tab value="0">الحساب</Tab>
                            <Tab value="1"> إعدادات الأمان</Tab>
                        </TabList>
                        <TabPanels>
                            <TabPanel value="0">
                                <div
                                    class="h-[17rem] flex flex-col justify-between py-2"
                                >
                                    <div>
                                        <h2
                                            class="text-xl font-semibold text-gray-800 mb-6"
                                        >
                                            معلومات الحساب
                                        </h2>
                                        <div class="space-y-4">
                                            <InputText
                                                v-model="infosForm.name"
                                                placeholder="الاسم"
                                                :disabled="editFormDisabled"
                                                class="w-full"
                                            />
                                            <InputText
                                                v-model="infosForm.email"
                                                placeholder="البريد الإلكتروني"
                                                :disabled="editFormDisabled"
                                                class="w-full"
                                            />
                                        </div>
                                    </div>
                                    <div class="flex gap-3 mt-6">
                                        <Button
                                            :label="
                                                editFormDisabled
                                                    ? 'تحديث'
                                                    : 'تأكيد'
                                            "
                                            :class="
                                                editFormDisabled
                                                    ? 'w-full'
                                                    : 'w-1/2'
                                            "
                                            :loading="infosForm.processing"
                                            @click="handleEditInfos"
                                        />
                                        <Button
                                            v-if="!editFormDisabled"
                                            label="إلغاء"
                                            class="w-1/2 bg-gray-500 hover:bg-gray-600 text-white"
                                            @click="handleCancel"
                                        />
                                    </div>
                                </div>
                            </TabPanel>

                            <!-- Security Tab -->
                            <TabPanel value="1">
                                <div
                                    class="py-2 h-[17rem] flex flex-col justify-between"
                                >
                                    <div>
                                        <h2
                                            class="text-xl font-semibold text-gray-800 mb-6"
                                        >
                                            إعدادات الأمان
                                        </h2>
                                        <div class="space-y-4">
                                            <div
                                                class="bg-slate-100 p-4 rounded-lg"
                                            >
                                                <h3
                                                    class="font-medium text-gray-700"
                                                >
                                                    كلمة المرور
                                                </h3>
                                                <p
                                                    class="text-sm text-gray-600 mt-1"
                                                >
                                                    حدث كلمة مرورك للحفاظ على
                                                    أمان حسابك. ستحتاج إلى تسجيل
                                                    الدخول مرة أخرى بعد تغييرها.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <Button
                                        label="تغيير كلمة المرور"
                                        @click="updatePasswordModal = true"
                                    />
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </Tabs>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.p-inputtext {
    @apply border-gray-300 focus:border-teal-400 focus:ring-teal-400 rounded-md;
}

.p-button {
    @apply transition-colors duration-200 rounded-md;
}

.p-tabview .p-tabview-nav {
    @apply border-b border-gray-200 bg-gray-50 rounded-t-xl;
}

.p-tabview .p-tabview-panels {
    @apply p-0 h-full;
}

.p-tabview-nav li .p-tabview-nav-link {
    @apply text-gray-600 hover:text-teal-800;
}

.p-tabview-nav li.p-highlight .p-tabview-nav-link {
    @apply text-teal-800 border-b-2 border-teal-800;
}
</style>
