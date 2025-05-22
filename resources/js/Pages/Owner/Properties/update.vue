<script setup>
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import { ref, watch } from "vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import { cities } from "@/plugins/cities";
import FileUpload from "@/Components/PrimeVilt/FileUpload.vue";
import PropertyMap from "@/Components/PropertyMap.vue";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Select from "primevue/select";
import InputNumber from "primevue/inputnumber";
import MultiSelect from "primevue/multiselect";
import Button from "primevue/button";
import Stepper from "primevue/stepper";
import StepList from "primevue/steplist";
import Step from "primevue/step";
import StepPanels from "primevue/steppanels";
import StepPanel from "primevue/steppanel";
import Header from "@/Components/Header.vue";

defineOptions({
    layout: OwnerLayout,
});

const props = defineProps({
    typeOptions: {
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

// For the stepper
const activeStep = ref(1);

const propertyAmenities = property.value.amenities.map((amenity) => {
    return { id: amenity.id, name: amenity.name };
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

watch(activeStep, (newStep) => {
    if (newStep === 3 && mapRef.value) {
        mapRef.value.resizeMap();
    }
});

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
            title="تعديل معلومات العقار "
            subtitle="بعد تعديل معلومات العقار ستتعير حالته الى غير جاهز و سيتعين عليك إعادة تقديم طلب الإستتمار او العرض"
        >
            <Link
                :href="route('owner.properties.index')"
                class="btn bg-slate-200 hover:bg-slate-100 text-slate-800 md:w-fit w-full mt-2 md:mt-0"
            >
            <span>العودة </span>
            <i class="pi pi-arrow-left"></i>
            </Link>
        </Header>

        <!-- Stepper -->
        <div class="mb-10">
            <Stepper
                v-model:value="activeStep"
                class="w-full sophisticated-stepper"
            >
                <StepList
                    class="bg-slate-200 p-4 rounded-md min-h-[6rem] overflow-hidden"
                >
                    <!-- Step 1 -->
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="1"
                    >
                        <div
                            class="flex flex-row flex-auto gap-2 justify-center"
                            v-bind="a11yAttrs.root"
                        >
                            <button
                                class="bg-transparent border-0 inline-flex flex-col gap-2 step-button"
                                @click="activateCallback"
                                v-bind="a11yAttrs.header"
                            >
                                <span
                                    :class="[
                                        'step-icon',
                                        {
                                            'step-active': value <= activeStep,
                                            'step-inactive': value > activeStep,
                                        },
                                    ]"
                                >
                                    <i class="pi pi-home"></i>
                                </span>
                                <span
                                    class="step-label"
                                    :class="{
                                        'text-emerald-700 font-medium':
                                            value <= activeStep,
                                    }"
                                    >المعلومات</span
                                >
                            </button>
                        </div>
                    </Step>

                    <!-- Step 2 -->
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="2"
                    >
                        <div
                            class="flex flex-row flex-auto gap-2 px-2 justify-center"
                            v-bind="a11yAttrs.root"
                        >
                            <button
                                class="bg-transparent border-0 inline-flex flex-col gap-2 step-button"
                                @click="activateCallback"
                                v-bind="a11yAttrs.header"
                            >
                                <span
                                    :class="[
                                        'step-icon',
                                        {
                                            'step-active': value <= activeStep,
                                            'step-inactive': value > activeStep,
                                        },
                                    ]"
                                >
                                    <i class="pi pi-list"></i>
                                </span>
                                <span
                                    class="step-label"
                                    :class="{
                                        'text-emerald-700 font-medium':
                                            value <= activeStep,
                                    }"
                                    >المواصفات</span
                                >
                            </button>
                        </div>
                    </Step>

                    <!-- Step 3 -->
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="3"
                    >
                        <div
                            class="flex flex-row flex-auto gap-2 px-2 justify-center"
                            v-bind="a11yAttrs.root"
                        >
                            <button
                                class="bg-transparent border-0 inline-flex flex-col gap-2 step-button"
                                @click="activateCallback"
                                v-bind="a11yAttrs.header"
                            >
                                <span
                                    :class="[
                                        'step-icon',
                                        {
                                            'step-active': value <= activeStep,
                                            'step-inactive': value > activeStep,
                                        },
                                    ]"
                                >
                                    <i class="pi pi-map-marker"></i>
                                </span>
                                <span
                                    class="step-label"
                                    :class="{
                                        'text-emerald-700 font-medium':
                                            value <= activeStep,
                                    }"
                                    >الموقع</span
                                >
                            </button>
                        </div>
                    </Step>

                    <!-- Step 4 -->
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="4"
                    >
                        <div
                            class="flex flex-row flex-auto gap-2 px-2 justify-center"
                            v-bind="a11yAttrs.root"
                        >
                            <button
                                class="bg-transparent border-0 inline-flex flex-col gap-2 step-button"
                                @click="activateCallback"
                                v-bind="a11yAttrs.header"
                            >
                                <span
                                    :class="[
                                        'step-icon',
                                        {
                                            'step-active': value <= activeStep,
                                            'step-inactive': value > activeStep,
                                        },
                                    ]"
                                >
                                    <i class="pi pi-images"></i>
                                </span>
                                <span
                                    class="step-label"
                                    :class="{
                                        'text-emerald-700 font-medium':
                                            value <= activeStep,
                                    }"
                                >
                                    الصور</span
                                >
                            </button>
                        </div>
                    </Step>
                </StepList>

                <StepPanels>
                    <!-- Step 1  (Informations) -->
                    <StepPanel
                        v-slot="{ activateCallback }"
                        :value="1"
                        class=""
                    >
                        <div class="bg-slate-200 p-4 rounded-md min-h-[24rem]">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="form-label"
                                        >عنوان العقار</label
                                    >
                                    <InputText
                                        v-model="propertyForm.title"
                                        class="w-full"
                                        placeholder="مثال: فيلا فاخرة في الرياض"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label class="form-label">نوع العقار</label>
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

                            <div class="space-y-2 mt-6">
                                <label class="form-label">وصف العقار</label>
                                <Textarea
                                    v-model="propertyForm.description"
                                    rows="4"
                                    class="w-full"
                                    placeholder="اكتب وصفاً تفصيلياً للعقار..."
                                />
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6"
                            >
                                <div class="space-y-2">
                                    <label class="form-label">المدينة</label>
                                    <Select
                                        filter
                                        empty-filter-message=" لا توجد مدينة مطابقة "
                                        v-model="propertyForm.city"
                                        :options="cities"
                                        placeholder="اختر المدينة"
                                        class="w-full"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label class="form-label"
                                        >العنوان التفصيلي</label
                                    >
                                    <InputText
                                        v-model="propertyForm.address"
                                        class="w-full"
                                        placeholder="مثال: حي الملقا، شارع الملك فهد"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <Button
                                label="التالي"
                                icon="pi pi-arrow-left"
                                @click="activateCallback(2)"
                            />
                        </div>
                    </StepPanel>

                    <!-- Step 2  (Property Details) -->
                    <StepPanel v-slot="{ activateCallback }" :value="2">
                        <div class="bg-slate-200 p-4 rounded-md min-h-[24rem]">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="form-label"
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
                                    <label class="form-label"
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
                                    <label class="form-label"
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

                            <div class="space-y-2 mt-6">
                                <label class="form-label"
                                    >المرافق المتوفرة</label
                                >
                                <MultiSelect
                                    v-model="propertyForm.amenities"
                                    :options="availableAmenities"
                                    optionLabel="name"
                                    placeholder="اختر المرافق المتوفرة"
                                    class="w-full"
                                />
                            </div>
                        </div>
                        <div class="flex justify-between pt-4">
                            <Button
                                label="السابق"
                                icon="pi pi-arrow-right"
                                @click="activateCallback(1)"
                                icon-pos="right"
                            />
                            <Button
                                label="التالي"
                                icon="pi pi-arrow-left"
                                @click="activateCallback(3)"
                            />
                        </div>
                    </StepPanel>

                    <!-- Step 3 (Map) -->
                    <StepPanel v-slot="{ activateCallback }" :value="3">
                        <div class="bg-slate-200 p-4 rounded-md min-h-[24rem]">
                            <PropertyMap
                                ref="mapRef"
                                @update:coordinates="handleCoordinatesUpdate"
                                :initialLat="property.latitude"
                                :initialLng="property.longitude"
                            />
                        </div>
                        <div class="flex justify-between pt-4">
                            <Button
                                label="السابق"
                                icon="pi pi-arrow-right"
                                @click="activateCallback(2)"
                                icon-pos="right"
                            />
                            <Button
                                label="التالي"
                                icon="pi pi-arrow-left"
                                @click="activateCallback(4)"
                            />
                        </div>
                    </StepPanel>

                    <!-- Step 4 (Images) -->
                    <StepPanel v-slot="{ activateCallback }" :value="4">
                        <div class="bg-slate-200 p-4 rounded-md min-h-[24rem]">
                            <div class="space-y-2 mt-6">
                                <label class="form-label">صور العقار</label>
                                <FileUpload
                                    @file-uploaded="handleFileUploaded"
                                    @file-reverted="handleFileReverted"
                                    @file-removed="handleFileRemoved"
                                    :initialFiles="propertyImages"
                                    :allowMultiple="true"
                                    :maxFiles="5"
                                    :maxFileSize="5 * 1024 * 1024"
                                />
                            </div>

                            <div class="info-panel mt-6">
                                <h3 class="info-panel-title">
                                    نصائح لصور أفضل
                                </h3>
                                <ul
                                    class="list-disc list-inside space-y-1 text-sm text-gray-600 font-BeinNormal"
                                >
                                    <li>
                                        الحد الأقصى لعدد الصور هو<span
                                            class="font-bold"
                                        >
                                            5 صور</span
                                        >، ولا يجب أن يتجاوز حجم كل صورة<span
                                            class="font-bold"
                                        >
                                            5 ميغابايت</span
                                        >
                                    </li>
                                    <li>
                                        استخدم صوراً بجودة عالية وإضاءة جيدة
                                    </li>
                                    <li>التقط صوراً لجميع الغرف الرئيسية</li>
                                    <li>
                                        أضف صوراً للمناطق الخارجية والإطلالة
                                    </li>
                                    <li>
                                        تجنب الصور المزدحمة بالأغراض الشخصية
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="flex justify-between pt-4">
                            <Button
                                label="السابق"
                                icon="pi pi-arrow-right"
                                @click="activateCallback(3)"
                                iconPos="right"
                            />
                            <Button
                                label="تعديل العقار"
                                icon="pi pi-check"
                                severity="success"
                                @click="submitUpdateProperty"
                                :loading="propertyForm.processing"
                            />
                        </div>
                    </StepPanel>
                </StepPanels>
            </Stepper>
        </div>
    </div>
</template>

<style scoped>
/* Step Icons */
.step-icon {
    @apply rounded-full border-2 w-14 h-14 inline-flex items-center justify-center transition-all duration-300 shadow-sm;
}

.step-active {
    @apply bg-emerald-600 text-white border-emerald-600;
    box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.15);
}

.step-inactive {
    @apply border-gray-300 text-gray-400 bg-white;
}

/* Step Labels */
.step-label {
    @apply text-sm transition-all duration-300;
    white-space: nowrap;
}

.info-panel {
    @apply p-5 bg-gray-50 rounded-lg border border-gray-200 shadow-sm;
}

.tip-panel {
    @apply bg-emerald-50 border border-emerald-200 rounded-lg p-4 shadow-sm;
}
</style>
