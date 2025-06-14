<script setup>
import InvestorLayout from "@/Layouts/InvestorLayout.vue";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { useTextHelpers } from "@/plugins/textHelpers";
import Galleria from "primevue/galleria";
import Card from "primevue/card";
import Tag from "primevue/tag";
import Avatar from "primevue/avatar";
import Tabs from "primevue/tabs";
import TabList from "primevue/tablist";
import Tab from "primevue/tab";
import TabPanels from "primevue/tabpanels";
import TabPanel from "primevue/tabpanel";
import PropertyStatus from "@/Components/PropertyStatus.vue";
import PropertyMapView from "@/Components/PropertyMapView.vue";
import Header from "@/Components/Header.vue";

defineOptions({
    layout: InvestorLayout,
});

const props = defineProps({
    property: {
        type: Object,
        required: true,
    },
    userInvestments: {
        type: Array,
        required: true,
    },
    investmentSummary: {
        type: Object,
        required: true,
    },
    otherInvestors: {
        type: Array,
        required: true,
    },
});

const textHelpers = useTextHelpers();
const property = ref(props.property);
const userInvestments = ref(props.userInvestments);
const investmentSummary = ref(props.investmentSummary);
const otherInvestors = ref(props.otherInvestors);

// Format images for Galleria
const getGalleriaImages = (images) => {
    return images.map((image) => ({
        itemImageSrc: image.path,
        alt: `Property image ${image.order}`,
    }));
};
</script>

<template>
    <div class="">
        <!-- Header Section -->
        <Header
            icon="fa-solid fa-chart-line"
            :title="property.title"
            :subtitle="property.address + ' - ' + property.city"
        >
            <Link
                :href="route('investor.investments.index')"
                class="btn bg-slate-200 hover:bg-slate-100 text-slate-800"
            >
                <p class="flex gap-1">العودة</p>
                <i class="pi pi-arrow-left"></i>
            </Link>
        </Header>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Images, Map, and Investment Details -->
            <div class="lg:col-span-2 space-y-6 md:order-first order-last">
                <!-- Image Gallery -->
                <Card>
                    <template #content>
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
                    </template>
                </Card>

                <!-- Location (Map) -->
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

                <!-- Investment Details Tabs -->
                <Card>
                    <template #content>
                        <Tabs value="0">
                            <TabList>
                                <Tab value="0">استثماراتي</Tab>
                                <Tab value="1">المستثمرون الآخرون</Tab>
                            </TabList>
                            <TabPanels>
                                <!-- My Investments Tab -->
                                <TabPanel value="0">
                                    <div class="space-y-4">
                                        <div
                                            v-for="investment in userInvestments"
                                            :key="investment.id"
                                            class="bg-gradient-to-r from-emerald-50 to-blue-50 p-4 rounded-lg border border-emerald-200"
                                        >
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h4 class="font-semibold text-emerald-800">
                                                        استثمار #{{ investment.id }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600">
                                                        <i class="pi pi-calendar ml-1"></i>
                                                        {{ new Date(investment.invested_at).toLocaleDateString('ar-SA') }}
                                                    </p>
                                                </div>
                                                <div class="text-left">
                                                    <p class="text-lg font-bold text-emerald-600">
                                                        {{ $formatCurrency(investment.amount) }} 
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Investment Summary -->
                                        <div class="bg-gray-50 p-4 rounded-lg border">
                                            <h4 class="font-semibold text-gray-800 mb-3">
                                                ملخص استثماراتي
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div class="text-center">
                                                    <p class="text-sm text-gray-600">إجمالي المبلغ</p>
                                                    <p class="text-lg font-bold text-yellow-600">
                                                        {{ $formatCurrency(investmentSummary.total_user_investment) }} 
                                                    </p>
                                                </div>
                                                <div class="text-center">
                                                    <p class="text-sm text-gray-600">نسبة الملكية</p>
                                                    <p class="text-lg font-bold text-blue-600">
                                                        {{ investmentSummary.user_investment_percentage }}%
                                                    </p>
                                                </div>
                                                <div class="text-center">
                                                    <p class="text-sm text-gray-600">العائد الشهري المتوقع</p>
                                                    <p class="text-lg font-bold text-teal-600">
                                                        {{ $formatCurrency(investmentSummary.user_expected_monthly_revenue) }} 
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </TabPanel>

                                <!-- Other Investors Tab -->
                                <TabPanel value="1">
                                    <div v-if="otherInvestors.length === 0" class="text-center p-8">
                                        <i class="pi pi-users text-gray-300 mb-3" style="font-size: 3rem"></i>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">
                                            لا يوجد مستثمرون آخرون
                                        </h3>
                                        <p class="text-gray-500">
                                            أنت المستثمر الوحيد في هذا العقار حالياً
                                        </p>
                                    </div>
                                    
                                    <div v-else class="space-y-4">
                                        <div
                                            v-for="investor in otherInvestors"
                                            :key="investor.name"
                                            class="bg-gray-50 p-4 rounded-lg border"
                                        >
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-3">
                                                    <Avatar
                                                        icon="pi pi-user"
                                                        class="bg-blue-100 text-blue-600"
                                                        shape="circle"
                                                    />
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">
                                                            {{ investor.name }}
                                                        </h4>
                                                        <p class="text-sm text-gray-600">
                                                            {{ investor.investment_count }} 
                                                            {{ investor.investment_count === 1 ? 'استثمار' : 'استثمارات' }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-left">
                                                    <p class="text-lg font-bold text-blue-600">
                                                        {{ $formatCurrency(investor.total_investment) }} 
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </TabPanel>
                            </TabPanels>
                        </Tabs>
                    </template>
                </Card>
            </div>

            <!-- Right Column: Owner, Details, and Investment Stats -->
            <div class="space-y-6">
                <!-- Investment Summary Card -->
                <Card>
                    <template #title>
                        <h2 class="text-xl font-bold text-teal-800">
                            ملخص الاستثمار
                        </h2>
                    </template>
                    <template #content>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                                <span class="text-sm text-yellow-700">استثماراتي</span>
                                <span class="font-bold text-yellow-800">
                                    {{ investmentSummary.investment_count }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-sky-50 rounded-lg">
                                <span class="text-sm text-sky-700">إجمالي المبلغ المستثمر</span>
                                <span class="font-bold text-sky-800">
                                    {{ $formatCurrency(investmentSummary.total_user_investment) }} 

                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-indigo-50 rounded-lg">
                                <span class="text-sm text-indigo-700">نسبة ملكيتي</span>
                                <span class="font-bold text-indigo-800">
                                    {{ investmentSummary.user_investment_percentage }}%
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-teal-50 rounded-lg">
                                <span class="text-sm text-teal-700">العائد الشهري المتوقع</span>
                                <span class="font-bold text-teal-800">
                                    {{ $formatCurrency(investmentSummary.user_expected_monthly_revenue) }} 
                                </span>
                            </div>

                            <!-- Property Funding Progress -->
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">تمويل العقار</span>
                                    <span class="font-medium">
                                        {{ property.percentage_funded.toFixed(1) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div
                                        class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-3 rounded-full transition-all duration-300"
                                        :style="{
                                            width: property.percentage_funded + '%',
                                        }"
                                    ></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>{{ $formatCurrency(property.total_funded) }} </span>
                                    <span>{{ $formatCurrency(property.investment_required) }} </span>
                                </div>
                            </div>
                        </div>
                    </template>
                </Card>

                <!-- Owner Info -->
                <Card>
                    <template #title>
                        <h2 class="text-xl font-bold text-teal-800">
                            معلومات المالك
                        </h2>
                    </template>
                    <template #content>
                        <div class="flex items-center gap-3">
                            <Avatar
                                :image="property.owner.image"
                                icon="pi pi-user"
                                size="large"
                                shape="circle"
                                class="bg-teal-100 text-teal-600"
                            />
                            <div>
                                <p class="text-lg font-semibold">
                                    {{ property.owner.name }}
                                </p>
                                <p class="text-gray-500 text-sm">
                                    مالك العقار
                                </p>
                            </div>
                        </div>

                    </template>
                </Card>

                <!-- Property Details -->
                <Card>
                    <template #title>
                        <div class="flex items-start justify-between">
                            <h2 class="text-xl font-bold text-teal-800">
                                تفاصيل العقار
                            </h2>
                        </div>
                    </template>
                    <template #content>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500 text-sm">نوع العقار</p>
                                <Tag
                                    :value="property.type"
                                    rounded
                                    class="bg-teal-100 text-teal-800"
                                />
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">المساحة</p>
                                <p class="text-lg">{{ property.area }} م²</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">غرف النوم</p>
                                <p class="text-lg">
                                    {{ property.bedrooms }} غرف
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">الحمامات</p>
                                <p class="text-lg">
                                    {{ property.bathrooms }} حمامات
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">إيجار الليلة</p>
                                <p class="text-lg text-emerald-600">
                                    {{
                                        property.nightly_rent !== null
                                            ? $formatCurrency(property.nightly_rent)
                                            : "لم يحدد بعد"
                                    }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">
                                    تاريخ الإضافة
                                </p>
                                <p class="text-lg">
                                    {{ $formatDate(property.created_at) }}
                                </p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-500 text-sm">الوصف</p>
                                <p class="text-gray-700">
                                    {{ property.description }}
                                </p>
                            </div>
                        </div>
                    </template>
                </Card>

                <!-- Amenities -->
                <Card>
                    <template #title>
                        <h2 class="text-xl font-bold text-teal-800">
                            المرافق
                        </h2>
                    </template>
                    <template #content>
                        <div
                            v-if="property.amenities.length === 0"
                            class="text-gray-500"
                        >
                            لا توجد مرافق
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <div
                                v-for="amenity in property.amenities"
                                :key="amenity.id"
                                class="bg-gray-100 text-gray-700 text-xs px-2 py-2 rounded-full flex items-center gap-1 w-fit"
                            >
                                <Icon
                                    :icon="amenity.icon"
                                    class="w-[0.9rem] h-[0.9rem] text-slate-700"
                                />
                                <p>{{ amenity.name }}</p>
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
        </div>
    </div>
</template>