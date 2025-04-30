<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, watch, computed } from "vue";
import { router, Link, useForm } from "@inertiajs/vue3";
import { useTextHelpers } from "@/plugins/textHelpers";
import { debounce } from "lodash";
import Select from "primevue/select";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Toast from "primevue/toast";
import { useToast } from "primevue/usetoast";
import Dialog from "primevue/dialog";
import Textarea from "primevue/textarea";
import Checkbox from "primevue/checkbox";
import InvestmentRequestStatus from "@/Components/InvestmentRequestStatus.vue";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    properties: {
        type: Object,
        required: true,
    },
});

const toast = useToast();

const isMobile = computed(() => window.innerWidth <= 768);

// ############################################## Search and filter
const textHelpers = useTextHelpers();
const search = ref(props.search);
const statusFilter = ref(props.statusFilter);

// ############################################## Utils
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
</script>

<template>
    <div>
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <i
                    class="pi pi-home text-teal-800 h-full"
                    style="font-size: 2.5rem"
                ></i>
                <div class="">
                    <h1 class="text-3xl font-semibold m-0 text-teal-800">
                        العقارات
                    </h1>
                    <p class="text-gray-500 text-sm m-0">
                        إدارة عقارات النظام المتاحة للإستثمار و الكراء
                    </p>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div
            class="filter-section bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6"
        >
            <div class="flex flex-wrap items-center gap-4">
                <!-- Search -->
                <div class="search-container flex-1 min-w-[250px]">
                    <InputText
                        v-model="search"
                        class="w-full rounded-lg border-gray-200"
                        placeholder="البحث عن مستخدم بالاسم   ..."
                    />
                </div>
                <!-- Filters -->
                <div
                    class="filter-controls flex items-center gap-3 md:w-72 w-full"
                >
                    <Select
                        v-model="statusFilter"
                        :options="investmentRequestsStatusOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="نوع الحساب"
                        class="md:w-60 w-full rounded-lg"
                    />
                    <Button
                        icon="pi pi-filter-slash"
                        outlined
                        severity="secondary"
                        class="p-button-rounded w-44"
                        @click="resetFilters"
                    />
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="props.properties.data.length === 0"
            class="empty-state bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center"
        >
            <div
                class="empty-state-icon bg-gray-50 inline-flex items-center justify-center w-24 h-24 rounded-full mb-4"
            >
                <i
                    class="pi pi-home text-gray-300"
                    style="font-size: 3rem"
                ></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">
                لا يوجد عقارات
            </h2>
            <p class="text-gray-500 max-w-md mx-auto mb-6">
                لم يتم العثور على أي عقارات مطابقين لمعايير البحث الحالية.
            </p>
            <div class="flex justify-center gap-3">
                <Button
                    icon="pi pi-filter-slash"
                    label="إعادة ضبط الفلتر"
                    outlined
                    @click="resetFilters"
                />
            </div>
        </div>

        <!-- Properties Table -->
        <div
            v-else
            class="rounded-xl overflow-hidden flex flex-col justify-between min-h-[75vh]"
        >
            <DataTable
                :value="props.properties.data"
                stripedRows
                showGridlines
                tableStyle="min-width: 50rem"
                class="border-none"
            >
                <!-- <Column field="name" header="الاسم">
                    <template #body="slotProps">
                        <div
                            v-html="
                                textHelpers.highlightText(
                                    slotProps.data.name,
                                    search
                                )
                            "
                        ></div>
                    </template>
                </Column> -->

                <Column field="created_at" header="تاريخ التسجيل">
                    <template #body="slotProps">
                        <div class="text-sm text-gray-600">
                            <i class="pi pi-calendar-plus ml-1"></i>
                            {{ formatDate(slotProps.data.created_at) }}
                        </div>
                    </template>
                </Column>
            </DataTable>
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
                        من أصل {{ props.properties.total }} مستخدم
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
    </div>
</template>
