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
import Checkbox from "primevue/checkbox";
import MultiSelect from "primevue/multiselect";
import Button from "primevue/button";
import Stepper from "primevue/stepper";
import StepList from "primevue/steplist";
import Step from "primevue/step";
import StepPanels from "primevue/steppanels";
import StepPanel from "primevue/steppanel";

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
});

const toast = useToast();

const propertyTypes = ref(props.typeOptions);
const availableAmenities = ref(props.amenities);

// For the stepper
const activeStep = ref(1);
const mapRef = ref(null);

watch(activeStep, (newStep) => {
    if (newStep === 3 && mapRef.value) {
        // Call the resizeMap method when step 2 becomes active
        mapRef.value.resizeMap();
    }
});

const propertyForm = useForm({
    title: "",
    description: "",
    type: null,
    city: null,
    address: "",
    area: null,
    bedrooms: null,
    bathrooms: null,
    daily_rent_price: null,
    amenities: [],
    images: [],
});

// ############################################## File upload
const tempFile = ref([]);

function handleFileUploaded(fileFolder) {
    tempFile.value.push(fileFolder);
}

function handleFileReverted(uniqueId) {
    tempFile.value = tempFile.value.filter((filePath) => {
        return !filePath.includes(uniqueId);
    });
}
// ############################################# Creation
function submitCreateProperty() {
    propertyForm.images = tempFile.value;
    propertyForm.post(route("owner.properties.store"), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "عملية ناجحة",
                detail: "تم إنشاء العقار بنجاح",
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
    });
}
</script>

<template>
    <div class="container mx-auto px-4 py-6" dir="rtl">
        <Toast position="top-center" />
        <!-- Header-->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    إضافة عقار جديد
                </h1>
                <p class="text-gray-600 mt-1">
                    أدخل معلومات العقار الخاص بك بالتفصيل لجذب المزيد من
                    المستأجرين والمستثمرين
                </p>
            </div>
            <!-- back button -->
            <Link
                :href="route('owner.properties.index')"
                class="btn bg-emerald-600 hover:bg-emerald-700 text-white md:w-fit w-full mt-2 md:mt-0"
            >
                <span>العودة للعقارات</span>
                <i class="pi pi-arrow-left"></i>
            </Link>
        </div>
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
                                >
                                    الصور</span
                                >
                            </button>
                        </div>
                    </Step>

                    <!-- Step 5 -->
                    <Step
                        v-slot="{ activateCallback, value, a11yAttrs }"
                        asChild
                        :value="5"
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
                    <!-- Step 1 Content (Informations) -->
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

                    <!-- Step 2 Content (Property Details) -->
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

                    <!-- Step 3 Content (Map) -->
                    <StepPanel v-slot="{ activateCallback }" :value="3">
                        <div class="bg-slate-200 p-4 rounded-md min-h-[24rem]">
                            <PropertyMap ref="mapRef" />
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

                    <!-- Step 4 Content (Images) -->
                    <StepPanel v-slot="{ activateCallback }" :value="4">
                        <div class="bg-slate-200 p-4 rounded-md min-h-[24rem]">
                            <div class="space-y-2 mt-6">
                                <label class="form-label">صور العقار</label>
                                <FileUpload
                                    @file-uploaded="handleFileUploaded"
                                    @file-reverted="handleFileReverted"
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
                                label="التالي"
                                icon="pi pi-arrow-left"
                                @click="activateCallback(5)"
                            />
                        </div>
                    </StepPanel>

                    <!-- Step 5 Content (Pricing) -->
                    <StepPanel v-slot="{ activateCallback }" :value="5">
                        <div class="bg-slate-200 p-4 rounded-md min-h-[24rem]">
                            <div class="space-y-2">
                                <label class="form-label"
                                    >سعر الإيجار اليومي (ريال)</label
                                >
                                <InputNumber
                                    v-model="propertyForm.daily_rent_price"
                                    class="w-full"
                                    placeholder="مثال: 300"
                                    :min="0"
                                    :step="50"
                                    suffix=" ريال"
                                />
                            </div>

                            <div class="info-panel mt-6">
                                <h3 class="info-panel-title">
                                    معلومات التسعير
                                </h3>
                                <ul
                                    class="list-disc list-inside space-y-1 text-sm text-gray-600"
                                >
                                    <li>
                                        قم بمقارنة أسعار العقارات المشابهة في
                                        المنطقة للحصول على سعر تنافسي يجذب
                                        المستأجرين والمستثمرين.
                                    </li>
                                    <li>
                                        سيتم استخدام هذه الأسعار لحساب العائد
                                        المتوقع للمستثمرين.
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="flex justify-between pt-4">
                            <Button
                                label="السابق"
                                icon="pi pi-arrow-right"
                                @click="activateCallback(4)"
                                icon-pos="right"
                            />
                            <Button
                                label="إضافة العقار"
                                icon="pi pi-check"
                                severity="contrast"
                                @click="submitCreateProperty"
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
