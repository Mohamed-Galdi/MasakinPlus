<script setup>
import { ref, computed, watch } from "vue";
import DynamicLayout from "@/Layouts/DynamicLayout.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Select from "primevue/select";
import Dialog from "primevue/dialog";
import InputNumber from "primevue/inputnumber";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import Card from "primevue/card";
import FloatLabel from "primevue/floatlabel";
import Badge from "primevue/badge";
import Divider from "primevue/divider";
import DatePicker from "primevue/datepicker";
import Header from "@/Components/Header.vue";
import MoyasarPaymentForm from "@/Components/MoyasarPaymentForm.vue";

defineOptions({
    layout: DynamicLayout,
});

const props = defineProps({
    wallet: {
        type: Object,
        required: false,
    },
    transactions: {
        type: Object,
        default: () => ({
            data: [],
            links: [],
            from: 1,
            to: 1,
            total: 0,
        }),
    },
    search: {
        type: String,
        default: "",
    },
    dateRange: {
        type: Array,
        default: () => [],
    },
    transactionType: {
        type: String,
        default: "",
    },
});

const toast = useToast();

// ########################################################################################## Filters
const search = ref(props.search);
const dateRange = ref(props.dateRange);
const transactionType = ref(props.transactionType);

const transactionTypes = ref([
    { label: "الكل", value: "" },
    { label: "إيداع", value: "deposit" },
    { label: "سحب", value: "withdraw" },
    { label: "تحويل", value: "transfer" },
]);

watch(
    [search, dateRange, transactionType],
    debounce(([searchValue, dateRangeValue, typeValue]) => {
        router.get(
            route("wallet.index"),
            {
                search: searchValue,
                dateRange: dateRangeValue,
                transactionType: typeValue,
            },
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    }, 500)
);

// Reset filters
const resetFilters = () => {
    search.value = "";
    dateRange.value = [];
    transactionType.value = "";
};

// ########################################################################################## Utils
// Format currency
const formatCurrency = (amount) => {
    return new Intl.NumberFormat("ar-SA", {
        style: "currency",
        currency: "SAR",
    }).format(amount);
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

// Get transaction type in Arabic
const getTransactionTypeArabic = (type) => {
    switch (type) {
        case "deposit":
            return "إيداع";
        case "withdraw":
            return "سحب";
        case "transfer":
            return "تحويل";
        default:
            return type;
    }
};

// Get transaction status
const getTransactionStatus = (status) => {
    switch (status) {
        case "completed":
            return "مكتمل";
        case "pending":
            return "قيد المعالجة";
        case "failed":
            return "فشل";
        default:
            return status;
    }
};

// Get transaction status severity
const getStatusSeverity = (status) => {
    switch (status) {
        case "completed":
            return "success";
        case "pending":
            return "warning";
        case "failed":
            return "danger";
        default:
            return "info";
    }
};

// Get transaction icon
const getTransactionIcon = (type) => {
    switch (type) {
        case "deposit":
            return "pi pi-arrow-down";
        case "withdraw":
            return "pi pi-arrow-up";
        case "transfer":
            return "pi pi-exchange";
        default:
            return "pi pi-circle";
    }
};

// Get transaction amount class
const getAmountClass = (type) => {
    switch (type) {
        case "deposit":
            return "text-green-600";
        case "withdraw":
            return "text-red-600";
        case "transfer":
            return "text-blue-600";
        default:
            return "";
    }
};

// ########################################################################################## Deposit Dialog
const showDepositDialog = ref(false);
const showPaymentDialog = ref(false);
const selectedMethodLabel = computed(() => {
    return (
        paymentMethods.value.find((m) => m.value === depositForm.payment_method)
            ?.label || ""
    );
});

const callbackUrl = route("wallet.index");
console.log(callbackUrl);
const depositForm = useForm({
    amount: null,
    payment_method: "credit_card",
});

const paymentMethods = ref([
    { label: "بطاقة الائتمان", value: "creditcard" },
    { label: "STC Pay", value: "stcpay" },
    { label: "Apple Pay", value: "applepay" },
]);

const showPayment = () => {
    if(depositForm.amount <= 10){
        toast.add({
            severity: "error",
            summary: "خطأ",
            detail: "المبلغ المطلوب للإيداع يجب أن يكون موجب على الأقل من 10 ريال",
            life: 3000,
        });
        return;
    }

    showPaymentDialog.value = true;
    showDepositDialog.value = false;
};

// ########################################################################################## Withdraw Dialog
const showWithdrawDialog = ref(false);
const withdrawForm = useForm({
    amount: null,
    bank_account: "",
});

const submitWithdraw = () => {
    withdrawForm.post(route("wallet.withdraw"), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "نجاح",
                detail: "تم إرسال طلب السحب بنجاح",
                life: 3000,
            });
            showWithdrawDialog.value = false;
            withdrawForm.reset();
        },
        onError: (errors) => {
            const errorMessage = Object.values(errors)[0];
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: errorMessage,
                life: 3000,
            });
        },
    });
};

// ########################################################################################## Stats
const pendingTransactions = computed(() => {
    return props.transactions.data.filter(
        (transaction) => transaction.status === "pending"
    ).length;
});

const totalDeposits = computed(() => {
    return props.transactions.data
        .filter(
            (transaction) =>
                transaction.type === "deposit" &&
                transaction.status === "completed"
        )
        .reduce((sum, transaction) => sum + transaction.amount, 0);
});

const totalWithdraws = computed(() => {
    return props.transactions.data
        .filter(
            (transaction) =>
                transaction.type === "withdraw" &&
                transaction.status === "completed"
        )
        .reduce((sum, transaction) => sum + transaction.amount, 0);
});
</script>

<template>
    <div>
        <Toast position="top-center" />

        <!-- Deposit Dialog -->
        <Dialog
            v-model:visible="showDepositDialog"
            modal
            header="إيداع رصيد"
            :style="{ width: '40rem' }"
            :rtl="true"
        >
            <div class="p-4 space-y-4">
                <FloatLabel variant="on" class="w-full">
                    <InputNumber
                        id="amount"
                        v-model="depositForm.amount"
                        :min="1"
                        :minFractionDigits="2"
                        :maxFractionDigits="2"
                        class="w-full"
                        suffix=" ر.س"
                    />
                    <label for="amount">المبلغ</label>
                </FloatLabel>
                <Button
                    @click="showPayment"
                    label="الإنتقال للدفع "
                    icon="pi pi-check"
                    :loading="depositForm.processing"
                    severity="success"
                    class="w-full"
                />
            </div>
        </Dialog>

        <!-- Payment Dialog -->
        <Dialog
            v-model:visible="showPaymentDialog"
            modal
            header="إيداع رصيد"
            :style="{ width: '40rem' }"
            :rtl="true"
        >
            <div class="p-4">
                <MoyasarPaymentForm
                    :amount="depositForm.amount * 100"
                    description="Balance deposit for User #123"
                    callback-url="http://127.0.0.1:8000/user/wallet"
                />
            </div>
        </Dialog>

        <!-- Withdraw Dialog -->
        <Dialog
            v-model:visible="showWithdrawDialog"
            modal
            header="سحب رصيد"
            :style="{ width: '450px' }"
            :rtl="true"
        >
            <div class="p-4">
                <form @submit.prevent="submitWithdraw" class="space-y-6">
                    <div>
                        <FloatLabel variant="on" class="w-full">
                            <InputNumber
                                id="withdraw_amount"
                                v-model="withdrawForm.amount"
                                :min="0"
                                :max="450"
                                :minFractionDigits="2"
                                :maxFractionDigits="2"
                                class="w-full"
                                placeholder="0.00"
                            />
                            <label for="withdraw_amount">المبلغ</label>
                        </FloatLabel>
                        <small class="text-gray-500">
                            الحد الأقصى للسحب:
                            {{ formatCurrency(450) }}
                        </small>
                    </div>

                    <div>
                        <FloatLabel variant="on" class="w-full">
                            <InputText
                                id="bank_account"
                                v-model="withdrawForm.bank_account"
                                class="w-full"
                            />
                            <label for="bank_account">رقم الحساب البنكي</label>
                        </FloatLabel>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Button
                            type="button"
                            label="إلغاء"
                            icon="pi pi-times"
                            outlined
                            @click="showWithdrawDialog = false"
                        />
                        <Button
                            type="submit"
                            label="سحب"
                            icon="pi pi-check"
                            :loading="withdrawForm.processing"
                            class="bg-teal-700 hover:bg-teal-800"
                        />
                    </div>
                </form>
            </div>
        </Dialog>

        <!-- Header -->
        <Header
            icon="fa-solid fa-wallet"
            title="المحفظة"
            subtitle="إدارة رصيدك والمعاملات المالية"
        ></Header>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Balance Card -->
            <Card class="shadow-sm">
                <template #header>
                    <div
                        class="bg-teal-700 p-4 flex justify-between items-center rounded-t-lg"
                    >
                        <h2 class="text-lg font-semibold text-gray-100 m-0">
                            الرصيد الحالي
                        </h2>
                        <i
                            class="pi pi-money-bill text-gray-100"
                            style="font-size: 1.5rem"
                        ></i>
                    </div>
                </template>
                <template #content>
                    <div class="text-center py-4">
                        <div class="text-3xl font-bold text-teal-800 mb-2">
                            {{ formatCurrency(450) }}
                        </div>
                        <div class="text-sm text-gray-500">آخر تحديث:</div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <Button
                            label="إيداع"
                            icon="pi pi-arrow-up"
                            class="flex-1 bg-teal-700 hover:bg-teal-800"
                            @click="showDepositDialog = true"
                            severity="success"
                        />
                        <Button
                            label="سحب"
                            icon="pi pi-arrow-down"
                            class="flex-1"
                            outlined
                            :disabled="450 <= 0"
                            @click="showWithdrawDialog = true"
                        />
                    </div>
                </template>
            </Card>

            <!-- Deposits Stats -->
            <Card class="shadow-sm">
                <template #header>
                    <div
                        class="bg-teal-700 p-4 flex justify-between items-center rounded-t-lg"
                    >
                        <h2 class="text-lg font-semibold text-gray-100 m-0">
                            الإيداعات
                        </h2>
                        <i
                            class="pi pi-arrow-up text-gray-100"
                            style="font-size: 1.5rem"
                        ></i>
                    </div>
                </template>
                <template #content>
                    <div class="text-center py-4">
                        <div class="text-2xl font-bold text-green-600 mb-2">
                            {{ formatCurrency(totalDeposits) }}
                        </div>
                        <div class="text-sm text-gray-500">
                            إجمالي الإيداعات المكتملة
                        </div>
                    </div>
                </template>
            </Card>

            <!-- Withdraws Stats -->
            <Card class="shadow-sm">
                <template #header>
                    <div
                        class="bg-teal-700 p-4 flex justify-between items-center rounded-t-lg"
                    >
                        <h2 class="text-lg font-semibold text-gray-100 m-0">
                            المسحوبات
                        </h2>
                        <i
                            class="pi pi-arrow-down text-gray-100"
                            style="font-size: 1.5rem"
                        ></i>
                    </div>
                </template>
                <template #content>
                    <div class="text-center py-4">
                        <div class="text-2xl font-bold text-red-600 mb-2">
                            {{ formatCurrency(totalWithdraws) }}
                        </div>
                        <div class="text-sm text-gray-500">
                            إجمالي المسحوبات المكتملة
                        </div>
                    </div>
                </template>
            </Card>
        </div>

        <!-- Transactions Section -->
        <Card class="shadow-sm mb-6">
            <template #header>
                <div
                    class="bg-teal-700 p-4 flex justify-between items-center rounded-t-lg"
                >
                    <h2 class="text-lg font-semibold text-gray-100 m-0">
                        سجل المعاملات
                    </h2>
                    <i
                        class="pi pi-arrow-right-arrow-left text-gray-100"
                        style="font-size: 1.5rem"
                    ></i>
                    <Badge
                        v-if="pendingTransactions > 0"
                        :value="pendingTransactions"
                        severity="warning"
                    ></Badge>
                </div>
            </template>
            <template #content>
                <!-- Filters -->
                <div class="filter-section mb-4">
                    <div class="flex flex-wrap items-center gap-4">
                        <!-- Search -->
                        <div class="search-container flex-1 min-w-[250px]">
                            <span class="p-input-icon-right w-full">
                                <InputText
                                    v-model="search"
                                    class="w-full rounded-lg border-gray-200"
                                    placeholder="البحث في المعاملات..."
                                />
                            </span>
                        </div>

                        <!-- Date Range -->
                        <div class="w-full md:w-auto">
                            <DatePicker
                                v-model="dateRange"
                                selectionMode="range"
                                placeholder="نطاق التاريخ"
                                :showIcon="true"
                                class="w-full md:w-auto"
                                dir="rtl"
                            />
                        </div>

                        <!-- Transaction Type -->
                        <div class="w-full md:w-auto">
                            <Select
                                v-model="transactionType"
                                :options="transactionTypes"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="نوع المعاملة"
                                class="w-full md:w-auto"
                            />
                        </div>

                        <!-- Reset Button -->
                        <div>
                            <Button
                                icon="pi pi-filter-slash"
                                label="إعادة ضبط"
                                outlined
                                severity="secondary"
                                @click="resetFilters"
                            />
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="props.transactions.data.length === 0"
                    class="p-8 text-center"
                >
                    <div
                        class="empty-state-icon bg-gray-50 inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                    >
                        <i
                            class="pi pi-history text-gray-300"
                            style="font-size: 2.5rem"
                        ></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">
                        لا توجد معاملات
                    </h2>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">
                        لم يتم العثور على أي معاملات مالية مطابقة لمعايير البحث
                        الحالية.
                    </p>
                    <Button
                        icon="pi pi-filter-slash"
                        label="إعادة ضبط الفلتر"
                        outlined
                        @click="resetFilters"
                    />
                </div>

                <!-- Transactions Table -->
                <div v-else>
                    <DataTable
                        :value="props.transactions.data"
                        stripedRows
                        showGridlines
                        tableStyle="min-width: 50rem"
                        class="border-none"
                        :rowHover="true"
                    >
                        <Column field="id" header="#" class="w-[60px]">
                            <template #body="slotProps">
                                <span class="font-mono"
                                    >#{{ slotProps.data.id }}</span
                                >
                            </template>
                        </Column>

                        <Column
                            field="type"
                            header="نوع المعاملة"
                            class="w-[150px]"
                        >
                            <template #body="slotProps">
                                <div class="flex items-center gap-2">
                                    <i
                                        :class="
                                            getTransactionIcon(
                                                slotProps.data.type
                                            )
                                        "
                                    ></i>
                                    <span>{{
                                        getTransactionTypeArabic(
                                            slotProps.data.type
                                        )
                                    }}</span>
                                </div>
                            </template>
                        </Column>

                        <Column
                            field="amount"
                            header="المبلغ"
                            class="w-[150px]"
                        >
                            <template #body="slotProps">
                                <span
                                    :class="getAmountClass(slotProps.data.type)"
                                >
                                    {{
                                        slotProps.data.type === "withdraw"
                                            ? "-"
                                            : "+"
                                    }}
                                    {{ formatCurrency(slotProps.data.amount) }}
                                </span>
                            </template>
                        </Column>

                        <Column field="description" header="الوصف">
                            <template #body="slotProps">
                                <div>{{ slotProps.data.description }}</div>
                            </template>
                        </Column>

                        <Column
                            field="status"
                            header="الحالة"
                            class="w-[120px]"
                        >
                            <template #body="slotProps">
                                <Badge
                                    :value="
                                        getTransactionStatus(
                                            slotProps.data.status
                                        )
                                    "
                                    :severity="
                                        getStatusSeverity(slotProps.data.status)
                                    "
                                />
                            </template>
                        </Column>

                        <Column
                            field="created_at"
                            header="التاريخ"
                            class="w-[180px]"
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
                                <span class="text-teal-700 font-bold text-lg">{{
                                    props.transactions.from
                                }}</span>
                                إلى
                                <span class="text-teal-700 font-bold text-lg">{{
                                    props.transactions.to
                                }}</span>
                                من أصل {{ props.transactions.total }} معاملة
                            </p>
                        </div>
                        <nav class="order-first md:order-last">
                            <div
                                class="flex items-center -space-x-px h-8 text-sm"
                            >
                                <template
                                    v-for="(link, index) in props.transactions
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
                                            'bg-teal-700 text-white hover:bg-teal-700':
                                                link.active,
                                            'rounded-l-lg': index === 0,
                                            'rounded-r-lg':
                                                index ===
                                                props.transactions.links
                                                    .length -
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
                                                props.transactions.links
                                                    .length -
                                                    1,
                                        }"
                                    />
                                </template>
                            </div>
                        </nav>
                    </div>
                </div>
            </template>
        </Card>

        <!-- Additional Information -->
        <Card class="shadow-sm">
            <template #header>
                <div
                    class="bg-teal-700 p-4 flex justify-between items-center rounded-t-lg"
                >
                    <h2 class="text-lg font-semibold text-gray-100 m-0">
                        معلومات المحفظة
                    </h2>
                    <i
                        class="pi pi-info-circle text-gray-100"
                        style="font-size: 1.5rem"
                    ></i>
                </div>
            </template>
            <template #content>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-md font-semibold mb-2">
                            طرق الإيداع المتاحة
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            <div
                                class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg"
                            >
                                <i class="pi pi-credit-card text-teal-700"></i>
                                <span>بطاقة الائتمان</span>
                            </div>
                            <div
                                class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg"
                            >
                                <i
                                    class="pi pi-building-columns text-teal-700"
                                ></i>
                                <span>التحويل البنكي</span>
                            </div>
                            <div
                                class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg"
                            >
                                <i class="pi pi-wallet text-teal-700"></i>
                                <span>المحفظة الإلكترونية</span>
                            </div>
                        </div>
                    </div>

                    <Divider />

                    <div>
                        <h3 class="text-md font-semibold mb-2">معلومات هامة</h3>
                        <ul
                            class="list-disc list-inside space-y-2 text-gray-600"
                        >
                            <li>الحد الأدنى للإيداع هو 100 ريال سعودي.</li>
                            <li>
                                الحد الأقصى للسحب اليومي هو 10,000 ريال سعودي.
                            </li>
                            <li>يتم معالجة طلبات السحب خلال 1-3 أيام عمل.</li>
                            <li>لا توجد رسوم على عمليات الإيداع.</li>
                            <li>
                                رسوم السحب: 2% من قيمة المبلغ (بحد أدنى 5 ريال).
                            </li>
                        </ul>
                    </div>

                    <Divider />

                    <div>
                        <h3 class="text-md font-semibold mb-2">
                            بحاجة إلى مساعدة؟
                        </h3>
                        <p class="text-gray-600">
                            إذا كنت بحاجة إلى مساعدة بخصوص المحفظة أو المعاملات
                            المالية، يرجى التواصل مع فريق الدعم الفني.
                        </p>
                        <Link :href="route('support.index')">
                            <Button
                                label="تواصل مع الدعم"
                                icon="pi pi-comments"
                                outlined
                                class="mt-2"
                            />
                        </Link>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>
