<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, onMounted } from "vue";
import Chart from "primevue/chart";
import Card from "primevue/card";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Avatar from "primevue/avatar";
import Tag from "primevue/tag";
import ProgressBar from "primevue/progressbar";
import Dropdown from "primevue/dropdown";
import Badge from "primevue/badge";
import Cards from "@/Components/AdminDashboard/Cards.vue";

defineOptions({
    layout: AdminLayout,
});

// Time period filter
const timePeriods = ref([
    { label: "اليوم", value: "today" },
    { label: "هذا الأسبوع", value: "week" },
    { label: "هذا الشهر", value: "month" },
    { label: "هذا العام", value: "year" },
]);
const selectedPeriod = ref("month");



// Recent properties
const recentProperties = ref([
    {
        id: 1,
        title: "فيلا فاخرة في حي الياسمين",
        location: "الرياض، حي الياسمين",
        price: "٢,٥٠٠,٠٠٠ ر.س",
        status: "متاح",
        owner: "أحمد محمد",
        image: "https://via.placeholder.com/50",
        date: "2023-04-15",
        views: 245,
    },
    {
        id: 2,
        title: "شقة مفروشة في حي النرجس",
        location: "الرياض، حي النرجس",
        price: "٨٥٠,٠٠٠ ر.س",
        status: "مؤجر",
        owner: "سارة عبدالله",
        image: "https://via.placeholder.com/50",
        date: "2023-04-10",
        views: 187,
    },
    {
        id: 3,
        title: "فيلا مع مسبح في حي الملقا",
        location: "الرياض، حي الملقا",
        price: "٣,٢٠٠,٠٠٠ ر.س",
        status: "قيد الاستثمار",
        owner: "خالد العمري",
        image: "https://via.placeholder.com/50",
        date: "2023-04-05",
        views: 320,
    },
    {
        id: 4,
        title: "شقة دوبلكس في حي الورود",
        location: "جدة، حي الورود",
        price: "١,٧٥٠,٠٠٠ ر.س",
        status: "متاح",
        owner: "نورة السالم",
        image: "https://via.placeholder.com/50",
        date: "2023-04-01",
        views: 156,
    },
]);

// Property status distribution chart
const propertyStatusData = ref({
    labels: ["متاح", "مؤجر", "قيد الاستثمار", "تحت الصيانة"],
    datasets: [
        {
            data: [45, 30, 15, 10],
            backgroundColor: ["#0d9488", "#0369a1", "#d97706", "#6b7280"],
            hoverBackgroundColor: ["#14b8a6", "#0284c7", "#f59e0b", "#9ca3af"],
            borderWidth: 0,
        },
    ],
});

// Monthly revenue chart
const revenueData = ref({
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
            label: "الإيرادات (ر.س)",
            data: [
                350000, 320000, 280000, 240000, 270000, 300000, 330000, 260000,
                390000, 420000, 450000, 480000,
            ],
            fill: true,
            backgroundColor: "rgba(13, 148, 136, 0.1)",
            borderColor: "#0d9488",
            tension: 0.4,
            pointBackgroundColor: "#0d9488",
            pointBorderColor: "#fff",
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
        },
    ],
});

// Property types chart
const propertyTypesData = ref({
    labels: ["فلل", "شقق", "أراضي", "عمائر", "مكاتب تجارية"],
    datasets: [
        {
            label: "عدد العقارات",
            data: [540, 325, 175, 120, 88],
            backgroundColor: "rgba(13, 148, 136, 0.8)",
            borderColor: "#0d9488",
            borderWidth: 0,
            borderRadius: 6,
            barThickness: 16,
        },
    ],
});

// City distribution chart
const cityDistributionData = ref({
    labels: ["الرياض", "جدة", "الدمام", "مكة", "المدينة", "أخرى"],
    datasets: [
        {
            data: [40, 25, 15, 10, 7, 3],
            backgroundColor: [
                "rgba(13, 148, 136, 0.9)",
                "rgba(13, 148, 136, 0.8)",
                "rgba(13, 148, 136, 0.7)",
                "rgba(13, 148, 136, 0.6)",
                "rgba(13, 148, 136, 0.5)",
                "rgba(13, 148, 136, 0.4)",
            ],
            borderWidth: 0,
        },
    ],
});

// User growth chart
const userGrowthData = ref({
    labels: ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو"],
    datasets: [
        {
            label: "المالكين",
            data: [65, 78, 90, 105, 125, 138],
            backgroundColor: "rgba(13, 148, 136, 0.8)",
            borderColor: "#0d9488",
            tension: 0.4,
            fill: false,
            pointBackgroundColor: "#0d9488",
        },
        {
            label: "المستثمرين",
            data: [45, 58, 70, 85, 95, 110],
            backgroundColor: "rgba(3, 105, 161, 0.8)",
            borderColor: "#0369a1",
            tension: 0.4,
            fill: false,
            pointBackgroundColor: "#0369a1",
        },
        {
            label: "المستأجرين",
            data: [85, 100, 120, 145, 165, 190],
            backgroundColor: "rgba(217, 119, 6, 0.8)",
            borderColor: "#d97706",
            tension: 0.4,
            fill: false,
            pointBackgroundColor: "#d97706",
        },
    ],
});

// Top investors
const topInvestors = ref([
    {
        name: "محمد العبدالله",
        image: "https://via.placeholder.com/40",
        investments: 12,
        amount: "٥,٢٠٠,٠٠٠ ر.س",
        roi: 8.5,
    },
    {
        name: "سارة الفهد",
        image: "https://via.placeholder.com/40",
        investments: 8,
        amount: "٣,٨٠٠,٠٠٠ ر.س",
        roi: 7.9,
    },
    {
        name: "عبدالرحمن السالم",
        image: "https://via.placeholder.com/40",
        investments: 6,
        amount: "٢,٥٠٠,٠٠٠ ر.س",
        roi: 8.2,
    },
    {
        name: "نورة العمري",
        image: "https://via.placeholder.com/40",
        investments: 5,
        amount: "٢,١٠٠,٠٠٠ ر.س",
        roi: 7.5,
    },
]);

// Recent activities
const recentActivities = ref([
    {
        type: "property_added",
        title: "تم إضافة عقار جديد",
        description: "تمت إضافة فيلا فاخرة في حي الياسمين بواسطة أحمد محمد",
        time: "منذ ساعتين",
        icon: "pi pi-home",
    },
    {
        type: "investment",
        title: "استثمار جديد",
        description: "قام عبدالرحمن السالم باستثمار ٨٠٠,٠٠٠ ر.س في عقار جديد",
        time: "منذ ٥ ساعات",
        icon: "pi pi-dollar",
    },
    {
        type: "rental",
        title: "عملية تأجير",
        description: "تم تأجير شقة في حي النرجس لمدة سنة بقيمة ٥٠,٠٠٠ ر.س",
        time: "منذ ٨ ساعات",
        icon: "pi pi-key",
    },
    {
        type: "user_joined",
        title: "مستخدم جديد",
        description: "انضم خالد العمري كمستثمر جديد إلى المنصة",
        time: "منذ يوم",
        icon: "pi pi-user-plus",
    },
]);

// Chart options
const chartOptions = ref({
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                usePointStyle: true,
                font: {
                    size: 12,
                },
                padding: 20,
            },
        },
        tooltip: {
            backgroundColor: "rgba(13, 148, 136, 0.9)",
            titleFont: {
                size: 14,
            },
            bodyFont: {
                size: 13,
            },
            padding: 12,
            cornerRadius: 8,
        },
    },
    responsive: true,
    maintainAspectRatio: false,
});

const lineChartOptions = ref({
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                usePointStyle: true,
                font: {
                    size: 12,
                },
                padding: 20,
            },
        },
        tooltip: {
            backgroundColor: "rgba(13, 148, 136, 0.9)",
            titleFont: {
                size: 14,
            },
            bodyFont: {
                size: 13,
            },
            padding: 12,
            cornerRadius: 8,
        },
    },
    scales: {
        x: {
            grid: {
                display: false,
            },
        },
        y: {
            grid: {
                display: true,
                color: "rgba(0, 0, 0, 0.05)",
                drawBorder: false,
            },
            ticks: {
                padding: 10,
            },
        },
    },
    responsive: true,
    maintainAspectRatio: false,
});

const barChartOptions = ref({
    indexAxis: "y",
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            backgroundColor: "rgba(13, 148, 136, 0.9)",
            titleFont: {
                size: 14,
            },
            bodyFont: {
                size: 13,
            },
            padding: 12,
            cornerRadius: 8,
        },
    },
    scales: {
        x: {
            grid: {
                display: false,
            },
        },
        y: {
            grid: {
                display: false,
            },
        },
    },
    responsive: true,
    maintainAspectRatio: false,
});

// Get property status severity
const getPropertyStatusSeverity = (status) => {
    switch (status) {
        case "متاح":
            return "success";
        case "مؤجر":
            return "info";
        case "قيد الاستثمار":
            return "warning";
        case "تحت الصيانة":
            return "secondary";
        default:
            return "secondary";
    }
};

// Get activity icon class
const getActivityIconClass = (type) => {
    switch (type) {
        case "property_added":
            return "bg-teal-100 text-teal-600";
        case "investment":
            return "bg-amber-100 text-amber-600";
        case "rental":
            return "bg-sky-100 text-sky-600";
        case "user_joined":
            return "bg-indigo-100 text-indigo-600";
        default:
            return "bg-gray-100 text-gray-600";
    }
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
</script>

<template>
    <div>
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <i
                    class="pi pi-chart-line text-teal-800 h-full"
                    style="font-size: 2.5rem"
                ></i>
                <div class="">
                    <h1 class="text-3xl font-semibold m-0 text-teal-800">
                        لوحة التحكم
                    </h1>
                    <p class="text-gray-500 text-sm m-0">
                        نظرة عامة على أداء المنصة والإحصائيات
                    </p>
                </div>
            </div>
            <div>
                <Dropdown
                    v-model="selectedPeriod"
                    :options="timePeriods"
                    optionLabel="label"
                    optionValue="value"
                    class="w-48 border border-gray-200 rounded-lg shadow-sm"
                />
            </div>
        </div>

        <!-- Metrics Cards 1 -->
        <Cards />

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 my-6">
            <!-- Monthly Revenue Chart -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">
                            الإيرادات الشهرية
                        </h3>
                        <div class="flex items-center gap-2">
                            <Badge value="18%" severity="success" />
                            <Button
                                icon="pi pi-download"
                                text
                                severity="secondary"
                                aria-label="تصدير"
                            />
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="h-80">
                        <Chart
                            type="line"
                            :data="revenueData"
                            :options="lineChartOptions"
                        />
                    </div>
                </div>
            </div>

            <!-- User Growth Chart -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">
                            نمو المستخدمين
                        </h3>
                        <div class="flex items-center gap-2">
                            <Badge value="12%" severity="success" />
                            <Button
                                icon="pi pi-download"
                                text
                                severity="secondary"
                                aria-label="تصدير"
                            />
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="h-80">
                        <Chart
                            type="line"
                            :data="userGrowthData"
                            :options="lineChartOptions"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row of Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Property Types Chart -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">
                            أنواع العقارات
                        </h3>
                        <Button
                            icon="pi pi-download"
                            text
                            severity="secondary"
                            aria-label="تصدير"
                        />
                    </div>
                </div>
                <div class="p-5">
                    <div class="h-80">
                        <Chart
                            type="bar"
                            :data="propertyTypesData"
                            :options="barChartOptions"
                        />
                    </div>
                </div>
            </div>

            <!-- Property Status Distribution -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">
                            توزيع حالات العقارات
                        </h3>
                        <Button
                            icon="pi pi-download"
                            text
                            severity="secondary"
                            aria-label="تصدير"
                        />
                    </div>
                </div>
                <div class="p-5">
                    <div class="h-80">
                        <Chart
                            type="doughnut"
                            :data="propertyStatusData"
                            :options="chartOptions"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Properties and Top Investors -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Properties -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">
                            أحدث العقارات
                        </h3>
                        <Button
                            icon="pi pi-external-link"
                            text
                            label="عرض الكل"
                        />
                    </div>
                </div>
                <DataTable
                    :value="recentProperties"
                    stripedRows
                    class="border-none"
                >
                    <Column field="image" header="العقار">
                        <template #body="slotProps">
                            <div class="flex items-center gap-2 p-2">
                                <Avatar
                                    :image="slotProps.data.image"
                                    size="normal"
                                    shape="circle"
                                />
                                <div>
                                    <div class="font-medium">
                                        {{ slotProps.data.title }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ slotProps.data.location }}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </Column>
                    <Column field="price" header="السعر">
                        <template #body="slotProps">
                            <div class="text-sm font-medium text-teal-800">
                                {{ slotProps.data.price }}
                            </div>
                        </template>
                    </Column>
                    <Column field="status" header="الحالة">
                        <template #body="slotProps">
                            <Tag
                                :value="slotProps.data.status"
                                :severity="
                                    getPropertyStatusSeverity(
                                        slotProps.data.status
                                    )
                                "
                                class="text-xs"
                            />
                        </template>
                    </Column>
                    <Column field="views" header="المشاهدات">
                        <template #body="slotProps">
                            <div
                                class="flex items-center text-sm text-gray-600"
                            >
                                <i class="pi pi-eye mr-1"></i>
                                {{ slotProps.data.views }}
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">
                            آخر النشاطات
                        </h3>
                        <Button
                            icon="pi pi-external-link"
                            text
                            label="عرض الكل"
                        />
                    </div>
                </div>
                <div class="p-2">
                    <div class="relative">
                        <div
                            class="absolute top-0 bottom-0 right-5 w-0.5 bg-gray-100"
                        ></div>
                        <div
                            v-for="(activity, index) in recentActivities"
                            :key="index"
                            class="relative pr-8 py-3 hover:bg-gray-50 rounded-lg transition-colors"
                        >
                            <div
                                class="absolute right-2 top-4 w-6 h-6 rounded-full flex items-center justify-center z-10"
                                :class="getActivityIconClass(activity.type)"
                            >
                                <i
                                    :class="activity.icon"
                                    style="font-size: 0.8rem"
                                ></i>
                            </div>
                            <div class="flex flex-col">
                                <div class="font-medium">
                                    {{ activity.title }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ activity.description }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ activity.time }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Investors -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">
                        أفضل المستثمرين
                    </h3>
                    <Button icon="pi pi-external-link" text label="عرض الكل" />
                </div>
            </div>
            <div class="p-5">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                >
                    <div
                        v-for="(investor, index) in topInvestors"
                        :key="index"
                        class="bg-gray-50 rounded-xl p-4 transition-all hover:shadow-md"
                    >
                        <div class="flex items-center gap-3 mb-3">
                            <Avatar
                                :image="investor.image"
                                size="large"
                                shape="circle"
                            />
                            <div>
                                <div class="font-medium text-lg">
                                    {{ investor.name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ investor.investments }} استثمار
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <div class="text-sm text-gray-600">
                                إجمالي الاستثمار
                            </div>
                            <div class="font-bold text-teal-800">
                                {{ investor.amount }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <div class="text-sm text-gray-600">العائد</div>
                            <div
                                class="flex items-center text-emerald-600 font-medium"
                            >
                                <i class="pi pi-arrow-up mr-1"></i>
                                {{ investor.roi }}%
                            </div>
                        </div>
                        <Button
                            label="عرض التفاصيل"
                            icon="pi pi-arrow-left"
                            iconPos="right"
                            class="w-full mt-4"
                            severity="secondary"
                            text
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- City Distribution Chart -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">
                        التوزيع حسب المدن
                    </h3>
                    <Button
                        icon="pi pi-download"
                        text
                        severity="secondary"
                        aria-label="تصدير"
                    />
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="h-80">
                        <Chart
                            type="pie"
                            :data="cityDistributionData"
                            :options="chartOptions"
                        />
                    </div>
                    <div class="flex flex-col justify-center">
                        <div
                            v-for="(city, index) in cityDistributionData.labels"
                            :key="index"
                            class="mb-4"
                        >
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium">{{
                                    city
                                }}</span>
                                <span class="text-sm font-medium"
                                    >{{
                                        cityDistributionData.datasets[0].data[
                                            index
                                        ]
                                    }}%</span
                                >
                            </div>
                            <ProgressBar
                                :value="
                                    cityDistributionData.datasets[0].data[index]
                                "
                                class="h-2 rounded-full"
                                :pt="{
                                    root: {
                                        style: 'background-color: rgba(13, 148, 136, 0.1)',
                                    },
                                    value: {
                                        style: `background-color: ${cityDistributionData.datasets[0].backgroundColor[index]}`,
                                    },
                                }"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Additional custom styles */
.p-datatable .p-datatable-tbody > tr {
    cursor: pointer;
}

.p-datatable .p-datatable-tbody > tr:hover {
    background-color: #f8fafc;
}

/* Ensure charts are responsive */
:deep(.p-chart) {
    width: 100%;
    height: 100%;
}

/* Custom progress bar styling */
:deep(.p-progressbar) {
    background: rgba(13, 148, 136, 0.1);
}

:deep(.p-progressbar-value) {
    background: #0d9488;
}

/* Custom badge styling */
/* :deep(.p-badge) {
    backgroun  {
    background: #0d9488;
} */

/* Custom badge styling */
:deep(.p-badge) {
    background: #0d9488;
    color: white;
    font-weight: 600;
    border-radius: 20px;
    padding: 0.25rem 0.5rem;
}

/* Timeline styling */
.timeline-item {
    position: relative;
    padding-right: 2rem;
}

.timeline-item::before {
    content: "";
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e5e7eb;
}

.timeline-item::after {
    content: "";
    position: absolute;
    right: -4px;
    top: 12px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #0d9488;
}
</style>
