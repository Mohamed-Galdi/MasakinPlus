<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, watch, computed } from "vue";
import { router, Link } from "@inertiajs/vue3";
import { useTextHelpers } from "@/plugins/textHelpers";
import { debounce } from "lodash";
import Select from "primevue/select";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Avatar from "primevue/avatar";
import Tag from "primevue/tag";
import { useToast } from "primevue/usetoast";
import PropertyStatus from "@/Components/PropertyStatus.vue";
import { cities } from "@/plugins/cities";
import Header from "@/Components/AdminDashboard/Header.vue";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    properties: {
        type: Object,
        required: true,
    },
    statusOptions: {
        type: Array,
        required: true,
    },
    typeOptions: {
        type: Array,
        required: true,
    },
    cityFilter: {
        type: String,
        required: false,
        default: "",
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
    search: {
        type: String,
        required: false,
        default: "",
    },
});

const statusOptions = ref(props.statusOptions);
const typeOptions = ref(props.typeOptions);

// ############################################## Search and filter
const textHelpers = useTextHelpers();
const search = ref(props.search);
const statusFilter = ref(props.statusFilter);
const typeFilter = ref(props.typeFilter);
const cityFilter = ref(props.cityFilter);

watch(
    [search, statusFilter, typeFilter, cityFilter],
    debounce(([search, statusFilter, typeFilter, cityFilter]) => {
        router.get(
            route("admin.properties.index"),
            {
                search: search,
                statusFilter: statusFilter,
                typeFilter: typeFilter,
                cityFilter: cityFilter,
            },
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    }, 500)
);

// Reset filters
const resetFilters = () => {
    search.value = "";
    statusFilter.value = "";
    typeFilter.value = "";
    cityFilter.value = "";
};

// ############################################## View property
const viewProperty = (property) => {
    router.get(route("admin.properties.view", { property: property.id }));
};

// ############################################## Utils

</script>

<template>
    <div>
        <!-- Header Section -->
        <Header
            icon="pi-home"
            title="العقارات "
            subtitle="إدارة عقارات النظام المتاحة للإستثمار و الكراء"
        >
        </Header>

        <!-- Filters Section -->
        <div class="flex flex-wrap gap-3 p-4 bg-white rounded-lg mb-6">
            <div class="w-full md:w-[calc(50%_-_0.375rem)] lg:flex-1">
                <InputText
                    v-model="search"
                    placeholder="البحث عن مالك أو عقار ..."
                    class="w-full"
                />
            </div>
            <div class="w-full md:w-[calc(50%_-_0.375rem)] lg:flex-1">
                <Select
                    v-model="typeFilter"
                    :options="typeOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="نوع العقار"
                    class="w-full"
                />
            </div>
            <div class="w-full md:w-[calc(50%_-_0.375rem)] lg:flex-1">
                <Select
                    v-model="cityFilter"
                    :options="cities"
                    placeholder="المدينة "
                    class="w-full"
                />
            </div>
            <div class="w-full md:w-[calc(50%_-_0.375rem)] lg:flex-1">
                <Select
                    v-model="statusFilter"
                    :options="statusOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="حالة العقار "
                    class="w-full"
                />
            </div>
            <div class="flex-none">
                <Button
                    icon="pi pi-filter-slash"
                    outlined
                    rounded
                    severity="secondary"
                    @click="resetFilters"
                />
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
                <i class="pi pi-home text-gray-300" style="font-size: 3rem"></i>
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
                @row-click="(event) => viewProperty(event.data)"
                :rowHover="true"
                :rowClass="() => 'cursor-pointer'"
            >
                <Column field="name" header="المالك">
                    <template #body="slotProps">
                        <div class="flex gap-2 items-center">
                            <Avatar
                                :image="slotProps.data.owner.image"
                                size="normal"
                                shape="circle"
                            />
                            <div>
                                <p
                                    v-html="
                                        textHelpers.highlightText(
                                            slotProps.data.owner.name,
                                            search
                                        )
                                    "
                                ></p>
                                <p class="text-xs text-gray-500">
                                    {{ slotProps.data.owner.email }}
                                </p>
                            </div>
                        </div>
                    </template>
                </Column>

                <Column field="created_at" header=" إسم العقار">
                    <template #body="slotProps">
                        <div class="text-sm">
                            <p
                                v-html="
                                    textHelpers.highlightText(
                                        slotProps.data.title,
                                        search
                                    )
                                "
                            ></p>
                        </div>
                    </template>
                </Column>
                <Column field="created_at" header=" نوع العقار">
                    <template #body="slotProps">
                        <div>
                            <Tag :value="slotProps.data.type" rounded></Tag>
                        </div>
                    </template>
                </Column>
                <Column field="created_at" header="  المدينة">
                    <template #body="slotProps">
                        <div>
                            <p>{{ slotProps.data.city }}</p>
                        </div>
                    </template>
                </Column>
                <Column field="created_at" header="  حالة العقار">
                    <template #body="slotProps">
                        <div>
                            <PropertyStatus
                                :status="slotProps.data.status"
                                :status-options="statusOptions"
                            />
                        </div>
                    </template>
                </Column>
                <Column field="created_at" header="تاريخ الإضافة">
                    <template #body="slotProps">
                        <div class="text-sm text-gray-600">
                            <i class="pi pi-calendar-plus ml-1"></i>
                            {{ $formatDate(slotProps.data.created_at) }}
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
                                        index ===
                                        props.properties.links.length - 1,
                                }"
                            />
                            <p
                                v-else
                                v-html="link.label"
                                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-slate-200 border border-gray-300"
                                :class="{
                                    'rounded-l-lg': index === 0,
                                    'rounded-r-lg':
                                        index ===
                                        props.properties.links.length - 1,
                                }"
                            />
                        </template>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</template>
