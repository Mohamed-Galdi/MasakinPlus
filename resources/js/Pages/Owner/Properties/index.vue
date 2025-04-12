<script setup>
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import Galleria from "primevue/galleria";
import Select from "primevue/select";

defineOptions({
    layout: OwnerLayout,
});

const props = defineProps({
    properties: {
        type: Array,
        required: true,
    },
});

const propertyList = ref(props.properties);

const types = [
    { name: "شقة", id: "1" },
    { name: "منزل", id: "2" },
    { name: "فيلا", id: "3" },
    { name: "استوديو", id: "4" },
    { name: "مكتب", id: "5" },
];

const statusOptions = [
    { name: "قيد المراجعة", id: "1" },
    { name: "معتمد", id: "2" },
    { name: "مرفوض", id: "3" },
    { name: "قيد المراجعة", id: "4" },
    { name: "معتمد", id: "5" },
];

// Format price with commas
const formatPrice = (price) => {
    return parseFloat(price).toLocaleString("ar-SA");
};

// Transform property images to Galleria format
const getGalleriaImages = (propertyImages) => {
    if (!propertyImages || propertyImages.length === 0) {
        return [
            {
                itemImageSrc: "/placeholder.svg?height=200&width=400",
                thumbnailImageSrc: "/placeholder.svg?height=50&width=100",
                alt: "Property Image Placeholder",
            },
        ];
    }

    return propertyImages.map((image) => ({
        itemImageSrc: `/${image.path}`,
        thumbnailImageSrc: `/${image.path}`,
        alt: "Property Image",
    }));
};
</script>

<template>
    <div class="container mx-auto px-1 py-1">
        <!-- Header -->
        <div
            class="flex flex-col md:flex-row justify-between items-end md:items-center mb-6"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-800">العقارات</h1>
                <p class="text-gray-600 mt-1">إدارة العقارات الخاصة بك</p>
            </div>
            <Link :href="route('owner.properties.create')" class="btn bg-emerald-600 hover:bg-emerald-700 text-white">
                <i class="pi pi-plus"></i>
                <span>إضافة عقار جديد</span>
            </Link>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex justify-end items-center gap-4">
                <!-- type -->
                <Select
                    v-model="selectedType"
                    :options="types"
                    optionLabel="name"
                    optionValue="id"
                    placeholder="اختر نوع العقار"
                    class="w-44"
                />
                <!-- status -->
                <Select
                    v-model="selectedStatus"
                    :options="statusOptions"
                    optionLabel="name"
                    optionValue="id"
                    placeholder="اختر حالة العقار"
                    class="w-44"
                />
                <i
                    class="pi pi-filter text-slate-400"
                    style="font-size: 1.2rem"
                ></i>
            </div>
        </div>

        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="property in propertyList"
                :key="property.id"
                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
            >
                <!-- Property Image Gallery -->
                <div class="relative">
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
                                    height: 280px;
                                    object-fit: cover;
                                    display: block;
                                "
                            />
                        </template>
                    </Galleria>

                    <div class="absolute top-3 right-3 z-10">
                        <span
                            :class="`px-2 py-1 rounded-md text-xs font-medium ${
                                property.status === 'approved'
                                    ? 'bg-emerald-100 text-emerald-800'
                                    : property.status === 'pending'
                                    ? 'bg-amber-100 text-amber-800'
                                    : 'bg-red-100 text-red-800'
                            }`"
                        >
                            {{
                                property.status === "approved"
                                    ? "معتمد"
                                    : property.status === "pending"
                                    ? "قيد المراجعة"
                                    : "مرفوض"
                            }}
                        </span>
                    </div>
                </div>

                <!-- Property Content -->
                <div class="p-4">
                    <h2 class="text-xl font-bold text-teal-800 mb-2">
                        {{ property.title }}
                    </h2>

                    <div class="flex items-center text-gray-600 mb-2">
                        <i class="pi pi-map-marker ml-1 text-gray-500"></i>
                        <span
                            >{{ property.city }} - {{ property.address }}</span
                        >
                    </div>

                    <div class="flex flex-wrap gap-4 mb-3">
                        <div class="flex items-center">
                            <i class="pi pi-home ml-1 text-gray-500"></i>
                            <span class="text-sm"
                                >{{ property.bedrooms }} غرف</span
                            >
                        </div>
                        <div class="flex items-center">
                            <i class="pi pi-inbox ml-1 text-gray-500"></i>
                            <span class="text-sm"
                                >{{ property.bathrooms }} حمامات</span
                            >
                        </div>
                        <div class="flex items-center">
                            <i class="pi pi-stop ml-1 text-gray-500"></i>
                            <span class="text-sm">{{ property.area }} م²</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        <div
                            v-for="amenity in property.amenities.slice(0, 3)"
                            :key="amenity.id"
                            class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full"
                        >
                            {{ amenity.name }}
                        </div>
                        <div
                            v-if="property.amenities.length > 3"
                            class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full"
                        >
                            +{{ property.amenities.length - 3 }}
                        </div>
                    </div>

                    <div
                        class="flex justify-between items-center border-t pt-3"
                    >
                        <div>
                            <p class="text-sm text-gray-500">الإيجار اليومي</p>
                            <p class="text-lg font-bold text-emerald-600">
                                {{ formatPrice(property.daily_rent_price) }}
                                ريال
                            </p>
                        </div>
                        <!-- Professional Action Buttons -->
                        <button
                            class="flex items-center justify-center gap-2 py-2 px-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-md transition-all shadow-sm hover:shadow"
                        >
                            <i class="pi pi-pencil text-gray-600"></i>
                            <span class="text-sm font-medium">تعديل</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State (shown when there are no properties) -->
        <div
            v-if="propertyList.length === 0"
            class="bg-white rounded-lg shadow-md p-8 text-center"
        >
            <i class="pi pi-home text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-medium text-gray-800 mb-2">
                لا توجد عقارات
            </h3>
            <p class="text-gray-600 mb-6">لم يتم العثور على أي عقارات</p>
            <Link :href="route('owner.properties.create')" 
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors"
            >
                إضافة عقار جديد
            </Link>
        </div>
    </div>
</template>
