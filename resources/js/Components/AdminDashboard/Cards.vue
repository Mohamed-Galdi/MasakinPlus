<script setup>
import Chart from "primevue/chart";
import { ref, onMounted } from "vue";

// Enhanced color palette with gradients for light theme
const colors = {
    teal: {
        main: "#0D9488",
        gradient: "linear-gradient(135deg, #0F766E 0%, #2DD4BF 100%)",
        chartLine: "#0F766E",
        chartFill: "rgba(15, 118, 110, 0.08)",
        light: "#CCFBF1",
    },
    purple: {
        main: "#7E22CE",
        gradient: "linear-gradient(135deg, #7E22CE 0%, #A855F7 100%)",
        chartLine: "#7E22CE",
        chartFill: "rgba(126, 34, 206, 0.08)",
        light: "#F3E8FF",
    },
    blue: {
        main: "#1D4ED8",
        gradient: "linear-gradient(135deg, #1D4ED8 0%, #3B82F6 100%)",
        chartLine: "#1D4ED8",
        chartFill: "rgba(29, 78, 216, 0.08)",
        light: "#DBEAFE",
    },
    emerald: {
        main: "#047857",
        gradient: "linear-gradient(135deg, #047857 0%, #10B981 100%)",
        chartLine: "#047857",
        chartFill: "rgba(4, 120, 87, 0.08)",
        light: "#D1FAE5",
    },
};

// dummy data with trends
const metrics = ref([
    {
        title: "إجمالي العقارات",
        value: "1,248",
        subtext: "عقار",
        change: 12.4,
        trend: "up",
        icon: "pi pi-building",
        color: colors.teal,
        chartData: {
            labels: [
                "يناير",
                "فبراير",
                "مارس",
                "أبريل",
                "مايو",
                "يونيو",
                "يوليو",
            ],
            datasets: [
                {
                    data: [950, 1005, 1102, 1080, 1175, 1210, 1248],
                    borderColor: colors.teal.chartLine,
                    backgroundColor: colors.teal.chartFill,
                    borderWidth: 2.5,
                    pointRadius: 0,
                    tension: 0.4,
                    fill: true,
                },
            ],
        },
        stats: "نمو بنسبة 31% منذ بداية العام",
    },
    {
        title: "إجمالي المستثمرين",
        value: "842",
        subtext: "مستثمر",
        change: 8.7,
        trend: "up",
        icon: "pi pi-briefcase",
        color: colors.purple,
        chartData: {
            labels: [
                "يناير",
                "فبراير",
                "مارس",
                "أبريل",
                "مايو",
                "يونيو",
                "يوليو",
            ],
            datasets: [
                {
                    data: [720, 710, 765, 780, 810, 825, 842],
                    borderColor: colors.purple.chartLine,
                    backgroundColor: colors.purple.chartFill,
                    borderWidth: 2.5,
                    pointRadius: 0,
                    tension: 0.4,
                    fill: true,
                },
            ],
        },
        stats: "8 مستثمرين جدد هذا الشهر",
    },
    {
        title: "إجمالي المستأجرين",
        value: "1,563",
        subtext: "مستأجر",
        change: -4.2,
        trend: "down",
        icon: "pi pi-users",
        color: colors.blue,
        chartData: {
            labels: [
                "يناير",
                "فبراير",
                "مارس",
                "أبريل",
                "مايو",
                "يونيو",
                "يوليو",
            ],
            datasets: [
                {
                    data: [1400, 1450, 1630, 1580, 1600, 1632, 1563],
                    borderColor: colors.blue.chartLine,
                    backgroundColor: colors.blue.chartFill,
                    borderWidth: 2.5,
                    pointRadius: 0,
                    tension: 0.4,
                    fill: true,
                },
            ],
        },
        stats: "انخفاض مؤقت بسبب انتهاء 69 عقد إيجار",
    },
    {
        title: "إجمالي الإيرادات",
        value: "٢,٥٤٨,٠٠٠",
        subtext: "ر.س",
        change: 18.5,
        trend: "up",
        icon: "pi pi-wallet",
        color: colors.emerald,
        chartData: {
            labels: [
                "يناير",
                "فبراير",
                "مارس",
                "أبريل",
                "مايو",
                "يونيو",
                "يوليو",
            ],
            datasets: [
                {
                    data: [
                        2000000, 2150000, 2200000, 2500000, 2700000, 2600000,
                        2548000,
                    ],
                    borderColor: colors.emerald.chartLine,
                    backgroundColor: colors.emerald.chartFill,
                    borderWidth: 2.5,
                    pointRadius: 0,
                    tension: 0.4,
                    fill: true,
                },
            ],
        },
        stats: "زيادة قياسية مقارنة بالعام السابق",
    },
]);

// Advanced chart options
const chartOptions = ref({
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            enabled: true,
            backgroundColor: "rgba(15, 23, 42, 0.8)",
            titleColor: "#ffffff",
            bodyColor: "#ffffff",
            cornerRadius: 8,
            padding: 12,
            displayColors: false,
            callbacks: {
                title: () => "",
                label: (context) => {
                    const value = context.dataset.data[context.dataIndex];
                    return value.toLocaleString();
                },
            },
        },
    },
    scales: {
        x: {
            display: false,
        },
        y: {
            display: false,
            min: (context) => {
                const values = context.chart.data.datasets[0].data;
                return Math.min(...values) * 0.8;
            },
        },
    },
    responsive: true,
    maintainAspectRatio: false,
    elements: {
        line: {
            tension: 0.5,
        },
        point: {
            radius: 0,
            hoverRadius: 6,
        },
    },
    interaction: {
        intersect: false,
        mode: "index",
    },
});

// Animation utilities
const animateCounters = () => {
    const counters = document.querySelectorAll(".counter-value");
    counters.forEach((counter) => {
        const value = parseFloat(
            counter.getAttribute("data-target").replace(/,/g, "")
        );
        const duration = 1500;
        const startTimestamp = performance.now();

        const updateCounter = (timestamp) => {
            const elapsed = timestamp - startTimestamp;
            const progress = Math.min(elapsed / duration, 1);
            const easeProgress =
                progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);

            const currentValue = Math.floor(easeProgress * value);
            counter.textContent = isNaN(currentValue)
                ? counter.getAttribute("data-target")
                : currentValue.toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = counter.getAttribute("data-target");
            }
        };

        requestAnimationFrame(updateCounter);
    });
};

onMounted(() => {
    animateCounters();
});
</script>

<template>
    <div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                v-for="(metric, index) in metrics"
                :key="index"
                class="relative shadow-sm overflow-hidden rounded-2xl border border-slate-300 bg-white"
            >
                <!-- Card Content -->
                <div class="relative z-10 p-6 flex flex-col h-44">
                    <div class="flex justify-between items-start mb-2">
                        <!-- Title and Metrics -->
                        <div class="mb-4 text-right">
                            <h3 class="text-slate-700 mb-1">
                                {{ metric.title }}
                            </h3>
                            <div class="flex items-baseline justify-start">
                                <span
                                    class="counter-value text-3xl font-bold text-slate-700"
                                    :data-target="metric.value"
                                    >0</span
                                >
                                <span class="text-slate-500 text-sm mr-1">{{
                                    metric.subtext
                                }}</span>
                            </div>
                        </div>
                        <!-- Icon -->
                        <div
                            class="h-14 w-14 rounded-2xl flex items-center justify-center icon-container"
                            :style="{ background: metric.color.gradient }"
                        >
                            <i
                                :class="metric.icon"
                                class="text-white"
                                style="font-size: 1.5rem"
                            ></i>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="absolute bottom-0 left-0 h-16 w-full">
                        <Chart
                            type="line"
                            :data="metric.chartData"
                            :options="chartOptions"
                            class="h-full w-full"
                        />
                    </div>
                </div>

                <!-- Decorative elements -->
                <!-- Top Bar -->
                <div
                    class="absolute top-0 left-0 w-full h-1"
                    :style="{ background: metric.color.gradient }"
                ></div>
                <!-- Circle -->
                <div
                    class="absolute -top-10 -left-10 w-32 h-32 rounded-full opacity-10"
                    :style="{ background: metric.color.gradient }"
                ></div>
            </div>
        </div>
    </div>
</template>
