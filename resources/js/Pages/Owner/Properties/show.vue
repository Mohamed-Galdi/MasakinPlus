<script setup>
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import PropertyStatus from "@/Components/PropertyStatus.vue";
import PropertyMapView from "@/Components/PropertyMapView.vue";
import Header from "@/Components/Header.vue";
import Button from "primevue/button";
import Card from "primevue/card";
import Galleria from "primevue/galleria";

defineOptions({
    layout: OwnerLayout,
});

const props = defineProps({
    typeOptions: {
        type: Array,
        required: true,
    },
    statusOptions: {
        type: Array,
        required: true,
    },
    amenities: {
        type: Array,
        required: true,
    },
    property: {
        type: Object,
        required: true,
    },
});

const property = ref(props.property);

// Get property type label
const propertyTypeLabel = computed(() => {
    const typeOption = props.typeOptions.find(
        (type) => type.value === property.value.type
    );
    return typeOption ? typeOption.label : property.value.type;
});

// Format images for Galleria
const getGalleriaImages = (images) => {
    if (!images || images.length === 0) return [];
    return images.map((image, index) => ({
        itemImageSrc: `/${image.path}`,
        alt: `صورة ${property.value.title} - ${index + 1}`,
    }));
};

// Property amenities
const propertyAmenities = computed(() => {
    return property.value.amenities || [];
});
</script>

<template>
    <div class="container mx-auto" dir="rtl">
        <!-- Header -->
        <Header
            icon="fa-solid fa-building"
            title="عرض تفاصيل العقار"
            :subtitle="property.title"
        >
            <div class="flex gap-2 flex-wrap">
                <Link
                    :href="route('owner.properties.index')"
                    class="btn bg-slate-200 hover:bg-slate-100 text-slate-800 md:w-fit w-full"
                >
                    <span>العودة </span>
                    <i class="pi pi-arrow-left mr-2"></i>
                </Link>
            </div>
        </Header>

        <!-- Images and Key Info Section -->
        <div class="mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Property Images -->
                <div
                    v-if="property.images && property.images.length > 0"
                    class="col-span-2"
                >
                    <h3
                        class="text-lg font-bold mb-4 text-gray-800 border-b border-gray-200 pb-2"
                    >
                        <i class="pi pi-images ml-2"></i>
                        صور العقار
                    </h3>
                    <div
                        class="bg-white rounded-lg border border-gray-200 overflow-hidden relative"
                    >
                        <div
                            class="absolute top-4 flex justify-between px-4 w-full z-50"
                        >
                            <div
                                class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full"
                            >
                                {{ propertyTypeLabel }}
                            </div>
                            <PropertyStatus
                                :status="property.status"
                                :status-options="statusOptions"
                            />
                        </div>
                        <Galleria
                            :value="getGalleriaImages(property.images)"
                            :circular="true"
                            :showItemNavigators="true"
                            :showThumbnails="false"
                            :showItemNavigatorsOnHover="true"
                            :showIndicators="true"
                            :showIndicatorsOnItem="true"
                        >
                            <template #item="slotProps">
                                <img
                                    :src="slotProps.item.itemImageSrc"
                                    :alt="slotProps.item.alt"
                                    style="
                                        width: 100%;
                                        height: 400px;
                                        object-fit: cover;
                                        display: block;
                                    "
                                />
                            </template>
                        </Galleria>
                    </div>
                </div>

                <!-- Property Key Information -->
                <div>
                    <div
                        class="text-lg font-bold mb-4 text-gray-800 border-b border-gray-200 pb-2 flex justify-between"
                    >
                        <h3 class="">
                            <i class="pi pi-info-circle"></i>
                            المعلومات الرئيسية
                        </h3>
                        <!-- <Link
                            :href="route('owner.properties.edit', property.id)"
                        >
                            <Button
                                label="تعديل العقار"
                                icon="pi pi-pencil"
                                class="md:w-fit w-full"
                                size="small"
                                severity="success"
                            />
                        </Link> -->
                    </div>

                    <div class="space-y-2">
                        <!-- Property Specifications -->
                        <Card class="">
                            <template #content>
                                <div class="grid grid-cols-3 gap-4">
                                    <div
                                        class="text-center p-3 bg-gray-50 rounded-lg"
                                    >
                                        <Icon
                                            icon="fa-solid fa-up-right-and-down-left-from-center"
                                            class="ml-1 w-[0.9rem] h-[0.9rem] text-slate-500"
                                        />

                                        <p class="text-xs text-gray-600 mb-1">
                                            المساحة
                                        </p>
                                        <p
                                            class="text-sm font-bold text-gray-900"
                                        >
                                            {{
                                                property.area
                                                    ? `${property.area} م²`
                                                    : "غير محدد"
                                            }}
                                        </p>
                                    </div>

                                    <div
                                        class="text-center p-3 bg-gray-50 rounded-lg"
                                    >
                                        <Icon
                                            icon="fa-solid fa-bed"
                                            class="ml-1 w-[1rem] h-[1rem] text-slate-500"
                                        />
                                        <p class="text-xs text-gray-600 mb-1">
                                            غرف النوم
                                        </p>
                                        <p
                                            class="text-sm font-bold text-gray-900"
                                        >
                                            {{
                                                property.bedrooms ?? "غير محدد"
                                            }}
                                        </p>
                                    </div>

                                    <div
                                        class="text-center p-3 bg-gray-50 rounded-lg"
                                    >
                                        <Icon
                                            icon="fa-solid fa-shower"
                                            class="ml-1 w-[1rem] h-[1rem] text-slate-500"
                                        />

                                        <p class="text-xs text-gray-600 mb-1">
                                            الحمامات
                                        </p>
                                        <p
                                            class="text-sm font-bold text-gray-900"
                                        >
                                            {{
                                                property.bathrooms ?? "غير محدد"
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </Card>

                        <!-- Amenities Section -->
                        <div v-if="propertyAmenities.length > 0" class="">
                            <Card>
                                <template #title>
                                    <div class="flex items-center">
                                        <i
                                            class="pi pi-star text-emerald-600 ml-2"
                                        ></i>
                                        المرافق المتوفرة
                                    </div>
                                </template>
                                <template #content>
                                    <div class="flex flex-wrap gap-2">
                                        <div
                                            v-for="amenity in propertyAmenities"
                                            :key="amenity.id"
                                            class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full cursor-pointer flex gap-1 items-center"
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

                        <!-- Property Statistics -->
                        <Card>
                            <template #title>
                                <div class="flex items-center">
                                    <i
                                        class="pi pi-chart-bar text-emerald-600 ml-2"
                                    ></i>
                                    إحصائيات العقار
                                </div>
                            </template>
                            <template #content>
                                <div class="space-y-3">
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span class="text-sm text-gray-600"
                                            >عدد المشاهدات</span
                                        >
                                        <span class="font-bold text-gray-900">{{
                                            property.views_count || 0
                                        }}</span>
                                    </div>

                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <span class="text-sm text-gray-600"
                                            >تاريخ آخر تحديث</span
                                        >
                                        <span
                                            class="font-bold text-gray-900 text-sm"
                                        >
                                            {{
                                                new Date(
                                                    property.updated_at
                                                ).toLocaleDateString("ar-SA")
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="mb-8">
            <Card>
                <template #title>
                    <div class="flex items-center">
                        <i class="pi pi-file-text text-emerald-600 ml-2"></i>
                        وصف العقار
                    </div>
                </template>
                <template #content>
                    <p class="text-gray-900 leading-relaxed">
                        {{ property.description || "لا يوجد وصف متاح للعقار" }}
                    </p>
                </template>
            </Card>
        </div>

        <!-- Location Information -->
        <Card>
            <template #title>
                <div class="flex items-center">
                    <i class="pi pi-map-marker text-emerald-600 ml-2"></i>
                    معلومات الموقع
                </div>
            </template>
            <template #content>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-600 mb-1"
                                >المدينة</label
                            >
                            <p class="text-gray-900 font-medium">
                                {{ property.city || "غير محدد" }}
                            </p>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-600 mb-1"
                                >العنوان التفصيلي</label
                            >
                            <p class="text-gray-900">
                                {{ property.address || "غير محدد" }}
                            </p>
                        </div>
                    </div>

                    <!-- Map -->
                    <div v-if="property.latitude && property.longitude">
                        <label
                            class="block text-sm font-medium text-gray-600 mb-2"
                            >الموقع على الخريطة</label
                        >
                        <div
                            class="bg-gray-50 p-4 rounded-lg border border-gray-200"
                        >
                            <PropertyMapView
                                :latitude="property.latitude"
                                :longitude="property.longitude"
                            />
                        </div>
                    </div>
                </div>
            </template>
        </Card>
    </div>
</template>

<style scoped>
/* Custom spacing */
.space-y-3 > * + * {
    margin-top: 0.75rem;
}

.space-y-4 > * + * {
    margin-top: 1rem;
}

/* Card title styling */
:deep(.p-card-title) {
    font-size: 1.1rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
}

/* Button styling */
:deep(.p-button) {
    justify-content: center;
}

/* Galleria styling */
:deep(.p-galleria) {
    border-radius: 0.5rem;
}

:deep(.p-galleria-item-wrapper) {
    border-radius: 0.5rem;
}

/* Section headers */
h3 {
    display: flex;
    align-items: center;
}

h3 i {
    margin-left: 0.5rem;
    color: #059669;
}

/* Label styling */
label {
    font-weight: 500;
    color: #6b7280;
}
</style>
