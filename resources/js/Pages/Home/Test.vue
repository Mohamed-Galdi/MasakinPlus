<script setup>
import { ref, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Select from "primevue/select";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import Avatar from "primevue/avatar";
import Tag from "primevue/tag";
import Card from "primevue/card";
import Chip from "primevue/chip";
import Menu from "primevue/menu";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    usersTypes: {
        type: Array,
        required: true,
    },
});

const UsersTypes = ref(props.usersTypes);
const filter = ref("");
const search = ref("");
const menuRef = ref();
const activeItem = ref(null);

const userTypeColors = {
    owner: 'success',
    investor: 'warning',
    tenant: 'info'
};

const userTypeLabels = {
    owner: 'مالك',
    investor: 'مستثمر',
    tenant: 'مستأجر'
};

const hasUsers = computed(() => props.users.data && props.users.data.length > 0);

const formatDate = (value) => {
    if (!value) return '';
    return new Date(value).toLocaleDateString('ar-SA');
};

const showMenu = (event, item) => {
    activeItem.value = item;
    menuRef.value.toggle(event);
};

const menuItems = [
    {
        label: 'تعديل',
        icon: 'pi pi-pencil',
        command: () => {
            // Edit action
        }
    },
    {
        label: 'تغيير كلمة المرور',
        icon: 'pi pi-key',
        command: () => {
            // Change password action
        }
    },
    {
        separator: true
    },
    {
        label: 'حذف',
        icon: 'pi pi-trash',
        class: 'text-red-500',
        command: () => {
            // Delete action
        }
    }
];
</script>

<template>
    <div class="users-container">
        <!-- Header section -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-primary-50 p-3 rounded-xl">
                    <i class="pi pi-users text-primary" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold m-0">المستخدمين</h1>
                    <p class="text-gray-500 m-0">إدارة مستخدمي النظام</p>
                </div>
            </div>
            <Button 
                icon="pi pi-plus" 
                label="إضافة مستخدم جديد" 
                class="p-button-primary shadow-md"
                severity="primary" 
            />
        </div>

        <!-- Filters section -->
        <Card class="mb-6 shadow-sm border-0">
            <template #content>
                <div class="flex flex-wrap gap-4 items-center">
                    <div class="flex-grow">
                        <span class="p-input-icon-right w-full">
                            <i class="pi pi-search" />
                            <InputText 
                                v-model="search" 
                                class="w-full p-inputtext-lg" 
                                placeholder="البحث عن مستخدم..." 
                            />
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <Select
                                v-model="filter"
                                :options="UsersTypes"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="نوع الحساب"
                                class="w-44"
                            />
                        </div>
                        <Button 
                            icon="pi pi-filter-slash" 
                            severity="secondary" 
                            class="p-button-outlined"
                            v-tooltip.top="'مسح الفلتر'"
                        />
                    </div>
                </div>
            </template>
        </Card>

        <!-- Users table -->
        <Card class="shadow-sm border-0" v-if="hasUsers">
            <template #content>
                <DataTable 
                    :value="props.users.data"
                    stripedRows
                    class="p-datatable-lg"
                    responsiveLayout="scroll"
                    :rowHover="true"
                >
                    <Column>
                        <template #body="{ data }">
                            <div class="flex items-center gap-2">
                                <Avatar :image="data.image" class="mr-2" size="large" shape="circle" />
                                <div>
                                    <div class="font-bold">{{ data.name }}</div>
                                    <div class="text-sm text-gray-500">{{ data.email }}</div>
                                </div>
                            </div>
                        </template>
                        <template #header>
                            <div class="text-sm font-semibold text-gray-500">المستخدم</div>
                        </template>
                    </Column>

                    <Column field="type">
                        <template #body="{ data }">
                            <Tag :value="userTypeLabels[data.type]" :severity="userTypeColors[data.type]" />
                        </template>
                        <template #header>
                            <div class="text-sm font-semibold text-gray-500">نوع الحساب</div>
                        </template>
                    </Column>

                    <Column field="is_active">
                        <template #body="{ data }">
                            <Tag v-if="data.is_active" value="نشط" severity="success" />
                            <Tag v-else value="غير نشط" severity="danger" />
                        </template>
                        <template #header>
                            <div class="text-sm font-semibold text-gray-500">الحالة</div>
                        </template>
                    </Column>

                    <Column field="created_at">
                        <template #body="{ data }">
                            <div class="flex items-center gap-2">
                                <i class="pi pi-calendar text-gray-400"></i>
                                <span>{{ formatDate(data.created_at) }}</span>
                            </div>
                        </template>
                        <template #header>
                            <div class="text-sm font-semibold text-gray-500">تاريخ الإنضمام</div>
                        </template>
                    </Column>

                    <Column style="width: 6rem">
                        <template #body="{ data }">
                            <div class="flex justify-end">
                                <Button 
                                    icon="pi pi-ellipsis-v" 
                                    class="p-button-rounded p-button-text p-button-secondary" 
                                    @click="showMenu($event, data)"
                                    aria-label="خيارات"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </template>
        </Card>

        <!-- Empty state -->
        <Card v-else class="text-center py-12 border-0 shadow-sm">
            <template #content>
                <div class="flex flex-col items-center gap-4">
                    <div class="bg-gray-100 p-6 rounded-full">
                        <i class="pi pi-users text-gray-400" style="font-size: 3rem"></i>
                    </div>
                    <h3 class="text-2xl font-medium text-gray-800">لا يوجد مستخدمين</h3>
                    <p class="text-gray-500 max-w-md">لم يتم العثور على أي مستخدمين مطابقين لمعايير البحث الخاصة بك</p>
                    <Button label="إضافة مستخدم جديد" icon="pi pi-plus" severity="primary" class="mt-2" />
                </div>
            </template>
        </Card>
    </div>

    <!-- Context menu -->
    <Menu ref="menuRef" :model="menuItems" :popup="true" />
</template>

<style scoped>
.users-container {
    min-height: 100%;
}

:deep(.p-datatable .p-datatable-thead) {
    background-color: #f8fafc;
}

:deep(.p-datatable .p-datatable-thead > tr > th) {
    border-width: 0;
    font-weight: 600;
    padding-top: 1rem;
    padding-bottom: 1rem;
}

:deep(.p-datatable .p-datatable-tbody > tr) {
    border-bottom: 1px solid #f1f5f9;
}

:deep(.p-datatable .p-datatable-tbody > tr > td) {
    border-width: 0;
    padding-top: 1rem;
    padding-bottom: 1rem;
}

:deep(.p-card .p-card-content) {
    padding: 0;
}

:deep(.p-datatable .p-datatable-tbody > tr:last-child) {
    border-bottom: none;
}

:deep(.p-tag) {
    font-size: 0.75rem;
    padding: 0.3rem 0.6rem;
    border-radius: 100px;
    font-weight: 500;
}

:deep(.p-button.p-button-primary) {
    border-radius: 10px;
}

:deep(.p-avatar) {
    border: 2px solid #f1f5f9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}
</style>