<script setup>
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import { ref, reactive, watch } from "vue";
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
// import RequestStatusBadge from "@/Components/RequestStatusBadge.vue";
import { useTextHelpers } from "@/plugins/textHelpers";

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
});

// Text helpers
const textHelpers = useTextHelpers();

// Modal state
const showNewRequestModal = ref(false);
const selectedProperty = ref(null);

// New request form
const form = useForm({
    property_id: "",
    suggested_investment_amount: 0,
    suggested_daily_rent_price: 0,
    owner_note: "",
});

// Function to handle submission
const submitRequest = () => {
    form.post(route("owner.investment-requests.store"), {
        onSuccess: () => {
            showNewRequestModal.value = false;
            form.reset();
        },
    });
};

// Format currency
const formatPrice = (price) => {
    return parseFloat(price).toLocaleString("ar-SA");
};

// Open modal with property pre-selected
const openRequestModal = (property) => {
    selectedProperty.value = property;
    form.property_id = property.id;
    form.suggested_daily_rent_price = property.daily_rent_price;
    showNewRequestModal.value = true;
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

// View request details
const viewRequestDetails = (request) => {
    // Navigate to request details or show details modal
    // router.get(route('owner.investment-requests.show', request.id));
    console.log("View request", request);
};
</script>

<template>
    <div class="container mx-auto ">
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
                @click="showNewRequestModal = true"
                class="btn bg-emerald-600 hover:bg-emerald-700 text-white md:w-fit w-full mt-2 md:mt-0"
            >
                <i class="pi pi-plus"></i>
                <span>طلب فتح استثمار جديد</span>
            </button>
        </div>

        <!-- Investment Requests Table/Empty State -->
        <div class="bg-white rounded-lg shadow p-5 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                طلبات الاستثمار
            </h2>

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
            <div v-else class="rounded-xl overflow-hidden">
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
                            <span class="font-mono"
                                >#{{ slotProps.data.id }}</span
                            >
                        </template>
                    </Column>

                    <!-- <Column field="property" header="العقار">
                        <template #body="slotProps">
                            <div class="flex items-center gap-2">
                                <div class="flex-1">
                                    <div class="font-medium">
                                        {{ slotProps.data.property.title }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <i class="pi pi-map-marker ml-1"></i
                                        >{{ slotProps.data.property.city }}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Column> -->

                    <Column
                        field="suggested_investment_amount"
                        header="قيمة الاستثمار"
                        class="w-[180px]"
                    >
                        <template #body="slotProps">
                            <div class="font-semibold text-emerald-700">
                                {{
                                    formatPrice(
                                        slotProps.data
                                            .suggested_investment_amount
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
                                        slotProps.data
                                            .suggested_daily_rent_price
                                    )
                                }}
                                ريال
                            </div>
                        </template>
                    </Column>

                    <Column field="status" header="الحالة" class="w-[130px]">
                        <template #body="slotProps">
                            <RequestStatusBadge
                                :status="slotProps.data.status"
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

                <!-- Pagination will go here if you add pagination to your requests -->
            </div>
        </div>

        <!-- Available Properties Section -->
        <div class="bg-white rounded-lg shadow p-5 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                العقارات المتاحة للاستثمار
            </h2>

            <div v-if="properties.length === 0" class="text-center py-10">
                <i class="pi pi-home text-gray-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-medium text-gray-800 mb-2">
                    لا توجد عقارات متاحة
                </h3>
                <p class="text-gray-600 mb-6">
                    ليس لديك عقارات متاحة لفتح استثمار عليها
                </p>
                <Link
                    :href="route('owner.properties.create')"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors"
                >
                    إضافة عقار جديد
                </Link>
            </div>

            <div
                v-else
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
            >
                <div
                    v-for="property in properties.data"
                    :key="property.id"
                    class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow"
                >
                    <div class="h-40 overflow-hidden">
                        <img
                            :src="
                                property.images && property.images.length > 0
                                    ? `/${property.images[0].path}`
                                    : '/placeholder.svg?height=160&width=300'
                            "
                            class="w-full h-full object-cover"
                            alt="Property Image"
                        />
                    </div>

                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-teal-800">
                                {{ property.title }}
                            </h3>
                            <span
                                class="bg-slate-800 text-white text-xs px-2 py-1 rounded-full"
                                >{{ property.type_label }}</span
                            >
                        </div>

                        <div class="flex items-center text-gray-600 mb-3">
                            <i class="pi pi-map-marker ml-1 text-gray-500"></i>
                            <span>{{ property.city }}</span>
                        </div>

                        <div
                            class="flex justify-between items-center border-t pt-3"
                        >
                            <div>
                                <p class="text-sm text-gray-500">
                                    الإيجار اليومي
                                </p>
                                <p class="text-lg font-bold text-emerald-600">
                                    {{ formatPrice(property.daily_rent_price) }}
                                    ريال
                                </p>
                            </div>

                            <Button
                                @click="openRequestModal(property)"
                                class="flex items-center justify-center gap-1 py-1 px-3 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 text-emerald-700 rounded-md transition-all"
                            >
                                <i
                                    class="pi pi-plus-circle text-emerald-600"
                                ></i>
                                <span class="text-sm font-medium"
                                    >فتح استثمار</span
                                >
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Investment Request Modal -->
        <Dialog
            v-model:visible="showNewRequestModal"
            modal
            header="طلب فتح استثمار جديد"
            :style="{ width: '500px' }"
            :closable="true"
            :closeOnEscape="true"
            class="rtl"
        >
            <div class="p-4">
                <form @submit.prevent="submitRequest">
                    <!-- Property Selection -->
                    <div class="mb-4" v-if="!selectedProperty">
                        <label class="block text-gray-700 mb-2">العقار</label>
                        <Select
                            v-model="form.property_id"
                            :options="properties"
                            optionLabel="title"
                            optionValue="id"
                            placeholder="اختر العقار"
                            class="w-full"
                            :class="{ 'p-invalid': form.errors.property_id }"
                        />
                        <small
                            class="text-red-600"
                            v-if="form.errors.property_id"
                            >{{ form.errors.property_id }}</small
                        >
                    </div>

                    <!-- Property Information (if selected) -->
                    <div
                        v-if="selectedProperty"
                        class="mb-4 p-3 bg-gray-50 rounded-lg"
                    >
                        <h4 class="font-medium text-gray-700">
                            {{ selectedProperty.title }}
                        </h4>
                        <div
                            class="flex items-center text-sm text-gray-600 mt-1"
                        >
                            <i class="pi pi-map-marker ml-1"></i>
                            <span>{{ selectedProperty.city }}</span>
                        </div>
                        <div class="mt-2 text-sm">
                            <span class="text-gray-500">السعر الحالي: </span>
                            <span class="font-semibold"
                                >{{
                                    formatPrice(
                                        selectedProperty.daily_rent_price
                                    )
                                }}
                                ريال</span
                            >
                        </div>
                    </div>

                    <!-- Investment Amount -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2"
                            >قيمة الاستثمار المقترحة (ريال)</label
                        >
                        <InputNumber
                            v-model="form.suggested_investment_amount"
                            :min="0"
                            :step="1000"
                            class="w-full"
                            :class="{
                                'p-invalid':
                                    form.errors.suggested_investment_amount,
                            }"
                            suffix=" ريال"
                        />
                        <small
                            class="text-red-600"
                            v-if="form.errors.suggested_investment_amount"
                            >{{
                                form.errors.suggested_investment_amount
                            }}</small
                        >
                    </div>

                    <!-- Daily Rent Price -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2"
                            >سعر الإيجار اليومي المقترح (ريال)</label
                        >
                        <InputNumber
                            v-model="form.suggested_daily_rent_price"
                            :min="0"
                            class="w-full"
                            :class="{
                                'p-invalid':
                                    form.errors.suggested_daily_rent_price,
                            }"
                            suffix=" ريال"
                        />
                        <small
                            class="text-red-600"
                            v-if="form.errors.suggested_daily_rent_price"
                            >{{ form.errors.suggested_daily_rent_price }}</small
                        >
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">ملاحظات</label>
                        <Textarea
                            v-model="form.owner_note"
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
                            type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700"
                            :loading="form.processing"
                        >
                            إرسال الطلب
                        </Button>
                    </div>
                </form>
            </div>
        </Dialog>
    </div>
</template>
