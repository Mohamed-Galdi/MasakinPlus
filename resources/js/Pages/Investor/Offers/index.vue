<script setup>
import InvestorLayout from "@/Layouts/InvestorLayout.vue";
import { ref, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import Button from "primevue/button";
import Slider from "primevue/slider";
import InputNumber from "primevue/inputnumber";
import MultiSelect from "primevue/multiselect";
import PropertiesMapView from "@/Components/PropertiesMapView.vue";
import PropertiesCardsView from "@/Components/PropertiesCardsView.vue";

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
const currentView = ref(localStorage.getItem("masakin:currentView") || "cards");
const isFilterOpen = ref(false); // State for mobile filter toggle

watch(currentView, (newView) => {
    localStorage.setItem("masakin:currentView", newView);

    watch(currentView, (newView) => {
    localStorage.setItem("masakin:currentView", newView);
});

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
                <div v-if="currentView === 'cards'">
                    <PropertiesCardsView :properties="properties" />
                </div>

                <!-- Map View -->
                <keep-alive>
                    <PropertiesMapView
                        v-if="currentView === 'map'"
                        :properties="properties"
                    />
                </keep-alive>

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
                            <span class="text-teal-600 font-bold text-lg">{{
                                props.properties.to
                            }}</span>
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
