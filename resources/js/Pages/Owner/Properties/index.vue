<script setup>
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import { ref, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { debounce } from "lodash";
import Select from "primevue/select";
import Galleria from "primevue/galleria";
import PropertyStatus from "@/Components/PropertyStatus.vue";
import { useTextHelpers } from "@/plugins/textHelpers";
import Header from "@/Components/Header.vue";
import Button from "primevue/button";

defineOptions({
    layout: OwnerLayout,
});

const props = defineProps({
    properties: {
        type: Object,
        required: true,
    },
    typeOptions: {
        type: Array,
        required: true,
    },
    statusOptions: {
        type: Array,
        required: true,
    },
    typeFilter: {
        type: String,
        required: false,
        default: "",
    },
    statusFilter: {
        type: String,
        required: false,
        default: "",
    },
});

const propertyList = ref(props.properties.data);

// ############################################## Search and filter

const typeFilter = ref(props.typeFilter);
const statusFilter = ref(props.statusFilter);

const typesOptions = ref(props.typeOptions);
const statusOptions = ref(props.statusOptions);

watch(
    [typeFilter, statusFilter],
    debounce(([typeFilter, statusFilter]) => {
        // Update the table with both filters
        router.get(
            route("owner.properties.index"),
            {
                typeFilter: typeFilter,
                statusFilter: statusFilter,
            },
            {
                preserveState: false,
                preserveScroll: true,
            }
        );
    }, 300)
);

// Clear filters function
const clearFilters = () => {
    typeFilter.value = "";
    statusFilter.value = "";
};

// Check if any filter is active
const isFilterActive = () => {
    return typeFilter.value !== "" || statusFilter.value !== "";
};

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

const textHelpers = useTextHelpers();
</script>

<template>
    <div class="container mx-auto">
        <!-- Header -->
        <Header
            icon="fa-solid fa-building"
            title="العقارات "
            subtitle="إدارة العقارات الخاصة بك"
        >
            <Link
                :href="route('owner.properties.create')"
                class="btn bg-slate-200 hover:bg-slate-100 text-slate-800 md:w-fit w-full mt-2 md:mt-0"
            >
                <i class="pi pi-plus"></i>
                <span>إضافة عقار جديد</span>
            </Link>
        </Header>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex md:flex-row flex-col items-center gap-4">
                <!-- type -->
                <Select
                    v-model="typeFilter"
                    :options="typesOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="اختر نوع العقار"
                    class="md:w-56 w-full"
                />
                <!-- status -->
                <Select
                    v-model="statusFilter"
                    :options="statusOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="اختر حالة العقار"
                    class="md:w-56 w-full"
                />
                <!-- Clear Filter Button -->
                <div class="flex-none">
                    <Button
                        icon="pi pi-filter-slash"
                        outlined
                        rounded
                        severity="secondary"
                        @click="clearFilters"
                    />
                </div>
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
                        <PropertyStatus
                            :status="property.status"
                            :status-options="statusOptions"
                        />
                    </div>
                </div>

                <!-- Property Content -->
                <div class="p-4">
                    <div class="flex justify-between">
                        <h2 class="text-xl font-bold text-teal-800 mb-2">
                            {{ textHelpers.limitText(property.title, 30) }}
                        </h2>
                        <p
                            class="bg-slate-800 text-white text-xs px-2 py-1 rounded-full font-sans h-fit"
                        >
                            {{ property.type }}
                        </p>
                    </div>

                    <div class="flex items-center text-gray-600 mb-2">
                        <i class="pi pi-map-marker ml-1 text-gray-500"></i>
                        <span
                            >{{ property.city }} -
                            {{
                                textHelpers.limitText(property.address, 40)
                            }}</span
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
                        <template v-if="property.amenities.length < 1">
                            <div
                                class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full"
                            >
                                <p>لا توجد مرافقات</p>
                            </div>
                        </template>
                        <template v-else>
                            <div
                                v-for="amenity in property.amenities.slice(
                                    0,
                                    3
                                )"
                                :key="amenity.id"
                                class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full"
                            >
                                {{ amenity.name }}
                            </div>
                            <div
                                v-if="property.amenities.length > 3"
                                class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full cursor-pointer"
                                @click="showAllAmenities"
                            >
                                +{{ property.amenities.length - 3 }}
                            </div>
                        </template>
                    </div>

                    <div
                        class="flex justify-between items-center border-t pt-3"
                    >
                        <div>
                            <p class="text-sm text-gray-500">إجار الليلة</p>
                            <p class="text-lg font-bold text-emerald-600">
                                <template
                                    v-if="property.nightly_rent === null"
                                >
                                    لم يحدد بعد
                                </template>
                                <template v-else>
                                    {{ formatPrice(property.nightly_rent) }}
                                    ريال
                                </template>
                            </p>
                        </div>
                        <!--  Edit Button -->
                        <div class="flex items-center justify-center gap-2">
                            <Link v-if="property.status === 'draft'"
                                :href="route('owner.properties.edit', property.id)"
                                class="flex items-center justify-center gap-2 py-2 px-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-md transition-all shadow-sm hover:shadow"
                            >
                                <i class="pi pi-pencil text-gray-600"></i>
                                <span class="text-sm font-medium">تعديل</span>
                            </Link>
                            <!--  Show Button -->
                            <Link
                                :href="route('owner.properties.show', property.id)"
                                class="flex items-center justify-center gap-2 py-2 px-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-md transition-all shadow-sm hover:shadow"
                            >
                                <i class="pi pi-eye text-gray-600"></i>
                                <span class="text-sm font-medium">عرض</span>
                            </Link>
                        </div>
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
            <Link
                :href="route('owner.properties.create')"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors"
            >
                إضافة عقار جديد
            </Link>
        </div>

        <!-- Pagination -->
        <div
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
                                    index === props.properties.links.length - 1,
                            }"
                        />
                        <p
                            v-else
                            v-html="link.label"
                            class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-slate-200 border border-gray-300"
                            :class="{
                                'rounded-l-lg': index === 0,
                                'rounded-r-lg':
                                    index === props.properties.links.length - 1,
                            }"
                        />
                    </template>
                </div>
            </nav>
        </div>
    </div>
</template>
