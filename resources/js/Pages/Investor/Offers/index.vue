<script setup>
import InvestorLayout from "@/Layouts/InvestorLayout.vue";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
import Slider from "primevue/slider";
import InputNumber from "primevue/inputnumber";
import MultiSelect from "primevue/multiselect";

defineOptions({
    layout: InvestorLayout,
});

const props = defineProps({
    properties: {
        type: Object,
        required: true,
    },
});

const properties = ref(props.properties.data);
const investorPercentageFilter = ref(10);
const currentView = ref("cards"); // 'cards' or 'map'
const isFilterOpen = ref(false); // State for mobile filter toggle

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

// Toggle filter visibility for mobile
const toggleFilter = () => {
    isFilterOpen.value = !isFilterOpen.value;
};

// Clear filters (placeholder function)
const clearFilters = () => {
    // Implement filter reset logic here
};

// Check if filters are active (placeholder function)
const isFilterActive = () => {
    return false; // Replace with actual logic
};
</script>

<template>
    <div>
        <!-- Cards/Map Toggle -->
        <div
            class="bg-slate-200 px-1 py-1 rounded-lg flex gap-2 mb-6 w-fit mx-auto border border-slate-400"
        >
            <button
                @click="currentView = 'cards'"
                :class="[
                    'flex justify-center items-center gap-2 p-2 rounded-md transition-all duration-300 font-medium w-44',
                    currentView === 'cards'
                        ? 'bg-teal-700 text-white shadow-lg'
                        : 'text-slate-800 hover:bg-slate-300',
                ]"
            >
                <i class="pi pi-th-large text-lg"></i>
                <span>عرض البطاقات</span>
                <div
                    v-if="currentView === 'cards'"
                    class="w-2 h-2 bg-white rounded-full"
                ></div>
            </button>
            <button
                @click="currentView = 'map'"
                :class="[
                    'flex justify-center items-center gap-2 p-2 rounded-md transition-all duration-300 font-medium w-44',
                    currentView === 'map'
                        ? 'bg-teal-700 text-white shadow-lg'
                        : 'text-slate-800 hover:bg-slate-300',
                ]"
            >
                <i class="pi pi-map text-lg"></i>
                <span>عرض الخريطة</span>
                <div
                    v-if="currentView === 'map'"
                    class="w-2 h-2 bg-white rounded-full"
                ></div>
            </button>
        </div>

        <!-- Mobile Filter Toggle Button -->
        <div class="lg:hidden mb-4">
            <Button
                label="الفلتر"
                icon="pi pi-filter"
                class="w-full"
                @click="toggleFilter"
            />
        </div>

        <!-- Main -->
        <div class="flex gap-6 flex-col lg:flex-row">
            <!-- Main Content Area -->
            <div class="flex-1">
                <!-- Cards View -->
                <div
                    v-if="currentView === 'cards'"
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
                                        <div
                                            class="flex items-start gap-1 text-sm"
                                        >
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
                                        <span
                                            class="text-xs font-bold text-white"
                                            >{{
                                                percentageFunded.toFixed(0)
                                            }}%</span
                                        >
                                    </div>
                                </div>

                                <!-- Investment Details -->
                                <div class="flex justify-between text-sm mt-2">
                                    <div>
                                        <p class="text-xs text-slate-500">
                                            المبلغ المطلوب
                                        </p>
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
                                        <p class="text-xs text-slate-500">
                                            المتبقي
                                        </p>
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
                                    <p
                                        class="text-sm text-slate-600 mb-2 font-medium"
                                    >
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
                                        <div
                                            class="flex justify-center items-center gap-1"
                                        >
                                            <p class="text-teal-700">
                                                {{
                                                    formatPrice(investorRevenue)
                                                }}
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
                                class="relative w-full py-2 px-8 text-teal-700 overflow-hidden bg-slate-50 rounded-full transition-all duration-400 ease-in-out hover:scale-100 hover:text-white before:absolute before:top-0 before:-right-full before:w-full before:h-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-800 before:transition-all before:duration-500 before:ease-in-out before:z-[-1] before:rounded-full hover:before:right-0 border border-teal-700"
                            >
                                {{ buttonText }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Map View Placeholder -->
                <div
                    v-else
                    class="bg-white rounded-lg shadow-lg p-8 text-center min-h-[600px] flex items-center justify-center"
                >
                    <div>
                        <i class="pi pi-map text-6xl text-slate-400 mb-4"></i>
                        <h3 class="text-xl font-medium text-slate-700 mb-2">
                            عرض الخريطة
                        </h3>
                        <p class="text-slate-500">
                            سيتم إضافة عرض الخريطة هنا لاحقاً
                        </p>
                    </div>
                </div>

                <!-- Empty State for Cards -->
                <div
                    v-if="currentView === 'cards' && properties.length === 0"
                    class="bg-white rounded-lg shadow-md p-12 text-center"
                >
                    <i
                        class="pi pi-chart-line text-slate-400 text-6xl mb-4"
                    ></i>
                    <h3 class="text-2xl font-medium text-slate-800 mb-3">
                        لا توجد فرص استثمارية متاحة
                    </h3>
                    <p class="text-slate-600 mb-6 max-w-md mx-auto">
                        لم يتم العثور على فرص استثمارية تطابق معايير البحث
                        الخاصة بك. جرب تعديل المرشحات أو تحقق مرة أخرى لاحقاً.
                    </p>
                    <Button
                        label="مسح المرشحات"
                        icon="pi pi-filter-slash"
                        outlined
                        class="bg-white border-slate-300 text-slate-700 hover:bg-slate-50"
                        @click="clearFilters"
                        v-if="isFilterActive()"
                    />
                </div>

                <!-- Pagination -->
                <div
                    v-if="currentView === 'cards'"
                    dir="ltr"
                    class="my-8 flex md:flex-row flex-col md:gap-0 gap-2 justify-between items-center w-full"
                >
                    <div class="order-last md:order-first">
                        <p class="text-base text-slate-600 rtl:text-right">
                            عرض
                            <span class="text-teal-600 font-bold text-lg">{{
                                props.properties.from
                            }}</span>
                            إلى
                            <span class="text-teal-600 font-bold text-lg"
                                >{{ props.properties.to }}</span
                            >
                            من أصل {{ props.properties.total }} عقار
                        </p>
                    </div>
                    <nav class="order-first md:order-last">
                        <div class="flex items-center -space-x-px h-8 text-sm">
                            <template
                                v-for="(link, index) in props.properties.links"
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
                                            index ===
                                            props.properties.links.length - 1,
                                    }"
                                />
                                <p
                                    v-else
                                    v-html="link.label"
                                    class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-slate-200 border border-gray-300"
                                    :class="{
                                        'rounded-l-lg': index === 0,
                                        'rounded-r-lg':
                                            index ===
                                            props.properties.links.length - 1,
                                    }"
                                />
                            </template>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Sidebar Filters (Desktop) -->
            <div class="hidden lg:block w-80 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                    <div class="space-y-6">
                        <!-- Cities -->
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 mb-2"
                                >المدن</label
                            >
                            <MultiSelect
                                optionLabel="label"
                                optionValue="value"
                                placeholder="اختر المدن"
                                class="w-full"
                                :maxSelectedLabels="2"
                            />
                        </div>

                        <!-- Property Types -->
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 mb-2"
                                >أنواع العقارات</label
                            >
                            <MultiSelect
                                optionLabel="label"
                                optionValue="value"
                                placeholder="اختر الأنواع"
                                class="w-full"
                                :maxSelectedLabels="2"
                            />
                        </div>

                        <!-- Investment Range -->
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 mb-2"
                                >مبلغ الاستثمار المطلوب (ريال)</label
                            >
                            <div class="flex gap-2">
                                <div class="w-1/2 filter-input">
                                    <InputNumber
                                        :min="5000"
                                        :max="1000000"
                                        :step="5000"
                                        class="w-full"
                                        placeholder="من"
                                        :pt="{
                                            input: { class: 'text-sm p-2' },
                                        }"
                                    />
                                </div>
                                <div class="w-1/2 filter-input">
                                    <InputNumber
                                        :min="10000"
                                        :max="2000000"
                                        :step="10000"
                                        class="w-full"
                                        placeholder="إلى"
                                        :pt="{
                                            input: { class: 'text-sm p-2' },
                                        }"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Revenue Range -->
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 mb-2"
                                >العائد الشهري الإجمالي المتوقع (ريال)</label
                            >
                            <div class="flex gap-2">
                                <div class="w-1/2 filter-input">
                                    <InputNumber
                                        :min="1000"
                                        :max="50000"
                                        :step="1000"
                                        class="w-full"
                                        placeholder="من"
                                        :pt="{
                                            input: { class: 'text-sm p-2' },
                                        }"
                                    />
                                </div>
                                <div class="w-1/2 filter-input">
                                    <InputNumber
                                        :min="5000"
                                        :max="100000"
                                        :step="1000"
                                        class="w-full"
                                        placeholder="إلى"
                                        :pt="{
                                            input: { class: 'text-sm p-2' },
                                        }"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Investor Percentage -->
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 mb-2"
                            >
                                نسبة التمويل المكتمل
                                <span
                                    v-if="
                                        investorPercentageFilter !== undefined
                                    "
                                >
                                    ({{ investorPercentageFilter + "%" }})
                                </span>
                            </label>
                            <Slider
                                v-model="investorPercentageFilter"
                                :min="0"
                                :max="100"
                                class="w-full"
                            />
                            <div
                                class="flex justify-between text-xs text-slate-500 mt-1"
                            >
                                <span>0%</span>
                                <span>100%</span>
                            </div>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex gap-2 pt-2">
                            <Button
                                label="مسح"
                                icon="pi pi-filter-slash"
                                outlined
                                class="w-1/2 text-slate-700 border-slate-300 hover:bg-slate-50"
                            />
                            <Button
                                label="بحث"
                                icon="pi pi-search"
                                class="w-1/2"
                                severity="success"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Filters (Overlay) -->
        <div
            v-if="isFilterOpen"
            class="lg:hidden fixed inset-0 bg-black/50 z-50 flex justify-end"
            @click="toggleFilter"
        >
            <div
                class="bg-white w-full max-w-md h-full p-6 overflow-y-auto"
                @click.stop
            >
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-slate-700">المرشحات</h3>
                    <Button
                        icon="pi pi-times"
                        class="text-slate-700"
                        text
                        @click="toggleFilter"
                    />
                </div>
                <div class="space-y-6">
                    <!-- Cities -->
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-2"
                            >المدن</label
                        >
                        <MultiSelect
                            optionLabel="label"
                            optionValue="value"
                            placeholder="اختر المدن"
                            class="w-full"
                            :maxSelectedLabels="2"
                        />
                    </div>

                    <!-- Property Types -->
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-2"
                            >أنواع العقارات</label
                        >
                        <MultiSelect
                            optionLabel="label"
                            optionValue="value"
                            placeholder="اختر الأنواع"
                            class="w-full"
                            :maxSelectedLabels="2"
                        />
                    </div>

                    <!-- Investment Range -->
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-2"
                            >مبلغ الاستثمار المطلوب (ريال)</label
                        >
                        <div class="flex gap-2">
                            <div class="w-1/2 filter-input">
                                <InputNumber
                                    :min="5000"
                                    :max="1000000"
                                    :step="5000"
                                    class="w-full"
                                    placeholder="من"
                                    :pt="{
                                        input: { class: 'text-sm p-2' },
                                    }"
                                />
                            </div>
                            <div class="w-1/2 filter-input">
                                <InputNumber
                                    :min="10000"
                                    :max="2000000"
                                    :step="10000"
                                    class="w-full"
                                    placeholder="إلى"
                                    :pt="{
                                        input: { class: 'text-sm p-2' },
                                    }"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Revenue Range -->
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-2"
                            >العائد الشهري الإجمالي المتوقع (ريال)</label
                        >
                        <div class="flex gap-2">
                            <div class="w-1/2 filter-input">
                                <InputNumber
                                    :min="1000"
                                    :max="50000"
                                    :step="1000"
                                    class="w-full"
                                    placeholder="من"
                                    :pt="{
                                        input: { class: 'text-sm p-2' },
                                    }"
                                />
                            </div>
                            <div class="w-1/2 filter-input">
                                <InputNumber
                                    :min="5000"
                                    :max="100000"
                                    :step="1000"
                                    class="w-full"
                                    placeholder="إلى"
                                    :pt="{
                                        input: { class: 'text-sm p-2' },
                                    }"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Investor Percentage -->
                    <div>
                        <label
                            class="block text-sm font-medium text-slate-700 mb-2"
                        >
                            نسبة التمويل المكتمل
                            <span v-if="investorPercentageFilter !== undefined">
                                ({{ investorPercentageFilter + "%" }})
                            </span>
                        </label>
                        <Slider
                            v-model="investorPercentageFilter"
                            :min="0"
                            :max="100"
                            class="w-full"
                        />
                        <div
                            class="flex justify-between text-xs text-slate-500 mt-1"
                        >
                            <span>0%</span>
                            <span>100%</span>
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex gap-2 pt-2">
                        <Button
                            label="مسح"
                            icon="pi pi-filter-slash"
                            outlined
                            class="w-1/2 text-slate-700 border-slate-300 hover:bg-slate-50"
                        />
                        <Button
                            label="بحث"
                            icon="pi pi-search"
                            class="w-1/2"
                            severity="success"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Ensure the filter overlay has a smooth transition */
.fixed {
    transition: all 0.3s ease-in-out;
}
</style>
