<script setup>
import { ref, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import Button from "primevue/button";
import Slider from "primevue/slider";
import InputNumber from "primevue/inputnumber";
import MultiSelect from "primevue/multiselect";
import Select from "primevue/select";
import { cities } from "@/plugins/cities";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";

const props = defineProps({
    properties: {
        type: Object,
        required: true,
    },
    typeOptions: {
        type: Array,
        required: true,
    },
    cityFilter: {
        required: false,
        default: "",
    },
    typeFilter: {
        required: false,
        default: "",
    },
    minInvestAmount: {
        type: Number,
        required: false,
        default: 0,
    },
    maxInvestAmount: {
        type: Number,
        required: false,
        default: 0,
    },
    minMonthlyRevenue: {
        type: Number,
        required: false,
        default: 0,
    },
    maxMonthlyRevenue: {
        type: Number,
        required: false,
        default: 0,
    },
    fundedPercentageFilter: {
        type: Number,
        required: false,
        default: 100,
    },
});

const toast = useToast();

const formatPrice = (price) => {
    return parseFloat(price).toLocaleString("ar-SA");
};
// //////////////////////////////////////////// Filters ////////////////////////////////////////////
const typeOptions = ref(props.typeOptions);
const cityFilter = ref(props.cityFilter);
const typeFilter = ref(props.typeFilter);
const minInvestAmount = ref(props.minInvestAmount);
const maxInvestAmount = ref(props.maxInvestAmount);
const minMonthlyRevenue = ref(props.minMonthlyRevenue);
const maxMonthlyRevenue = ref(props.maxMonthlyRevenue);
const fundedPercentageFilter = ref(props.fundedPercentageFilter);

function filter() {
    // insure minInvestAmount is less than maxInvestAmount if both are set
    if (
        minInvestAmount.value &&
        maxInvestAmount.value &&
        minInvestAmount.value > maxInvestAmount.value
    ) {
        toast.add({
            severity: "error",
            summary: "خطأ",
            detail: "الحد الأدنى للمبلغ المطلوب يجب أن يكون أقل من الحد الأقصى",
            life: 3000,
        });
        return;
    }
    // insure minMonthlyRevenue is less than maxMonthlyRevenue if both are set
    if (
        minMonthlyRevenue.value &&
        maxMonthlyRevenue.value &&
        minMonthlyRevenue.value > maxMonthlyRevenue.value
    ) {
        toast.add({
            severity: "error",
            summary: "خطأ",
            detail: "الحد الأدنى للعائد الشهري يجب أن يكون أقل من الحد الأقصى",
            life: 3000,
        });
        return;
    }

    router.get(
        route("investor.offers.index"),
        {
            typeFilter: typeFilter.value,
            cityFilter: cityFilter.value,
            minInvestAmount: minInvestAmount.value,
            maxInvestAmount: maxInvestAmount.value,
            minMonthlyRevenue: minMonthlyRevenue.value,
            maxMonthlyRevenue: maxMonthlyRevenue.value,
            fundedPercentageFilter: fundedPercentageFilter.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );

    if (isFilterOpen.value) {
        isFilterOpen.value = false;
    }
}

function clearFilters() {
    router.get(route("investor.offers.index"));
    if (isFilterOpen.value) {
        isFilterOpen.value = false;
    }
}

// Filter on Mobile
const isFilterOpen = ref(false);
const toggleFilter = () => {
    isFilterOpen.value = !isFilterOpen.value;
};
</script>

<template>
    <div>
        <!-- Toast -->
        <Toast position="top-center" />
        <!-- Mobile Filter Toggle Button -->
        <div class="lg:hidden mb-4">
            <Button
                label="الفلتر"
                icon="pi pi-filter"
                class="w-full"
                @click="toggleFilter"
            />
        </div>
        <div class="flex gap-6 flex-col lg:flex-row">
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 flex-1"
            >
                <!-- Properties -->
                <div
                    v-for="property in properties.data"
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
                                        left: `${
                                            90 - property.percentage_funded
                                        }%`,
                                    }"
                                >
                                    <div class="flex items-start gap-1 text-sm">
                                        <p class="text-teal-700">
                                            {{
                                                formatPrice(
                                                    property.total_funded
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
                            </div>

                            <!-- Progress Bar with Percentage -->
                            <div
                                class="relative h-6 rounded-full bg-slate-200 overflow-hidden"
                            >
                                <div
                                    class="h-full bg-gradient-to-l from-teal-800 to-teal-400 flex items-center justify-center"
                                    :style="{
                                        width: `${property.percentage_funded}%`,
                                    }"
                                >
                                    <span class="text-xs font-bold text-white"
                                        >{{
                                            property.percentage_funded.toFixed(
                                                0
                                            )
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
                                            {{
                                                formatPrice(
                                                    property.investment_required
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
                                <div class="text-right">
                                    <p class="text-xs text-slate-500">
                                        المتبقي
                                    </p>
                                    <div class="flex items-start gap-1">
                                        <p class="text-teal-700">
                                            {{
                                                formatPrice(
                                                    property.remaining_investment
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
                                        {{
                                            formatPrice(
                                                property.expected_monthly_income
                                            )
                                        }}
                                    </p>
                                    <img
                                        src="/assets/rs-green.svg"
                                        alt=""
                                        class="w-5 h-5"
                                    />
                                </div>
                                <div>
                                    <p class="text-xs text-slate-600 mt-2">
                                        حصة المستثمرين:
                                        {{
                                            (
                                                property.investor_share * 100
                                            ).toFixed(2)
                                        }}%
                                    </p>
                                    <div
                                        class="flex justify-center items-center gap-1"
                                    >
                                        <p class="text-teal-700">
                                            {{
                                                formatPrice(
                                                    property.investors_monthly_share
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
                            </div>
                        </div>

                        <!-- cta button -->
                        <button
                            class="relative w-full text-teal-700 overflow-hidden bg-slate-50 rounded-full transition-all duration-400 ease-in-out hover:scale-100 hover:text-white before:absolute before:top-0 before:-right-full before:w-full before:h-full before:bg-gradient-to-r before:from-teal-400 before:to-teal-800 before:transition-all before:duration-500 before:ease-in-out before:z-[-1] before:rounded-full hover:before:right-0 border border-teal-700"
                        >
                            <a
                                :href="
                                    route('investor.offers.show', property.id)
                                "
                                class="flex justify-center py-2 px-8"
                            >
                                استثمار الآن
                            </a>
                        </button>
                    </div>
                </div>

                <!-- Empty State for Cards -->
                <div
                    v-if="properties.data.length === 0"
                    class="bg-white rounded-lg shadow-md p-12 text-center sm:col-span-3 col-span-1 flex flex-col items-center justify-center"
                >
                    <i
                        class="pi pi-chart-line text-teal-700 text-6xl mb-4"
                        style="font-size: 3rem"
                    ></i>
                    <h3 class="text-2xl text-teal-700 mb-3">
                        لم يثم العثور على عقارات مطابقة للبحث
                    </h3>
                    <p class="text-slate-600 mb-6 max-w-md mx-auto">
                        لم يتم العثور على فرص استثمارية تطابق معايير البحث
                        الخاصة بك. جرب تعديل الفلتر أو تحقق مرة أخرى لاحقاً.
                    </p>
                    <Button
                        label="مسح الفلتر"
                        icon="pi pi-filter-slash"
                        outlined
                        class="bg-white border-slate-300 text-slate-700 hover:bg-slate-50"
                        @click="clearFilters"
                    />
                </div>

                <!-- Pagination -->
                <div
                    v-if="properties.data.length !== 0"
                    class="my-8 flex md:flex-row flex-col md:gap-0 gap-2 justify-between items-center w-full sm:col-span-3 col-span-1"
                    dir="ltr"
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
                                v-model="cityFilter"
                                :options="cities"
                                placeholder="اختر المدن"
                                class="w-full"
                            />
                        </div>

                        <!-- Property Types -->
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 mb-2"
                                >أنواع العقارات</label
                            >
                            <MultiSelect
                                v-model="typeFilter"
                                :options="typeOptions"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="اختر الأنواع"
                                class="w-full"
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
                                        v-model="minInvestAmount"
                                        class="w-full"
                                        placeholder="من"
                                        :pt="{
                                            input: { class: 'text-sm p-2' },
                                        }"
                                    />
                                </div>
                                <div class="w-1/2 filter-input">
                                    <InputNumber
                                        v-model="maxInvestAmount"
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
                                        v-model="minMonthlyRevenue"
                                        class="w-full"
                                        placeholder="من"
                                        :pt="{
                                            input: { class: 'text-sm p-2' },
                                        }"
                                    />
                                </div>
                                <div class="w-1/2 filter-input">
                                    <InputNumber
                                        v-model="maxMonthlyRevenue"
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
                                <span v-if="fundedPercentageFilter > 0">
                                    ({{ fundedPercentageFilter + "%" }})
                                </span>
                            </label>
                            <Slider
                                v-model="fundedPercentageFilter"
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
                                @click="clearFilters"
                            />
                            <Button
                                label="بحث"
                                icon="pi pi-search"
                                class="w-1/2"
                                severity="success"
                                @click="filter"
                            />
                        </div>
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
            <div class="space-y-6">
                <!-- Cities -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                        >المدن</label
                    >
                    <MultiSelect
                        v-model="cityFilter"
                        :options="cities"
                        placeholder="اختر المدن"
                        class="w-full"
                    />
                </div>

                <!-- Property Types -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                        >أنواع العقارات</label
                    >
                    <MultiSelect
                        v-model="typeFilter"
                        :options="typeOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="اختر الأنواع"
                        class="w-full"
                    />
                </div>

                <!-- Investment Range -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                        >مبلغ الاستثمار المطلوب (ريال)</label
                    >
                    <div class="flex gap-2">
                        <div class="w-1/2 filter-input">
                            <InputNumber
                                v-model="minInvestAmount"
                                class="w-full"
                                placeholder="من"
                                :pt="{
                                    input: { class: 'text-sm p-2' },
                                }"
                            />
                        </div>
                        <div class="w-1/2 filter-input">
                            <InputNumber
                                v-model="maxInvestAmount"
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
                    <label class="block text-sm font-medium text-slate-700 mb-2"
                        >العائد الشهري الإجمالي المتوقع (ريال)</label
                    >
                    <div class="flex gap-2">
                        <div class="w-1/2 filter-input">
                            <InputNumber
                                v-model="minMonthlyRevenue"
                                class="w-full"
                                placeholder="من"
                                :pt="{
                                    input: { class: 'text-sm p-2' },
                                }"
                            />
                        </div>
                        <div class="w-1/2 filter-input">
                            <InputNumber
                                v-model="maxMonthlyRevenue"
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
                        <span v-if="fundedPercentageFilter > 0">
                            ({{ fundedPercentageFilter + "%" }})
                        </span>
                    </label>
                    <Slider
                        v-model="fundedPercentageFilter"
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
                        @click="clearFilters"
                    />
                    <Button
                        label="بحث"
                        icon="pi pi-search"
                        class="w-1/2"
                        severity="success"
                        @click="filter"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
