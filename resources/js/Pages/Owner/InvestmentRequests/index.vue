<script setup>
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Textarea from "primevue/textarea";
import InputNumber from "primevue/inputnumber";
import Select from "primevue/select";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import InvestmentRequestStatus from "@/Components/InvestmentRequestStatus.vue";
import Header from "@/Components/Header.vue";

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
    investmentRequestsStatusOptions: {
        type: Array,
        required: true,
    },
});

const toast = useToast();

const isMobile = computed(() => window.innerWidth <= 768);

const investmentRequestsStatusOptions = ref(
    props.investmentRequestsStatusOptions
);

// ########################################################################################## Create request
const showNewRequestModal = ref(false);
const isThereAvailableProperty = computed(() => {
    return props.properties.length > 0;
});

// New request form
const createRequestForm = useForm({
    property_id: "",
    suggested_valuation: null,
    suggested_investment_amount: null,
    suggested_monthly_operating_cost: null,
    suggested_nightly_rent: null,
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
        onError: () => {
            const errorMessage = Object.values(createRequestForm.errors)[0];
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: errorMessage,
                life: 3000,
            });
        },
    });
};

// ########################################################################################## View request details

const viewRequestDetails = (request) => {
    router.get(route("owner.investment-requests.show", request.id), {});
};
</script>

<template>
    <div class="container mx-auto">
        <!-- Toast -->
        <Toast position="top-center" />

        <!-- Header -->
        <Header
            icon="fa-solid fa-circle-dollar-to-slot"
            title="طلبات فتح الاستثمار"
            subtitle="إدارة طلبات فتح الاستثمار على عقاراتك"
        >
            <button
                @click="openCreateRequestModal"
                class="btn bg-slate-200 hover:bg-slate-100 text-slate-800 md:w-fit w-full mt-2 md:mt-0"
                :class="isThereAvailableProperty ? '' : ' cursor-not-allowed'"
            >
                <i class="pi pi-plus"></i>
                <span>طلب فتح استثمار جديد</span>
            </button>
        </Header>

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
                <form
                    @submit.prevent="submitCreateRequest"
                    class="font-BeinNormal"
                >
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">
                                قيمة العقار المقترحة<span class="text-gray-500">
                                    (ثمن العقار)</span
                                >
                            </label>
                            <InputNumber
                                v-model="createRequestForm.suggested_valuation"
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
                                    createRequestForm.suggested_investment_amount
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
                                    createRequestForm.suggested_monthly_operating_cost
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
                                    createRequestForm.suggested_nightly_rent
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
                        <label class="block text-gray-700 mb-2">ملاحظات</label>
                        <Textarea
                            v-model="createRequestForm.owner_note"
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
                            @click="showNewRequestModal = false"
                        >
                            إلغاء
                        </Button>
                        <Button
                            label=" إرسال الطلب "
                            icon="pi pi-check"
                            severity="success"
                            type="submit"
                            :loading="createRequestForm.processing"
                        />
                    </div>
                </form>
            </div>
        </Dialog>

        <!-- Empty State -->
        <div
            v-if="investmentRequests.data.length === 0"
            class="p-8 text-center bg-white rounded-lg"
        >
            <div
                class="empty-state-icon bg-gray-50 inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
            >
                <i
                    class="pi pi-file-edit text-gray-300"
                    style="font-size: 2.5rem"
                ></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-700 mb-2">
                لا توجد طلبات فتح استثمار
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
                        <span class="font-mono">{{ slotProps.data.id }}#</span>
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

                <Column field="status" header="الحالة" class="w-[130px]">
                    <template #body="slotProps">
                        <InvestmentRequestStatus
                            :status="slotProps.data.status"
                            :statusOptions="investmentRequestsStatusOptions"
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
                            {{ $formatDate(slotProps.data.created_at) }}
                        </div>
                    </template>
                </Column>
                <Column
                    field="created_at"
                    header=" آخر تعديل"
                    class="w-[150px]"
                >
                    <template #body="slotProps">
                        <div class="text-sm">
                            <i class="pi pi-calendar ml-1"></i>
                            {{ $formatDate(slotProps.data.updated_at) }}
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
