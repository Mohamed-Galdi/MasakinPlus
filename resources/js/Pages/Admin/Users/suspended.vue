<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, computed, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { useTextHelpers } from "@/plugins/textHelpers";
import { debounce } from "lodash";
import Select from "primevue/select";
import InputText from "primevue/inputtext";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Avatar from "primevue/avatar";
import Tag from "primevue/tag";
import Button from "primevue/button";
import Tooltip from "primevue/tooltip";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    usersTypes: {
        type: Array,
        required: true,
    },
    search: {
        type: String,
        required: false,
        default: "",
    },
    userType: {
        type: String,
        required: false,
        default: "",
    },
});

const UsersTypes = ref(props.usersTypes);

// ############################################## Search and filter

const textHelpers = useTextHelpers();
const search = ref(props.search);
const userType = ref(props.userType);

watch(
    [search, userType],
    debounce(([search, userType]) => {
        // Update the table with both filters
        router.get(
            route("admin.users.suspended"),
            {
                search: search,
                userType: userType,
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
    userType.value = "";
};

// Computed property to check if users list is empty
const isEmptyUsers = computed(() => {
    return !props.users.data || props.users.data.length === 0;
});

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

// Function to format date
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("ar-SA", {
        year: "numeric",
        month: "short",
        day: "numeric",
    }).format(date);
};

// Function to get user type in Arabic
const getUserTypeArabic = (type) => {
    switch (type) {
        case "owner":
            return "مالك";
        case "investor":
            return "مستثمر";
        case "tenant":
            return "مستأجر";
        default:
            return type;
    }
};

//
function editUser(id) {
    router.get(route("admin.users.view", { user: id }));
}
</script>

<template>
    <div>
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <i
                    class="pi pi-users text-rose-800 h-full"
                    style="font-size: 2.5rem"
                ></i>
                <div class="">
                    <h1 class="text-3xl font-semibold m-0 text-rose-800">
                        المستخدمين المحضورين 
                    </h1>
                    <p class="text-gray-500 text-sm m-0">
                        إدارة حسابات المستخدمين في النظام
                    </p>
                </div>
            </div>
            <div>
                <Link :href="route('admin.users.index')" class="btn bg-teal-600 hover:bg-teal-700 text-white">
                    <p>قائمة المستخدمين  </p>
                    <i class="pi pi-users"></i>
                </Link>
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
                        placeholder="البحث عن مستخدم بالاسم أو البريد الإلكتروني..."
                    />
                </div>
                <!-- Filters -->
                <div
                    class="filter-controls flex items-center gap-3 md:w-72 w-full"
                >
                    <Select
                        v-model="userType"
                        :options="UsersTypes"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="نوع الحساب"
                        class="md:w-60 w-full rounded-lg"
                    />
                    <Button
                        v-tooltip.top="'إعادة ضبط الفلتر'"
                        icon="pi pi-filter-slash"
                        outlined
                        severity="secondary"
                        aria-label="إعادة ضبط الفلتر"
                        class="p-button-rounded w-44"
                        @click="resetFilters"
                    />
                </div>
            </div>
        </div>

        <!-- Enhanced Empty State -->
        <div
            v-if="isEmptyUsers"
            class="empty-state bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center"
        >
            <div
                class="empty-state-icon bg-gray-50 inline-flex items-center justify-center w-24 h-24 rounded-full mb-4"
            >
                <i
                    class="pi pi-users text-gray-300"
                    style="font-size: 3rem"
                ></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">
                لا يوجد مستخدمين محضورين
            </h2>
            <p class="text-gray-500 max-w-md mx-auto mb-6">
                لم يتم العثور على أي مستخدمين مطابقين لمعايير البحث الحالية.
                يمكنك تعديل الفلتر أو إضافة مستخدمين جدد.
            </p>
            <div class="flex justify-center gap-3">
                <Button
                    icon="pi pi-filter-slash"
                    label="إعادة ضبط الفلتر"
                    outlined
                    @click="resetFilters"
                />
            </div>
        </div>

        <!-- Users Table -->
        <div
            v-else
            class="users-table rounded-xl shadow-sm border border-gray-100 overflow-hidden"
        >
            <DataTable
                :value="props.users.data"
                stripedRows
                showGridlines
                tableStyle="min-width: 50rem"
                class="border-none"
            >
                <Column field="image" header="الصورة" class="w-[80px]">
                    <template #body="slotProps">
                        <Avatar
                            :image="slotProps.data.image"
                            size="normal"
                            shape="circle"
                        />
                    </template>
                </Column>

                <Column field="name" header="الاسم">
                    <template #body="slotProps">
                        <div
                            v-html="
                                textHelpers.highlightText(
                                    slotProps.data.name,
                                    search
                                )
                            "
                        ></div>
                    </template>
                </Column>

                <Column field="email" header="البريد الإلكتروني">
                    <template #body="slotProps">
                        <div
                            class="text-sm text-gray-600"
                            v-html="
                                textHelpers.highlightText(
                                    slotProps.data.email,
                                    search
                                )
                            "
                        ></div>
                    </template>
                </Column>

                <Column field="type" header="نوع الحساب">
                    <template #body="slotProps">
                        <Tag
                            :value="getUserTypeArabic(slotProps.data.type)"
                            :severity="getTypeSeverity(slotProps.data.type)"
                            class="text-xs"
                        />
                    </template>
                </Column>

                <Column field="created_at" header="تاريخ التسجيل">
                    <template #body="slotProps">
                        <div class="text-sm text-gray-600">
                            <i class="pi pi-calendar-plus ml-1"></i>
                            {{ formatDate(slotProps.data.created_at) }}
                        </div>
                    </template>
                </Column>
                <Column field="created_at" header="تاريخ الحضر">
                    <template #body="slotProps">
                        <div class="text-sm text-red-600 flex gap-1">
                            <i class="pi pi-ban"></i>
                            <p class>{{ formatDate(slotProps.data.suspended_at) || '--' }}</p>
                        </div>
                    </template>
                </Column>

                <Column header="الإجراءات" class="w-[120px]">
                    <template #body="slotProps">
                        <div class="flex gap-1 justify-center">
                            <Button
                                @click="editUser(slotProps.data.id)"
                                v-tooltip="'تعديل المستخدم'"
                                icon="pi pi-pencil"
                                text
                                rounded
                                aria-label="تعديل"
                                class="p-button-sm"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Pagination -->
        <div
            v-if="!isEmptyUsers"
            dir="ltr"
            class="my-8 flex md:flex-row flex-col md:gap-0 gap-2 justify-between items-center w-full"
        >
            <div class="order-last md:order-first">
                <p class="text-base text-slate-600 rtl:text-right">
                    عرض
                    <span class="text-teal-600 font-bold text-lg">{{
                        props.users.from
                    }}</span>
                    إلى
                    <span class="text-teal-600 font-bold text-lg">{{
                        props.users.to
                    }}</span>
                    من أصل {{ props.users.total }} مستخدم
                </p>
            </div>
            <nav class="order-first md:order-last">
                <div class="flex items-center -space-x-px h-8 text-sm">
                    <template
                        v-for="(link, index) in props.users.links"
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
                                    index === props.users.links.length - 1,
                            }"
                        />
                        <p
                            v-else
                            v-html="link.label"
                            class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-slate-200 border border-gray-300"
                            :class="{
                                'rounded-l-lg': index === 0,
                                'rounded-r-lg':
                                    index === props.users.links.length - 1,
                            }"
                        />
                    </template>
                </div>
            </nav>
        </div>
    </div>
</template>

<style scoped>
/* Table styling */
:deep(.p-datatable .p-datatable-thead > tr > th) {
    background-color: #f8fafc;
    color: #475569;
    font-weight: 600;
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

:deep(.p-datatable .p-datatable-tbody > tr > td) {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f5f9;
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
    background-color: #f8fafc;
}

:deep(.p-datatable-wrapper) {
    border-radius: 0.75rem;
}

/* Ensure RTL support for PrimeVue components */
:deep(.p-inputtext),
:deep(.p-dropdown) {
    text-align: right;
}

/* Empty state animation */
.empty-state-icon {
    animation: pulse 2s infinite ease-in-out;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}
</style>
