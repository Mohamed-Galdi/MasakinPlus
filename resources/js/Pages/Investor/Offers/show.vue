<script setup>
import { ref, computed, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import InvestorLayout from "@/Layouts/InvestorLayout.vue";
import Galleria from "primevue/galleria";
import Slider from "primevue/slider";
import PropertyMapView from "@/Components/PropertyMapView.vue";
import Card from "primevue/card";

defineOptions({
    layout: InvestorLayout,
});

// Define property prop
const props = defineProps({
    property: {
        type: Object,
        required: true,
    },
});

const property = ref(props.property);

// Financial data
const currentFunded = ref(54000); // Current amount funded in SAR
const percentageFunded = computed(
    () => (currentFunded.value / property.value.investment_required) * 100
);
const remaining = computed(
    () => property.value.investment_required - currentFunded.value
);

// Investment input and slider
const investmentAmount = ref(10000);
const sliderValue = ref(10000);

// Sync slider and input
watch(sliderValue, (newValue) => {
    investmentAmount.value = newValue;
});

watch(investmentAmount, (newValue) => {
    // Ensure investment amount is within valid range
    if (newValue < 0) {
        investmentAmount.value = 0;
    } else if (newValue > remaining.value) {
        investmentAmount.value = remaining.value;
    }

    sliderValue.value = investmentAmount.value;
});

// Investment validation
const isInvestmentValid = computed(() => {
    return (
        investmentAmount.value >= 1000 &&
        investmentAmount.value <= remaining.value
    );
});

// Calculate expected monthly revenue (assuming 26 nights occupancy)
const expectedRevenue = computed(() => property.value.nightly_rent * 26);

// Calculate investor share
const calculateInvestorShare = (revenue, share) => {
    const percentage = (share * 100).toFixed(0);
    const investorRevenue = (revenue * share).toFixed(2);
    return { percentage, investorRevenue };
};

const { percentage, investorRevenue } = calculateInvestorShare(
    expectedRevenue.value,
    property.value.investor_share
);

// Calculate user's investment metrics
const userInvestmentPercentage = computed(
    () => investmentAmount.value / property.value.investment_required
);

const userMonthlyEarnings = computed(
    () =>
        expectedRevenue.value *
        property.value.investor_share *
        userInvestmentPercentage.value
);

const annualReturnPercentage = computed(() => {
    if (investmentAmount.value <= 0) return 0;
    return (
        ((userMonthlyEarnings.value * 12) / investmentAmount.value) *
        100
    ).toFixed(1);
});

// Calculate new funding progress after investment
const newTotalFunded = computed(
    () => currentFunded.value + investmentAmount.value
);

const newPercentageFunded = computed(
    () => (newTotalFunded.value / property.value.investment_required) * 100
);

// Additional computed properties for the  progress bar
const investmentPercentage = computed(
    () => (investmentAmount.value / property.value.investment_required) * 100
);

const remainingAfterInvestment = computed(
    () => remaining.value - investmentAmount.value
);

const remainingPercentage = computed(
    () =>
        (remainingAfterInvestment.value / property.value.investment_required) *
        100
);

// Format images for Galleria
const getGalleriaImages = (images) => {
    return images.map((image) => ({
        itemImageSrc: image.path,
        alt: `صورة العقار ${image.order}`,
    }));
};
</script>
<template>
    <div class="max-w-7xl mx-auto">
        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Investment, Images, Map, Tabs -->
            <div class="lg:col-span-2 space-y-6">
                <!--  Investment Section -->
                <div
                    class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-teal-800"
                >
                    <h2
                        class="text-xl font-bold text-teal-800 mb-4 flex items-center gap-2"
                    >
                        <i class="pi pi-chart-line"></i>
                        استثمر الآن
                    </h2>

                    <div class="space-y-6">
                        <!-- Investment Amount Controls -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <label
                                    for="investment-amount"
                                    class="text-sm font-medium text-slate-700"
                                >
                                    مبلغ الاستثمار (ريال سعودي)
                                </label>
                                <span
                                    class="text-xs bg-slate-100 text-teal-700 px-2 py-1 rounded-full"
                                >
                                    المتبقي:
                                    {{ $formatCurrency(remaining) }} ريال
                                </span>
                            </div>

                            <!-- Input -->
                            <div class="relative">
                                <input
                                    id="investment-amount"
                                    v-model.number="investmentAmount"
                                    type="number"
                                    min="1000"
                                    :max="remaining"
                                    class="w-full p-3 border-2 border-slate-300 rounded-lg text-center text-teal-800 text-lg focus:outline-none focus:ring-0 focus:border-teal-700"
                                    placeholder="أدخل مبلغ الاستثمار"
                                />

                                <div
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-teal-800 font-bold"
                                >
                                    <img
                                        src="/assets/rs-green.svg"
                                        alt=""
                                        class="w-4 h-4"
                                    />
                                </div>
                            </div>
                            <!-- Slider -->
                            <div class="px-2">
                                <Slider
                                    v-model="sliderValue"
                                    :min="1000"
                                    :max="remaining"
                                    :step="1000"
                                    class="w-full"
                                />
                                <div
                                    class="flex justify-between text-xs text-slate-500 mt-1"
                                >
                                    <span>1,000</span>
                                    <span>{{
                                        $formatCurrency(remaining)
                                    }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Interactive Progress Visualization -->
                        <div
                            class="space-y-4 p-4 bg-gradient-to-r from-sky-50 to-blue-50 rounded-lg"
                        >
                            <h3
                                class="font-semibold text-slate-800 flex items-center gap-2"
                            >
                                <i class="pi pi-calculator"></i>
                                تقدم التمويل التفاعلي
                            </h3>

                            <!-- Interactive Progress Bar -->
                            <div class="space-y-3">
                                <!-- Main Interactive Progress Bar -->
                                <div
                                    class="relative h-8 rounded-full bg-slate-200 overflow-hidden shadow-inner"
                                >
                                    <!-- Already Funded (Fixed) -->
                                    <div
                                        class="absolute top-0 right-0 h-full bg-gradient-to-r from-teal-700 to-teal-600 transition-all duration-500 ease-out"
                                        :style="{
                                            width: `${percentageFunded}%`,
                                        }"
                                    ></div>

                                    <!-- Your Investment (Dynamic) -->
                                    <div
                                        class="absolute top-0 h-full bg-yellow-500 transition-all duration-300 ease-out"
                                        :style="{
                                            right: `${percentageFunded}%`,
                                            width: `${investmentPercentage}%`,
                                        }"
                                    ></div>

                                    <!-- Progress Labels -->
                                    <div
                                        class="absolute inset-0 flex items-center justify-center"
                                    >
                                        <span
                                            v-if="newPercentageFunded > 15"
                                            class="text-xs font-bold text-white"
                                        >
                                            {{
                                                newPercentageFunded.toFixed(0)
                                            }}%
                                        </span>
                                    </div>
                                </div>

                                <!-- Progress Legend -->
                                <div
                                    class="flex justify-between items-center text-xs"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center gap-1">
                                            <div
                                                class="w-3 h-3 rounded-full bg-gradient-to-r from-teal-700 to-teal-600"
                                            ></div>
                                            <span class="text-slate-600"
                                                >تم تمويله</span
                                            >
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div
                                                class="w-3 h-3 rounded-full bg-yellow-500"
                                            ></div>
                                            <span class="text-slate-600"
                                                >استثمارك</span
                                            >
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div
                                                class="w-3 h-3 rounded-full bg-slate-200"
                                            ></div>
                                            <span class="text-slate-600"
                                                >المتبقي</span
                                            >
                                        </div>
                                    </div>
                                    <span class="font-semibold text-slate-700">
                                        {{
                                            $formatCurrency(
                                                property.investment_required
                                            )
                                        }}
                                        ريال
                                    </span>
                                </div>

                                <!-- Detailed Breakdown with Animation -->
                                <div class="grid grid-cols-3 gap-3 mt-4">
                                    <div
                                        class="p-3 bg-white rounded-lg border-l-4 border-teal-600"
                                    >
                                        <p class="text-xs text-slate-500 mb-1">
                                            تم تمويله
                                        </p>
                                        <div class="flex items-center gap-1">
                                            <p
                                                class="font-bold text-teal-700 text-lg"
                                            >
                                                {{
                                                    $formatCurrency(
                                                        currentFunded
                                                    )
                                                }}
                                            </p>
                                            <img
                                                src="/assets/rs-green.svg"
                                                alt=""
                                                class="w-4 h-4"
                                            />
                                        </div>
                                        <p class="text-xs text-teal-600">
                                            {{ percentageFunded.toFixed(1) }}%
                                        </p>
                                    </div>

                                    <div
                                        class="p-3 bg-white rounded-lg border-l-4 border-yellow-400"
                                    >
                                        <p class="text-xs text-slate-500 mb-1">
                                            استثمارك
                                        </p>
                                        <div class="flex items-center gap-1">
                                            <p
                                                class="font-bold text-yellow-500 text-lg"
                                            >
                                                {{
                                                    $formatCurrency(
                                                        investmentAmount
                                                    )
                                                }}
                                            </p>
                                            <img
                                                src="/assets/rs-gold.svg"
                                                alt=""
                                                class="w-4 h-4"
                                            />
                                        </div>
                                        <p class="text-xs text-yellow-500">
                                            {{
                                                investmentPercentage.toFixed(1)
                                            }}%
                                        </p>
                                    </div>

                                    <div
                                        class="p-3 bg-white rounded-lg border-l-4 border-slate-400"
                                    >
                                        <p class="text-xs text-slate-500 mb-1">
                                            المتبقي
                                        </p>
                                        <div class="flex items-center gap-1">
                                            <p
                                                class="font-bold text-slate-600 text-lg"
                                            >
                                                {{
                                                    $formatCurrency(
                                                        remainingAfterInvestment
                                                    )
                                                }}
                                            </p>
                                            <img
                                                src="/assets/rs-gray.svg"
                                                alt=""
                                                class="w-4 h-4"
                                            />
                                        </div>
                                        <p class="text-xs text-slate-600">
                                            {{
                                                remainingPercentage.toFixed(1)
                                            }}%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Expected Returns -->
                        <div
                            class="p-4 bg-slate-100 rounded-lg border border-slate-200"
                        >
                            <h3
                                class="font-semibold text-slate-800 flex items-center gap-2 mb-3"
                            >
                                <i class="pi pi-dollar"></i>
                                العائد المتوقع
                            </h3>

                            <div
                                class="grid grid-cols-3 gap-4 place-items-center"
                            >
                                <div
                                    class="bg-white w-40 flex flex-col justify-center items-center py-2 shadow-md rounded-lg"
                                >
                                    <p class="text-xs text-slate-600 mb-1">
                                        عائدك الشهري
                                    </p>
                                    <div class="flex items-center gap-1">
                                        <p
                                            class="text-2xl font-bold text-green-700"
                                        >
                                            {{
                                                $formatCurrency(
                                                    Math.round(
                                                        userMonthlyEarnings
                                                    )
                                                )
                                            }}
                                        </p>
                                        <img
                                            src="/assets/rs-green.svg"
                                            alt=""
                                            class="w-4 h-4"
                                        />
                                    </div>
                                </div>
                                <div
                                    class="bg-white w-40 flex flex-col justify-center items-center py-2 shadow-md rounded-lg"
                                >
                                    <p class="text-xs text-slate-600 mb-1">
                                        عائدك السنوي
                                    </p>
                                    <div class="flex items-center gap-1">
                                        <p
                                            class="text-2xl font-bold text-green-700"
                                        >
                                            {{
                                                $formatCurrency(
                                                    Math.round(
                                                        userMonthlyEarnings * 12
                                                    )
                                                )
                                            }}
                                        </p>
                                        <img
                                            src="/assets/rs-green.svg"
                                            alt=""
                                            class="w-4 h-4"
                                        />
                                    </div>
                                </div>
                                <div
                                    class="bg-white w-40 flex flex-col justify-center items-center py-2 shadow-md rounded-lg"
                                >
                                    <p class="text-xs text-slate-600 mb-1">
                                        نسبة العائد السنوي
                                    </p>
                                    <p
                                        class="text-2xl font-bold text-green-700"
                                    >
                                        {{ annualReturnPercentage }}%
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Investment Button -->
                        <button
                            class="w-full bg-gradient-to-r from-teal-600 to-teal-800 hover:from-teal-700 hover:to-teal-900 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 text-lg"
                            :disabled="!isInvestmentValid"
                        >
                            استثمر {{ $formatCurrency(investmentAmount) }} ريال
                        </button>
                    </div>
                </div>

                <!-- Image Gallery -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <Galleria
                        :value="getGalleriaImages(property.images)"
                        :circular="true"
                        :showItemNavigators="true"
                        :showThumbnails="true"
                        :showItemNavigatorsOnHover="true"
                        :showIndicators="true"
                        :showIndicatorsOnItem="true"
                    >
                        <template #item="slotProps">
                            <img
                                :src="'/' + slotProps.item.itemImageSrc"
                                :alt="slotProps.item.alt"
                                class="w-full h-[400px] object-cover rounded-lg"
                            />
                        </template>
                        <template #thumbnail="slotProps">
                            <img
                                :src="'/' + slotProps.item.itemImageSrc"
                                :alt="slotProps.item.alt"
                                class="w-20 h-20 object-cover rounded-md"
                            />
                        </template>
                    </Galleria>
                </div>

                <!-- Map -->
                <Card>
                    <template #title>
                        <div class="flex items-center gap-3 mb-4">
                            <h2 class="text-xl font-bold text-teal-800">
                                الموقع
                            </h2>
                            <p class="text-gray-600 text-base">
                                {{ property.address }}, {{ property.city }}
                            </p>
                        </div>
                    </template>

                    <template #content>
                        <div
                            class="w-full h-[500px] bg-gray-200 rounded-lg overflow-hidden"
                        >
                            <PropertyMapView
                                :latitude="property.latitude"
                                :longitude="property.longitude"
                            />
                        </div>
                    </template>
                </Card>
            </div>

            <!-- Right Column: Financials, Details, Amenities -->
            <div class="space-y-6">
                <!-- Financial Metrics -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h2
                        class="text-xl font-bold text-teal-800 mb-4 flex items-center gap-2"
                    >
                        <i class="pi pi-chart-bar"></i>
                        المعلومات المالية
                    </h2>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-slate-600">تقدم التمويل</span>
                            <span class="font-semibold"
                                >{{ percentageFunded.toFixed(0) }}%</span
                            >
                        </div>
                        <div
                            class="h-8 rounded-full bg-slate-200 overflow-hidden"
                        >
                            <div
                                class="h-full bg-gradient-to-l from-teal-800 to-teal-400 flex items-center justify-center"
                                :style="{ width: `${percentageFunded}%` }"
                            >
                                <span class="text-xs font-bold text-white"
                                    >{{ percentageFunded.toFixed(0) }}%</span
                                >
                            </div>
                        </div>
                        <div class="flex justify-between text-sm mt-2">
                            <div>
                                <p class="text-xs text-slate-600">
                                    المبلغ المطلوب
                                </p>
                                <div class="flex items-center gap-1">
                                    <p class="font-semibold text-teal-800">
                                        {{
                                            $formatCurrency(
                                                property.investment_required
                                            )
                                        }}
                                    </p>
                                    <img
                                        src="/assets/rs-green.svg"
                                        alt=""
                                        class="w-3 h-3"
                                    />
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-600">المتبقي</p>
                                <div class="flex items-center gap-1">
                                    <p class="font-semibold text-teal-800">
                                        {{ $formatCurrency(remaining) }}
                                    </p>
                                    <img
                                        src="/assets/rs-green.svg"
                                        alt=""
                                        class="w-3 h-3"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expected Revenue -->
                    <div
                        class="p-5 bg-white rounded-lg shadow-lg border-t-4 border-yellow-400 mb-4"
                    >
                        <div class="text-center">
                            <p class="text-sm text-slate-600 mb-2 font-medium">
                                مداخل العقار المتوقعة (شهريا)
                            </p>
                            <div class="flex items-center justify-center gap-1">
                                <p
                                    class="text-3xl font-extrabold text-teal-800"
                                >
                                    {{ $formatCurrency(expectedRevenue) }}
                                </p>
                                <img
                                    src="/assets/rs-green.svg"
                                    alt=""
                                    class="w-6 h-6"
                                />
                            </div>
                            <p class="text-xs text-slate-600 mt-2">
                                حصة المستثمرين: {{ percentage }}% ({{
                                    $formatCurrency(investorRevenue)
                                }}
                                ريال)
                            </p>
                        </div>
                    </div>

                    <!-- Additional Metrics -->
                    <div class="grid grid-cols-1 gap-4 mt-4">
                        <div
                            class="flex justify-between items-center p-2 bg-slate-50 rounded-lg"
                        >
                            <p class="text-sm text-slate-600">
                                قيمة العقار المقدرة
                            </p>
                            <div class="flex items-center gap-1">
                                <p class="text-sm font-medium text-teal-800">
                                    {{ $formatCurrency(property.valuation) }}
                                </p>
                                <img
                                    src="/assets/rs-green.svg"
                                    alt=""
                                    class="w-3 h-3"
                                />
                            </div>
                        </div>
                        <div
                            class="flex justify-between items-center p-2 bg-slate-50 rounded-lg"
                        >
                            <p class="text-sm text-slate-600">
                                تكاليف التشغيل الشهرية
                            </p>
                            <div class="flex items-center gap-1">
                                <p class="text-sm font-medium text-teal-800">
                                    {{
                                        $formatCurrency(
                                            property.monthly_operating_cost
                                        )
                                    }}
                                </p>
                                <img
                                    src="/assets/rs-green.svg"
                                    alt=""
                                    class="w-3 h-3"
                                />
                            </div>
                        </div>
                        <div
                            class="flex justify-between items-center p-2 bg-slate-50 rounded-lg"
                        >
                            <p class="text-sm text-slate-600">
                                سعر إيجار الليلة
                            </p>
                            <div class="flex items-center gap-1">
                                <p class="text-sm font-medium text-teal-800">
                                    {{ $formatCurrency(property.nightly_rent) }}
                                </p>
                                <img
                                    src="/assets/rs-green.svg"
                                    alt=""
                                    class="w-3 h-3"
                                />
                            </div>
                        </div>
                        <div
                            class="flex justify-between items-center p-2 bg-slate-50 rounded-lg"
                        >
                            <p class="text-sm text-slate-600">حصة المالك</p>
                            <p class="text-sm font-medium text-teal-800">
                                {{ (property.owner_share * 100).toFixed(0) }}%
                            </p>
                        </div>
                        <div
                            class="flex justify-between items-center p-2 bg-slate-50 rounded-lg"
                        >
                            <p class="text-sm text-slate-600">حصة المستثمرين</p>
                            <p class="text-sm font-medium text-teal-800">
                                {{
                                    (property.investor_share * 100).toFixed(0)
                                }}%
                            </p>
                        </div>
                        <div
                            class="flex justify-between items-center p-2 bg-slate-50 rounded-lg"
                        >
                            <p class="text-sm text-slate-600">رسوم المنصة</p>
                            <p class="text-sm font-medium text-teal-800">
                                {{
                                    (property.platform_fee_share * 100).toFixed(
                                        0
                                    )
                                }}%
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Property Details -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h2
                        class="text-xl font-bold text-teal-800 mb-4 flex items-center gap-2"
                    >
                        <i class="pi pi-home"></i>
                        تفاصيل العقار
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div
                            class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg"
                        >
                            <i class="pi pi-building text-teal-700"></i>
                            <div>
                                <p class="text-xs text-slate-600">نوع العقار</p>
                                <p class="text-sm font-medium text-teal-800">
                                    {{ property.type }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg"
                        >
                            <Icon
                                icon="fa-solid fa-up-right-and-down-left-from-center"
                                class="ml-1 w-[0.9rem] h-[0.9rem] text-teal-700"
                            />
                            <div>
                                <p class="text-xs text-slate-600">المساحة</p>
                                <p class="text-sm font-medium text-teal-800">
                                    {{ property.area }} م²
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg"
                        >
                            <Icon
                                icon="fa-solid fa-bed"
                                class="ml-1 w-[1rem] h-[1rem] text-teal-700"
                            />
                            <div>
                                <p class="text-xs text-slate-600">غرف النوم</p>
                                <p class="text-sm font-medium text-teal-800">
                                    {{ property.bedrooms }} غرف
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg"
                        >
                            <Icon
                                icon="fa-solid fa-shower"
                                class="ml-1 w-[1rem] h-[1rem] text-teal-700"
                            />
                            <div>
                                <p class="text-xs text-slate-600">الحمامات</p>
                                <p class="text-sm font-medium text-teal-800">
                                    {{ property.bathrooms }} حمامات
                                </p>
                            </div>
                        </div>

                        <div class="col-span-2 p-3 bg-slate-50 rounded-lg">
                            <p class="text-xs text-teal-600 mb-1">الوصف</p>
                            <p class="text-sm text-slate-700">
                                {{ property.description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h2
                        class="text-xl font-bold text-teal-800 mb-4 flex items-center gap-2"
                    >
                        <i class="pi pi-list"></i>
                        المرافقات
                    </h2>
                    <div
                        v-if="property.amenities.length === 0"
                        class="text-slate-600"
                    >
                        لا توجد مرافقات
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <div
                            v-for="amenity in property.amenities"
                            :key="amenity.id"
                            class="bg-slate-100 text-slate-700 text-xs px-3 py-2 rounded-full flex items-center gap-2"
                        >
                            <Icon
                                :icon="amenity.icon"
                                class="text-sm text-slate-700"
                            ></Icon>
                            <span>{{ amenity.name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom slider styling */
:deep(.p-slider) {
    background: #e2e8f0;
    border-radius: 9999px;
    height: 0.5rem;
}

:deep(.p-slider .p-slider-range) {
    background: linear-gradient(to right, #0f766e, #2dd4bf);
    border-radius: 9999px;
}

:deep(.p-slider .p-slider-handle) {
    border: 2px solid #0f766e;
    background: white;
    width: 1.25rem;
    height: 1.25rem;
    margin-top: -0.5rem;
    margin-left: -0.625rem;
    transition: box-shadow 0.2s;
}

:deep(.p-slider .p-slider-handle:hover) {
    background: #f8fafc;
    border-color: #0f766e;
    box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.2);
}

/* Animation for progress bars */
@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: v-bind('percentageFunded + "%"');
    }
}

/* Input number styling */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}
</style>
