<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, watch, computed } from "vue";
import { router, Link, useForm } from "@inertiajs/vue3";
import { useTextHelpers } from "@/plugins/textHelpers";
import { debounce } from "lodash";
import Select from "primevue/select";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import Dialog from "primevue/dialog";
import Textarea from "primevue/textarea";
import Checkbox from "primevue/checkbox";
import InvestmentRequestStatus from "@/Components/InvestmentRequestStatus.vue";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    investmentRequests: {
        type: Object,
        required: true,
    },
    investmentRequestsStatusOptions: {
        type: Array,
        required: true,
    },
    statusFilter: {
        type: String,
        required: false,
        default: "",
    },
    search: {
        type: String,
        required: false,
        default: "",
    },
});

const investmentRequestsStatusOptions = ref(
    props.investmentRequestsStatusOptions
);

const toast = useToast();

const isMobile = computed(() => window.innerWidth <= 768);

// ############################################## Search and filter
const textHelpers = useTextHelpers();
const search = ref(props.search);
const statusFilter = ref(props.statusFilter);

watch(
    [search, statusFilter],
    debounce(([search, statusFilter]) => {
        // Update the table with both filters
        router.get(
            route("admin.investment-requests.index"),
            {
                search: search,
                statusFilter: statusFilter,
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
    statusFilter.value = "";
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

// ############################################## View request details
const showRequestDetailsModal = ref(false);
const requestDetails = ref({});
const viewRequestDetails = (request) => {
    showRequestDetailsModal.value = true;
    requestDetails.value = request;
};

// ############################################## Reply to request
const replyForm = useForm({
    request_id: "",
    reply: "",
    admin_note: "",
});

const replyToRequest = (reply) => {
    replyForm.request_id = requestDetails.value.id;
    replyForm.reply = reply;
    replyForm.post(route("admin.investment-requests.reply"), {
        onSuccess: () => {
            showRequestDetailsModal.value = false;
            toast.add({
                severity: "success",
                summary: "تم",
                detail: "تم إرسال الرد",
                life: 3000,
            });
            replyForm.reset();
        },
        onError: () => {
            const errorMessage = Object.values(replyForm.errors)[0];
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: errorMessage,
                life: 3000,
            });
        },
    });
};

// ############################################## Change request status
const showChangeStatusModal = ref(false);
const availableStatuses = ref([]);

const changeRequestForm = useForm({
    request_id: "",
    new_status: "",
    admin_note: "",
    send_notification: false,
});

const openChangeStatusModal = () => {
    showRequestDetailsModal.value = false;
    showChangeStatusModal.value = true;
    availableStatuses.value = investmentRequestsStatusOptions.value.filter(
        (status) => status.value !== requestDetails.value.status
    );
};

const closeChangeStatusModal = () => {
    showChangeStatusModal.value = false;
    changeRequestForm.reset();
};

const updateRequestStatus = () => {
    changeRequestForm.request_id = requestDetails.value.id;
    changeRequestForm.post(route("admin.investment-requests.changeStatus"), {
        onSuccess: () => {
            showChangeStatusModal.value = false;
            toast.add({
                severity: "success",
                summary: "تم",
                detail: "تم تغيير الحالة بنجاح",
                life: 3000,
            });
            changeRequestForm.reset();
        },
        onError: () => {
            const errorMessage = Object.values(changeRequestForm.errors)[0];
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
        <!-- Toast -->
        <Toast position="top-center" />

        <!-- View Investment Request Modal -->
        <Dialog
            v-model:visible="showRequestDetailsModal"
            modal
            header="تفاصيل طلب الاستثمار"
            :style="{ width: isMobile ? '90%' : '50vw' }"
            :closable="true"
            :closeOnEscape="true"
        >
            <div class="p-4 font-BeinNormal" v-if="requestDetails.id">
                <!-- Request Status and Property Owner Info -->
                <div class="mb-6">
                    <div class="flex flex-col items-center justify-center mb-4">
                        <InvestmentRequestStatus
                            :status="requestDetails.status"
                            :statusOptions="investmentRequestsStatusOptions"
                        />
                        <p class="text-gray-500 mt-2 text-sm">
                            تم إنشاء الطلب في
                            {{ formatDate(requestDetails.created_at) }}
                        </p>
                    </div>

                    <!-- Property Owner Info -->
                    <div class="bg-slate-50 rounded-lg p-4 mb-4">
                        <div class="flex items-start justify-between">
                            <h3
                                class="text-lg font-semibold text-gray-800 mb-3"
                            >
                                معلومات المالك
                            </h3>
                            <Link
                                :href="
                                    route(
                                        'admin.users.view',
                                        requestDetails.property.owner.id
                                    )
                                "
                                class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 rounded-md font-Bein text-sm transition duration-150 ease-in-out"
                            >
                                <i class="pi pi-user ml-1"></i>
                                صفحة المالك
                            </Link>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-12 h-12 rounded-full overflow-hidden ml-3"
                            >
                                <img
                                    :src="requestDetails.property.owner.image"
                                    class="w-full h-full object-cover"
                                    alt="Owner Image"
                                />
                            </div>
                            <div>
                                <h4 class="text-md font-semibold text-teal-800">
                                    {{ requestDetails.property.owner.name }}
                                </h4>
                                <p class="text-gray-600 text-sm">
                                    {{ requestDetails.property.owner.email }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-start justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">
                            معلومات العقار
                        </h3>
                        <Link
                            :href="
                                route(
                                    'admin.users.view',
                                    requestDetails.property.owner.id
                                )
                            "
                            class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-300 rounded-md font-Bein text-sm transition duration-150 ease-in-out"
                        >
                            <i class="pi pi-home ml-1"></i>
                            صفحة العقار
                        </Link>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Property Image -->
                        <div class="md:w-1/3 mb-3 md:mb-0">
                            <div class="h-40 rounded-lg overflow-hidden">
                                <img
                                    :src="
                                        '/' +
                                        requestDetails.property.images[0].path
                                    "
                                    class="w-full h-full object-cover"
                                    alt="Property Image"
                                />
                            </div>
                        </div>

                        <!-- Property Details -->
                        <div class="md:w-2/3">
                            <div class="flex justify-between items-start">
                                <h4 class="text-md font-semibold text-teal-800">
                                    {{ requestDetails.property.title }}
                                </h4>
                            </div>

                            <div class="flex items-center text-gray-600 mt-2">
                                <i class="pi pi-map-marker ml-1"></i>
                                <span
                                    >{{ requestDetails.property.city }} -
                                    {{ requestDetails.property.address }}</span
                                >
                            </div>

                            <div class="flex flex-wrap gap-3 mt-3">
                                <div
                                    class="flex items-center"
                                    v-if="requestDetails.property.bedrooms > 0"
                                >
                                    <i
                                        class="pi pi-home ml-1 text-gray-500"
                                    ></i>
                                    <span class="text-sm"
                                        >{{
                                            requestDetails.property.bedrooms
                                        }}
                                        غرف</span
                                    >
                                </div>
                                <div
                                    class="flex items-center"
                                    v-if="requestDetails.property.bathrooms > 0"
                                >
                                    <i
                                        class="pi pi-inbox ml-1 text-gray-500"
                                    ></i>
                                    <span class="text-sm"
                                        >{{
                                            requestDetails.property.bathrooms
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
                                            requestDetails.property.area
                                        }}
                                        م²</span
                                    >
                                </div>
                            </div>
                            <div class="mt-2 w-fit">
                                <div
                                    class="bg-slate-800 text-white text-xs px-2 py-1 rounded-full"
                                >
                                    {{ requestDetails.property.type }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Investment Details -->
                <div
                    class="bg-white rounded-lg p-4 border border-gray-200 mb-4"
                >
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        تفاصيل الاستثمار
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-emerald-50 rounded-lg p-3">
                            <p class="text-gray-600 text-sm">
                                قيمة الاستثمار المقترحة
                            </p>
                            <p class="text-xl font-bold text-emerald-700">
                                {{
                                    formatPrice(
                                        requestDetails.suggested_investment_amount
                                    )
                                }}
                                ريال
                            </p>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-gray-600 text-sm">
                                سعر الإيجار اليومي المقترح
                            </p>
                            <p class="text-xl font-bold text-blue-700">
                                {{
                                    formatPrice(
                                        requestDetails.suggested_daily_rent_price
                                    )
                                }}
                                ريال
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Owner Notes -->
                <div
                    class="bg-white rounded-lg p-4 border border-gray-200 mb-4"
                >
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        ملاحظات المالك
                    </h3>
                    <div class="p-3 bg-gray-50 rounded-lg text-gray-700">
                        {{ requestDetails.owner_note || "لا توجد ملاحظات" }}
                    </div>
                </div>

                <!-- Admin Response Form -->
                <div
                    class="bg-white rounded-lg p-4 border border-gray-200 mb-4"
                    v-if="requestDetails.status === 'pending'"
                >
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        الرد على الطلب
                    </h3>

                    <div class="mb-4">
                        <label
                            class="block text-gray-700 text-sm font-bold mb-2"
                        >
                            ملاحظات الإدارة
                        </label>
                        <Textarea
                            v-model="replyForm.admin_note"
                            rows="3"
                            class="w-full"
                            placeholder="أدخل ملاحظاتك هنا..."
                        />
                    </div>

                    <div class="flex flex-col md:flex-row gap-3 mt-4">
                        <Button
                            icon="pi pi-check"
                            label="موافقة على الطلب"
                            severity="success"
                            class="flex-1"
                            @click="replyToRequest('approve')"
                            :loading="replyForm.processing"
                        />

                        <Button
                            icon="pi pi-times"
                            label="رفض الطلب"
                            severity="danger"
                            class="flex-1"
                            @click="replyToRequest('reject')"
                            :loading="replyForm.processing"
                        />
                    </div>
                </div>

                <!-- Admin Response (if already responded) -->
                <div
                    class="bg-white rounded-lg p-4 border border-gray-200 mb-4"
                    v-if="requestDetails.status !== 'pending'"
                >
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        رد الإدارة
                    </h3>

                    <div class="mb-3">
                        <div class="flex items-center mb-2">
                            <i
                                class="pi"
                                :class="{
                                    'pi-check-circle text-emerald-600':
                                        requestDetails.status === 'approved',
                                    'pi-times-circle text-red-600':
                                        requestDetails.status === 'rejected',
                                }"
                                style="font-size: 1.2rem; margin-left: 0.5rem"
                            ></i>
                            <span
                                class="font-semibold"
                                :class="{
                                    'text-emerald-700':
                                        requestDetails.status === 'approved',
                                    'text-red-700':
                                        requestDetails.status === 'rejected',
                                }"
                            >
                                {{
                                    requestDetails.status === "approved"
                                        ? "تمت الموافقة على الطلب"
                                        : "تم رفض الطلب"
                                }}
                            </span>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-lg text-gray-700">
                            {{ requestDetails.admin_note || "لا توجد ملاحظات" }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end mt-6 gap-6">
                    <Button
                        icon="pi pi-times"
                        label="إغلاق"
                        @click="showRequestDetailsModal = false"
                    />
                    <Button
                        v-if="requestDetails.status !== 'pending'"
                        severity="success"
                        icon="pi pi-refresh"
                        label="تغيير الحالة"
                        @click="openChangeStatusModal()"
                    />
                </div>
            </div>
        </Dialog>

        <!-- Change Investment Request Status Modal -->
        <Dialog
            v-model:visible="showChangeStatusModal"
            modal
            header="تغيير حالة طلب الاستثمار"
            :style="{ width: isMobile ? '90%' : '50vw' }"
            :closable="true"
            :closeOnEscape="true"
        >
            <div class="p-4 font-BeinNormal">
                <!-- Current Status Display -->
                <div class="mb-4 text-center">
                    <p class="text-gray-600 mb-2">الحالة الحالية</p>
                    <div class="inline-block">
                        <InvestmentRequestStatus
                            :status="requestDetails.status"
                            :statusOptions="investmentRequestsStatusOptions"
                        />
                    </div>
                </div>

                <!-- Status Selection -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        الحالة الجديدة
                    </label>
                    <Select
                        v-model="changeRequestForm.new_status"
                        :options="availableStatuses"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="اختر الحالة"
                        class="w-full"
                    />
                </div>

                <!-- Admin Notes -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        ملاحظات الإدارة
                    </label>
                    <Textarea
                        v-model="changeRequestForm.admin_note"
                        rows="3"
                        class="w-full"
                        placeholder="أدخل ملاحظاتك هنا..."
                    />
                </div>

                <!-- Send Notification Option -->
                <div class="mb-4">
                    <div class="flex items-center">
                        <Checkbox
                            v-model="changeRequestForm.send_notification"
                            :binary="true"
                            id="sendNotification"
                        />
                        <label
                            for="sendNotification"
                            class="mr-2 text-gray-700"
                        >
                            إرسال إشعار للمالك
                        </label>
                    </div>
                </div>

                <!-- Warning Message -->
                <div
                    class="p-3 bg-amber-50 rounded-lg border border-amber-100 mb-4"
                >
                    <div class="flex items-start">
                        <i
                            class="pi pi-exclamation-triangle text-amber-500 ml-2 mt-1"
                        ></i>
                        <p class="text-sm text-amber-700">
                            تغيير حالة الطلب سيؤثر على سير العمل الخاص بهذا
                            العقار. يرجى التأكد من صحة المعلومات قبل الحفظ.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-6 mt-6">
                    <Button
                        icon="pi pi-times"
                        label="إلغاء"
                        class="border-gray-400 text-gray-700 hover:bg-gray-50"
                        @click="closeChangeStatusModal"
                    />
                    <Button
                        icon="pi pi-check"
                        label="حفظ التغييرات"
                        severity="success"
                        :loading="changeRequestForm.processing"
                        @click="updateRequestStatus"
                    />
                </div>
            </div>
        </Dialog>

        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <Icon
                    icon="fa-regular fa-file-zipper"
                    class="block h-10 text-teal-800"
                />
                <div class="">
                    <h1 class="text-3xl font-semibold m-0 text-teal-800">
                        طلبات فتح الاستثمار
                    </h1>
                    <p class="text-gray-500 text-sm m-0">
                        إدارة طلبات فتح الاستثمار على العقارات
                    </p>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div
            class="filter-section bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6"
        >
            <div class="flex flex-wrap items-center gap-4">
                <!-- Search -->
                <div class="search-container flex-1 min-w-[250px]">
                    <InputText
                        v-model="search"
                        class="w-full rounded-lg border-gray-200"
                        placeholder="البحث عن مستخدم بالاسم   ..."
                    />
                </div>
                <!-- Filters -->
                <div
                    class="filter-controls flex items-center gap-3 md:w-72 w-full"
                >
                    <Select
                        v-model="statusFilter"
                        :options="investmentRequestsStatusOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="نوع الحساب"
                        class="md:w-60 w-full rounded-lg"
                    />
                    <Button
                        icon="pi pi-filter-slash"
                        outlined
                        severity="secondary"
                        class="p-button-rounded w-44"
                        @click="resetFilters"
                    />
                </div>
            </div>
        </div>

        <!--  Empty State -->
        <div
            v-if="props.investmentRequests.data.length === 0"
            class="empty-state bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center"
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
                لا توجد طلبات فتح الاستثمار
            </h2>
            <p class="text-gray-500 max-w-md mx-auto mb-6">
                لم يتم تقديم أي طلبات فتح الاستثمار بعد.
            </p>
        </div>

        <!-- Requests table -->
        <div
            v-else
            class="rounded-xl overflow-hidden flex flex-col justify-between min-h-[75vh]"
        >
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
                <Column field="created_at" header=" المالك">
                    <template #body="slotProps">
                        <div
                            class="text-sm text-gray-600"
                            v-html="
                                textHelpers.highlightText(
                                    slotProps.data.property.owner.name,
                                    search
                                )
                            "
                        ></div>
                    </template>
                </Column>
                <Column field="created_at" header=" العقار">
                    <template #body="slotProps">
                        <div class="text-sm text-gray-600">
                            <p>{{ slotProps.data.property.title }}</p>
                            <p>{{ slotProps.data.property.address }}</p>
                        </div>
                    </template>
                </Column>
                <Column field="created_at" header=" حالة الطلب">
                    <template #body="slotProps">
                        <div class="text-sm text-gray-600">
                            <InvestmentRequestStatus
                                :status="slotProps.data.status"
                                :statusOptions="investmentRequestsStatusOptions"
                            />
                        </div>
                    </template>
                </Column>

                <Column field="created_at" header="تاريخ  الطلب">
                    <template #body="slotProps">
                        <div class="text-sm text-gray-600">
                            <i class="pi pi-calendar-plus ml-1"></i>
                            {{ formatDate(slotProps.data.created_at) }}
                        </div>
                    </template>
                </Column>

                <Column field="created_at" header="  آخر تعديل">
                    <template #body="slotProps">
                        <div class="text-sm text-gray-600">
                            <i class="pi pi-calendar-plus ml-1"></i>
                            {{ formatDate(slotProps.data.updated_at) }}
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
                        من أصل {{ props.investmentRequests.total }} مستخدم
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
