<script setup>
import { ref } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";
import Avatar from "primevue/avatar";
import Tag from "primevue/tag";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import ConfirmDialog from "primevue/confirmdialog";
import { useConfirm } from "primevue/useconfirm";
import Tooltip from "primevue/tooltip";
import Divider from "primevue/divider";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const confirm = useConfirm();
const showSuspendDialog = ref(false);
const suspensionReason = ref("");
const isUserActive = ref(props.user.is_active === 1);

// Function to get tag severity based on user type
const getTypeSeverity = (type) => {
    switch (type) {
        case "owner":
            return "success";
        case "investor":
            return "warning";
        case "tenant":
            return "info";
        default:
            return "secondary";
    }
};

// Function to format date
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("ar-SA", {
        year: "numeric",
        month: "long",
        day: "numeric",
    }).format(date);
};

// Function to get user type in Arabic
const getUserTypeArabic = (type) => {
    switch (type) {
        case "owner":
            return "مالك";
        case "investor":
            return "مستثمر";
        case "tenant":
            return "مستأجر";
        default:
            return type;
    }
};

// Toggle user status with confirmation
const toggleUserStatus = () => {
    if (isUserActive.value) {
        // If currently active, show suspension dialog
        showSuspendDialog.value = true;
    } else {
        // If currently suspended, confirm reactivation
        confirm.require({
            message: "هل أنت متأكد من رغبتك في إعادة تنشيط هذا المستخدم؟",
            header: "تأكيد إعادة التنشيط",
            icon: "pi pi-info-circle",
            acceptLabel: "نعم، إعادة تنشيط",
            rejectLabel: "إلغاء",
            accept: () => {
                // Here you would call your API to reactivate the user
                isUserActive.value = true;
                // Show success message
                // This would be replaced with your actual implementation
            },
            reject: () => {
                // Reset the switch to match the current state
                isUserActive.value = true; // Corrected: Reset to true to avoid hook call issue
            },
        });
    }
};

// Confirm user suspension
const confirmSuspension = () => {
    // Here you would call your API to suspend the user with the reason
    isUserActive.value = false;
    showSuspendDialog.value = false;
    // Show success message
    // This would be replaced with your actual implementation
};
</script>

<template>
    <div class="user-details-page">
        <!-- Confirm Dialog Component -->
        <ConfirmDialog />

        <!-- Header with Back Button -->
        <div class="flex items-center justify-between mb-6">
            <Link
                :href="route('admin.users.index')"
                class="flex items-center gap-2 text-gray-600 hover:text-primary transition-colors"
            >
                <i class="pi pi-arrow-right"></i>
                <span>العودة إلى قائمة المستخدمين</span>
            </Link>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Sidebar -->
            <div class="lg:col-span-3">
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-100 p-0 sticky top-4"
                >
                    <!-- User Profile -->
                    <div class="p-6 text-center border-b border-gray-100">
                        <div class="relative inline-block mb-4">
                            <Avatar
                                :image="user.image"
                                size="xlarge"
                                shape="circle"
                                class="w-24 h-24"
                            />
                            <span
                                class="absolute bottom-0 left-0 w-5 h-5 rounded-full border-2 border-white"
                                :class="
                                    isUserActive ? 'bg-green-500' : 'bg-red-500'
                                "
                            ></span>
                        </div>

                        <h2 class="text-xl font-bold mb-1">{{ user.name }}</h2>
                        <p class="text-gray-500 text-sm mb-3">
                            {{ user.email }}
                        </p>

                        <div class="flex justify-center gap-2 mb-4">
                            <Tag
                                :value="getUserTypeArabic(user.type)"
                                :severity="getTypeSeverity(user.type)"
                            />
                            <Tag
                                :value="isUserActive ? 'نشط' : 'معلق'"
                                :severity="isUserActive ? 'success' : 'danger'"
                            />
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="p-4">
                        <h3
                            class="text-sm font-semibold text-gray-500 mb-3 px-2"
                        >
                            إجراءات سريعة
                        </h3>

                        <div class="flex flex-col gap-2">
                            <Button
                                :label="
                                    isUserActive
                                        ? 'تعليق الحساب'
                                        : 'إعادة تنشيط الحساب'
                                "
                                :icon="
                                    isUserActive ? 'pi pi-ban' : 'pi pi-check'
                                "
                                :severity="isUserActive ? 'danger' : 'success'"
                                :outlined="!isUserActive"
                                class="p-button-sm justify-start"
                                @click="toggleUserStatus"
                            />

                            <Button
                                label="تعديل المعلومات"
                                icon="pi pi-pencil"
                                outlined
                                class="p-button-sm justify-start"
                            />

                            <Button
                                label="إرسال بريد إلكتروني"
                                icon="pi pi-envelope"
                                text
                                class="p-button-sm justify-start"
                            />
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="p-4 border-t border-gray-100">
                        <h3
                            class="text-sm font-semibold text-gray-500 mb-3 px-2"
                        >
                            معلومات الحساب
                        </h3>

                        <div class="flex flex-col gap-3 px-2">
                            <div class="flex items-center gap-2">
                                <i class="pi pi-calendar text-gray-400"></i>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500"
                                        >تاريخ التسجيل</span
                                    >
                                    <span class="text-sm">{{
                                        formatDate(user.created_at)
                                    }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <i class="pi pi-clock text-gray-400"></i>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500"
                                        >آخر تحديث</span
                                    >
                                    <span class="text-sm">{{
                                        user.updated_at
                                            ? formatDate(user.updated_at)
                                            : "لم يتم التحديث"
                                    }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <i class="pi pi-id-card text-gray-400"></i>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500"
                                        >معرف المستخدم</span
                                    >
                                    <span class="text-sm font-mono">{{
                                        user.id
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-9">
                <!-- User Information Card -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i class="pi pi-user text-primary"></i>
                            المعلومات الشخصية
                        </h2>
                        <Button
                            icon="pi pi-pencil"
                            text
                            v-tooltip="'تعديل المعلومات الشخصية'"
                        />
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6"
                    >
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 mb-1"
                                >الاسم الكامل</span
                            >
                            <span class="font-medium">{{ user.name }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 mb-1"
                                >البريد الإلكتروني</span
                            >
                            <span class="font-medium">{{ user.email }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 mb-1"
                                >رقم الهاتف</span
                            >
                            <span class="font-medium">{{
                                user.phone || "غير متوفر"
                            }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 mb-1"
                                >تاريخ الميلاد</span
                            >
                            <span class="font-medium">{{
                                user.birthdate || "غير متوفر"
                            }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 mb-1"
                                >الجنس</span
                            >
                            <span class="font-medium">{{
                                user.gender || "غير متوفر"
                            }}</span>
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 mb-1"
                                >الجنسية</span
                            >
                            <span class="font-medium">{{
                                user.nationality || "غير متوفر"
                            }}</span>
                        </div>
                    </div>
                </div>

                <!-- Account Status Card -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i class="pi pi-shield text-primary"></i>
                            حالة الحساب
                        </h2>
                    </div>

                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Status Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center"
                                    :class="
                                        isUserActive
                                            ? 'bg-green-100'
                                            : 'bg-red-100'
                                    "
                                >
                                    <i
                                        :class="
                                            isUserActive
                                                ? 'pi pi-check text-green-500'
                                                : 'pi pi-ban text-red-500'
                                        "
                                        style="font-size: 1.5rem"
                                    ></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold m-0">
                                        {{
                                            isUserActive
                                                ? "الحساب نشط"
                                                : "الحساب معلق"
                                        }}
                                    </h3>
                                    <p class="text-gray-500 m-0">
                                        {{
                                            isUserActive
                                                ? "يمكن للمستخدم الوصول إلى النظام"
                                                : "تم منع المستخدم من الوصول إلى النظام"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4"
                            >
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500 mb-1"
                                        >نوع الحساب</span
                                    >
                                    <Tag
                                        :value="getUserTypeArabic(user.type)"
                                        :severity="getTypeSeverity(user.type)"
                                    />
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-500 mb-1"
                                        >تاريخ التحقق من البريد</span
                                    >
                                    <span class="font-medium">{{
                                        user.email_verified_at
                                            ? formatDate(user.email_verified_at)
                                            : "غير متحقق"
                                    }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Actions -->
                        <div
                            class="md:w-64 flex flex-col gap-3 md:border-r md:border-gray-100 md:pr-6"
                        >
                            <h3 class="text-lg font-semibold">إدارة الحساب</h3>
                            <p class="text-sm text-gray-500">
                                يمكنك تغيير حالة الحساب من هنا
                            </p>

                            <Button
                                :label="
                                    isUserActive
                                        ? 'تعليق الحساب'
                                        : 'إعادة تنشيط الحساب'
                                "
                                :icon="
                                    isUserActive ? 'pi pi-ban' : 'pi pi-check'
                                "
                                :severity="isUserActive ? 'danger' : 'success'"
                                :outlined="!isUserActive"
                                class="w-full mt-2"
                                @click="toggleUserStatus"
                            />
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-100 p-6"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i class="pi pi-phone text-primary"></i>
                            معلومات الاتصال
                        </h2>
                        <Button
                            icon="pi pi-pencil"
                            text
                            v-tooltip="'تعديل معلومات الاتصال'"
                        />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            class="bg-gray-50 rounded-lg p-4 flex flex-col items-center text-center"
                        >
                            <div
                                class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mb-3"
                            >
                                <i class="pi pi-envelope text-primary"></i>
                            </div>
                            <h3 class="text-base font-semibold mb-1">
                                البريد الإلكتروني
                            </h3>
                            <p class="text-gray-600 m-0">{{ user.email }}</p>
                        </div>

                        <div
                            class="bg-gray-50 rounded-lg p-4 flex flex-col items-center text-center"
                        >
                            <div
                                class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mb-3"
                            >
                                <i class="pi pi-phone text-primary"></i>
                            </div>
                            <h3 class="text-base font-semibold mb-1">
                                رقم الهاتف
                            </h3>
                            <p class="text-gray-600 m-0">
                                {{ user.phone || "غير متوفر" }}
                            </p>
                        </div>

                        <div
                            class="bg-gray-50 rounded-lg p-4 flex flex-col items-center text-center"
                        >
                            <div
                                class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mb-3"
                            >
                                <i class="pi pi-map-marker text-primary"></i>
                            </div>
                            <h3 class="text-base font-semibold mb-1">
                                العنوان
                            </h3>
                            <p class="text-gray-600 m-0">
                                {{ user.address || "غير متوفر" }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suspension Dialog -->
        <Dialog
            v-model:visible="showSuspendDialog"
            modal
            :header="'تعليق حساب ' + user.name"
            :style="{ width: '450px' }"
            :closable="true"
            class="rtl-dialog"
        >
            <div class="flex flex-col gap-4 p-3">
                <div
                    class="flex items-center gap-3 bg-yellow-50 text-yellow-800 p-4 rounded-lg"
                >
                    <i
                        class="pi pi-exclamation-triangle text-yellow-500 text-xl"
                    ></i>
                    <p class="m-0">
                        سيتم منع المستخدم من الوصول إلى حسابه حتى يتم إعادة
                        تنشيطه.
                    </p>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="suspensionReason" class="font-medium"
                        >سبب التعليق</label
                    >
                    <textarea
                        id="suspensionReason"
                        v-model="suspensionReason"
                        rows="3"
                        class="w-full p-3 border border-gray-300 rounded-lg"
                        placeholder="أدخل سبب تعليق الحساب..."
                    ></textarea>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button
                        label="إلغاء"
                        icon="pi pi-times"
                        outlined
                        @click="
                            showSuspendDialog = false;
                            isUserActive = true;
                        "
                    />
                    <Button
                        label="تأكيد التعليق"
                        icon="pi pi-check"
                        severity="danger"
                        @click="confirmSuspension"
                    />
                </div>
            </template>
        </Dialog>
    </div>
</template>

<style scoped>
.user-details-page {
    direction: rtl;
}

/* Dialog customization */
:deep(.p-dialog-header) {
    padding: 1.25rem;
    border-bottom: 1px solid #e2e8f0;
}

:deep(.p-dialog-content) {
    padding: 0;
}

:deep(.p-dialog-footer) {
    padding: 1.25rem;
    border-top: 1px solid #e2e8f0;
}

.rtl-dialog :deep(.p-dialog-header),
.rtl-dialog :deep(.p-dialog-content),
.rtl-dialog :deep(.p-dialog-footer) {
    direction: rtl;
    text-align: right;
}

/* Button customization */
:deep(.p-button.justify-start) {
    justify-content: flex-start;
}

/* Sticky sidebar */
@media (min-width: 1024px) {
    .sticky {
        position: sticky;
        top: 1rem;
    }
}
</style>
