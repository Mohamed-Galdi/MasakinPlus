<script setup>
import InvestorLayout from "@/Layouts/InvestorLayout.vue";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import Galleria from "primevue/galleria";
import PropertyStatus from "@/Components/PropertyStatus.vue";
import { useTextHelpers } from "@/plugins/textHelpers";
import Header from "@/Components/Header.vue";
import Card from "primevue/card";

defineOptions({
    layout: InvestorLayout,
});

const props = defineProps({
    investments: {
        type: Array,
        required: true,
    },
    summary: {
        type: Object,
        required: true,
    },
});

const investmentsList = ref(props.investments);

// Transform property image to Galleria format
const getGalleriaImages = (imagePath) => {
    if (!imagePath) {
        return [
            {
                itemImageSrc: "/placeholder.svg?height=200&width=400",
                thumbnailImageSrc: "/placeholder.svg?height=50&width=100",
                alt: "Property Image Placeholder",
            },
        ];
    }

    return [
        {
            itemImageSrc: `/${imagePath}`,
            thumbnailImageSrc: `/${imagePath}`,
            alt: "Property Image",
        },
    ];
};

const textHelpers = useTextHelpers();
</script>

<template>
    <div class="container mx-auto">
        <!-- Header -->
        <Header
            icon="fa-solid fa-chart-line"
            title="استثماراتي"
            subtitle="تتبع استثماراتك العقارية والعوائد المتوقعة"
        />

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Invested -->
            <Card
                class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white"
            >
                <template #content>
                    <div class="text-center">
                        <i
                            class="fa-solid fa-coins text-3xl mb-2 opacity-80"
                        ></i>
                        <h3 class="text-sm font-medium opacity-90">
                            إجمالي المبلغ المستثمر
                        </h3>
                        <p class="text-2xl font-bold mt-1">
                            {{ $formatCurrency(summary.total_invested) }}
                        </p>
                    </div>
                </template>
            </Card>

            <!-- Expected Monthly Revenue -->
            <Card class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <template #content>
                    <div class="text-center">
                        <i
                            class="fa-solid fa-calendar-check text-3xl mb-2 opacity-80"
                        ></i>
                        <h3 class="text-sm font-medium opacity-90">
                            العائد الشهري المتوقع
                        </h3>
                        <p class="text-2xl font-bold mt-1">
                            {{
                                $formatCurrency(
                                    summary.total_expected_monthly_revenue
                                )
                            }}
                        </p>
                    </div>
                </template>
            </Card>

            <!-- Total Properties -->
            <Card
                class="bg-gradient-to-r from-purple-500 to-purple-600 text-white"
            >
                <template #content>
                    <div class="text-center">
                        <i
                            class="fa-solid fa-building text-3xl mb-2 opacity-80"
                        ></i>
                        <h3 class="text-sm font-medium opacity-90">
                            عدد العقارات
                        </h3>
                        <p class="text-2xl font-bold mt-1">
                            {{ summary.total_properties }}
                        </p>
                    </div>
                </template>
            </Card>

            <!-- Average Return -->
            <Card
                class="bg-gradient-to-r from-orange-500 to-orange-600 text-white"
            >
                <template #content>
                    <div class="text-center">
                        <i
                            class="fa-solid fa-percentage text-3xl mb-2 opacity-80"
                        ></i>
                        <h3 class="text-sm font-medium opacity-90">
                            متوسط العائد الشهري
                        </h3>
                        <p class="text-2xl font-bold mt-1">
                            {{
                                summary.average_monthly_return_percentage.toFixed(
                                    2
                                )
                            }}%
                        </p>
                    </div>
                </template>
            </Card>
        </div>

        <!-- Investments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="investment in investmentsList"
                :key="investment.property_id"
                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
            >
                <!-- Property Image -->
                <div class="relative">
                    <Galleria
                        :value="getGalleriaImages(investment.property.image)"
                        :circular="true"
                        :showItemNavigators="false"
                        :showThumbnails="false"
                        :showIndicators="false"
                    >
                        <template #item="slotProps">
                            <img
                                :src="slotProps.item.itemImageSrc"
                                :alt="slotProps.item.alt"
                                style="
                                    width: 100%;
                                    height: 200px;
                                    object-fit: cover;
                                    display: block;
                                "
                            />
                        </template>
                    </Galleria>

                    <!-- Investment Badge -->
                    <div class="absolute top-3 right-3 z-10">
                        <div
                            class="bg-teal-700 text-white text-xs px-2 py-1 rounded-full font-BeinNormal "
                        >
                            {{ investment.investment_percentage.toFixed(1) }}%
                            من العقار
                        </div>
                    </div>

                    <!-- Investment Count Badge -->
                    <div
                        class="absolute top-3 left-3 z-10"
                        v-if="investment.investment_count > 1"
                    >
                        <div
                            class="bg-slate-800 text-white text-sm px-2 py-1 rounded-full border  "
                        >
                            {{ investment.investment_count }} 
                        </div>
                    </div>
                </div>

                <!-- Investment Content -->
                <div class="p-4">
                    <!-- Property Title and Type -->
                    <div class="flex justify-between mb-2">
                        <h2 class="text-lg font-bold text-teal-800">
                            {{
                                textHelpers.limitText(
                                    investment.property.title,
                                    25
                                )
                            }}
                        </h2>
                        <p
                            class="bg-slate-800 text-white text-xs px-2 py-1 rounded-full font-sans h-fit"
                        >
                            {{ investment.property.type }}
                        </p>
                    </div>

                    <!-- Location -->
                    <div class="flex items-center text-gray-600 mb-3">
                        <i class="pi pi-map-marker ml-1 text-gray-500"></i>
                        <span>{{ investment.property.city }}</span>
                    </div>

                    <!-- Investment Metrics -->
                    <div class="space-y-3 mb-4">
                        <!-- Total Amount Invested -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">
                                إجمالي المبلغ المستثمر
                            </span>
                            <span class="font-bold text-emerald-600">
                                {{
                                    $formatCurrency(
                                        investment.total_amount_invested
                                    )
                                }}
                            </span>
                        </div>

                        <!-- Expected Monthly Revenue -->
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">
                                العائد الشهري المتوقع
                            </span>
                            <span class="font-bold text-blue-600">
                                {{
                                    $formatCurrency(
                                        investment.expected_monthly_revenue
                                    )
                                }}
                                
                            </span>
                        </div>

                        <!-- Property Funding Progress -->
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">نسبة التمويل</span>
                                <span class="font-medium">
                                    {{
                                        investment.property.percentage_funded.toFixed(
                                            1
                                        )
                                    }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div
                                    class="bg-emerald-600 h-2 rounded-full transition-all duration-300"
                                    :style="{
                                        width:
                                            investment.property
                                                .percentage_funded + '%',
                                    }"
                                ></div>
                            </div>
                        </div>
                    </div>

                    <!-- First Investment Date -->
                    <div class="text-xs text-gray-500 mb-4">
                        <i class="pi pi-calendar ml-1"></i>
                        أول استثمار:
                        {{
                            new Date(
                                investment.first_invested_at
                            ).toLocaleDateString("ar-SA")
                        }}
                    </div>

                    <!-- Actions -->
                    <div
                        class="flex justify-between items-center border-t pt-3"
                    >
                        <div class="text-sm">
                            <span class="text-gray-500">المطلوب:</span>
                            <span class="font-bold text-gray-700">
                                {{
                                    $formatCurrency(
                                        investment.property.investment_required
                                    )
                                }}
                                
                            </span>
                        </div>

                        <!-- View Details Button -->
                        <Link
                            :href="
                                route(
                                    'investor.investments.show',
                                    investment.property_id
                                )
                            "
                            class="flex items-center justify-center gap-2 py-2 px-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-md transition-all shadow-sm hover:shadow"
                        >
                            <i class="pi pi-eye text-gray-600"></i>
                            <span class="text-sm font-medium">التفاصيل</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="investmentsList.length === 0"
            class="bg-white rounded-lg shadow-md p-8 text-center"
        >
            <i class="fa-solid fa-chart-line text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-medium text-gray-800 mb-2">
                لا توجد استثمارات
            </h3>
            <p class="text-gray-600 mb-6">لم تقم بأي استثمارات عقارية بعد</p>
            <Link
                :href="route('investor.offers.index')"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors"
            >
                تصفح العقارات المتاحة
            </Link>
        </div>
    </div>
</template>
