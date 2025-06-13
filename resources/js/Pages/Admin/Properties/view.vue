<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { useTextHelpers } from "@/plugins/textHelpers";
import { useToast } from "primevue/usetoast";
import Galleria from "primevue/galleria";
import Card from "primevue/card";
import Button from "primevue/button";
import Tag from "primevue/tag";
import Avatar from "primevue/avatar";
import Tabs from "primevue/tabs";
import TabList from "primevue/tablist";
import Tab from "primevue/tab";
import TabPanels from "primevue/tabpanels";
import TabPanel from "primevue/tabpanel";
import PropertyStatus from "@/Components/PropertyStatus.vue";
import PropertyMapView from "@/Components/PropertyMapView.vue";
import Header from "@/Components/AdminDashboard/Header.vue";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    property: {
        type: Object,
        required: true,
    },
    statusOptions: {
        type: Array,
        required: true,
    },
});

const textHelpers = useTextHelpers();
const toast = useToast();
const property = ref(props.property);
const statusOptions = ref(props.statusOptions);

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
            icon="pi-home"
            :title="property.title"
            :subtitle="property.address + ' - ' + property.city"
        >
            <Link
                :href="route('admin.properties.index')"
                class="btn bg-slate-200 hover:bg-slate-100 text-slate-800"
            >
                <p class="flex gap-1">العودة</p>
                <i class="pi pi-arrow-left"></i>
            </Link>
        </Header>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Images, Map, and Tabs -->
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

                <!-- Rent and Investment Tabs -->
                <Card>
                    <template #content>
                        <Tabs value="0">
                            <TabList>
                                <Tab value="0">سجل الإيجار</Tab>
                                <Tab value="1">سجل الاستثمار</Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel value="0">
                                    <div
                                        class="flex flex-col items-center justify-center p-10 rounded-lg"
                                    >
                                        <i
                                            class="pi pi-calendar text-gray-300 mb-3"
                                            style="font-size: 3rem"
                                        ></i>
                                        <h3
                                            class="text-xl font-semibold text-gray-700 mb-2"
                                        >
                                            سجل الإيجار
                                        </h3>
                                        <p
                                            class="text-gray-500 text-center max-w-lg"
                                        >
                                            سيتم عرض سجل الإيجار والحجوزات
                                            الخاصة بهذا العقار هنا عندما تكون
                                            متاحة.
                                        </p>
                                    </div>
                                </TabPanel>
                                <TabPanel value="1">
                                    <div
                                        class="flex flex-col items-center justify-center p-10 rounded-lg"
                                    >
                                        <i
                                            class="pi pi-chart-line text-gray-300 mb-3"
                                            style="font-size: 3rem"
                                        ></i>
                                        <h3
                                            class="text-xl font-semibold text-gray-700 mb-2"
                                        >
                                            معلومات الاستثمار
                                        </h3>
                                        <p
                                            class="text-gray-500 text-center max-w-lg"
                                        >
                                            سيتم عرض معلومات الاستثمار الخاصة
                                            بهذا العقار هنا عندما تكون متاحة.
                                        </p>
                                    </div>
                                </TabPanel>
                            </TabPanels>
                        </Tabs>
                    </template>
                </Card>
            </div>

            <!-- Right Column: Owner, Details, and Amenities -->
            <div class="space-y-6">
                <!-- Owner Info -->
                <Card>
                    <template #title>
                        <div class="flex items-start justify-between">
                            <h2 class="text-xl font-bold text-teal-800">
                                معلومات المالك
                            </h2>
                            <Link
                                :href="
                                    route('admin.users.view', property.owner.id)
                                "
                                class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 border border-slate-300 rounded-md font-Bein text-sm transition duration-150 ease-in-out"
                            >
                                <i class="pi pi-user ml-1"></i>
                                صفحة المالك
                            </Link>
                        </div>
                    </template>
                    <template #content>
                        <div class="flex items-center gap-3">
                            <Avatar
                                :image="property.owner.image"
                                size="large"
                                shape="circle"
                            />
                            <div>
                                <p class="text-lg font-semibold">
                                    {{ property.owner.name }}
                                </p>
                                <p class="text-gray-500 text-sm">
                                    {{ property.owner.email }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="">
                                <p class="text-gray-500 text-sm">
                                    تاريخ التسجيل
                                </p>
                                <p class="text-gray-700">
                                    {{ $formatDate(property.owner.created_at) }}
                                </p>
                            </div>
                            <div
                                class="flex flex-col items-center justify-center"
                            >
                                <p class="text-gray-500 text-sm">
                                    مجموع العقارات
                                </p>
                                <Tag severity="info">
                                    {{ property.owner.owned_properties_count }}
                                </Tag>
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
                            <PropertyStatus
                                :status="property.status"
                                :status-options="statusOptions"
                            />
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
                                <p class="text-gray-500 text-sm">إجار الليلة</p>
                                <p class="text-lg text-emerald-600">
                                    {{
                                        property.nightly_rent !== null
                                            ? $formatCurrency(
                                                  property.nightly_rent
                                              )
                                            : "لم يحدد بعد"
                                    }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray- 500 text-sm">
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
                            المرافقات
                        </h2>
                    </template>
                    <template #content>
                        <div
                            v-if="property.amenities.length === 0"
                            class="text-gray-500"
                        >
                            لا توجد مرافقات
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <div
                                v-for="amenity in property.amenities.slice(
                                    0,
                                    3
                                )"
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
