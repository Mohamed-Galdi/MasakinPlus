<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
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

// Format images for Galleria
const getGalleriaImages = (images) => {
    return images.map((image) => ({
        itemImageSrc: image.path,
        alt: `Property image ${image.order}`,
    }));
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("ar-SA", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
    }).format(date);
};

// Format price
const formatPrice = (price) => {
    return new Intl.NumberFormat("ar-SA", {
        style: "currency",
        currency: "SAR",
    }).format(price);
};
</script>

<template>
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <i
                    class="pi pi-home text-teal-800"
                    style="font-size: 2.5rem"
                ></i>
                <div>
                    <h1 class="text-3xl font-semibold text-teal-800 m-0">
                        {{ property.title }}
                    </h1>
                    <p class="text-gray-500 text-sm m-0">
                        {{ property.city }} - {{ property.address }}
                    </p>
                </div>
            </div>
            <!-- <PropertyStatus
                :status="property.status"
                :status-options="statusOptions"
            /> -->
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Images, Map, and Tabs -->
            <div class="lg:col-span-2 space-y-6">
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
                                    :src="slotProps.item.itemImageSrc"
                                    :alt="slotProps.item.alt"
                                    class="w-full h-[400px] object-cover rounded-lg"
                                />
                            </template>
                            <template #thumbnail="slotProps">
                                <img
                                    :src="slotProps.item.itemImageSrc"
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
                        <h2 class="text-xl font-bold text-teal-800">الموقع</h2>
                    </template>
                    <template #content>
                        <p class="text-gray-500 text-sm">العنوان</p>
                        <p class="text-gray-700 mb-4">
                            {{ property.address }}, {{ property.city }}
                        </p>
                        <div
                            class="w-full h-[500px] bg-gray-200 rounded-lg overflow-hidden"
                        >
                            <iframe
                                class="w-full h-full"
                                :src="`https://www.openstreetmap.org/export/embed.html?bbox=${
                                    property.longitude - 0.005
                                }%2C${property.latitude - 0.005}%2C${
                                    property.longitude + 0.005
                                }%2C${
                                    property.latitude + 0.005
                                }&layer=mapnik&marker=${property.latitude}%2C${
                                    property.longitude
                                }`"
                                frameborder="0"
                            ></iframe>
                        </div>
                    </template>
                </Card>

                <!-- Rent and Investment Tabs -->
                <Card>
                    <template #content>
                        <Tabs value="0">
                            <TabList>
                                <Tab value="0">سجل الإيجار</Tab>
                                <Tab value="1">طلبات الاستثمار</Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel value="0">
                                    <p class="text-gray-500 m-0">
                                        سيتم إضافة سجل الإيجار قريبًا.
                                    </p>
                                </TabPanel>
                                <TabPanel value="1">
                                    <p class="text-gray-500 m-0">
                                        سيتم إضافة طلبات الاستثمار قريبًا.
                                    </p>
                                    <div
                                        v-if="
                                            property.investment_requests
                                                .length > 0
                                        "
                                        class="mt-4"
                                    >
                                        <p class="text-gray-700">
                                            عدد الطلبات:
                                            {{
                                                property.investment_requests
                                                    .length
                                            }}
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
                        <h2 class="text-xl font-bold text-teal-800">
                            معلومات المالك
                        </h2>
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
                        <div class="mt-4">
                            <p class="text-gray-500 text-sm">تاريخ التسجيل</p>
                            <p class="text-gray-700">
                                {{ formatDate(property.owner.created_at) }}
                            </p>
                        </div>
                    </template>
                </Card>

                <!-- Property Details -->
                <Card>
                    <template #title>
                        <h2 class="text-xl font-bold text-teal-800">
                            تفاصيل العقار
                        </h2>
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
                                <p class="text-lg font-semibold">
                                    {{ property.area }} م²
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">غرف النوم</p>
                                <p class="text-lg font-semibold">
                                    {{ property.bedrooms }} غرف
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">الحمامات</p>
                                <p class="text-lg font-semibold">
                                    {{ property.bathrooms }} حمامات
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">
                                    الإيجار اليومي
                                </p>
                                <p
                                    class="text-lg font-semibold text-emerald-600"
                                >
                                    {{
                                        property.daily_rent_price
                                            ? formatPrice(
                                                  property.daily_rent_price
                                              )
                                            : "لم يحدد بعد"
                                    }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">
                                    تاريخ الإضافة
                                </p>
                                <p class="text-lg font-semibold">
                                    {{ formatDate(property.created_at) }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-gray-500 text-sm">الوصف</p>
                            <p class="text-gray-700">
                                {{ property.description }}
                            </p>
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
                        <div v-else class="flex flex-wrap gap-2">
                            <Tag
                                v-for="amenity in property.amenities"
                                :key="amenity.id"
                                :value="amenity.name"
                                rounded
                                class="bg-gray-100 text-gray-700"
                            />
                        </div>
                    </template>
                </Card>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 mt-6">
            <Button
                label="تعديل العقار"
                icon="pi pi-pencil"
                class="p-button-outlined p-button-teal"
            />
            <Button
                label="رجوع"
                icon="pi pi-arrow-right"
                class="p-button-text p-button-teal"
                :href="route('admin.properties.index')"
            />
        </div>
    </div>
</template>

<style scoped>
:deep(.p-galleria .p-galleria-indicators) {
    background: rgba(0, 0, 0, 0.5);
    padding: 0.5rem;
    border-radius: 0 0 0.5rem 0.5rem;
}
:deep(.p-galleria .p-galleria-item-navigator) {
    background: rgba(0, 0, 0, 0.7);
    color: white;
}
:deep(.p-card) {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
:deep(.p-tabs-nav) {
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}
:deep(.p-tab) {
    font-weight: 500;
    color: #475569;
}
:deep(.p-tab.p-highlight) {
    color: #0f766e;
    border-bottom: 2px solid #0f766e;
}
</style>
