<script setup>
import { ref } from "vue";
import DynamicLayout from "@/Layouts/DynamicLayout.vue";
import { Link } from "@inertiajs/vue3";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputNumber from "primevue/inputnumber";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import Card from "primevue/card";
import FloatLabel from "primevue/floatlabel";
import Badge from "primevue/badge";
import Divider from "primevue/divider";
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
        required: false,
    },
    money_in: {},
    money_out:  {},
});

const toast = useToast();

// ########################################################################################## Deposit Dialog
const showDepositDialog = ref(false);
const showPaymentDialog = ref(false);

const depositForm = useForm({
    amount: null,
});

const showPayment = () => {
    if (depositForm.amount < 100) {
        toast.add({
            severity: "error",
            summary: "خطأ",
            detail: "المبلغ المطلوب للإيداع يجب أن يكون موجب على الأقل من 100 ريال",
            life: 3000,
        });
        return;
    }

    showPaymentDialog.value = true;
    showDepositDialog.value = false;
};
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
                        :min="100"
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
                    description=" إداع في محفظة MasakinPlus"
                    callback-url="http://127.0.0.1:8000/user/wallet"
                />
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
                            {{ $formatCurrency(wallet.balance) }}
                        </div>
                        <div class="text-sm text-slate-700">
                            آخر تحديث:
                            <span class="text-slate-500">{{
                                $formatDate(wallet.updated_at)
                            }}</span>
                        </div>
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
                            {{ $formatCurrency(money_in) }}
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
                        <div class="text-2xl font-bold text-yellow-500 mb-2">
                            {{ $formatCurrency(money_out) }}
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
                </div>
            </template>
            <template #content>
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
                                    <i :class="slotProps.data.type_icon"></i>
                                    <span>{{ slotProps.data.type_label }}</span>
                                </div>
                            </template>
                        </Column>

                        <Column
                            field="amount"
                            header="المبلغ"
                            class="w-[150px]"
                        >
                            <template #body="slotProps">
                                <span :class="slotProps.data.type_color">
                                    {{ slotProps.data.type_sign }}
                                    {{ $formatCurrency(slotProps.data.amount) }}
                                </span>
                            </template>
                        </Column>

                        <Column
                            field="description"
                            header="الوصف"
                            class="w-[200px]"
                        >
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
                                    :value="slotProps.data.status_label"
                                    :severity="slotProps.data.status_severity"
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
                                    {{ $formatDate(slotProps.data.created_at) }}
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
