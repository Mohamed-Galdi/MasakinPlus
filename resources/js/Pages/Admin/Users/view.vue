<script setup>
import { ref, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import Avatar from "primevue/avatar";
import Tag from "primevue/tag";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import ConfirmDialog from "primevue/confirmdialog";
import { useConfirm } from "primevue/useconfirm";
import Tooltip from "primevue/tooltip";
import Drawer from "primevue/drawer";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import FloatLabel from "primevue/floatlabel";
import Header from "@/Components/AdminDashboard/Header.vue";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const toast = useToast();
const confirm = useConfirm();
const showSuspendDialog = ref(false);
const suspensionReason = ref("");
const isUserActive = ref(props.user.is_active === 1);

const isMobile = computed(() => window.innerWidth <= 768);

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

// Function to get user type in Arabic
const getUserTypeArabic = (type) => {
    switch (type) {
        case "owner":
            return "حساب مالك";
        case "investor":
            return "حساب مستثمر";
        case "tenant":
            return "حساب مستأجر";
        default:
            return type;
    }
};

// Toggle user status with confirmation
const toggleUserStatus = () => {
    if (isUserActive.value) {
        showSuspendDialog.value = true;
    } else {
        confirm.require({
            message: "هل أنت متأكد من رغبتك في إعادة تنشيط هذا المستخدم؟",
            header: "تأكيد إعادة التنشيط",
            icon: "pi pi-info-circle",
            acceptLabel: "نعم، إعادة تنشيط",
            rejectLabel: "إلغاء",
            accept: () => {
                router.post(
                    route("admin.users.reactivate", { id: props.user.id }),
                    {},
                    {
                        preserveState: false,
                        onSuccess: () => {
                            toast.add({
                                severity: "success",
                                summary: "نجاح",
                                detail: "تم إعادة تنشيط الحساب بنجاح",
                                life: 3000,
                            });
                        },
                        onError: () => {
                            toast.add({
                                severity: "error",
                                summary: "خطأ",
                                detail: "حدث خطأ أثناء إعادة تنشيط الحساب",
                                life: 3000,
                            });
                        },
                    }
                );
            },
            reject: () => {},
        });
    }
};

// Confirm user suspension
const confirmSuspension = () => {
    router.post(
        route("admin.users.suspend", { id: props.user.id }),
        { suspension_reason: suspensionReason.value },

        {
            preserveState: false,
            onSuccess: () => {
                showSuspendDialog.value = false;
                toast.add({
                    severity: "success",
                    summary: "نجاح",
                    detail: "تم تعليق الحساب بنجاح",
                    life: 3000,
                });
            },
            onError: () => {
                toast.add({
                    severity: "error",
                    summary: "خطأ",
                    detail: "حدث خطأ أثناء تعليق الحساب",
                    life: 3000,
                });
            },
        }
    );
};

// ########################################################################################## Create ticket
const showNewTicketDrawer = ref(false);
const ticketForm = useForm({
    subject: "",
    content: "",
    users_id: [props.user.id],
});

const sendTicket = () => {
    ticketForm.post(route("admin.support.create"), {
        preserveState: false,
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "نجاح",
                detail: "تم إنشاء تذكرة جديدة بنجاح",
                life: 3000,
            });
            showNewTicketDrawer.value = false;
            ticketForm.reset();
        },
        onError: () => {
            const errorMessage = Object.values(ticketForm.errors)[0];
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: errorMessage,
                life: 3000,
            });
        },
    });
};
</script>

<template>
    <div>
        <Toast position="top-center" />

        <!-- New Ticket Drawer -->
        <Drawer
            v-model:visible="showNewTicketDrawer"
            :style="{ width: isMobile ? '100%' : '50vw' }"
        >
            <template #header>
                <div class="flex justify-center items-center gap-2">
                    <i
                        class="pi pi-ticket text-teal-800"
                        style="font-size: 1.5rem"
                    ></i>
                    <h1 class="text-2xl font-bold m-0 text-teal-800 font-Bein">
                        إنشاء تذكرة جديدة
                    </h1>
                </div>
            </template>
            <div class="p-8 h-full">
                <form
                    class="flex flex-col gap-6 h-full"
                    @submit.prevent="sendTicket"
                >
                    <!-- Subject Field -->
                    <div>
                        <FloatLabel variant="on" class="w-full">
                            <InputText
                                id="subject"
                                v-model="ticketForm.subject"
                                class="w-full"
                            />
                            <label for="subject" class="text-gray-600"
                                >الموضوع</label
                            >
                        </FloatLabel>
                    </div>

                    <!-- Message Content -->
                    <div class="form-group flex-1">
                        <FloatLabel variant="on" class="w-full h-full">
                            <Textarea
                                class="w-full h-full"
                                id="message"
                                v-model="ticketForm.content"
                                rows="8"
                                style="resize: none"
                            />
                            <label for="message" class="text-gray-600"
                                >محتوى التذكرة</label
                            >
                        </FloatLabel>
                    </div>

                    <!-- Submit Button -->
                    <Button
                        label="إرسال التذكرة"
                        icon="pi pi-send"
                        :loading="ticketForm.processing"
                        type="submit"
                    />
                </form>
            </div>
        </Drawer>

        <ConfirmDialog />

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

        <!-- Header Section -->
        <Header icon="pi-user" :title="user.name" :subtitle="user.email">
            <Link
                :href="route('admin.users.index')"
                class="btn bg-slate-200 hover:bg-slate-100 text-slate-800"
            >
                <p class="flex gap-1">العودة</p>
                <i class="pi pi-arrow-left"></i>
            </Link>
        </Header>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Sidebar -->
            <div class="lg:col-span-3">
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-100 p-0 sticky top-4"
                >
                    <!-- User Profile -->
                    <div class="p-6 text-center border-b border-gray-100">
                        <Avatar
                            :image="user.image"
                            size="xlarge"
                            shape="circle"
                            class="w-24 h-24"
                        />

                        <h2 class="text-xl font-bold mb-2">{{ user.name }}</h2>

                        <div class="flex justify-center gap-2 mb-2">
                            <Tag
                                :value="getUserTypeArabic(user.type)"
                                :severity="getTypeSeverity(user.type)"
                            />
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="p-4">
                        <h3 class="text-sm font-Bein text-gray-500 mb-3 px-2">
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
                                class="p-button-sm"
                                @click="toggleUserStatus"
                            />

                            <Button
                                v-if="isUserActive"
                                @click="
                                    showNewTicketDrawer = !showNewTicketDrawer
                                "
                                label="إرسال رسالة  "
                                icon="pi pi-envelope"
                                outlined
                                class="p-button-sm"
                            />
                        </div>
                    </div>

                    <!-- Account Info -->
                    <div class="p-4 border-t border-gray-100">
                        <h3 class="text-sm font-Bein text-gray-500 mb-3 px-2">
                            معلومات الإتصال
                        </h3>

                        <div class="flex flex-col gap-3 px-2">
                            <div class="flex items-center gap-2">
                                <i class="pi pi-envelope text-gray-500"></i>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500"
                                        >البريد الإلكتروني</span
                                    >
                                    <p class="text-gray-800 text-sm">
                                        {{ user.email }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <i class="pi pi-phone text-gray-500"></i>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500"
                                        >رقم الهاتف
                                    </span>
                                    <p class="text-sm text-gray-800">
                                        {{ user.phone || "غير متوفر" }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <i class="pi pi-map-marker text-gray-500"></i>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500"
                                        >العنوان
                                    </span>
                                    <p class="text-gray-800 text-sm">
                                        {{ user.address || "غير متوفر" }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-9">
                <!-- Personal Information Card -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i class="pi pi-user"></i>
                            معلومات الحساب
                        </h2>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6"
                    >
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

                        <div class="flex items-center gap-2">
                            <i class="pi pi-calendar text-gray-400"></i>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500"
                                    >تاريخ التسجيل</span
                                >
                                <span class="text-sm">{{
                                    $formatDate(user.created_at)
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
                                        ? $formatDate(user.updated_at)
                                        : "لم يتم التحديث"
                                }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="pi pi-check-circle text-gray-400"></i>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500"
                                    >تحقق البريد</span
                                >
                                <span class="text-sm">{{
                                    user.email_verified_at
                                        ? $formatDate(user.email_verified_at)
                                        : "غير متحقق"
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Management Card -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-100 p-6"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold flex items-center gap-2">
                            <i class="pi pi-shield"></i>
                            إدارة الحساب
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
                                v-if="!isUserActive"
                                class="bg-red-50 border border-red-100 rounded-lg p-4 mb-4 space-y-3"
                            >
                                <!-- reason -->
                                <div class="flex items-start gap-3">
                                    <i
                                        class="pi pi-info-circle text-red-500 mt-0.5"
                                    ></i>
                                    <div>
                                        <h4
                                            class="text-sm font-semibold text-red-700 mb-1"
                                        >
                                            سبب التعليق
                                        </h4>
                                        <p class="text-sm text-red-600 m-0">
                                            {{
                                                user.suspension_reason ||
                                                "لم يتم تحديد سبب للتعليق"
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <!-- date -->
                                <div class="flex items-start gap-3">
                                    <i
                                        class="pi pi-calendar text-red-500 mt-0.5"
                                    ></i>
                                    <div>
                                        <h4
                                            class="text-sm font-semibold text-red-700 mb-1"
                                        >
                                            تاريخ التعليق
                                        </h4>
                                        <p class="text-sm text-red-600 m-0">
                                            {{ $formatDate(user.suspended_at) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Actions -->
                        <div class="md:w-64 flex flex-col gap-3">
                            <h3 class="text-lg font-semibold">
                                تغيير حالة الحساب
                            </h3>
                            <p class="text-sm text-gray-500">
                                يمكنك تعليق أو إعادة تنشيط الحساب
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
            </div>
        </div>
    </div>
</template>
