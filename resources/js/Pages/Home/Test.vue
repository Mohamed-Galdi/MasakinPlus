<script setup>
import { ref, computed, onMounted } from "vue";
import UserLayout from "@/Layouts/OwnerLayout.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import Card from "primevue/card";
import Button from "primevue/button";
// import Chart from "primevue/chart";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Badge from "primevue/badge";
// import ProgressBar from "primevue/progressbar";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import Divider from "primevue/divider";
import Avatar from "primevue/avatar";
import Menu from "primevue/menu";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";

defineOptions({
    layout: UserLayout,
});

const toast = useToast();
const menuRef = ref();
const page = usePage();
// const user = computed(() => page.props.auth.user);

const props = defineProps({
    statistics: {
        type: Object,
        required: true,
    },
    properties: {
        type: Array,
        default: () => [],
    },
    recentTransactions: {
        type: Array,
        default: () => [],
    },
    notifications: {
        type: Array,
        default: () => [],
    },
});

// Menu items for property actions
const propertyMenuItems = [
    {
        label: "عرض التفاصيل",
        icon: "pi pi-eye",
        command: () => {
            router.visit(
                route("owner.properties.show", {
                    id: selectedProperty.value.id,
                })
            );
        },
    },
    {
        label: "تعديل العقار",
        icon: "pi pi-pencil",
        command: () => {
            router.visit(
                route("owner.properties.edit", {
                    id: selectedProperty.value.id,
                })
            );
        },
    },
    {
        label: "إدارة المستأجرين",
        icon: "pi pi-users",
        command: () => {
            router.visit(
                route("owner.properties.tenants", {
                    id: selectedProperty.value.id,
                })
            );
        },
    },
    {
        label: "إدارة المستثمرين",
        icon: "pi pi-chart-line",
        command: () => {
            router.visit(
                route("owner.properties.investors", {
                    id: selectedProperty.value.id,
                })
            );
        },
    },
];

const selectedProperty = ref(null);

const toggleMenu = (event, property) => {
    selectedProperty.value = property;
    menuRef.value.toggle(event);
};

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
    }).format(date);
};

// Format percentage
const formatPercentage = (value) => {
    return `${value}%`;
};

// Chart data
const revenueChartData = ref({
    labels: [
        "يناير",
        "فبراير",
        "مارس",
        "أبريل",
        "مايو",
        "يونيو",
        "يوليو",
        "أغسطس",
        "سبتمبر",
        "أكتوبر",
        "نوفمبر",
        "ديسمبر",
    ],
    datasets: [
        {
            label: "الإيرادات",
            data:  [
                65000, 59000, 80000, 81000, 56000, 55000, 70000, 75000, 82000,
                79000, 86000, 90000,
            ],
            backgroundColor: "rgba(20, 184, 166, 0.2)",
            borderColor: "rgb(20, 184, 166)",
            borderWidth: 2,
            tension: 0.4,
        },
    ],
});

const occupancyChartData = ref({
    labels: props.properties.map((property) => property.name) || [
        "عقار 1",
        "عقار 2",
        "عقار 3",
        "عقار 4",
        "عقار 5",
    ],
    datasets: [
        {
            label: "نسبة الإشغال",
            data: props.properties.map(
                (property) => property.occupancy_rate
            ) || [95, 85, 100, 75, 90],
            backgroundColor: [
                "rgba(20, 184, 166, 0.7)",
                "rgba(14, 165, 233, 0.7)",
                "rgba(168, 85, 247, 0.7)",
                "rgba(249, 115, 22, 0.7)",
                "rgba(236, 72, 153, 0.7)",
            ],
            borderWidth: 0,
        },
    ],
});

const incomeDistributionData = ref({
    labels: ["حصة المالك", "حصة المستثمرين", "رسوم المنصة"],
    datasets: [
        {
            data: [
                 70,
                 25,
                 5,
            ],
            backgroundColor: [
                "rgba(20, 184, 166, 0.7)",
                "rgba(14, 165, 233, 0.7)",
                "rgba(168, 85, 247, 0.7)",
            ],
            hoverBackgroundColor: [
                "rgb(20, 184, 166)",
                "rgb(14, 165, 233)",
                "rgb(168, 85, 247)",
            ],
        },
    ],
});

const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                font: {
                    family: "Tajawal, sans-serif",
                },
            },
        },
    },
});

// Get property status
const getPropertyStatus = (property) => {
    if (property.status === "rented") {
        return "مؤجر";
    } else if (property.status === "available") {
        return "متاح";
    } else if (property.status === "maintenance") {
        return "صيانة";
    } else {
        return property.status;
    }
};

// Get property status severity
const getStatusSeverity = (status) => {
    switch (status) {
        case "rented":
            return "success";
        case "available":
            return "info";
        case "maintenance":
            return "warning";
        default:
            return "info";
    }
};

// Get transaction type in Arabic
const getTransactionTypeArabic = (type) => {
    switch (type) {
        case "rent_payment":
            return "دفعة إيجار";
        case "maintenance_cost":
            return "تكلفة صيانة";
        case "investor_distribution":
            return "توزيع للمستثمرين";
        case "platform_fee":
            return "رسوم المنصة";
        default:
            return type;
    }
};

// Get transaction amount class
const getAmountClass = (type) => {
    switch (type) {
        case "rent_payment":
            return "text-green-600";
        case "maintenance_cost":
            return "text-red-600";
        case "investor_distribution":
            return "text-blue-600";
        case "platform_fee":
            return "text-purple-600";
        default:
            return "";
    }
};

// Calculate total revenue
const totalRevenue = computed(() => {
    return  0;
});

// Calculate average occupancy
const averageOccupancy = computed(() => {
    if (!props.properties || props.properties.length === 0) return 0;

    const total = props.properties.reduce(
        (sum, property) => sum + property.occupancy_rate,
        0
    );
    return (total / props.properties.length).toFixed(1);
});

// Calculate total investors
const totalInvestors = computed(() => {
    return  0;
});

// Calculate pending maintenance requests
const pendingMaintenanceRequests = computed(() => {
    return  0;
});
</script>

<template>
    <div>
        <Toast position="top-center" />
        <Menu ref="menuRef" :model="propertyMenuItems" :popup="true" />

        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <i class="pi pi-home text-teal-800" style="font-size: 2rem"></i>
                <div>
                    <h1 class="text-3xl font-semibold m-0 text-teal-800">
                        لوحة التحكم
                    </h1>
                    <p class="text-gray-500 text-sm m-0">
                        مرحباً mmm، هذه نظرة عامة على عقاراتك
                    </p>
                </div>
            </div>
            <div>
                <Button
                    label="إضافة عقار جديد"
                    icon="pi pi-plus"
                    class="bg-teal-700 hover:bg-teal-800"
                />
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Properties -->
            <Card class="shadow-sm">
                <template #content>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm m-0 mb-1">
                                إجمالي العقارات
                            </p>
                            <h2 class="text-3xl font-bold text-teal-800 m-0">
                                {{ props.properties.length || 0 }}
                            </h2>
                        </div>
                        <div class="bg-teal-50 p-3 rounded-full">
                            <i
                                class="pi pi-building text-teal-700"
                                style="font-size: 1.5rem"
                            ></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <Badge value="نشط" severity="success" />
                        <span class="text-sm text-gray-500"
                            >{{  0 }} عقار
                            نشط</span
                        >
                    </div>
                </template>
            </Card>

            <!-- Total Revenue -->
            <Card class="shadow-sm">
                <template #content>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm m-0 mb-1">
                                إجمالي الإيرادات
                            </p>
                            <h2 class="text-3xl font-bold text-teal-800 m-0">
                                {{ formatCurrency(totalRevenue) }}
                            </h2>
                        </div>
                        <div class="bg-teal-50 p-3 rounded-full">
                            <i
                                class="pi pi-money-bill text-teal-700"
                                style="font-size: 1.5rem"
                            ></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <i class="pi pi-arrow-up text-green-600"></i>
                        <span class="text-sm text-green-600"
                            >{{  12 }}%
                            زيادة</span
                        >
                        <span class="text-sm text-gray-500"
                            >مقارنة بالشهر السابق</span
                        >
                    </div>
                </template>
            </Card>

            <!-- Average Occupancy -->
            <Card class="shadow-sm">
                <template #content>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm m-0 mb-1">
                                متوسط نسبة الإشغال
                            </p>
                            <h2 class="text-3xl font-bold text-teal-800 m-0">
                                {{ averageOccupancy }}%
                            </h2>
                        </div>
                        <div class="bg-teal-50 p-3 rounded-full">
                            <i
                                class="pi pi-chart-bar text-teal-700"
                                style="font-size: 1.5rem"
                            ></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <ProgressBar :value="averageOccupancy" class="h-2" />
                    </div>
                </template>
            </Card>

            <!-- Total Investors -->
            <Card class="shadow-sm">
                <template #content>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm m-0 mb-1">
                                إجمالي المستثمرين
                            </p>
                            <h2 class="text-3xl font-bold text-teal-800 m-0">
                                {{ totalInvestors }}
                            </h2>
                        </div>
                        <div class="bg-teal-50 p-3 rounded-full">
                            <i
                                class="pi pi-users text-teal-700"
                                style="font-size: 1.5rem"
                            ></i>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <Badge
                            :value="pendingMaintenanceRequests"
                            severity="warning"
                        />
                        <span class="text-sm text-gray-500"
                            >طلبات صيانة معلقة</span
                        >
                    </div>
                </template>
            </Card>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Revenue Chart -->
            <Card class="shadow-sm lg:col-span-2">
                <template #header>
                    <div
                        class="bg-teal-50 p-4 flex justify-between items-center"
                    >
                        <h2 class="text-lg font-semibold text-teal-800 m-0">
                            الإيرادات الشهرية
                        </h2>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">{{
                                new Date().getFullYear()
                            }}</span>
                            <Button
                                icon="pi pi-download"
                                text
                                rounded
                                aria-label="تحميل"
                            />
                        </div>
                    </div>
                </template>
                <template #content>
                    <div class="h-80">
                        <Chart
                            type="line"
                            :data="revenueChartData"
                            :options="chartOptions"
                        />
                    </div>
                </template>
            </Card>

            <!-- Income Distribution -->
            <Card class="shadow-sm">
                <template #header>
                    <div
                        class="bg-teal-50 p-4 flex justify-between items-center"
                    >
                        <h2 class="text-lg font-semibold text-teal-800 m-0">
                            توزيع الإيرادات
                        </h2>
                    </div>
                </template>
                <template #content>
                    <div class="h-80">
                        <Chart
                            type="pie"
                            :data="incomeDistributionData"
                            :options="chartOptions"
                        />
                    </div>
                </template>
            </Card>
        </div>

        <!-- Properties and Transactions Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Properties List -->
            <Card class="shadow-sm lg:col-span-2">
                <template #header>
                    <div
                        class="bg-teal-50 p-4 flex justify-between items-center"
                    >
                        <h2 class="text-lg font-semibold text-teal-800 m-0">
                            العقارات
                        </h2>
                        <Link :href="route('owner.properties.index')">
                            <Button
                                label="عرض الكل"
                                text
                                class="text-teal-700"
                            />
                        </Link>
                    </div>
                </template>
                <template #content>
                    <div
                        v-if="props.properties.length === 0"
                        class="text-center py-8"
                    >
                        <div
                            class="bg-gray-50 inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
                        >
                            <i
                                class="pi pi-home text-gray-300"
                                style="font-size: 2rem"
                            ></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">
                            لا توجد عقارات
                        </h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-4">
                            لم تقم بإضافة أي عقارات بعد. أضف عقارك الأول للبدء
                            في استقبال المستأجرين والمستثمرين.
                        </p>
                        <Button
                            label="إضافة عقار جديد"
                            icon="pi pi-plus"
                            class="bg-teal-700 hover:bg-teal-800"
                        />
                    </div>
                    <div v-else class="space-y-4">
                        <div
                            v-for="property in props.properties"
                            :key="property.id"
                            class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
                        >
                            <div class="flex justify-between">
                                <div class="flex gap-4">
                                    <img
                                        :src="
                                            property.thumbnail ||
                                            '/placeholder.svg?height=80&width=80'
                                        "
                                        :alt="property.name"
                                        class="w-20 h-20 object-cover rounded-lg"
                                    />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-medium text-lg">
                                                {{ property.name }}
                                            </h3>
                                            <Badge
                                                :value="
                                                    getPropertyStatus(property)
                                                "
                                                :severity="
                                                    getStatusSeverity(
                                                        property.status
                                                    )
                                                "
                                            />
                                        </div>
                                        <p class="text-gray-600 text-sm mb-2">
                                            <i
                                                class="pi pi-map-marker ml-1"
                                            ></i>
                                            {{ property.location }}
                                        </p>
                                        <div
                                            class="flex items-center gap-4 text-sm"
                                        >
                                            <span
                                                class="flex items-center gap-1"
                                            >
                                                <i
                                                    class="pi pi-home text-teal-700"
                                                ></i>
                                                {{ property.units || 1 }} وحدات
                                            </span>
                                            <span
                                                class="flex items-center gap-1"
                                            >
                                                <i
                                                    class="pi pi-users text-teal-700"
                                                ></i>
                                                {{
                                                    property.investors || 0
                                                }}
                                                مستثمرين
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <div
                                        class="text-lg font-semibold text-teal-800"
                                    >
                                        {{
                                            formatCurrency(
                                                property.monthly_revenue || 0
                                            )
                                        }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        شهرياً
                                    </div>
                                    <Button
                                        icon="pi pi-ellipsis-v"
                                        text
                                        rounded
                                        aria-label="خيارات"
                                        @click="toggleMenu($event, property)"
                                    />
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500"
                                        >نسبة الإشغال</span
                                    >
                                    <span class="text-sm font-medium"
                                        >{{ property.occupancy_rate }}%</span
                                    >
                                </div>
                                <ProgressBar
                                    :value="property.occupancy_rate"
                                    class="h-2 mt-1"
                                    :class="{
                                        'teal-progress':
                                            property.occupancy_rate >= 80,
                                        'yellow-progress':
                                            property.occupancy_rate < 80 &&
                                            property.occupancy_rate >= 50,
                                        'red-progress':
                                            property.occupancy_rate < 50,
                                    }"
                                />
                            </div>
                        </div>
                    </div>
                </template>
            </Card>

            <!-- Right Sidebar -->
            <div class="space-y-6">
                <!-- Recent Transactions -->
                <Card class="shadow-sm">
                    <template #header>
                        <div
                            class="bg-teal-50 p-4 flex justify-between items-center"
                        >
                            <h2 class="text-lg font-semibold text-teal-800 m-0">
                                آخر المعاملات
                            </h2>
                            <Link :href="route('home')">
                                <Button
                                    label="عرض الكل"
                                    text
                                    class="text-teal-700"
                                />
                            </Link>
                        </div>
                    </template>
                    <template #content>
                        <div
                            v-if="props.recentTransactions.length === 0"
                            class="text-center py-4"
                        >
                            <p class="text-gray-500">لا توجد معاملات حديثة</p>
                        </div>
                        <div v-else class="space-y-3">
                            <div
                                v-for="transaction in props.recentTransactions"
                                :key="transaction.id"
                                class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center"
                                        :class="{
                                            'bg-green-100':
                                                transaction.type ===
                                                'rent_payment',
                                            'bg-red-100':
                                                transaction.type ===
                                                'maintenance_cost',
                                            'bg-blue-100':
                                                transaction.type ===
                                                'investor_distribution',
                                            'bg-purple-100':
                                                transaction.type ===
                                                'platform_fee',
                                        }"
                                    >
                                        <i
                                            class="text-lg"
                                            :class="{
                                                'pi pi-arrow-down text-green-600':
                                                    transaction.type ===
                                                    'rent_payment',
                                                'pi pi-wrench text-red-600':
                                                    transaction.type ===
                                                    'maintenance_cost',
                                                'pi pi-share-alt text-blue-600':
                                                    transaction.type ===
                                                    'investor_distribution',
                                                'pi pi-percentage text-purple-600':
                                                    transaction.type ===
                                                    'platform_fee',
                                            }"
                                        ></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">
                                            {{
                                                getTransactionTypeArabic(
                                                    transaction.type
                                                )
                                            }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ formatDate(transaction.date) }}
                                        </div>
                                    </div>
                                </div>
                                <div
                                    :class="getAmountClass(transaction.type)"
                                    class="font-semibold"
                                >
                                    {{
                                        transaction.type ===
                                            "maintenance_cost" ||
                                        transaction.type ===
                                            "investor_distribution" ||
                                        transaction.type === "platform_fee"
                                            ? "-"
                                            : "+"
                                    }}
                                    {{ formatCurrency(transaction.amount) }}
                                </div>
                            </div>
                        </div>
                    </template>
                </Card>

                <!-- Notifications -->
                <Card class="shadow-sm">
                    <template #header>
                        <div
                            class="bg-teal-50 p-4 flex justify-between items-center"
                        >
                            <h2 class="text-lg font-semibold text-teal-800 m-0">
                                الإشعارات
                            </h2>
                            <Badge
                                v-if="props.notifications.length > 0"
                                :value="props.notifications.length"
                                severity="danger"
                            />
                        </div>
                    </template>
                    <template #content>
                        <div
                            v-if="props.notifications.length === 0"
                            class="text-center py-4"
                        >
                            <p class="text-gray-500">لا توجد إشعارات جديدة</p>
                        </div>
                        <div v-else class="space-y-3">
                            <div
                                v-for="notification in props.notifications"
                                :key="notification.id"
                                class="p-3 rounded-lg"
                                :class="{
                                    'bg-blue-50': notification.type === 'info',
                                    'bg-green-50':
                                        notification.type === 'success',
                                    'bg-yellow-50':
                                        notification.type === 'warning',
                                    'bg-red-50': notification.type === 'danger',
                                }"
                            >
                                <div class="flex items-start gap-3">
                                    <i
                                        class="mt-0.5"
                                        :class="{
                                            'pi pi-info-circle text-blue-600':
                                                notification.type === 'info',
                                            'pi pi-check-circle text-green-600':
                                                notification.type === 'success',
                                            'pi pi-exclamation-triangle text-yellow-600':
                                                notification.type === 'warning',
                                            'pi pi-times-circle text-red-600':
                                                notification.type === 'danger',
                                        }"
                                    ></i>
                                    <div>
                                        <div class="font-medium">
                                            {{ notification.title }}
                                        </div>
                                        <p
                                            class="text-sm text-gray-600 mt-1 mb-2"
                                        >
                                            {{ notification.message }}
                                        </p>
                                        <div class="text-xs text-gray-500">
                                            {{ formatDate(notification.date) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
        </div>
    </div>
</template>
