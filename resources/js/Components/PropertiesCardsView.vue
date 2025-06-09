<script setup>
const props = defineProps({
    properties: {
        type: Array,
        required: true,
    },
});

// Helper functions
const formatPrice = (price) => {
    return parseFloat(price).toLocaleString("ar-SA");
};

// Define dynamic values
const totalRequired = 90000; // Total investment required in SAR
const currentFunded = 49000; // Current amount funded in SAR
const percentageFunded = (currentFunded / totalRequired) * 100; // Percentage funded
const remaining = totalRequired - currentFunded; // Remaining amount in SAR
const expectedRevenue = 10000; // Expected monthly revenue in SAR
const investorShare = 0.28; // Investor share percentage (28%)
const buttonText = "استثمر الآن";

const calculateInvestorShare = (revenue, share) => {
    const percentage = (share * 100).toFixed(0); // Whole number percentage (e.g., 28)
    const investorRevenue = (revenue * share).toFixed(2); // Two decimal places (e.g., 2800.00)
    return { percentage, investorRevenue };
};

// Calculate investor share values
const { percentage, investorRevenue } = calculateInvestorShare(
    expectedRevenue,
    investorShare
);
</script>

<template>
    <div
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6"
    >
        <div
            v-for="property in properties"
            :key="property.id"
            class="bg-white relative rounded-xl overflow-hidden border-2 border-slate-200"
        >
            <!-- Hero Image with Gradient Overlay -->
            <div class="relative h-52 overflow-hidden">
                <img
                    :src="'/' + property.images[0]?.path"
                    :alt="property.title"
                    class="w-full h-full object-cover"
                />
                <div
                    class="absolute flex justify-end top-2 left-2 w-full gap-4 text-white text-sm"
                >
                    <div
                        class="text-slate-100 bg-slate-800/50 px-2 py-[1px] rounded-md flex items-center gap-2"
                    >
                        <p>{{ property.type }}</p>
                    </div>
                    <div
                        class="text-slate-100 bg-slate-800/50 px-2 rounded-md flex items-center gap-2"
                    >
                        <i
                            class="pi pi-map-marker"
                            style="font-size: 0.9rem"
                        ></i>
                        <p>{{ property.city }}</p>
                    </div>
                </div>
            </div>

            <div class="p-3 space-y-3">
                <!-- title -->
                <div>
                    <p class="text-slate-700 font-BeinNormal">
                        {{ property.title }}
                    </p>
                </div>

                <!-- progress bar -->
                <div class="max-w-md mx-auto p-3">
                    <!-- Funded Amount -->
                    <div class="relative mb-2">
                        <div
                            class="absolute -top-4"
                            :style="{
                                left: `${90 - percentageFunded}%`,
                            }"
                        >
                            <div class="flex items-start gap-1 text-sm">
                                <p class="text-teal-700">
                                    {{ formatPrice(currentFunded) }}
                                </p>
                                <img
                                    src="/assets/rs-green.svg"
                                    alt=""
                                    class="w-4 h-4"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar with Percentage -->
                    <div
                        class="relative h-6 rounded-full bg-slate-200 overflow-hidden"
                    >
                        <div
                            class="h-full bg-gradient-to-l from-teal-800 to-teal-400 flex items-center justify-center"
                            :style="{
                                width: `${percentageFunded}%`,
                            }"
                        >
                            <span class="text-xs font-bold text-white"
                                >{{ percentageFunded.toFixed(0) }}%</span
                            >
                        </div>
                    </div>

                    <!-- Investment Details -->
                    <div class="flex justify-between text-sm mt-2">
                        <div>
                            <p class="text-xs text-slate-500">المبلغ المطلوب</p>
                            <div class="flex items-start gap-1">
                                <p class="text-teal-700">
                                    {{ formatPrice(totalRequired) }}
                                </p>
                                <img
                                    src="/assets/rs-green.svg"
                                    alt=""
                                    class="w-4 h-4"
                                />
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500">المتبقي</p>
                            <div class="flex items-start gap-1">
                                <p class="text-teal-700">
                                    {{ formatPrice(remaining) }}
                                </p>
                                <img
                                    src="/assets/rs-green.svg"
                                    alt=""
                                    class="w-4 h-4"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- revenue -->
                <div
                    class="max-w-sm mx-auto p-3 bg-white rounded-lg shadow-md border-t-4 border-yellow-400"
                    dir="rtl"
                >
                    <div class="text-center">
                        <p class="text-sm text-slate-600 mb-2 font-medium">
                            مداخل العقار المتوقعة (شهريا)
                        </p>
                        <div
                            class="flex items-center justify-center gap-1 text-3xl"
                        >
                            <p class="text-teal-700">
                                {{ formatPrice(expectedRevenue) }}
                            </p>
                            <img
                                src="/assets/rs-green.svg"
                                alt=""
                                class="w-5 h-5"
                            />
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 mt-2">
                                حصة المستثمرين: {{ percentage }}%
                            </p>
                            <div class="flex justify-center items-center gap-1">
                                <p class="text-teal-700">
                                    {{ formatPrice(investorRevenue) }}
                                </p>
                                <img
                                    src="/assets/rs-green.svg"
                                    alt=""
                                    class="w-4 h-4"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- cta button -->
                <button
                    class="relative w-full text-teal-700 overflow-hidden bg-slate-50 rounded-full transition-all duration-400 ease-in-out hover:scale-100 hover:text-white before:absolute before:top-0 before:-right-full before:w-full before:h-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-800 before:transition-all before:duration-500 before:ease-in-out before:z-[-1] before:rounded-full hover:before:right-0 border border-teal-700"
                >
                    <a
                        :href="route('investor.offers.show', property.id)"
                        class="flex justify-center py-2 px-8"
                    >
                        {{ buttonText }}
                    </a>
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
