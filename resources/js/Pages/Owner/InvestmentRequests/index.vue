<script setup>
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import { ref, reactive, watch, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import Select from "primevue/select";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Badge from "primevue/badge";
import { useForm } from "@inertiajs/vue3";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import InvestmentRequestStatus from "@/Components/InvestmentRequestStatus.vue";

defineOptions({
    layout: OwnerLayout,
});

const props = defineProps({
    properties: {
        type: Array,
        required: true,
    },
    investmentRequests: {
        type: Object,
        required: true,
    },
    InvestmentRequestStatusOptions: {
        type: Array,
        required: true,
    },
});

const toast = useToast();

const isMobile = computed(() => window.innerWidth <= 768);

const InvestmentRequestStatusOptions = ref(
    props.InvestmentRequestStatusOptions
);

// ########################################################################################## Create request
const showNewRequestModal = ref(false);
const isThereAvailableProperty = computed(() => {
    return props.properties.length > 0;
});

// New request form
const createRequestForm = useForm({
    property_id: "",
    suggested_investment_amount: 0,
    suggested_daily_rent_price: 0,
    owner_note: "",
});

const openCreateRequestModal = () => {
    if (isThereAvailableProperty.value) {
        showNewRequestModal.value = true;
    } else {
        toast.add({
            severity: "error",
            summary: "خطأ",
            detail: "لا يوجد عقارات متاحة لطلب للاستثمار",
            life: 3000,
        });
    }
};

// Function to handle submission
const submitCreateRequest = () => {
    createRequestForm.post(route("owner.investment-requests.create"), {
        onSuccess: () => {
            showNewRequestModal.value = false;
            createRequestForm.reset();
            toast.add({
                severity: "success",
                summary: "تم",
                detail: "تم إرسال الطلب بنجاح",
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
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("ar-SA", {
        year: "numeric",
        month: "short",
        day: "numeric",
    }).format(date);
};
</script>

<template>
    <div class="container mx-auto">
        <!-- Toast -->
        <Toast position="top-center" />

        <!-- New Investment Request Modal -->
        <Dialog
            v-model:visible="showNewRequestModal"
            modal
            header="طلب فتح استثمار جديد"
            :style="{ width: isMobile ? '90%' : '50vw' }"
            :closable="true"
            :closeOnEscape="true"
        >
            <div class="p-4">
                <form @submit.prevent="submitRequest" class="font-BeinNormal">
                    <!-- Property Selection -->

                    <div class="mb-6">
                        <label> العقار</label>
                        <Select
                            v-model="createRequestForm.property_id"
                            :options="properties"
                            optionLabel="title"
                            optionValue="id"
                            placeholder="اختر  العقار"
                            class="w-full"
                        />
                    </div>

                    <!-- Investment Amount -->
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2"
                            >قيمة الاستثمار المقترحة (ريال)</label
                        >
                        <InputNumber
                            v-model="
                                createRequestForm.suggested_investment_amount
                            "
                            :min="0"
                            :step="1000"
                            class="w-full"
                            suffix=" ريال"
                        />
                    </div>

                    <!-- Daily Rent Price -->
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2"
                            >سعر الإيجار اليومي المقترح (ريال)</label
                        >
                        <InputNumber
                            v-model="
                                createRequestForm.suggested_daily_rent_price
                            "
                            :min="0"
                            class="w-full"
                            suffix=" ريال"
                        />
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">ملاحظات</label>
                        <Textarea
                            v-model="createRequestForm.owner_note"
                            rows="3"
                            class="w-full"
                            placeholder="أضف أي ملاحظات أو تفاصيل إضافية عن عرض الاستثمار"
                        />
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <Button
                            type="button"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            @click="showNewRequestModal = false"
                        >
                            إلغاء
                        </Button>
                        <Button
                            label=" إرسال الطلب "
                            icon="pi pi-check"
                            severity="contrast"
                            @click="submitCreateRequest"
                            :loading="createRequestForm.processing"
                        />
                    </div>
                </form>
            </div>
        </Dialog>

        <!-- Header -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6"
        >
            <div class="flex items-center gap-3">
                <Icon
                    icon="fa-solid fa-circle-dollar-to-slot"
                    class="block h-8 text-teal-800"
                />
                <div class="">
                    <h1 class="text-3xl font-semibold m-0 text-teal-800">
                        طلبات فتح الاستثمار
                    </h1>
                    <p class="text-gray-500 text-sm m-0">
                        إدارة طلبات فتح الاستثمار على عقاراتك
                    </p>
                </div>
            </div>
            <button
                @click="openCreateRequestModal"
                :class="
                    isThereAvailableProperty
                        ? 'btn bg-emerald-600 hover:bg-emerald-700 text-white md:w-fit w-full mt-2 md:mt-0'
                        : 'btn bg-gray-500 hover:bg-gray-600 text-white md:w-fit w-full mt-2 md:mt-0 cursor-not-allowed'
                "
            >
                <i class="pi pi-plus"></i>
                <span>طلب فتح استثمار جديد</span>
            </button>
        </div>

        <!-- Empty State -->
        <div v-if="investmentRequests.length === 0" class="p-8 text-center">
            <div
                class="empty-state-icon bg-gray-50 inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
            >
                <i
                    class="pi pi-file-edit text-gray-300"
                    style="font-size: 2.5rem"
                ></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">
                لا توجد طلبات استثمار
            </h2>
            <p class="text-gray-500 max-w-md mx-auto mb-6">
                لم تقم بإنشاء أي طلبات فتح استثمار بعد. يمكنك إنشاء طلب جديد
                لعرض عقارك للاستثمارات.
            </p>
            <Button
                v-if="properties.length > 0"
                icon="pi pi-plus"
                label="إنشاء طلب استثمار جديد"
                outlined
                class="border-emerald-700 text-emerald-700 hover:bg-emerald-50"
                @click="showNewRequestModal = true"
            />
        </div>

        <!-- Requests Table -->
        <div
            v-else
            class="rounded-xl overflow-hidden flex flex-col justify-between min-h-[75vh]"
        >
            <!-- Requests Table -->
            <DataTable
                :value="props.investmentRequests.data"
                stripedRows
                showGridlines
                tableStyle="min-width: 50rem"
                class="border-none"
                @row-click="(event) => viewRequestDetails(event.data)"
                :rowHover="true"
                :rowClass="() => 'cursor-pointer'"
            >
                <Column field="id" header="#" class="w-[60px]">
                    <template #body="slotProps">
                        <span class="font-mono">#{{ slotProps.data.id }}</span>
                    </template>
                </Column>

                <Column field="property" header="العقار" class="w-[250px]">
                    <template #body="slotProps">
                        <div class="flex flex-col">
                            <div class="font-medium">
                                {{ slotProps.data.property.title }}
                            </div>
                            <div class="text-sm text-gray-500">
                                <p>
                                    {{ slotProps.data.property.city }} -
                                    {{ slotProps.data.property.address }}
                                </p>
                            </div>
                        </div>
                    </template>
                </Column>

                <Column
                    field="suggested_investment_amount"
                    header="قيمة الاستثمار"
                    class="w-[180px]"
                >
                    <template #body="slotProps">
                        <div class="font-semibold text-emerald-700">
                            {{
                                formatPrice(
                                    slotProps.data.suggested_investment_amount
                                )
                            }}
                            ريال
                        </div>
                    </template>
                </Column>

                <Column
                    field="suggested_daily_rent_price"
                    header="الإيجار اليومي"
                    class="w-[150px]"
                >
                    <template #body="slotProps">
                        <div class="font-semibold">
                            {{
                                formatPrice(
                                    slotProps.data.suggested_daily_rent_price
                                )
                            }}
                            ريال
                        </div>
                    </template>
                </Column>

                <Column field="status" header="الحالة" class="w-[130px]">
                    <template #body="slotProps">
                        <InvestmentRequestStatus
                            :status="slotProps.data.status"
                            :statusOptions="InvestmentRequestStatusOptions"
                        />
                    </template>
                </Column>

                <Column
                    field="created_at"
                    header="تاريخ الطلب"
                    class="w-[150px]"
                >
                    <template #body="slotProps">
                        <div class="text-sm">
                            <i class="pi pi-calendar ml-1"></i>
                            {{ formatDate(slotProps.data.created_at) }}
                        </div>
                    </template>
                </Column>
            </DataTable>
            <!-- Pagination -->
            <div
                dir="ltr"
                class="my-8 flex md:flex-row flex-col md:gap-0 gap-2 justify-between items-center w-full"
            >
                <div class="order-last md:order-first">
                    <p class="text-base text-slate-600 rtl:text-right">
                        عرض
                        <span class="text-teal-600 font-bold text-lg">{{
                            props.investmentRequests.from
                        }}</span>
                        إلى
                        <span class="text-teal-600 font-bold text-lg">{{
                            props.investmentRequests.to
                        }}</span>
                        من أصل {{ props.investmentRequests.total }} عقار
                    </p>
                </div>
                <nav class="order-first md:order-last">
                    <div class="flex items-center -space-x-px h-8 text-sm">
                        <template
                            v-for="(link, index) in props.investmentRequests
                                .links"
                            :key="link.url"
                        >
                            <Link
                                :preserve-scroll="true"
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                class="flex items-center justify-center px-3 h-8 leading-tight border border-gray-300 transition-colors"
                                :class="{
                                    'text-teal-800 bg-white hover:bg-gray-100 hover:text-teal-900':
                                        !link.active,
                                    'bg-teal-600 text-white hover:bg-teal-600':
                                        link.active,
                                    'rounded-l-lg': index === 0,
                                    'rounded-r-lg':
                                        index ===
                                        props.investmentRequests.links.length -
                                            1,
                                }"
                            />
                            <p
                                v-else
                                v-html="link.label"
                                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-slate-200 border border-gray-300"
                                :class="{
                                    'rounded-l-lg': index === 0,
                                    'rounded-r-lg':
                                        index ===
                                        props.investmentRequests.links.length -
                                            1,
                                }"
                            />
                        </template>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</template>
