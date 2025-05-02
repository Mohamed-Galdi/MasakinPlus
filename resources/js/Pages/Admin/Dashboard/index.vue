<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, onMounted, computed } from "vue";
import Card from "primevue/card";
import Chart from "primevue/chart";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Tag from "primevue/tag";
import Avatar from "primevue/avatar";
import ProgressBar from "primevue/progressbar";
import Divider from "primevue/divider";
import Badge from "primevue/badge";

defineOptions({
    layout: AdminLayout,
});

// Dashboard data (dummy data)
const stats = ref([
    {
        title: "إجمالي العقارات",
        value: 368,
        icon: "pi pi-home",
        change: 12.5,
        color: "teal",
    },
    {
        title: "المستثمرين النشطين",
        value: 124,
        icon: "pi pi-chart-line",
        change: 8.3,
        color: "emerald",
    },
    {
        title: "العقارات المؤجرة",
        value: 293,
        icon: "pi pi-check-circle",
        change: 15.2,
        color: "cyan",
    },
    {
        title: "إجمالي الإيرادات",
        value: "٥٫٣ مليون ريال",
        icon: "pi pi-money-bill",
        change: 9.7,
        color: "sky",
    },
]);

// Revenue chart data
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
    ],
    datasets: [
        {
            label: "الإيرادات الشهرية",
            data: [
                450000, 520000, 480000, 560000, 610000, 590000, 680000, 710000,
            ],
            backgroundColor: "rgba(5, 150, 105, 0.2)",
            borderColor: "rgba(5, 150, 105, 1)",
            borderWidth: 2,
            tension: 0.4,
            fill: true,
        },
    ],
});

// Revenue chart options
const revenueOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            mode: "index",
            intersect: false,
            callbacks: {
                label: function (context) {
                    return ` ${context.raw.toLocaleString()} ريال`;
                },
            },
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                display: false,
            },
            ticks: {
                callback: function (value) {
                    if (value >= 1000000) {
                        return (value / 1000000).toFixed(1) + "M ريال";
                    } else if (value >= 1000) {
                        return (value / 1000).toFixed(0) + "K ريال";
                    }
                    return value + " ريال";
                },
            },
        },
        x: {
            grid: {
                display: false,
            },
        },
    },
});

// Properties by type chart data
const propertyTypeData = ref({
    labels: ["شقق", "فلل", "مكاتب تجارية", "فنادق", "أراضي"],
    datasets: [
        {
            data: [45, 25, 15, 10, 5],
            backgroundColor: [
                "rgba(5, 150, 105, 0.9)", // Dark teal (main)
                "rgba(20, 184, 166, 0.9)", // Teal
                "rgba(6, 182, 212, 0.9)", // Cyan
                "rgba(14, 165, 233, 0.9)", // Sky
                "rgba(56, 189, 248, 0.9)", // Light blue
            ],
            hoverBackgroundColor: [
                "rgba(5, 150, 105, 1)",
                "rgba(20, 184, 166, 1)",
                "rgba(6, 182, 212, 1)",
                "rgba(14, 165, 233, 1)",
                "rgba(56, 189, 248, 1)",
            ],
        },
    ],
});

// Property type chart options
const propertyTypeOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                usePointStyle: true,
                padding: 15,
                font: {
                    size: 12,
                },
            },
        },
        tooltip: {
            callbacks: {
                label: function (context) {
                    return ` ${context.label}: ${context.raw}%`;
                },
            },
        },
    },
    cutout: "70%",
});

// Recent properties data
const recentProperties = ref([
    {
        id: 1,
        title: "فيلا فاخرة بحي النرجس",
        type: "فيلا",
        price: "٢٫٥ مليون ريال",
        status: "متاحة",
        owner: {
            name: "أحمد عبدالله",
            image: "https://i.pravatar.cc/150?img=1",
        },
        progress: 100,
    },
    {
        id: 2,
        title: "شقة عائلية بحي الياسمين",
        type: "شقة",
        price: "٨٠٠ ألف ريال",
        status: "مستأجرة",
        owner: { name: "محمد خالد", image: "https://i.pravatar.cc/150?img=2" },
        progress: 90,
    },
    {
        id: 3,
        title: "مكتب تجاري بطريق الملك فهد",
        type: "مكتب",
        price: "١٫٢ مليون ريال",
        status: "متاحة",
        owner: { name: "سارة أحمد", image: "https://i.pravatar.cc/150?img=3" },
        progress: 100,
    },
    {
        id: 4,
        title: "شقة استديو بوسط الرياض",
        type: "شقة",
        price: "٤٠٠ ألف ريال",
        status: "مستأجرة",
        owner: { name: "عمر يوسف", image: "https://i.pravatar.cc/150?img=4" },
        progress: 75,
    },
    {
        id: 5,
        title: "فيلا دوبلكس بحي الملقا",
        type: "فيلا",
        price: "٣٫١ مليون ريال",
        status: "متاحة",
        owner: { name: "فاطمة علي", image: "https://i.pravatar.cc/150?img=5" },
        progress: 100,
    },
]);

// Top performers data
const topPerformers = ref([
    {
        id: 1,
        name: "أحمد محمد",
        role: "owner",
        revenue: "٨٠٠ ألف ريال",
        properties: 12,
        image: "https://i.pravatar.cc/150?img=6",
    },
    {
        id: 2,
        name: "نورة سعد",
        role: "investor",
        revenue: "٦٥٠ ألف ريال",
        properties: 8,
        image: "https://i.pravatar.cc/150?img=7",
    },
    {
        id: 3,
        name: "خالد عبدالعزيز",
        role: "owner",
        revenue: "٥٥٠ ألف ريال",
        properties: 10,
        image: "https://i.pravatar.cc/150?img=8",
    },
    {
        id: 4,
        name: "سلمان محمد",
        role: "investor",
        revenue: "٤٥٠ ألف ريال",
        properties: 6,
        image: "https://i.pravatar.cc/150?img=9",
    },
]);

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

// Function to get tag severity based on status
const getStatusSeverity = (status) => {
    switch (status) {
        case "متاحة":
            return "success";
        case "مستأجرة":
            return "info";
        case "قيد التجهيز":
            return "warning";
        default:
            return "secondary";
    }
};

// Function to get tag severity based on role
const getRoleSeverity = (role) => {
    switch (role) {
        case "owner":
            return "success";
        case "investor":
            return "warning";
        default:
            return "secondary";
    }
};

// Function to get progress bar color based on value
const getProgressColor = (value) => {
    if (value < 50) return "bg-amber-500";
    if (value < 75) return "bg-blue-500";
    return "bg-teal-600";
};

// Active users data
const activeUsersData = ref({
    labels: [
        "يناير",
        "فبراير",
        "مارس",
        "أبريل",
        "مايو",
        "يونيو",
        "يوليو",
        "أغسطس",
    ],
    datasets: [
        {
            label: "مستأجرين",
            data: [65, 80, 95, 110, 125, 145, 160, 175],
            backgroundColor: "rgba(14, 165, 233, 0.8)",
            borderColor: "rgba(14, 165, 233, 1)",
            borderWidth: 1,
        },
        {
            label: "مستثمرين",
            data: [25, 35, 40, 50, 65, 75, 85, 95],
            backgroundColor: "rgba(245, 158, 11, 0.8)",
            borderColor: "rgba(245, 158, 11, 1)",
            borderWidth: 1,
        },
        {
            label: "ملاك",
            data: [40, 45, 50, 55, 65, 70, 80, 90],
            backgroundColor: "rgba(5, 150, 105, 0.8)",
            borderColor: "rgba(5, 150, 105, 1)",
            borderWidth: 1,
        },
    ],
});

const activeUsersOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "top",
            labels: {
                usePointStyle: true,
                font: {
                    size: 12,
                },
            },
        },
    },
    scales: {
        x: {
            stacked: true,
            grid: {
                display: false,
            },
        },
        y: {
            stacked: true,
            grid: {
                display: false,
            },
        },
    },
});

// Market share data
const marketShareData = ref({
    labels: ["الرياض", "جدة", "الدمام", "مكة", "المدينة"],
    datasets: [
        {
            data: [40, 25, 15, 12, 8],
            backgroundColor: [
                "rgba(5, 150, 105, 0.9)",
                "rgba(14, 165, 233, 0.9)",
                "rgba(245, 158, 11, 0.9)",
                "rgba(236, 72, 153, 0.9)",
                "rgba(139, 92, 246, 0.9)",
            ],
            hoverBackgroundColor: [
                "rgba(5, 150, 105, 1)",
                "rgba(14, 165, 233, 1)",
                "rgba(245, 158, 11, 1)",
                "rgba(236, 72, 153, 1)",
                "rgba(139, 92, 246, 1)",
            ],
        },
    ],
});

const marketShareOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "right",
            labels: {
                usePointStyle: true,
                font: {
                    size: 12,
                },
            },
        },
    },
});

// Notifications
const notifications = ref([
    {
        id: 1,
        title: "عقار جديد",
        message: "تم إضافة عقار جديد بواسطة أحمد محمد",
        time: "منذ ٣٠ دقيقة",
        icon: "pi pi-home",
        color: "teal",
    },
    {
        id: 2,
        title: "مستثمر جديد",
        message: "انضم مستثمر جديد الى المنصة",
        time: "منذ ٤٥ دقيقة",
        icon: "pi pi-user-plus",
        color: "amber",
    },
    {
        id: 3,
        title: "عقد تأجير",
        message: "تم توقيع عقد تأجير جديد",
        time: "منذ ساعتين",
        icon: "pi pi-file-edit",
        color: "sky",
    },
]);

// Filter active button
const activePeriod = ref("yearly");
</script>

<template>
    <div class="pb-8">
        <!-- Header Section -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-gradient-to-l from-teal-800 to-teal-700 text-white rounded-xl shadow-md p-6"
        >
            <div class="flex items-center gap-3">
                <div class="p-3 bg-white bg-opacity-10 rounded-full">
                    <i
                        class="pi pi-chart-bar text-white"
                        style="font-size: 2rem"
                    ></i>
                </div>
                <div>
                    <h1 class="text-3xl font-semibold m-0">لوحة التحكم</h1>
                    <p class="text-teal-100 text-sm m-0 mt-1">
                        مرحباً بك، <span class="font-medium">أحمد!</span> هذا هو
                        ملخص نشاط منصة مساكن بلس
                    </p>
                </div>
            </div>
            <div class="flex gap-3 mt-4 md:mt-0">
                <Button
                    label="تقرير الأداء"
                    icon="pi pi-file-pdf"
                    severity="secondary"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 border-none text-white"
                />
                <Button
                    label="تحديث البيانات"
                    icon="pi pi-refresh"
                    class="bg-white text-teal-800 hover:bg-teal-50"
                />
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div
                v-for="(stat, index) in stats"
                :key="index"
                class="bg-white rounded-xl shadow-sm p-5 transition-all duration-300 hover:shadow-md border-b-4"
                :class="`border-${stat.color}-600`"
            >
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm m-0 mb-2">
                            {{ stat.title }}
                        </p>
                        <h2 class="text-2xl font-bold m-0 text-gray-800">
                            {{ stat.value }}
                        </h2>
                    </div>
                    <div :class="`bg-${stat.color}-50 p-3 rounded-full`">
                        <i
                            :class="`${stat.icon} text-${stat.color}-600`"
                            style="font-size: 1.5rem"
                        ></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <i
                        :class="`pi ${
                            stat.change >= 0
                                ? 'pi-arrow-up text-emerald-600'
                                : 'pi-arrow-down text-rose-600'
                        } text-xs ml-1`"
                    ></i>
                    <span
                        :class="`text-xs font-medium ${
                            stat.change >= 0
                                ? 'text-emerald-600'
                                : 'text-rose-600'
                        }`"
                    >
                        {{ Math.abs(stat.change) }}%
                    </span>
                    <span class="text-xs text-gray-500 mr-1"
                        >منذ الشهر الماضي</span
                    >
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Revenue Chart -->
            <div
                class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5 border border-gray-100"
            >
                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h3 class="text-xl font-bold m-0 text-gray-800">
                            الإيرادات الشهرية
                        </h3>
                        <p class="text-gray-500 text-sm m-0">
                            تحليل إيرادات المنصة خلال العام
                        </p>
                    </div>
                    <div class="flex gap-1 bg-gray-100 rounded-lg p-1">
                        <Button
                            label="سنوي"
                            :outlined="activePeriod !== 'yearly'"
                            :text="activePeriod === 'yearly'"
                            class="text-xs py-1 px-3"
                            :class="
                                activePeriod === 'yearly'
                                    ? 'bg-white shadow-sm'
                                    : ''
                            "
                            @click="activePeriod = 'yearly'"
                            severity="secondary"
                        />
                        <Button
                            label="شهري"
                            :outlined="activePeriod !== 'monthly'"
                            :text="activePeriod === 'monthly'"
                            class="text-xs py-1 px-3"
                            :class="
                                activePeriod === 'monthly'
                                    ? 'bg-white shadow-sm'
                                    : ''
                            "
                            @click="activePeriod = 'monthly'"
                            severity="secondary"
                        />
                        <Button
                            label="أسبوعي"
                            :outlined="activePeriod !== 'weekly'"
                            :text="activePeriod === 'weekly'"
                            class="text-xs py-1 px-3"
                            :class="
                                activePeriod === 'weekly'
                                    ? 'bg-white shadow-sm'
                                    : ''
                            "
                            @click="activePeriod = 'weekly'"
                            severity="secondary"
                        />
                    </div>
                </div>

                <!-- Stats row above chart -->
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="p-3 bg-teal-50 rounded-lg">
                        <p class="text-xs text-gray-500 m-0">
                            إجمالي الإيرادات
                        </p>
                        <p class="text-lg font-bold text-teal-800 m-0">
                            ٥٫٣ مليون ريال
                        </p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs text-gray-500 m-0">متوسط شهري</p>
                        <p class="text-lg font-bold text-blue-700 m-0">
                            ٧٥٠ ألف ريال
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg">
                        <p class="text-xs text-gray-500 m-0">النمو السنوي</p>
                        <p class="text-lg font-bold text-emerald-700 m-0">
                            +٢٣٪
                        </p>
                    </div>
                </div>

                <div style="height: 350px">
                    <Chart
                        type="line"
                        :data="revenueData"
                        :options="revenueOptions"
                    />
                </div>
            </div>

            <!-- Property Type Chart -->
            <div
                class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 flex flex-col"
            >
                <div class="mb-3">
                    <h3 class="text-xl font-bold m-0 text-gray-800">
                        أنواع العقارات
                    </h3>
                    <p class="text-gray-500 text-sm m-0">
                        توزيع العقارات حسب النوع
                    </p>
                </div>

                <div
                    class="flex-1 flex flex-col justify-center"
                    style="min-height: 350px"
                >
                    <Chart
                        type="doughnut"
                        :data="propertyTypeData"
                        :options="propertyTypeOptions"
                    />
                </div>

                <!-- Center text in doughnut -->
                <div
                    class="relative mt-[-220px] mb-[100px] mx-auto text-center pointer-events-none"
                >
                    <p class="text-gray-500 text-sm m-0">إجمالي العقارات</p>
                    <p class="text-3xl font-bold text-teal-800">٣٦٨</p>
                </div>

                <!-- Chart legends as custom items -->
                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                        <span
                            class="w-3 h-3 rounded-full bg-teal-600 ml-2"
                        ></span>
                        <span class="text-xs">شقق: ٤٥٪</span>
                    </div>
                    <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                        <span
                            class="w-3 h-3 rounded-full bg-sky-500 ml-2"
                        ></span>
                        <span class="text-xs">فلل: ٢٥٪</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Recent Properties -->
            <div
                class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5 border border-gray-100"
            >
                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h3 class="text-xl font-bold m-0 text-gray-800">
                            أحدث العقارات
                        </h3>
                        <p class="text-gray-500 text-sm m-0">
                            آخر العقارات المضافة للمنصة
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <span
                            class="p-1 px-3 bg-teal-50 text-teal-700 text-xs rounded-full border border-teal-100"
                            >٢٥ عقار جديد</span
                        >
                        <Button
                            label="عرض الكل"
                            icon="pi pi-external-link"
                            text
                        />
                    </div>
                </div>

                <DataTable
                    :value="recentProperties"
                    class="border-none"
                    :rowHover="true"
                    :rowClass="() => 'cursor-pointer hover:bg-gray-50'"
                    :pt="{
                        root: { class: 'border-none' },
                        header: {
                            class: 'bg-gray-50 border-0 rounded-lg mb-2',
                        },
                        headerCell: {
                            class: 'text-sm text-gray-600 font-medium p-3',
                        },
                    }"
                >
                    <Column>
                        <template #body="slotProps">
                            <div class="flex items-center gap-3">
                                <Avatar
                                    :image="slotProps.data.owner.image"
                                    size="normal"
                                    shape="circle"
                                />
                                <div>
                                    <p class="m-0 font-medium">
                                        {{ slotProps.data.title }}
                                    </p>
                                    <p class="m-0 text-xs text-gray-500">
                                        {{ slotProps.data.owner.name }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </Column>

                    <Column field="type" header="النوع" class="w-20">
                        <template #body="slotProps">
                            <p class="m-0 text-sm">{{ slotProps.data.type }}</p>
                        </template>
                    </Column>

                    <Column field="price" header="السعر" class="w-32">
                        <template #body="slotProps">
                            <p class="m-0 text-sm font-medium">
                                {{ slotProps.data.price }}
                            </p>
                        </template>
                    </Column>

                    <Column field="status" header="الحالة" class="w-28">
                        <template #body="slotProps">
                            <Tag
                                :value="slotProps.data.status"
                                :severity="
                                    getStatusSeverity(slotProps.data.status)
                                "
                                class="text-xs"
                            />
                        </template>
                    </Column>

                    <Column field="progress" header="الاكتمال" class="w-32">
                        <template #body="slotProps">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs text-gray-500"
                                        >{{ slotProps.data.progress }}%</span
                                    >
                                </div>
                                <ProgressBar
                                    :value="slotProps.data.progress"
                                    class="h-2"
                                    :class="
                                        getProgressColor(
                                            slotProps.data.progress
                                        )
                                    "
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>

            <!-- Right Section with Top Performers and Notifications -->
            <div class="flex flex-col gap-6">
                <!-- Top Performers -->
                <div
                    class="bg-white rounded-xl shadow-sm p-5 border border-gray-100"
                >
                    <div class="flex justify-between items-center mb-5">
                        <div>
                            <h3 class="text-xl font-bold m-0 text-gray-800">
                                أفضل المستخدمين
                            </h3>
                            <p class="text-gray-500 text-sm m-0">
                                أعلى إيرادات هذا الشهر
                            </p>
                        </div>
                        <Badge value="4" severity="success"></Badge>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="(user, index) in topPerformers"
                            :key="user.id"
                            class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer border-r-4 border-teal-600"
                            :class="{
                                'border-amber-500': user.role === 'investor',
                            }"
                        >
                            <div class="relative">
                                <Avatar
                                    :image="user.image"
                                    size="large"
                                    shape="circle"
                                    class="ml-3"
                                />
                                <span
                                    class="absolute -bottom-1 -right-1 w-5 h-5 flex items-center justify-center bg-teal-700 text-white rounded-full text-xs font-bold"
                                    >{{ index + 1 }}</span
                                >
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="m-0 font-medium">
                                            {{ user.name }}
                                        </p>
                                        <div
                                            class="flex items-center gap-2 mt-1"
                                        >
                                            <Tag
                                                :value="
                                                    getUserTypeArabic(user.role)
                                                "
                                                :severity="
                                                    getRoleSeverity(user.role)
                                                "
                                                class="text-xs"
                                            />
                                            <p
                                                class="m-0 text-xs text-gray-500"
                                            >
                                                {{ user.properties }} عقار
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-teal-700 font-bold text-sm">
                                        {{ user.revenue }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div
                    class="bg-white rounded-xl shadow-sm p-5 border border-gray-100"
                >
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-xl font-bold m-0 text-gray-800">
                                آخر التحديثات
                            </h3>
                            <p class="text-gray-500 text-sm m-0">
                                آخر نشاطات المنصة
                            </p>
                        </div>
                        <Badge value="3" severity="danger"></Badge>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="notification in notifications"
                            :key="notification.id"
                            class="flex p-3 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer"
                        >
                            <div
                                :class="`flex-shrink-0 w-10 h-10 bg-${notification.color}-100 text-${notification.color}-600 rounded-full flex items-center justify-center ml-3`"
                            >
                                <i :class="`${notification.icon}`"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <p class="m-0 font-medium text-sm">
                                        {{ notification.title }}
                                    </p>
                                    <p class="m-0 text-xs text-gray-500">
                                        {{ notification.time }}
                                    </p>
                                </div>
                                <p class="m-0 text-xs text-gray-500 mt-1">
                                    {{ notification.message }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <Divider class="my-3" />
                    <div class="text-center">
                        <Button label="عرض كل التحديثات" text class="text-xs" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Third Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Active Users Chart -->
            <div
                class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5 border border-gray-100"
            >
                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h3 class="text-xl font-bold m-0 text-gray-800">
                            المستخدمين النشطين
                        </h3>
                        <p class="text-gray-500 text-sm m-0">
                            نمو المستخدمين حسب النوع خلال العام
                        </p>
                    </div>
                    <div class="flex gap-2 items-center">
                        <span class="flex items-center text-xs">
                            <span
                                class="inline-block w-3 h-3 rounded-full bg-teal-600 ml-1"
                            ></span>
                            ملاك
                        </span>
                        <span class="flex items-center text-xs">
                            <span
                                class="inline-block w-3 h-3 rounded-full bg-amber-500 ml-1"
                            ></span>
                            مستثمرين
                        </span>
                        <span class="flex items-center text-xs">
                            <span
                                class="inline-block w-3 h-3 rounded-full bg-sky-500 ml-1"
                            ></span>
                            مستأجرين
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-5">
                    <div class="rounded-lg bg-gray-50 p-3 text-center">
                        <p class="text-gray-500 text-xs m-0">
                            إجمالي المستخدمين
                        </p>
                        <p class="text-xl font-bold text-gray-800 m-0">٣٦٠</p>
                        <p class="text-xs text-emerald-600 m-0">
                            +١٥٪ <i class="pi pi-arrow-up"></i>
                        </p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3 text-center">
                        <p class="text-gray-500 text-xs m-0">
                            معدل النمو الشهري
                        </p>
                        <p class="text-xl font-bold text-gray-800 m-0">١٢٪</p>
                        <p class="text-xs text-emerald-600 m-0">
                            +٣٪ <i class="pi pi-arrow-up"></i>
                        </p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3 text-center">
                        <p class="text-gray-500 text-xs m-0">معدل النشاط</p>
                        <p class="text-xl font-bold text-gray-800 m-0">٧٨٪</p>
                        <p class="text-xs text-emerald-600 m-0">
                            +٥٪ <i class="pi pi-arrow-up"></i>
                        </p>
                    </div>
                </div>

                <div style="height: 350px">
                    <Chart
                        type="bar"
                        :data="activeUsersData"
                        :options="activeUsersOptions"
                    />
                </div>
            </div>

            <!-- Market Share -->
            <div
                class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 flex flex-col"
            >
                <div class="mb-3">
                    <h3 class="text-xl font-bold m-0 text-gray-800">
                        التوزيع الجغرافي
                    </h3>
                    <p class="text-gray-500 text-sm m-0">
                        توزيع العقارات حسب المناطق
                    </p>
                </div>

                <div
                    class="flex-1 flex flex-col justify-center"
                    style="height: 350px"
                >
                    <Chart
                        type="pie"
                        :data="marketShareData"
                        :options="marketShareOptions"
                    />
                </div>

                <div class="mt-4 grid grid-cols-2 gap-2">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 m-0">أعلى منطقة</p>
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-bold m-0">الرياض</p>
                            <Tag
                                value="٤٠٪"
                                severity="success"
                                class="text-xs"
                            />
                        </div>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 m-0">أسرع نمو</p>
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-bold m-0">جدة</p>
                            <Tag
                                value="+٢٣٪"
                                severity="warning"
                                class="text-xs"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fourth Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Statistical Cards -->
            <div
                class="bg-white rounded-xl shadow-sm p-5 border border-gray-100"
            >
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-xl font-bold m-0 text-gray-800">
                            الإيرادات حسب النوع
                        </h3>
                        <p class="text-gray-500 text-sm m-0">
                            توزيع الإيرادات حسب نوع العقار
                        </p>
                    </div>
                </div>

                <div class="space-y-4 mt-5">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium">فلل</span>
                            <span class="text-sm text-gray-600">٤٢٪</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full">
                            <div
                                class="h-2 bg-teal-600 rounded-full"
                                style="width: 42%"
                            ></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium">شقق</span>
                            <span class="text-sm text-gray-600">٣٥٪</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full">
                            <div
                                class="h-2 bg-sky-500 rounded-full"
                                style="width: 35%"
                            ></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium"
                                >مكاتب تجارية</span
                            >
                            <span class="text-sm text-gray-600">١٥٪</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full">
                            <div
                                class="h-2 bg-amber-500 rounded-full"
                                style="width: 15%"
                            ></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium">فنادق</span>
                            <span class="text-sm text-gray-600">٨٪</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full">
                            <div
                                class="h-2 bg-purple-500 rounded-full"
                                style="width: 8%"
                            ></div>
                        </div>
                    </div>

                    <div class="pt-3 mt-3 border-t border-gray-100">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium"
                                >إجمالي الإيرادات</span
                            >
                            <span class="text-sm font-bold text-teal-700"
                                >٥٫٣ مليون ريال</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div
                class="lg:col-span-2 bg-white rounded-xl shadow-sm p-5 border border-gray-100"
            >
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-xl font-bold m-0 text-gray-800">
                            آخر العمليات
                        </h3>
                        <p class="text-gray-500 text-sm m-0">
                            أحدث الأنشطة على المنصة
                        </p>
                    </div>
                    <Button icon="pi pi-ellipsis-h" text rounded />
                </div>

                <div class="relative">
                    <!-- Timeline track -->
                    <div
                        class="absolute h-full w-0.5 bg-gray-200 left-2.5 top-0"
                    ></div>

                    <div class="space-y-6 relative">
                        <div class="flex">
                            <div
                                class="flex-shrink-0 w-5 h-5 rounded-full bg-teal-500 z-10 ml-3"
                            ></div>
                            <div class="flex-grow p-3 bg-gray-50 rounded-lg">
                                <div class="flex justify-between">
                                    <p class="text-sm font-medium m-0">
                                        تم تأجير شقة في حي الياسمين
                                    </p>
                                    <span class="text-xs text-gray-500"
                                        >منذ ١٠ دقائق</span
                                    >
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    قام العميل محمد خالد بتأجير الشقة بقيمة ٥٥
                                    ألف ريال سنوياً
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div
                                class="flex-shrink-0 w-5 h-5 rounded-full bg-amber-500 z-10 ml-3"
                            ></div>
                            <div class="flex-grow p-3 bg-gray-50 rounded-lg">
                                <div class="flex justify-between">
                                    <p class="text-sm font-medium m-0">
                                        استثمار جديد
                                    </p>
                                    <span class="text-xs text-gray-500"
                                        >منذ ساعة</span
                                    >
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    قامت نورة سعد باستثمار ٥٠٠ ألف ريال في مشروع
                                    فيلا النرجس
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div
                                class="flex-shrink-0 w-5 h-5 rounded-full bg-sky-500 z-10 ml-3"
                            ></div>
                            <div class="flex-grow p-3 bg-gray-50 rounded-lg">
                                <div class="flex justify-between">
                                    <p class="text-sm font-medium m-0">
                                        إضافة عقار جديد
                                    </p>
                                    <span class="text-xs text-gray-500"
                                        >منذ ٣ ساعات</span
                                    >
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    قام فهد محمد بإضافة مكتب تجاري جديد بمساحة
                                    ١٥٠ متر مربع
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div
                                class="flex-shrink-0 w-5 h-5 rounded-full bg-rose-500 z-10 ml-3"
                            ></div>
                            <div class="flex-grow p-3 bg-gray-50 rounded-lg">
                                <div class="flex justify-between">
                                    <p class="text-sm font-medium m-0">
                                        تسجيل مستخدم جديد
                                    </p>
                                    <span class="text-xs text-gray-500"
                                        >منذ ٥ ساعات</span
                                    >
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    قام سعد العبدالله بالتسجيل كمالك عقار
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <Button label="عرض المزيد" severity="secondary" text />
                </div>
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div
            class="fixed bottom-6 left-6 px-4 py-3 bg-white rounded-full shadow-lg z-50 flex items-center gap-2"
        >
            <Button
                icon="pi pi-plus"
                rounded
                severity="success"
                class="shadow-md"
            />
            <Button
                icon="pi pi-bell"
                rounded
                text
                badge="3"
                badgeClass="bg-rose-500"
            />
            <Button icon="pi pi-cog" rounded text />
            <Button icon="pi pi-question-circle" rounded text />
        </div>
    </div>
</template>
