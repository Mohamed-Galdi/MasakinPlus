<script setup>
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import InvestmentRequestStatus from "@/Components/InvestmentRequestStatus.vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import Header from "@/Components/Header.vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from "primevue/confirmdialog";
import { set } from "lodash";

defineOptions({
    layout: OwnerLayout,
});

const props = defineProps({
    investmentRequest: {
        type: Object,
        required: true,
    },
    investmentOffer: {
        type: Object,
        required: false,
        default: null,
    },
    investmentRequestsStatusOptions: {
        type: Array,
        required: true,
    },
    approved: {
        type: String,
        required: true,
    },
});

const investmentRequest = ref(props.investmentRequest);

const isMobile = computed(() => window.innerWidth <= 768);

// ############################################## initialize Toast and Confirm
const toast = useToast();
const confirm = useConfirm();

// ############################################## Accept Offer
const AcceptOffer = () => {
    confirm.require({
        group: "acceptOffer",
        message: "هل أنت متأكد من أنك تريد قبول هذا العرض؟",
        header: "تأكيد الموافقة",
        rejectProps: {
            label: "إلغاء",
            severity: "secondary",
            outlined: true,
        },
        acceptProps: {
            label: " تأكيد قبول العرض ",
            severity: "success",
        },
        accept: () => {
            router.post(
                route("owner.investment-requests.acceptOffer", {
                    id: props.investmentOffer.id,
                }),
                {},
                {
                    onSuccess: () => {
                        toast.add({
                            severity: "success",
                            summary: "تم",
                            detail: "تم قبول العرض بنجاح",
                            life: 2000,
                        });
                        setTimeout(() => {
                            router.visit(window.location.href);
                        }, 1000);
                        
                    },
                    onError: (errors) => {
                        toast.add({
                            severity: "error",
                            summary: "خطأ",
                            detail: errors.error,
                            life: 3000,
                        });
                    },
                }
            );
        },
    });
};

// ############################################## Reject Offer
const RejectOffer = () => {
    confirm.require({
        group: "rejectOffer",
        message: "هل أنت متأكد من أنك تريد رفض هذا العرض؟",
        header: "تأكيد الرفض",
        rejectProps: {
            label: "إلغاء",
            severity: "secondary",
            outlined: true,
        },
        acceptProps: {
            label: "   تأكيد رفض العرض   ",
            severity: "danger",
        },
        accept: () => {
            router.post(
                route("owner.investment-requests.rejectOffer", {
                    id: props.investmentOffer.id,
                }),
                {},
                {
                    onBefore: () => {
                        toast.add({
                            severity: "success",
                            summary: "تم",
                            detail: "تم رفض العرض بنجاح",
                            life: 3000,
                        });
                    },
                    onError: (errors) => {
                        toast.add({
                            severity: "error",
                            summary: "خطأ",
                            detail: errors.error,
                            life: 3000,
                        });
                    },
                }
            );
        },
    });
};

// ########################################################################################## utils
// Format currency
const formatPrice = (price) => {
    return parseFloat(price).toLocaleString("ar-SA");
};
// Format date
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("ar-SA", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
    }).format(date);
};
</script>

<template>
    <div>
        <div class="font-BeinNormal">
            <!-- Toast -->
            <Toast position="top-center" />
            <!-- Header -->
            <Header
                icon="fa-solid fa-circle-dollar-to-slot"
                title="تفقد طلب الاستثمار"
                subtitle="إدارة طلبات فتح الاستثمار على عقاراتك"
            >
                <Link
                    :href="route('owner.investment-requests.index')"
                    class="btn bg-slate-200 hover:bg-slate-100 text-slate-800 md:w-fit w-full mt-2 md:mt-0"
                >
                    <span>العودة </span>
                    <i class="pi pi-arrow-left"></i>
                </Link>
            </Header>

            <!-- Accept Offer Dialog -->
            <ConfirmDialog
                group="acceptOffer"
                class="w-full md:w-1/2 lg:w-1/3 mx-8"
            >
                <template #message="slotProps">
                    <div class="space-y-2">
                        <p class="font-Bein">
                            هل أنت متأكد من أنك تريد قبول هذا العرض؟
                        </p>
                        <p class="font-BeinNormal text-slate-600">
                            بعد قبول العرض سيتم نشر العقار لإستقبال الإستتمارات
                            وفق النسب الموضحة في عرض الاستثمار
                        </p>
                    </div>
                </template>
            </ConfirmDialog>

            <!-- Reject Offer Dialog -->
            <ConfirmDialog
                group="rejectOffer"
                class="w-full md:w-1/2 lg:w-1/3 mx-8"
            >
                <template #message="slotProps">
                    <div class="space-y-2">
                        <p class="font-Bein">
                            هل أنت متأكد من أنك تريد رفض هذا العرض؟
                        </p>
                        <p class="font-BeinNormal text-slate-600">
                            بعد رفض العرض سيتم إلغاء العرض و تحديث حالة العقار
                            الى "غير جاحز"
                        </p>
                    </div>
                </template>
            </ConfirmDialog>

            <!-- Property & Investment Request -->
            <div class="flex md:flex-row flex-col gap-4">
                <!-- Property Info -->
                <div class="bg-white rounded-lg p-4 mb-4 md:w-1/2 w-full">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        معلومات العقار
                    </h3>

                    <div class="flex flex-col gap-4">
                        <!-- Property Details -->
                        <div class="">
                            <div class="flex items-center gap-4">
                                <div class="flex justify-between items-start">
                                    <h4
                                        class="text-md font-semibold text-teal-800"
                                    >
                                        {{ investmentRequest.property.title }}
                                    </h4>
                                </div>

                                <div class="flex items-center text-gray-600">
                                    <span
                                        >{{ investmentRequest.property.city }} -
                                        {{
                                            investmentRequest.property.address
                                        }}</span
                                    >
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 mt-3">
                                <div
                                    class="flex items-center"
                                    v-if="
                                        investmentRequest.property.bedrooms > 0
                                    "
                                >
                                    <i
                                        class="pi pi-home ml-1 text-gray-500"
                                    ></i>
                                    <span class="text-sm"
                                        >{{
                                            investmentRequest.property.bedrooms
                                        }}
                                        غرف</span
                                    >
                                </div>
                                <div
                                    class="flex items-center"
                                    v-if="
                                        investmentRequest.property.bathrooms > 0
                                    "
                                >
                                    <i
                                        class="pi pi-inbox ml-1 text-gray-500"
                                    ></i>
                                    <span class="text-sm"
                                        >{{
                                            investmentRequest.property.bathrooms
                                        }}
                                        حمامات</span
                                    >
                                </div>
                                <div class="flex items-center">
                                    <i
                                        class="pi pi-stop ml-1 text-gray-500"
                                    ></i>
                                    <span class="text-sm"
                                        >{{
                                            investmentRequest.property.area
                                        }}
                                        م²</span
                                    >
                                </div>
                            </div>
                        </div>
                        <!-- Property Image -->
                        <div class="mb-3 md:mb-0">
                            <div class="h-72 rounded-lg overflow-hidden">
                                <img
                                    :src="
                                        '/' +
                                        investmentRequest.property.images[0]
                                            .path
                                    "
                                    class="w-full h-full object-cover"
                                    alt="Property Image"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Investment Request Details -->
                <div
                    class="bg-white rounded-lg p-4 border border-gray-200 mb-4 md:w-1/2 w-full"
                >
                    <div class="space-y-2">
                        <div class="flex items-center gap-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                تفاصيل طلب الاستثمار
                            </h3>
                            <InvestmentRequestStatus
                                :status="investmentRequest.status"
                                :statusOptions="investmentRequestsStatusOptions"
                            />
                        </div>
                        <p class="text-gray-500 text-sm">
                            تم إنشاء الطلب في
                            {{ formatDate(investmentRequest.created_at) }}
                        </p>
                    </div>

                    <div class="grid gap-2 text-center pt-6">
                        <div class="bg-slate-100 rounded-lg p-3">
                            <p class="text-gray-600 text-sm">
                                قيمة العقار المقترحة
                            </p>
                            <p class="text-xl font-bold text-teal-700">
                                {{
                                    formatPrice(
                                        investmentRequest.suggested_valuation
                                    )
                                }}
                                ريال
                            </p>
                        </div>
                        <div class="bg-slate-100 rounded-lg p-3">
                            <p class="text-gray-600 text-sm">
                                قيمة الاستثمار المقترحة
                            </p>
                            <p class="text-xl font-bold text-teal-700">
                                {{
                                    formatPrice(
                                        investmentRequest.suggested_investment_amount
                                    )
                                }}
                                ريال
                            </p>
                        </div>
                        <div class="bg-slate-100 rounded-lg p-3">
                            <p class="text-gray-600 text-sm">
                                مبلغ التشغيل الشهري المقترح
                            </p>
                            <p class="text-xl font-bold text-teal-700">
                                {{
                                    formatPrice(
                                        investmentRequest.suggested_monthly_operating_cost
                                    )
                                }}
                                ريال
                            </p>
                        </div>

                        <div class="bg-slate-100 rounded-lg p-3">
                            <p class="text-gray-600 text-sm">
                                سعر الإيجار اليومي المقترح
                            </p>
                            <p class="text-xl font-bold text-teal-700">
                                {{
                                    formatPrice(
                                        investmentRequest.suggested_nightly_rent
                                    )
                                }}
                                ريال
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invesment Offer -->
            <div
                class="bg-white rounded-lg p-6 border-2 border-teal-600 mb-4 shadow-sm"
            >
                <!-- header -->
                <div
                    v-if="investmentOffer !== null"
                    class="flex items-center justify-between mb-6"
                >
                    <div class="flex items-center gap-2">
                        <h3
                            class="text-lg font-semibold text-gray-800 font-BeinNormal"
                        >
                            عرض الاستثمار
                        </h3>
                    </div>
                    <p class="text-sm text-slate-600">
                        <span class="font-medium text-slate-700"
                            >تاريخ الإنشاء:</span
                        >
                        {{ formatDate(investmentOffer.created_at) }}
                    </p>
                </div>
                <!-- Empty state -->
                <div
                    v-if="investmentOffer === null"
                    class="mb-3 flex flex-col items-center gap-4"
                >
                    <p class="text-slate-500 font-BeinNormal">
                        عرض الاستثمار ليس متوفرا بعد. سيتم تجهيزه في أقرب وقت
                        ممكن
                    </p>
                    <Icon
                        icon="fa-solid fa-file-pen"
                        class="w-16 h-16 text-slate-300 me-4"
                    />
                </div>
                <!-- Offer Details -->
                <div
                    v-else
                    class="font-BeinNormal space-y-6"
                    ref="offerDetails"
                >
                    <!-- Key Metrics -->
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-4 animate-fade-in"
                    >
                        <div class="border-b border-slate-200 pb-3">
                            <p class="text-sm text-slate-600">قيمة العقار</p>
                            <p class="text-lg font-semibold text-teal-800">
                                {{
                                    formatPrice(
                                        investmentOffer.suggested_valuation
                                    )
                                }}
                                ريال
                            </p>
                        </div>
                        <div class="border-b border-slate-200 pb-3">
                            <p class="text-sm text-slate-600">قيمة الاستثمار</p>
                            <p class="text-lg font-semibold text-teal-800">
                                {{
                                    formatPrice(
                                        investmentOffer.suggested_investment_amount
                                    )
                                }}
                                ريال
                            </p>
                        </div>
                        <div class="border-b border-slate-200 pb-3">
                            <p class="text-sm text-slate-600">التشغيل الشهري</p>
                            <p class="text-lg font-semibold text-teal-800">
                                {{
                                    formatPrice(
                                        investmentOffer.suggested_monthly_operating_cost
                                    )
                                }}
                                ريال
                            </p>
                        </div>
                        <div class="border-b border-slate-200 pb-3">
                            <p class="text-sm text-slate-600">الإيجار اليومي</p>
                            <p class="text-lg font-semibold text-teal-800">
                                {{
                                    formatPrice(
                                        investmentOffer.suggested_nightly_rent
                                    )
                                }}
                                ريال
                            </p>
                        </div>
                    </div>
                    <!-- Shares Breakdown -->
                    <div
                        class="bg-slate-50 rounded-lg p-4 border border-slate-100"
                    >
                        <h4 class="text-md font-semibold text-slate-700 mb-3">
                            توزيع الحصص
                        </h4>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <p class="text-sm text-slate-600">
                                    حصتك (المالك)
                                </p>
                                <p class="text-lg font-semibold text-teal-700">
                                    {{
                                        (
                                            investmentOffer.owner_share * 100
                                        ).toFixed(0)
                                    }}%
                                </p>
                                <div class="h-2 bg-slate-200 rounded-full mt-2">
                                    <div
                                        class="h-full bg-teal-600 rounded-full transition-all duration-500"
                                        :style="{
                                            width: `${
                                                investmentOffer.owner_share *
                                                100
                                            }%`,
                                        }"
                                    ></div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-slate-600">
                                    حصة المستثمرين
                                </p>
                                <p class="text-lg font-semibold text-slate-700">
                                    {{
                                        (
                                            investmentOffer.investor_share * 100
                                        ).toFixed(0)
                                    }}%
                                </p>
                                <div class="h-2 bg-slate-200 rounded-full mt-2">
                                    <div
                                        class="h-full bg-slate-500 rounded-full transition-all duration-500"
                                        :style="{
                                            width: `${
                                                investmentOffer.investor_share *
                                                100
                                            }%`,
                                        }"
                                    ></div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-slate-600">حصة المنصة</p>
                                <p class="text-lg font-semibold text-slate-700">
                                    {{
                                        (
                                            investmentOffer.platform_fee_share *
                                            100
                                        ).toFixed(0)
                                    }}%
                                </p>
                                <div class="h-2 bg-slate-200 rounded-full mt-2">
                                    <div
                                        class="h-full bg-slate-500 rounded-full transition-all duration-500"
                                        :style="{
                                            width: `${
                                                investmentOffer.platform_fee_share *
                                                100
                                            }%`,
                                        }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Accept Offer Button -->
                    <div
                        v-if="investmentRequest.status === approved"
                        class="bg-teal-800 text-center p-2 rounded-lg text-slate-300"
                    >
                        <p>
                            تم قبول العرض بتاريخ
                            <span class="font-Bein text-white">
                                :
                                {{
                                    formatDate(investmentRequest.updated_at)
                                }}</span
                            >
                        </p>
                    </div>
                    <div v-else class="flex justify-end gap-8">
                        <Button
                            label=" قبول العرض"
                            icon="pi pi-check"
                            @click="AcceptOffer"
                            severity="success"
                            class="w-full"
                        />
                        <Button
                            label=" رفض العرض"
                            icon="pi pi-times"
                            @click="RejectOffer"
                            severity="danger"
                            class="w-full"
                        />
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 mb-3">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    الملاحظات
                </h3>

                <!-- Owner Notes -->
                <div class="mb-3">
                    <p class="text-gray-600 text-sm mb-1">ملاحظات المالك:</p>
                    <div class="p-3 bg-gray-50 rounded-lg text-gray-700">
                        {{ investmentRequest.owner_note || "لا توجد ملاحظات" }}
                    </div>
                </div>

                <!-- Admin Notes -->
                <div v-if="investmentOffer !== null">
                    <p class="text-gray-600 text-sm mb-1">ملاحظات الإدارة:</p>
                    <div class="p-3 bg-gray-50 rounded-lg text-gray-700">
                        {{ investmentOffer.admin_note || "لا توجد ملاحظات" }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
