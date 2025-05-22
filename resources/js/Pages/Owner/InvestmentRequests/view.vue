<script setup>
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import InvestmentRequestStatus from "@/Components/InvestmentRequestStatus.vue";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import Header from "@/Components/Header.vue";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";

defineOptions({
    layout: OwnerLayout,
});

const props = defineProps({
    investmentRequest: {
        type: Object,
        required: true,
    },
    InvestmentRequestStatusOptions: {
        type: Array,
        required: true,
    },
});

const investmentRequest = ref(props.investmentRequest);

const toast = useToast();

const isMobile = computed(() => window.innerWidth <= 768);

// ########################################################################################## update request
const showUpdateRequestModal = ref(false);
const updateRequestForm = useForm(() => ({
    request_id: "",
    suggested_valuation: investmentRequest.value.suggested_valuation,
    suggested_investment_amount:
        investmentRequest.value.suggested_investment_amount,
    suggested_monthly_operating_cost:
        investmentRequest.value.suggested_monthly_operating_cost,
    suggested_nightly_rent: investmentRequest.value.suggested_nightly_rent,
    owner_note: investmentRequest.value.owner_note,
}));

const submitUpdateRequest = () => {
    updateRequestForm.request_id = investmentRequest.value.id;
    updateRequestForm.put(route("owner.investment-requests.update"), {
        preserveState: false,
        onSuccess: () => {
            showUpdateRequestModal.value = false;
            toast.add({
                severity: "success",
                summary: "تم",
                detail: "تم تحديث الطلب بنجاح",
                life: 3000,
            });
            updateRequestForm.reset();
        },
        onError: () => {
            const errorMessage = Object.values(updateRequestForm.errors)[0];
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: errorMessage,
                life: 3000,
            });
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

            <!-- Update Investment Request Modal -->
            <Dialog
                v-model:visible="showUpdateRequestModal"
                modal
                header=" تعديل طلب فتح استثمار "
                :style="{ width: isMobile ? '90%' : '50vw' }"
                :closable="true"
                :closeOnEscape="true"
            >
                <div class="p-4">
                    <form
                        @submit.prevent="submitUpdateRequest"
                        class="font-BeinNormal"
                    >
                        <!-- Investment Amount -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-6">
                                <label class="block text-gray-700 mb-2">
                                    قيمة العقار المقترحة<span
                                        class="text-gray-500"
                                    >
                                        (ثمن العقار)</span
                                    >
                                </label>
                                <InputNumber
                                    v-model="
                                        updateRequestForm.suggested_valuation
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
                                    قيمة الاستثمار المقترحة<span
                                        class="text-gray-500"
                                    >
                                        (المبلغ المطلوب جمعه )</span
                                    >
                                </label>
                                <InputNumber
                                    v-model="
                                        updateRequestForm.suggested_investment_amount
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
                                        updateRequestForm.suggested_monthly_operating_cost
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
                                    v-model="
                                        updateRequestForm.suggested_nightly_rent
                                    "
                                    :min="0"
                                    placeholder="00.00"
                                    class="w-full"
                                    suffix=" ريال"
                                />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2"
                                >ملاحظات</label
                            >
                            <Textarea
                                v-model="updateRequestForm.owner_note"
                                rows="3"
                                class="w-full"
                                placeholder="أضف أي ملاحظات أو تفاصيل إضافية عن عرض الاستثمار"
                            />
                        </div>

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
                                سيتم تقييم العقار بناء على البيانات التي يتم
                                إدخالها، و من خلالها سيتم تقديم عرض الإستثمار و
                                التشغيل المناسب
                            </p>
                        </div>

                        <div class="flex justify-end gap-2 mt-6">
                            <Button
                                type="button"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                @click="showUpdateRequestModal = false"
                            >
                                إلغاء
                            </Button>
                            <Button
                                label=" إرسال الطلب "
                                icon="pi pi-check"
                                severity="success"
                                type="submit"
                                :loading="updateRequestForm.processing"
                            />
                        </div>
                    </form>
                </div>
            </Dialog>

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
                                :statusOptions="InvestmentRequestStatusOptions"
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

            <!-- Investment Offer -->
            <div class="bg-white rounded-lg p-4 border border-gray-200 mb-3">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    عرض الاستثمار
                </h3>

                <div class="mb-3">
                    <p>لم يتم إنشاء عرض الاستثمار بعد</p>
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
                <div>
                    <p class="text-gray-600 text-sm mb-1">ملاحظات الإدارة:</p>
                    <div class="p-3 bg-gray-50 rounded-lg text-gray-700">
                        {{ investmentRequest.admin_note || "لا توجد ملاحظات" }}
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2 mt-6">
                <Button
                    icon="pi pi-refresh"
                    label="تعديل طلب الاستثمار"
                    severity="success"
                    class="border-emerald-700 text-emerald-700 hover:bg-emerald-50"
                    @click="showUpdateRequestModal = !showUpdateRequestModal"
                />
            </div>
        </div>
    </div>
</template>
