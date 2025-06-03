<script setup>
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import { ref } from "vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import { cities } from "@/plugins/cities";
import FileUpload from "@/Components/PrimeVilt/FileUpload.vue";
import PropertyStatus from "@/Components/PropertyStatus.vue";
import PropertyMap from "@/Components/PropertyMap.vue";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Select from "primevue/select";
import InputNumber from "primevue/inputnumber";
import MultiSelect from "primevue/multiselect";
import Button from "primevue/button";
import Header from "@/Components/Header.vue";

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

const toast = useToast();

const property = ref(props.property);
const propertyTypes = ref(props.typeOptions);
const availableAmenities = ref(props.amenities);

const propertyAmenities = property.value.amenities.map((amenity) => {
    return { id: amenity.id, name: amenity.name, icon: amenity.icon };
});

const propertyImages = property.value.images.map((image) => {
    return "/" + image.path;
});

const propertyForm = useForm({
    title: property.value.title,
    description: property.value.description,
    type: property.value.type,
    city: property.value.city,
    address: property.value.address,
    area: property.value.area,
    bedrooms: property.value.bedrooms,
    bathrooms: property.value.bathrooms,
    latitude: property.value.latitude,
    longitude: property.value.longitude,
    amenities: propertyAmenities,
    images: propertyImages, // Existing images
    newImages: [], // New images added during update
    removedImages: [], // Images removed during update
});


// ############################################## File upload
// Track new and removed images
const tempFile = ref([...propertyImages]); // Initialize with existing images

function handleFileUploaded(fileFolder) {
    tempFile.value.push(fileFolder);
    propertyForm.newImages.push(fileFolder);
    propertyForm.images = tempFile.value;
}

function handleFileRemoved(imagePath) {
    tempFile.value = tempFile.value.filter(
        (filePath) => filePath !== imagePath
    );
    propertyForm.removedImages.push(imagePath);
    propertyForm.images = tempFile.value;
}

function handleFileReverted(uniqueId) {
    tempFile.value = tempFile.value.filter(
        (filePath) => !filePath.includes(uniqueId)
    );
    propertyForm.newImages = propertyForm.newImages.filter(
        (filePath) => !filePath.includes(uniqueId)
    );
    propertyForm.images = tempFile.value;
}

// ############################################## Map
const mapRef = ref(null);

const handleCoordinatesUpdate = (coords) => {
    propertyForm.latitude = coords.lat;
    propertyForm.longitude = coords.lng;
};

// ############################################# Update
function submitUpdateProperty() {
    propertyForm.put(
        route("owner.properties.update", {
            property: property.value.id,
        }),
        {
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "عملية ناجحة",
                    detail: "تم تحديث العقار بنجاح",
                    life: 3000,
                });
                router.get(route("owner.properties.index"));
            },
            onError: () => {
                const errorMessage = Object.values(propertyForm.errors)[0];
                toast.add({
                    severity: "error",
                    summary: "خطأ",
                    detail: errorMessage,
                    life: 3000,
                });
            },
        }
    );
}
</script>

<template>
    <div class="container mx-auto" dir="rtl">
        <Toast position="top-center" />

        <!-- Header -->
        <Header
            icon="fa-solid fa-building"
            title="تعديل معلومات العقار"
            subtitle=" بعد تعديل معلومات العقار ستتغير حالته إلى غير جاهز"
        >
            <Link
                :href="route('owner.properties.index')"
                class="btn bg-slate-200 hover:bg-slate-100 text-slate-800 md:w-fit w-full mt-2 md:mt-0"
            >
                <span>العودة</span>
                <i class="pi pi-arrow-left"></i>
            </Link>
        </Header>

        <!-- Property Status Info -->
        <div class="mb-6">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i
                        class="pi pi-info-circle text-amber-600 text-lg ml-3"
                    ></i>
                    <div>
                        <h4 class="font-medium text-amber-800 mb-1">
                            تنبيه مهم
                        </h4>
                        <p class="text-sm text-amber-700">
                            تعديل معلومات العقار سيؤدي إلى إعادة تعيين حالته
                            وسيحتاج إلى مراجعة جديدة من قبل الإدارة
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <!-- Basic Information Section -->
            <div class="mb-8">
                <h3
                    class="text-lg font-bold mb-4 text-gray-800 border-b border-gray-200 pb-2"
                >
                    <i class="pi pi-info-circle ml-2"></i>
                    المعلومات الأساسية
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700"
                            >عنوان العقار</label
                        >
                        <InputText
                            v-model="propertyForm.title"
                            class="w-full"
                            placeholder="مثال: فيلا فاخرة في الرياض"
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700"
                            >نوع العقار</label
                        >
                        <Select
                            v-model="propertyForm.type"
                            :options="propertyTypes"
                            optionLabel="label"
                            optionValue="value"
                            placeholder="اختر نوع العقار"
                            class="w-full"
                        />
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700"
                        >وصف العقار</label
                    >
                    <Textarea
                        v-model="propertyForm.description"
                        rows="4"
                        class="w-full"
                        placeholder="اكتب وصفاً تفصيلياً للعقار..."
                    />
                </div>
            </div>

            <!-- Location Section -->
            <div class="mb-8">
                <h3
                    class="text-lg font-bold mb-4 text-gray-800 border-b border-gray-200 pb-2"
                >
                    <i class="pi pi-map-marker ml-2"></i>
                    معلومات الموقع
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700"
                            >المدينة</label
                        >
                        <Select
                            filter
                            empty-filter-message="لا توجد مدينة مطابقة"
                            v-model="propertyForm.city"
                            :options="cities"
                            placeholder="اختر المدينة"
                            class="w-full"
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700"
                            >العنوان التفصيلي</label
                        >
                        <InputText
                            v-model="propertyForm.address"
                            class="w-full"
                            placeholder="مثال: حي الملقا، شارع الملك فهد"
                        />
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700"
                        >تحديد الموقع على الخريطة</label
                    >
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <PropertyMap
                            ref="mapRef"
                            @update:coordinates="handleCoordinatesUpdate"
                            :initialLat="property.latitude"
                            :initialLng="property.longitude"
                        />
                    </div>
                </div>
            </div>

            <!-- Property Details Section -->
            <div class="mb-8">
                <h3
                    class="text-lg font-bold mb-4 text-gray-800 border-b border-gray-200 pb-2"
                >
                    <i class="pi pi-list ml-2"></i>
                    مواصفات العقار
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700"
                            >المساحة (م²)</label
                        >
                        <InputNumber
                            v-model="propertyForm.area"
                            class="w-full"
                            placeholder="مثال: 150"
                            :min="0"
                            :step="0.5"
                            suffix=" م²"
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700"
                            >عدد غرف النوم</label
                        >
                        <InputNumber
                            v-model="propertyForm.bedrooms"
                            class="w-full"
                            placeholder="مثال: 3"
                            :min="0"
                            :step="1"
                        />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700"
                            >عدد الحمامات</label
                        >
                        <InputNumber
                            v-model="propertyForm.bathrooms"
                            class="w-full"
                            placeholder="مثال: 2"
                            :min="0"
                            :step="1"
                        />
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700"
                        >المرافق المتوفرة</label
                    >
                    <MultiSelect
                        v-model="propertyForm.amenities"
                        :options="availableAmenities"
                        optionLabel="name"
                        placeholder="اختر المرافق المتوفرة"
                        class="w-full"
                    >
                        <template #option="slotProps">
                            <div
                                class="flex items-center gap-2 font-BeinNormal"
                            >
                                <Icon
                                    :icon="slotProps.option.icon"
                                    class="w-[0.9rem] h-[0.9rem] text-slate-700"
                                />
                                <p>{{ slotProps.option.name }}</p>
                            </div>
                        </template>
                    </MultiSelect>
                </div>
            </div>

            <!-- Images Section -->
            <div class="mb-8">
                <h3
                    class="text-lg font-bold mb-4 text-gray-800 border-b border-gray-200 pb-2"
                >
                    <i class="pi pi-images ml-2"></i>
                    صور العقار
                </h3>

                <div class="space-y-4">
                    <FileUpload
                        @file-uploaded="handleFileUploaded"
                        @file-reverted="handleFileReverted"
                        @file-removed="handleFileRemoved"
                        :initialFiles="propertyImages"
                        :allowMultiple="true"
                        :maxFiles="5"
                        :maxFileSize="5 * 1024 * 1024"
                    />

                    <div class="info-panel">
                        <h4 class="font-medium text-gray-800 mb-2">
                            نصائح لصور أفضل
                        </h4>
                        <ul
                            class="list-disc list-inside space-y-1 text-sm text-gray-600"
                        >
                            <li>
                                الحد الأقصى لعدد الصور هو
                                <span class="font-bold">5 صور</span>، ولا يجب أن
                                يتجاوز حجم كل صورة
                                <span class="font-bold">5 ميغابايت</span>
                            </li>
                            <li>استخدم صوراً بجودة عالية وإضاءة جيدة</li>
                            <li>التقط صوراً لجميع الغرف الرئيسية</li>
                            <li>أضف صوراً للمناطق الخارجية والإطلالة</li>
                            <li>تجنب الصور المزدحمة بالأغراض الشخصية</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Current Property Status Display -->
            <div class="mb-8">
                <h3
                    class="text-lg font-bold mb-4 text-gray-800 border-b border-gray-200 pb-2"
                >
                    <i class="pi pi-flag ml-2"></i>
                    الحالة الحالية للعقار
                </h3>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <PropertyStatus
                                :status="property.status"
                                :status-options="statusOptions"
                            />
                        </div>
                        <div class="text-xs text-gray-500">
                            سيتم إعادة تعيين الحالة بعد التعديل
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-6 border-t border-gray-200">
                <Button
                    label="حفظ التعديلات"
                    severity="success"
                    size="large"
                    @click="submitUpdateProperty"
                    :loading="propertyForm.processing"
                    class="px-8 py-3 w-full"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>
.info-panel {
    @apply p-4 bg-gray-50 rounded-lg border border-gray-200;
}

/* Form section spacing */
.space-y-2 > * + * {
    margin-top: 0.5rem;
}

.space-y-4 > * + * {
    margin-top: 1rem;
}

/* Custom label styling */
label {
    font-weight: 500;
    color: #374151;
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
</style>
