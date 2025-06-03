<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, computed } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import Header from "@/Components/AdminDashboard/Header.vue";
import InvestmentRequestStatus from "@/Components/InvestmentRequestStatus.vue";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import Dialog from "primevue/dialog";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import InputNumber from "primevue/inputnumber";
import Slider from "primevue/slider";

defineOptions({
    layout: AdminLayout,
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

const toast = useToast();
const isMobile = computed(() => window.innerWidth <= 768);

// ########################################################################################## Shares Calculation

// Compute total shares
const createTotalShares = computed(() => {
    return (
        Number(createOfferForm.owner_share) +
        Number(createOfferForm.investor_share) +
        Number(createOfferForm.platform_fee_share)
    );
});

const updateTotalShares = computed(() => {
    return (
        Number(updateOfferForm.owner_share) +
        Number(updateOfferForm.investor_share) +
        Number(updateOfferForm.platform_fee_share)
    );
});

// Adjust shares to maintain 100% total
const adjustShares = (changedField, formType = "createOffer") => {
    const total = 100;
    let owner, investor, platform;

    if (formType === "createOffer") {
        owner = Number(createOfferForm.owner_share);
        investor = Number(createOfferForm.investor_share);
        platform = Number(createOfferForm.platform_fee_share);
    } else if (formType === "updateOffer") {
        owner = Number(updateOfferForm.owner_share);
        investor = Number(updateOfferForm.investor_share);
        platform = Number(updateOfferForm.platform_fee_share);
    }

    // Ensure values are within 0-100
    owner = Math.max(0, Math.min(100, owner));
    investor = Math.max(0, Math.min(100, investor));
    platform = Math.max(0, Math.min(100, platform));

    // Calculate remaining total after the changed field
    let remaining =
        total -
        (changedField === "owner"
            ? owner
            : changedField === "investor"
            ? investor
            : platform);

    if (changedField === "owner") {
        // Adjust investor and platform proportionally
        if (investor + platform > 0) {
            const ratio = investor / (investor + platform);
            investor = Math.round(remaining * ratio);
            platform = remaining - investor;
        } else {
            investor = Math.round(remaining * 0.8); // Default split if both are 0
            platform = remaining - investor;
        }
    } else if (changedField === "investor") {
        // Adjust owner and platform proportionally
        if (owner + platform > 0) {
            const ratio = owner / (owner + platform);
            owner = Math.round(remaining * ratio);
            platform = remaining - owner;
        } else {
            owner = Math.round(remaining * 0.8); // Default split if both are 0
            platform = remaining - owner;
        }
    } else if (changedField === "platform") {
        // Adjust owner and investor proportionally
        if (owner + investor > 0) {
            const ratio = owner / (owner + investor);
            owner = Math.round(remaining * ratio);
            investor = remaining - owner;
        } else {
            owner = Math.round(remaining * 0.75); // Default split if both are 0
            investor = remaining - owner;
        }
    }

    // Ensure no negative values
    owner = Math.max(0, owner);
    investor = Math.max(0, investor);
    platform = Math.max(0, platform);

    // Handle edge case where sum might not be exactly 100 due to rounding
    const currentTotal = owner + investor + platform;
    if (currentTotal !== total) {
        const diff = total - currentTotal;
        if (changedField !== "owner") owner += diff;
        else if (changedField !== "investor") investor += diff;
        else platform += diff;
    }

    // Update form values
    if (formType === "createOffer") {
        createOfferForm.owner_share = owner;
        createOfferForm.investor_share = investor;
        createOfferForm.platform_fee_share = platform;
    } else if (formType === "updateOffer") {
        updateOfferForm.owner_share = owner;
        updateOfferForm.investor_share = investor;
        updateOfferForm.platform_fee_share = platform;
    }
};
// ########################################################################################## Create Offer
const showNewOfferModal = ref(false);

const createOfferForm = useForm({
    property_id: props.investmentRequest?.property_id || "",
    suggested_valuation: null,
    suggested_investment_amount: null,
    suggested_monthly_operating_cost: null,
    suggested_nightly_rent: null,
    owner_share: 75,
    investor_share: 20,
    platform_fee_share: 5,
    admin_note: "",
});

const submitCreateOffer = () => {
    if (createTotalShares.value === 100) {
        createOfferForm.post(route("admin.investment-requests.createOffer"), {
            preserveState: false,
            onSuccess: () => {
                showNewOfferModal.value = false;
                createOfferForm.reset();
                toast.add({
                    severity: "success",
                    summary: "تم",
                    detail: "تم إرسال عرض الاستثمار بنجاح",
                    life: 3000,
                });
            },
            onError: () => {
                const errorMessage = Object.values(createOfferForm.errors)[0];

                toast.add({
                    severity: "error",
                    summary: "خطأ",
                    detail: errorMessage,
                    life: 3000,
                });
            },
        });
    } else {
        toast.add({
            severity: "error",
            summary: "خطأ",
            detail: "مجموع الحصص يجب أن يكون 100%",
            life: 3000,
        });
    }
};
// ########################################################################################## Update Offer
const showEditOfferModal = ref(false);

const updateOfferForm = useForm({
    id: props.investmentOffer?.id || "",
    suggested_valuation: props.investmentOffer?.suggested_valuation || "",
    suggested_investment_amount:
        props.investmentOffer?.suggested_investment_amount || "",
    suggested_monthly_operating_cost:
        props.investmentOffer?.suggested_monthly_operating_cost || "",
    suggested_nightly_rent: props.investmentOffer?.suggested_nightly_rent || "",
    owner_share: props.investmentOffer?.owner_share * 100 || 75,
    investor_share: props.investmentOffer?.investor_share * 100 || 20,
    platform_fee_share: props.investmentOffer?.platform_fee_share * 100 || 5,
    admin_note: props.investmentOffer?.admin_note || "",
});

const submitUpdateOffer = () => {
    if (updateTotalShares.value === 100) {
        updateOfferForm.id = props.investmentOffer?.id;
        updateOfferForm.put(route("admin.investment-requests.updateOffer"), {
            preserveState: false,
            onSuccess: () => {
                showEditOfferModal.value = false;
                toast.add({
                    severity: "success",
                    summary: "تم",
                    detail: "تم تحديث العرض بنجاح",
                    life: 3000,
                });
                updateOfferForm.reset();
            },
            onError: () => {
                const errorMessage = Object.values(updateOfferForm.errors)[0];
                toast.add({
                    severity: "error",
                    summary: "خطأ",
                    detail: errorMessage,
                    life: 3000,
                });
            },
        });
    } else {
        toast.add({
            severity: "error",
            summary: "خطأ",
            detail: "مجموع الحصص يجب أن يكون 100%",
            life: 3000,
        });
    }
};

// ########################################################################################## utils
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

// Format price
const formatPrice = (price) => {
    return price
        ? parseFloat(price).toLocaleString("ar-SA", {
              minimumFractionDigits: 2,
          })
        : "غير محدد";
};
</script>

<template>
    <div>
        <!-- Toast -->
        <Toast position="top-center" />

        <!-- Header Section -->
        <Header
            :isFontAwesome="true"
            icon="fa-regular fa-file-zipper"
            title=" مراجعة طلب فتح الاستثمار"
            subtitle=" إدارة طلبات فتح الاستثمار على العقارات"
        >
            <Link
                :href="route('admin.investment-requests.index')"
                class="btn bg-slate-200 hover:bg-slate-100 text-slate-800"
            >
                <p class="flex gap-1">العودة</p>
                <i class="pi pi-arrow-left"></i>
            </Link>
        </Header>

        <!-- New Investment Offer Modal -->
        <Dialog
            v-model:visible="showNewOfferModal"
            modal
            header="طلب فتح استثمار جديد"
            :style="{ width: isMobile ? '90%' : '70vw' }"
            :closable="true"
            :closeOnEscape="true"
        >
            <div class="p-4">
                <form
                    @submit.prevent="submitCreateOffer"
                    class="font-BeinNormal"
                >
                    <!-- Owner's Suggestions Summary -->
                    <div
                        class="bg-gray-100 rounded-lg p-4 mb-6 border border-gray-200"
                    >
                        <h3 class="text-md font-semibold text-gray-800 mb-2">
                            اقتراحات المالك
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">
                                    <span class="font-medium"
                                        >قيمة العقار:</span
                                    >
                                    <span class="text-teal-800 font-Bein mx-1">
                                        {{
                                            formatPrice(
                                                investmentRequest.suggested_valuation
                                            )
                                        }}
                                    </span>
                                    ريال
                                </p>
                                <p class="text-gray-600">
                                    <span class="font-medium"
                                        >قيمة الاستثمار:</span
                                    >
                                    <span class="text-teal-800 font-Bein mx-1">
                                        {{
                                            formatPrice(
                                                investmentRequest.suggested_investment_amount
                                            )
                                        }}
                                    </span>
                                    ريال
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600">
                                    <span class="font-medium"
                                        >التشغيل الشهري:</span
                                    >
                                    <span class="text-teal-800 font-Bein mx-1">
                                        {{
                                            formatPrice(
                                                investmentRequest.suggested_monthly_operating_cost
                                            )
                                        }}
                                    </span>
                                    ريال
                                </p>
                                <p class="text-gray-600">
                                    <span class="font-medium"
                                        >الإيجار اليومي:</span
                                    >
                                    <span class="text-teal-800 font-Bein mx-1">
                                        {{
                                            formatPrice(
                                                investmentRequest.suggested_nightly_rent
                                            )
                                        }}
                                    </span>
                                    ريال
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Investment Amount -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">
                                قيمة العقار المقترحة
                                <span class="text-gray-500">(ثمن العقار)</span>
                            </label>
                            <InputNumber
                                v-model="createOfferForm.suggested_valuation"
                                :min="0"
                                :step="1000"
                                placeholder="00.00"
                                class="w-full"
                                suffix=" ريال"
                            />
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">
                                قيمة الاستثمار المقترحة
                                <span class="text-gray-500"
                                    >(المبلغ المطلوب جمعه)</span
                                >
                            </label>
                            <InputNumber
                                v-model="
                                    createOfferForm.suggested_investment_amount
                                "
                                :min="0"
                                :step="1000"
                                placeholder="00.00"
                                class="w-full"
                                suffix=" ريال"
                            />
                        </div>
                    </div>

                    <!-- Daily Rent Price -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">
                                مبلغ التشغيل الشهري المقترح
                            </label>
                            <InputNumber
                                v-model="
                                    createOfferForm.suggested_monthly_operating_cost
                                "
                                :min="0"
                                :step="1000"
                                placeholder="00.00"
                                class="w-full"
                                suffix=" ريال"
                            />
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">
                                سعر الإيجار اليومي المقترح
                            </label>
                            <InputNumber
                                v-model="createOfferForm.suggested_nightly_rent"
                                :min="0"
                                :step="1000"
                                placeholder="00.00"
                                class="w-full"
                                suffix=" ريال"
                            />
                        </div>
                    </div>

                    <!-- Shares Section -->
                    <div class="mb-6">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">
                            توزيع الحصص
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Owner Share -->
                            <div>
                                <label class="block text-gray-700 mb-2"
                                    >حصة المالك (%)</label
                                >
                                <InputNumber
                                    v-model="createOfferForm.owner_share"
                                    :min="0"
                                    :max="100"
                                    :step="1"
                                    class="w-full mb-2"
                                    suffix=" %"
                                    @input="adjustShares('owner')"
                                />
                                <Slider
                                    v-model="createOfferForm.owner_share"
                                    :min="0"
                                    :max="100"
                                    class="w-full"
                                    @change="adjustShares('owner')"
                                />
                            </div>
                            <!-- Investor Share -->
                            <div>
                                <label class="block text-gray-700 mb-2"
                                    >حصة المستثمرين (%)</label
                                >
                                <InputNumber
                                    v-model="createOfferForm.investor_share"
                                    :min="0"
                                    :max="100"
                                    :step="1"
                                    class="w-full mb-2"
                                    suffix=" %"
                                    @input="adjustShares('investor')"
                                />
                                <Slider
                                    v-model="createOfferForm.investor_share"
                                    :min="0"
                                    :max="100"
                                    class="w-full"
                                    @change="adjustShares('investor')"
                                />
                            </div>
                            <!-- Platform Fee Share -->
                            <div>
                                <label class="block text-gray-700 mb-2"
                                    >حصة المنصة (%)</label
                                >
                                <InputNumber
                                    v-model="createOfferForm.platform_fee_share"
                                    :min="0"
                                    :max="100"
                                    :step="1"
                                    class="w-full mb-2"
                                    suffix=" %"
                                    @input="adjustShares('platform')"
                                />
                                <Slider
                                    v-model="createOfferForm.platform_fee_share"
                                    :min="0"
                                    :max="100"
                                    class="w-full"
                                    @change="adjustShares('platform')"
                                />
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            مجموع الحصص: {{ createTotalShares }}%
                            <span
                                v-if="createTotalShares !== 100"
                                class="text-red-500"
                            >
                                (يجب أن يكون المجموع 100%)
                            </span>
                        </p>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">ملاحظات</label>
                        <Textarea
                            v-model="createOfferForm.admin_note"
                            rows="3"
                            class="w-full"
                            placeholder="أضف أي ملاحظات أو تفاصيل إضافية عن عرض الاستثمار"
                        />
                    </div>

                    <!-- Note about listing -->
                    <div
                        class="bg-blue-50 rounded-lg p-4 border border-blue-100 font-BeinNormal"
                    >
                        <div class="flex items-center">
                            <i
                                class="pi pi-check-circle text-blue-600 text-xl ml-2"
                            ></i>
                            <h3 class="text-md font-semibold text-blue-800">
                                ملاحظة
                            </h3>
                        </div>
                        <p class="text-blue-700 mt-2 text-sm">
                            في حال وافق المالك على عرض الإستثمار سيتم إدراج
                            العقار مباشرة في منصة الإستثمارات
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-2 mt-6">
                        <Button
                            type="button"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            @click="showNewOfferModal = false"
                        >
                            إلغاء
                        </Button>
                        <Button
                            label="إرسال العرض"
                            icon="pi pi-check"
                            severity="success"
                            type="submit"
                            :loading="createOfferForm.processing"
                            :disabled="createTotalShares !== 100"
                        />
                    </div>
                </form>
            </div>
        </Dialog>

        <!-- Edit Investment Offer Modal -->
        <Dialog
            v-model:visible="showEditOfferModal"
            modal
            header="تعديل عرض الاستثمار"
            :style="{ width: isMobile ? '90%' : '70vw' }"
            :closable="true"
            :closeOnEscape="true"
        >
            <div class="p-4">
                <form
                    @submit.prevent="submitUpdateOffer"
                    class="font-BeinNormal"
                >
                    <!-- Owner's Suggestions Summary -->
                    <div
                        class="bg-gray-100 rounded-lg p-4 mb-6 border border-gray-200"
                    >
                        <h3 class="text-md font-semibold text-gray-800 mb-2">
                            اقتراحات المالك
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">
                                    <span class="font-medium"
                                        >قيمة العقار:</span
                                    >
                                    <span class="text-teal-800 font-Bein mx-1">
                                        {{
                                            formatPrice(
                                                investmentRequest.suggested_valuation
                                            )
                                        }}
                                    </span>
                                    ريال
                                </p>
                                <p class="text-gray-600">
                                    <span class="font-medium"
                                        >قيمة الاستثمار:</span
                                    >
                                    <span class="text-teal-800 font-Bein mx-1">
                                        {{
                                            formatPrice(
                                                investmentRequest.suggested_investment_amount
                                            )
                                        }}
                                    </span>
                                    ريال
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600">
                                    <span class="font-medium"
                                        >التشغيل الشهري:</span
                                    >
                                    <span class="text-teal-800 font-Bein mx-1">
                                        {{
                                            formatPrice(
                                                investmentRequest.suggested_monthly_operating_cost
                                            )
                                        }}
                                    </span>
                                    ريال
                                </p>
                                <p class="text-gray-600">
                                    <span class="font-medium"
                                        >الإيجار اليومي:</span
                                    >
                                    <span class="text-teal-800 font-Bein mx-1">
                                        {{
                                            formatPrice(
                                                investmentRequest.suggested_nightly_rent
                                            )
                                        }}
                                    </span>
                                    ريال
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Investment Amount -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">
                                قيمة العقار المقترحة
                                <span class="text-gray-500">(ثمن العقار)</span>
                            </label>
                            <InputNumber
                                v-model="updateOfferForm.suggested_valuation"
                                :min="0"
                                :step="1000"
                                placeholder="00.00"
                                class="w-full"
                                suffix=" ريال"
                            />
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">
                                قيمة الاستثمار المقترحة
                                <span class="text-gray-500"
                                    >(المبلغ المطلوب جمعه)</span
                                >
                            </label>
                            <InputNumber
                                v-model="
                                    updateOfferForm.suggested_investment_amount
                                "
                                :min="0"
                                placeholder="00.00"
                                :step="1000"
                                class="w-full"
                                suffix=" ريال"
                            />
                        </div>
                    </div>

                    <!-- Daily Rent Price -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">
                                مبلغ التشغيل الشهري المقترح
                            </label>
                            <InputNumber
                                v-model="
                                    updateOfferForm.suggested_monthly_operating_cost
                                "
                                :min="0"
                                placeholder="00.00"
                                class="w-full"
                                suffix=" ريال"
                            />
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2"
                                >سعر الإيجار اليومي المقترح
                            </label>
                            <InputNumber
                                v-model="updateOfferForm.suggested_nightly_rent"
                                :min="0"
                                placeholder="00.00"
                                class="w-full"
                                suffix=" ريال"
                            />
                        </div>
                    </div>

                    <!-- Shares Section -->
                    <div class="mb-6">
                        <h3 class="text-md font-semibold text-gray-800 mb-4">
                            توزيع الحصص
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Owner Share -->
                            <div>
                                <label class="block text-gray-700 mb-2"
                                    >حصة المالك (%)</label
                                >
                                <InputNumber
                                    v-model="updateOfferForm.owner_share"
                                    :min="0"
                                    :max="100"
                                    :step="1"
                                    class="w-full mb-2"
                                    suffix=" %"
                                    @input="
                                        adjustShares('owner', 'updateOffer')
                                    "
                                />
                                <Slider
                                    v-model="updateOfferForm.owner_share"
                                    :min="0"
                                    :max="100"
                                    class="w-full"
                                    @change="
                                        adjustShares('owner', 'updateOffer')
                                    "
                                />
                            </div>
                            <!-- Investor Share -->
                            <div>
                                <label class="block text-gray-700 mb-2"
                                    >حصة المستثمرين (%)</label
                                >
                                <InputNumber
                                    v-model="updateOfferForm.investor_share"
                                    :min="0"
                                    :max="100"
                                    :step="1"
                                    class="w-full mb-2"
                                    suffix=" %"
                                    @input="
                                        adjustShares('investor', 'updateOffer')
                                    "
                                />
                                <Slider
                                    v-model="updateOfferForm.investor_share"
                                    :min="0"
                                    :max="100"
                                    class="w-full"
                                    @change="
                                        adjustShares('investor', 'updateOffer')
                                    "
                                />
                            </div>
                            <!-- Platform Fee Share -->
                            <div>
                                <label class="block text-gray-700 mb-2"
                                    >حصة المنصة (%)</label
                                >
                                <InputNumber
                                    v-model="updateOfferForm.platform_fee_share"
                                    :min="0"
                                    :max="100"
                                    :step="1"
                                    class="w-full mb-2"
                                    suffix=" %"
                                    @input="
                                        adjustShares('platform', 'updateOffer')
                                    "
                                />
                                <Slider
                                    v-model="updateOfferForm.platform_fee_share"
                                    :min="0"
                                    :max="100"
                                    class="w-full"
                                    @change="
                                        adjustShares('platform', 'updateOffer')
                                    "
                                />
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            مجموع الحصص: {{ updateTotalShares }}%
                            <span
                                v-if="updateTotalShares !== 100"
                                class="text-red-500"
                            >
                                (يجب أن يكون المجموع 100%)
                            </span>
                        </p>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">ملاحظات</label>
                        <Textarea
                            v-model="updateOfferForm.admin_note"
                            rows="3"
                            class="w-full"
                            placeholder="أضف أي ملاحظات أو تفاصيل إضافية عن عرض الاستثمار"
                        />
                    </div>

                    <!-- Note about listing -->
                    <div
                        class="bg-blue-50 rounded-lg p-4 border border-blue-100 font-BeinNormal"
                    >
                        <div class="flex items-center">
                            <i
                                class="pi pi-check-circle text-blue-600 text-xl ml-2"
                            ></i>
                            <h3 class="text-md font-semibold text-blue-800">
                                ملاحظة
                            </h3>
                        </div>
                        <p class="text-blue-700 mt-2 text-sm">
                            في حال وافق المالك على عرض الإستثمار سيتم إدراج
                            العقار مباشرة في منصة الإستثمارات
                        </p>
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <Button
                            type="button"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            @click="showEditOfferModal = false"
                        >
                            إلغاء
                        </Button>
                        <Button
                            label="تعديل العرض"
                            icon="pi pi-check"
                            severity="success"
                            type="submit"
                            :loading="updateOfferForm.processing"
                            :disabled="updateTotalShares !== 100"
                        />
                    </div>
                </form>
            </div>
        </Dialog>

        <div class="space-y-6">
            <!-- Property & Investment Request -->
            <div class="flex md:flex-row flex-col gap-4">
                <!-- Property Info -->
                <div class="bg-white rounded-lg p-4 mb-4 md:w-1/2 w-full">
                    <div class="flex items-start justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">
                            معلومات العقار
                        </h3>
                        <div class="flex items-center gap-3">
                            <Link
                                :href="
                                    route(
                                        'admin.users.view',
                                        investmentRequest.property.owner.id
                                    )
                                "
                                class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 rounded-md font-Bein text-sm transition duration-150 ease-in-out"
                            >
                                <i class="pi pi-user ml-1"></i>
                                صفحة المالك
                            </Link>
                            <Link
                                :href="
                                    route(
                                        'admin.properties.view',
                                        investmentRequest.property.id
                                    )
                                "
                                class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 rounded-md font-Bein text-sm transition duration-150 ease-in-out"
                            >
                                <i class="pi pi-home ml-1"></i>
                                صفحة العقار
                            </Link>
                        </div>
                    </div>

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
                                        >{{ investmentRequest.property.city }}
                                        -
                                        {{
                                            investmentRequest.property.address
                                        }}</span
                                    >
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 mt-3">
                                <div class="flex items-center">
                                    <Icon
                                        icon="fa-solid fa-up-right-and-down-left-from-center"
                                        class="ml-1 w-[0.9rem] h-[0.9rem] text-slate-500"
                                    />
                                    <span class="text-sm"
                                        >{{
                                            investmentRequest.property.area
                                        }}
                                        م²</span
                                    >
                                </div>
                                <div
                                    class="flex items-center"
                                    v-if="
                                        investmentRequest.property.bedrooms > 0
                                    "
                                >
                                    <Icon
                                        icon="fa-solid fa-bed"
                                        class="ml-1 w-[1rem] h-[1rem] text-slate-500"
                                    />
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
                                    <Icon
                                        icon="fa-solid fa-shower"
                                        class="ml-1 w-[1rem] h-[1rem] text-slate-500"
                                    />

                                    <span class="text-sm"
                                        >{{
                                            investmentRequest.property.bathrooms
                                        }}
                                        حمامات</span
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
                class="bg-white rounded-lg p-6 border border-slate-200 mb-4 shadow-sm"
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
                        لم يتم إنشاء عرض الاستثمار بعد !
                    </p>
                    <Icon
                        icon="fa-solid fa-file-pen"
                        class="w-16 h-16 text-slate-300 me-4"
                    />
                    <Button
                        icon="pi pi-plus"
                        label="إنشاء عرض الاستثمار"
                        outlined
                        class="w-fit border-slate-300 text-slate-700 hover:bg-slate-50 transition-all duration-300"
                        @click="showNewOfferModal = !showNewOfferModal"
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
                                <p class="text-sm text-slate-600">حصة المالك</p>
                                <p class="text-lg font-semibold text-teal-900">
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
                                <p class="text-lg font-semibold text-teal-900">
                                    {{
                                        (
                                            investmentOffer.investor_share * 100
                                        ).toFixed(0)
                                    }}%
                                </p>
                                <div class="h-2 bg-slate-200 rounded-full mt-2">
                                    <div
                                        class="h-full bg-teal-600 rounded-full transition-all duration-500"
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
                                <p class="text-lg font-semibold text-teal-900">
                                    {{
                                        (
                                            investmentOffer.platform_fee_share *
                                            100
                                        ).toFixed(0)
                                    }}%
                                </p>
                                <div class="h-2 bg-slate-200 rounded-full mt-2">
                                    <div
                                        class="h-full bg-teal-600 rounded-full transition-all duration-500"
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
                    <div v-else class="flex justify-end">
                        <Button
                            label="تعديل العرض"
                            icon="pi pi-pencil"
                            outlined
                            @click="showEditOfferModal = !showEditOfferModal"
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
