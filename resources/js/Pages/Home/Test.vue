<script setup>
import { ref, watch, computed } from "vue";

// Define property prop
const props = defineProps({
    property: {
        type: Object,
        required: true,
    },
    errors: Object,
});

const property = ref(props.property);
const investmentAmount = ref(10000);
const sliderValue = ref(10000);

// Format price for display
const formatPrice = (price) => {
    return parseFloat(price).toLocaleString("ar-SA");
};

// Sync slider and input
watch(sliderValue, (newValue) => {
    investmentAmount.value = newValue;
});

watch(investmentAmount, (newValue) => {
    if (newValue < 0) {
        investmentAmount.value = 0;
    } else if (newValue > property.value.remaining_investment) {
        investmentAmount.value = property.value.remaining_investment;
    }
    sliderValue.value = investmentAmount.value;
});

// Computed properties using backend data
const percentageFunded = computed(() => property.value.percentage_funded);
const currentFunded = computed(() => property.value.total_funded);
const remaining = computed(() => property.value.remaining_investment);
const userMonthlyEarnings = computed(
    () =>
        (investmentAmount.value / property.value.investment_required) *
        property.value.investors_monthly_share
);
const annualReturnPercentage = computed(() => {
    if (investmentAmount.value <= 0) return 0;
    return (
        ((userMonthlyEarnings.value * 12) / investmentAmount.value) *
        100
    ).toFixed(1);
});
const newTotalFunded = computed(
    () => currentFunded.value + investmentAmount.value
);
const newPercentageFunded = computed(
    () => (newTotalFunded.value / property.value.investment_required) * 100
);
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
</script>

<template>
    <div class="max-w-7xl mx-auto">
        <Toast position="top-center" />
        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Investment, Images, Map, Tabs -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Investment Section -->
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
                                    المتبقي: {{ formatPrice(remaining) }} ريال
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
                                    <span>{{ formatPrice(remaining) }}</span>
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
                                            formatPrice(
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
                                                {{ formatPrice(currentFunded) }}
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
                                                    formatPrice(
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
                                                    formatPrice(
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
                                                formatPrice(
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
                                                formatPrice(
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
