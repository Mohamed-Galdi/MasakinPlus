<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
// Import the new Stepper components
import Stepper from "primevue/stepper";
import StepList from "primevue/steplist";
import Step from "primevue/step";
import StepPanels from "primevue/steppanels";
import StepPanel from "primevue/steppanel";
import Divider from "primevue/divider";

// Other imports remain the same...
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Dropdown from "primevue/dropdown";
import InputNumber from "primevue/inputnumber";
import Checkbox from "primevue/checkbox";
import MultiSelect from "primevue/multiselect";
import FileUpload from "primevue/fileupload";
import Button from "primevue/button";

defineOptions({
    layout: OwnerLayout,
});

// For the stepper
const activeStep = ref(1);
const steps = [
    { label: "المعلومات الأساسية", icon: "pi pi-home", value: 1 },
    { label: "المواصفات", icon: "pi pi-list", value: 2 },
    { label: "المرافق والصور", icon: "pi pi-images", value: 3 },
    { label: "التسعير", icon: "pi pi-money-bill", value: 4 },
];

// Form model (empty for now as requested)
const property = ref({
    title: "",
    description: "",
    type: null,
    city: null,
    address: "",
    area: null,
    bedrooms: null,
    bathrooms: null,
    is_furnished: false,
    daily_rent_price: null,
    monthly_rent_price: null,
    amenities: [],
    images: [],
});

// Sample data (as before)
const availableAmenities = ref([
    { name: "واي فاي", code: "1" },
    { name: "موقف سيارات", code: "2" },
    { name: "مكيفات", code: "3" },
    { name: "مسبح", code: "4" },
    { name: "مطبخ", code: "5" },
    { name: "غسالة", code: "6" },
    { name: "تلفزيون", code: "7" },
    { name: "مصعد", code: "8" },
    { name: "حديقة", code: "9" },
    { name: "أمن", code: "10" },
]);

const propertyTypes = ref([
    { name: "شقة", code: "شقة" },
    { name: "فيلا", code: "فيلا" },
    { name: "استوديو", code: "استوديو" },
    { name: "بيت", code: "بيت" },
    { name: "شاليه", code: "شاليه" },
]);

const cities = ref([
    { name: "الرياض", code: "الرياض" },
    { name: "جدة", code: "جدة" },
    { name: "الدمام", code: "الدمام" },
    { name: "مكة", code: "مكة" },
    { name: "المدينة", code: "المدينة" },
    { name: "الخبر", code: "الخبر" },
    { name: "الطائف", code: "الطائف" },
    { name: "تبوك", code: "تبوك" },
]);
</script>

<template>
    <div class="container mx-auto px-4 py-6 rtl" dir="rtl">
        <!-- Header with refined styling -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                إضافة عقار جديد
            </h1>
            <p class="text-gray-600">
                أدخل معلومات العقار الخاص بك بالتفصيل لجذب المزيد من المستأجرين
                والمستثمرين
            </p>
        </div>

        <!-- Sophisticated Stepper -->
        <div class="mb-10">
            <Stepper
                v-model:value="activeStep"
                class="w-full sophisticated-stepper"
            >
                <StepList class=" overflow-hidden h-24">
                    <!-- Step 1 -->
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="1"
                    >
                        <div
                            class="flex flex-row flex-auto gap-2"
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
                                    >المعلومات الأساسية</span
                                >
                            </button>
                            <Divider class="flex-grow step-divider" />
                        </div>
                    </Step>

                    <!-- Step 2 -->
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="2"
                    >
                        <div
                            class="flex flex-row flex-auto gap-2 px-2"
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
                            <Divider class="flex-grow step-divider" />
                        </div>
                    </Step>

                    <!-- Step 3 -->
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="3"
                    >
                        <div
                            class="flex flex-row flex-auto gap-2 px-2"
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
                                    >المرافق والصور</span
                                >
                            </button>
                            <Divider class="flex-grow step-divider" />
                        </div>
                    </Step>

                    <!-- Step 4 -->
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="4"
                    >
                        <div class="flex flex-row px-2" v-bind="a11yAttrs.root">
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
                                    <i class="pi pi-money-bill"></i>
                                </span>
                                <span
                                    class="step-label"
                                    :class="{
                                        'text-emerald-700 font-medium':
                                            value <= activeStep,
                                    }"
                                    >التسعير</span
                                >
                            </button>
                        </div>
                    </Step>
                </StepList>

                <StepPanels>
                    <!-- Step 1 Content -->
                    <StepPanel v-slot="{ activateCallback }" :value="1" class="bg-green-500 p-2">
                        <div class="form-panel">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="form-label"
                                        >عنوان العقار</label
                                    >
                                    <InputText
                                        v-model="property.title"
                                        class="w-full"
                                        placeholder="مثال: فيلا فاخرة في الرياض"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label class="form-label">نوع العقار</label>
                                    <Dropdown
                                        v-model="property.type"
                                        :options="propertyTypes"
                                        optionLabel="name"
                                        optionValue="code"
                                        placeholder="اختر نوع العقار"
                                        class="w-full"
                                    />
                                </div>
                            </div>

                            <div class="space-y-2 mt-6">
                                <label class="form-label">وصف العقار</label>
                                <Textarea
                                    v-model="property.description"
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
                                    <Dropdown
                                        v-model="property.city"
                                        :options="cities"
                                        optionLabel="name"
                                        optionValue="code"
                                        placeholder="اختر المدينة"
                                        class="w-full"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label class="form-label"
                                        >العنوان التفصيلي</label
                                    >
                                    <InputText
                                        v-model="property.address"
                                        class="w-full"
                                        placeholder="مثال: حي الملقا، شارع الملك فهد"
                                    />
                                </div>
                            </div>

                            <div class="flex justify-end pt-8">
                                <Button
                                    label="التالي"
                                    icon="pi pi-arrow-left"
                                    iconPos="right"
                                    class="sophisticated-button"
                                    @click="activateCallback(2)"
                                />
                            </div>
                        </div>
                    </StepPanel>

                    <!-- Step 2 Content -->
                    <StepPanel v-slot="{ activateCallback }" :value="2">
                        <div class="form-panel">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="form-label"
                                        >المساحة (م²)</label
                                    >
                                    <InputNumber
                                        v-model="property.area"
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
                                        v-model="property.bedrooms"
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
                                        v-model="property.bathrooms"
                                        class="w-full"
                                        placeholder="مثال: 2"
                                        :min="0"
                                        :step="1"
                                    />
                                </div>
                            </div>

                            <div class="space-y-2 mt-6">
                                <div class="flex items-center">
                                    <Checkbox
                                        v-model="property.is_furnished"
                                        :binary="true"
                                        inputId="is_furnished"
                                    />
                                    <label
                                        for="is_furnished"
                                        class="mr-2 form-label"
                                        >العقار مفروش</label
                                    >
                                </div>
                            </div>

                            <div class="info-panel mt-6">
                                <h3 class="info-panel-title">معلومات إضافية</h3>

                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4"
                                >
                                    <div class="space-y-2">
                                        <label class="form-label"
                                            >عدد الطوابق</label
                                        >
                                        <InputNumber
                                            class="w-full"
                                            placeholder="مثال: 2"
                                            :min="1"
                                            :step="1"
                                        />
                                    </div>

                                    <div class="space-y-2">
                                        <label class="form-label"
                                            >سنة البناء</label
                                        >
                                        <InputNumber
                                            class="w-full"
                                            placeholder="مثال: 2020"
                                            :min="1900"
                                            :max="2030"
                                            :step="1"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between pt-8">
                                <Button
                                    label="السابق"
                                    severity="secondary"
                                    icon="pi pi-arrow-right"
                                    class="sophisticated-button-secondary"
                                    @click="activateCallback(1)"
                                />
                                <Button
                                    label="التالي"
                                    icon="pi pi-arrow-left"
                                    iconPos="right"
                                    class="sophisticated-button"
                                    @click="activateCallback(3)"
                                />
                            </div>
                        </div>
                    </StepPanel>

                    <!-- Step 3 Content -->
                    <StepPanel v-slot="{ activateCallback }" :value="3">
                        <div class="form-panel">
                            <div class="space-y-2">
                                <label class="form-label"
                                    >المرافق المتوفرة</label
                                >
                                <MultiSelect
                                    v-model="property.amenities"
                                    :options="availableAmenities"
                                    optionLabel="name"
                                    placeholder="اختر المرافق المتوفرة"
                                    class="w-full"
                                />
                            </div>

                            <div class="space-y-2 mt-6">
                                <label class="form-label">صور العقار</label>
                                <FileUpload
                                    mode="advanced"
                                    :multiple="true"
                                    accept="image/*"
                                    :maxFileSize="5000000"
                                    chooseLabel="اختر الصور"
                                    uploadLabel="رفع"
                                    cancelLabel="إلغاء"
                                    :showUploadButton="false"
                                    class="w-full sophisticated-upload"
                                >
                                    <template #empty>
                                        <div class="upload-placeholder">
                                            <i
                                                class="pi pi-images text-4xl text-gray-400 mb-4"
                                            ></i>
                                            <p class="text-gray-600">
                                                اسحب وأفلت الصور هنا أو انقر
                                                لاختيار الصور
                                            </p>
                                            <p
                                                class="text-gray-500 text-sm mt-1"
                                            >
                                                الحد الأقصى: 5 ميجابايت لكل صورة
                                            </p>
                                        </div>
                                    </template>
                                </FileUpload>
                            </div>

                            <div class="info-panel mt-6">
                                <h3 class="info-panel-title">
                                    نصائح لصور أفضل
                                </h3>
                                <ul
                                    class="list-disc list-inside space-y-1 text-sm text-gray-600"
                                >
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

                            <div class="flex justify-between pt-8">
                                <Button
                                    label="السابق"
                                    severity="secondary"
                                    icon="pi pi-arrow-right"
                                    class="sophisticated-button-secondary"
                                    @click="activateCallback(2)"
                                />
                                <Button
                                    label="التالي"
                                    icon="pi pi-arrow-left"
                                    iconPos="right"
                                    class="sophisticated-button"
                                    @click="activateCallback(4)"
                                />
                            </div>
                        </div>
                    </StepPanel>

                    <!-- Step 4 Content -->
                    <StepPanel v-slot="{ activateCallback }" :value="4">
                        <div class="form-panel">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="form-label"
                                        >سعر الإيجار اليومي (ريال)</label
                                    >
                                    <InputNumber
                                        v-model="property.daily_rent_price"
                                        class="w-full"
                                        placeholder="مثال: 300"
                                        :min="0"
                                        :step="50"
                                        suffix=" ريال"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label class="form-label"
                                        >سعر الإيجار الشهري (ريال)</label
                                    >
                                    <InputNumber
                                        v-model="property.monthly_rent_price"
                                        class="w-full"
                                        placeholder="مثال: 5000"
                                        :min="0"
                                        :step="100"
                                        suffix=" ريال"
                                    />
                                </div>
                            </div>

                            <div class="info-panel mt-6">
                                <h3 class="info-panel-title">
                                    معلومات التسعير
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    يمكنك تحديد سعر الإيجار اليومي والشهري
                                    للعقار. سيتم استخدام هذه الأسعار لحساب
                                    العائد المتوقع للمستثمرين.
                                </p>

                                <div class="tip-panel">
                                    <div class="flex items-center">
                                        <i
                                            class="pi pi-info-circle text-emerald-600 ml-2"
                                        ></i>
                                        <h4
                                            class="text-sm font-medium text-emerald-800"
                                        >
                                            نصيحة للتسعير
                                        </h4>
                                    </div>
                                    <p class="text-sm text-emerald-700 mt-1">
                                        قم بمقارنة أسعار العقارات المشابهة في
                                        المنطقة للحصول على سعر تنافسي يجذب
                                        المستأجرين والمستثمرين.
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-between pt-8">
                                <Button
                                    label="السابق"
                                    severity="secondary"
                                    icon="pi pi-arrow-right"
                                    class="sophisticated-button-secondary"
                                    @click="activateCallback(3)"
                                />
                                <Button
                                    label="إضافة العقار"
                                    icon="pi pi-check"
                                    class="sophisticated-button-success"
                                />
                            </div>
                        </div>
                    </StepPanel>
                </StepPanels>
            </Stepper>
        </div>
    </div>
</template>

<style scoped>
/* RTL support */
.rtl {
    text-align: right;
}

/* Sophisticated Stepper Styling */
.sophisticated-stepper {
    --step-active-color: #059669;
    --step-inactive-color: #e5e7eb;
    --step-hover-color: #d1fae5;
}

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

/* Step Buttons */
.step-button {
    @apply transition-transform duration-300;
}

.step-button:hover .step-icon:not(.step-active) {
    @apply border-emerald-200 bg-emerald-50 text-emerald-600 transform scale-105;
}

/* Step Divider */
.step-divider {
    @apply my-7;
}

:deep(.p-divider-content) {
    background-color: white;
}

/* Form Styling */
.form-panel {
    @apply space-y-6;
}

.form-label {
    @apply block text-sm font-medium text-gray-700;
}

.info-panel {
    @apply p-5 bg-gray-50 rounded-lg border border-gray-200 shadow-sm;
}

/* .info-panel-title {
    @apply text-md font-medium text-gray-800 mb-3;
} */

.tip-panel {
    @apply bg-emerald-50 border border-emerald-200 rounded-lg p-4 shadow-sm;
}

.upload-placeholder {
    @apply flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg transition-all duration-300 hover:border-emerald-300 hover:bg-emerald-50;
}

/* Button Styling */
.sophisticated-button {
    @apply bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg transition-all duration-300 shadow-sm hover:shadow;
}

.sophisticated-button-secondary {
    @apply bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg transition-all duration-300 shadow-sm hover:shadow border border-gray-300;
}

.sophisticated-button-success {
    @apply bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg transition-all duration-300 shadow-sm hover:shadow;
}

/* Fix PrimeVue RTL issues */
:deep(.p-stepper) {
    direction: rtl;
}

:deep(.p-stepper-header) {
    flex-direction: row-reverse;
}

:deep(.p-dropdown-label),
:deep(.p-inputtext),
:deep(.p-multiselect-label) {
    text-align: right;
}

:deep(.p-button-icon-right) {
    margin-right: 0.5rem;
    margin-left: 0;
}

:deep(.p-button-icon-left) {
    margin-left: 0.5rem;
    margin-right: 0;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .step-icon {
        @apply w-12 h-12;
    }

    .step-label {
        font-size: 0.75rem;
    }

    :deep(.p-stepper-header) {
        overflow-x: auto;
        padding-bottom: 1rem;
    }

    .sophisticated-button,
    .sophisticated-button-secondary,
    .sophisticated-button-success {
        @apply w-full justify-center mt-2;
    }

    .flex.justify-between {
        @apply flex-col;
    }

    .flex.justify-between .sophisticated-button-secondary {
        @apply order-2;
    }

    .flex.justify-between .sophisticated-button,
    .flex.justify-between .sophisticated-button-success {
        @apply order-1;
    }
}

/* Enhanced FileUpload */
:deep(.sophisticated-upload) .p-fileupload-buttonbar {
    @apply bg-gray-50 border-gray-200 rounded-t-lg;
}

:deep(.sophisticated-upload) .p-button {
    @apply bg-emerald-600 hover:bg-emerald-700 border-emerald-600;
}

:deep(.sophisticated-upload) .p-fileupload-content {
    @apply border-gray-200;
}

/* Enhanced Form Controls */
:deep(.p-inputtext:focus) {
    @apply border-emerald-500 ring-2 ring-emerald-200;
}

:deep(.p-dropdown:focus),
:deep(.p-multiselect:focus) {
    @apply border-emerald-500 ring-2 ring-emerald-200;
}

:deep(.p-checkbox:focus) {
    @apply border-emerald-500 ring-2 ring-emerald-200;
}

:deep(.p-checkbox-box.p-highlight) {
    @apply bg-emerald-600 border-emerald-600;
}
</style>
