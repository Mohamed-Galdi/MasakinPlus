<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import { useTextHelpers } from "@/plugins/textHelpers";
import { debounce } from "lodash";
import ProductStatus from "@/Components/PrimeVilt/Status/ProductStatus.vue";
import FileUpload from "@/Components/PrimeVilt/FileUpload.vue";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from "primevue/confirmdialog";
import Drawer from "primevue/drawer";
import Select from "primevue/select";
import FloatLabel from "primevue/floatlabel";
import InputText from "primevue/inputtext";
import InputNumber from "primevue/inputnumber";
import Button from "primevue/button";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
    productFilter: {
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

// ############################################## initialize Toast and Confirm
const toast = useToast();
const confirm = useConfirm();

const isMobile = computed(() => window.innerWidth <= 768);
// ############################################## Search and filter
const textHelpers = useTextHelpers();
const search = ref(props.search);
const productFilter = ref(props.productFilter);
const filterOptions = ref([
    { label: "All", value: "all" },
    { label: "Available", value: "available" },
    { label: "Out of stock", value: "outofstock" },
]);

watch(
    [search, productFilter],
    debounce(([search, productFilter]) => {
        // Update the table with both filters
        router.get(
            route("admin.products.drawer-crud"),
            {
                search: search,
                productFilter: productFilter,
            },
            {
                preserveState: false,
                preserveScroll: true,
            }
        );
    }, 300)
);

// ############################################## File upload
const tempFile = ref(null);
function handleFileUploaded(fileFolder) {
    tempFile.value = fileFolder;
}

function handleFileReverted() {
    tempFile.value = null;
}

// ############################################## Create Section
const openCreateDrawer = ref(false);
const createProductForm = useForm({
    name: "",
    price: "",
    image: "",
    status: "",
});

const statusOptions = ref([
    { label: "Available", value: "available" },
    { label: "Out of stock", value: "outofstock" },
]);

function handleCreateProduct() {
    createProductForm.image = tempFile.value;
    createProductForm.post(route("admin.products.store"), {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Success",
                detail: "Product created successfully",
                life: 3000,
            });
            openCreateDrawer.value = false;
            search.value = "";
            productFilter.value = "all";
        },
        onError: () => {
            const errorMessage = Object.values(createProductForm.errors)[0];
            toast.add({
                severity: "error",
                summary: "Erreur",
                detail: errorMessage,
                life: 3000,
            });
        },
    });
}

// ############################################## Edit Section
const openEditDrawer = ref(false);

const editProductForm = useForm({
    id: null,
    name: "",
    price: "",
    image: "",
    status: "",
});

const productImageToEdit = ref(null);

function handleOpenEditDrawer(product) {
    editProductForm.id = product.id;
    editProductForm.name = product.name;
    editProductForm.price = product.price;
    editProductForm.status = product.status;
    productImageToEdit.value = product.image;
    openEditDrawer.value = true;
}

function handleEditProduct() {
    editProductForm.image = tempFile.value ?? null;
    editProductForm.post(
        route("admin.products.update", { id: editProductForm.id }),
        {
            preserveState: false,
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "Success",
                    detail: "Product updated successfully",
                    life: 3000,
                });
                openEditDrawer.value = false;
                search.value = "";
                productFilter.value = "all";
            },
            onError: () => {
                const errorMessage = Object.values(editProductForm.errors)[0];
                toast.add({
                    severity: "error",
                    summary: "Erreur",
                    detail: errorMessage,
                    life: 3000,
                });
            },
        }
    );
}

// ############################################## Delete Section
const deleteProduct = (productId) => {
    confirm.require({
        group: "templating",
        message: "Are you sure you want to delete this product?",
        header: "Confirm Deletion",
        rejectProps: {
            label: "Cancel",
            severity: "secondary",
            outlined: true,
        },
        acceptProps: {
            label: "Delete",
            severity: "danger",
        },
        accept: () => {
            router.post(route("admin.products.delete", { id: productId }));
            setTimeout(() => {
                toast.add({
                    severity: "success",
                    summary: "Succes",
                    detail: "Product deleted successfully",
                    life: 3000,
                });
            }, 500);
        },
    });
};
</script>

<template>
    <div
        class="max-w-7xl mx-auto px-6 flex flex-col justify-between min-h-screen"
    >
        <Head title="| Drawer Crud" />

        <Toast position="top-center" />

        <!-- Create Drawer -->
        <Drawer
            v-model:visible="openCreateDrawer"
            header="Create New Product"
            :style="{ width: isMobile ? '100%' : '50vw' }"
            position="right"
        >
            <form class="p-2 space-y-6" @submit.prevent="handleCreateProduct">
                <FloatLabel variant="on" class="w-full">
                    <InputText
                        v-model="createProductForm.name"
                        id="name"
                        type="text"
                        name="name"
                        class="w-full"
                    />
                    <label for="name">Product Name</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <InputNumber
                        v-model="createProductForm.price"
                        mode="currency"
                        currency="USD"
                        locale="en-US"
                        fluid
                    />
                    <label for="price">Price</label>
                </FloatLabel>
                <Select
                    v-model="createProductForm.status"
                    :options="statusOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Select a status"
                    class="w-full"
                />
                <div>
                    <p class="ms-1 text-sm text-slate-600">Product Image</p>
                    <FileUpload
                        @file-uploaded="handleFileUploaded"
                        @file-reverted="handleFileReverted"
                    />
                </div>

                <Button
                    type="submit"
                    label="Create"
                    :loading="createProductForm.processing"
                    severity="success"
                    class="w-full"
                />
            </form>
        </Drawer>

        <!-- Edit Drawer -->
        <Drawer
            v-model:visible="openEditDrawer"
            header="Edit Product"
            :style="{ width: isMobile ? '100%' : '50vw' }"
            position="right"
        >
            <form class="p-2 space-y-6" @submit.prevent="handleEditProduct">
                <FloatLabel variant="on" class="w-full">
                    <InputText
                        v-model="editProductForm.name"
                        id="name"
                        type="text"
                        name="name"
                        class="w-full"
                    />
                    <label for="name">Product Name</label>
                </FloatLabel>
                <FloatLabel variant="on" class="w-full">
                    <InputNumber
                        v-model="editProductForm.price"
                        mode="currency"
                        currency="USD"
                        locale="en-US"
                        fluid
                    />
                    <label for="price">Price</label>
                </FloatLabel>
                <Select
                    v-model="editProductForm.status"
                    :options="statusOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Select a status"
                    class="w-full"
                />
                <div>
                    <p class="ms-1 text-sm text-slate-600">Product Image</p>
                    <FileUpload
                        :initial-files="productImageToEdit"
                        @file-uploaded="handleFileUploaded"
                        @file-reverted="handleFileReverted"
                    />
                </div>

                <Button
                    type="submit"
                    label="Update"
                    :loading="editProductForm.processing"
                    severity="success"
                    class="w-full"
                />
            </form>
        </Drawer>

        <!-- Confirm Dialog -->
        <ConfirmDialog group="templating" class="w-full md:w-1/2 lg:w-1/3 mx-8">
            <template #message="slotProps">
                <div class="flex flex-col items-center justify-center w-full">
                    <div class="bg-rose-500 rounded-full p-3 mb-4">
                        <svg
                            class="h-8 w-8 text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="w-full">
                        <h2
                            class="text-xl font-bold text-gray-800 mb-4 text-center"
                        >
                            Confirm Deletion
                        </h2>
                        <p>Are you sure you want to delete this product?</p>
                    </div>
                </div>
            </template>
        </ConfirmDialog>
        <!-- Main Content -->
        <div>
            <!-- Create and Search -->
            <div
                class="flex md:flex-row flex-col lg:items-center items-start lg:justify-between justify-start gap-3 mt-2 mb-6 w-full"
            >
                <div
                    class="flex md:flex-row flex-col justify-start lg:items-center items-start gap-2 lg:w-fit w-full"
                >
                    <input
                        v-model="search"
                        type="search"
                        name="search"
                        placeholder="Search"
                        class="lg:w-64 w-full h-10 px-3 border-b-2 border-gray-200 focus:border-gray-300 rounded-lg text-sm outline-none focus:ring-0"
                    />
                    <Select
                        v-model="productFilter"
                        :options="filterOptions"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="Filter"
                        class="lg:w-56 w-full"
                    />
                </div>
                <button
                    @click="openCreateDrawer = !openCreateDrawer"
                    class="btn bg-teal-600 hover:bg-teal-700 text-white"
                >
                    <p>New Product</p>
                    <i class="pi pi-plus" />
                </button>
            </div>
            <!-- Product Grid -->
            <div
                v-if="products.data && products.data.length > 0"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
            >
                <div
                    v-for="product in products.data"
                    :key="product.id"
                    class="group bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200"
                >
                    <div class="relative">
                        <img
                            :src="product.image"
                            :alt="product.name"
                            class="w-full h-56 object-contain p-4 bg-gray-50"
                        />

                        <ProductStatus
                            class="absolute bottom-2 right-2"
                            :status="product.status"
                        />
                    </div>
                    <div class="p-4 flex flex-col justify-between h-40">
                        <div>
                            <h3
                                v-html="
                                    textHelpers.highlightText(
                                        product.name,
                                        search
                                    )
                                "
                                class="text-lg font-semibold text-gray-800 truncate hover:text-gray-900 transition-colors"
                            ></h3>
                            <p class="text-gray-600 font-medium mt-1">
                                ${{ product.price.toFixed(2) }}
                            </p>
                        </div>
                        <div class="flex justify-end gap-2 mt-2">
                            <button
                                @click="handleOpenEditDrawer(product)"
                                class="bg-gray-200 text-gray-700 rounded-full w-9 h-9 flex items-center justify-center hover:bg-yellow-500 hover:text-yellow-200 transition-colors"
                            >
                                <i class="pi pi-pencil text-sm"></i>
                            </button>
                            <button
                                @click="deleteProduct(product.id)"
                                class="bg-gray-200 text-gray-700 rounded-full w-9 h-9 flex items-center justify-center hover:bg-rose-500 hover:text-rose-200 transition-colors"
                            >
                                <i class="pi pi-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-else
                class="flex flex-col items-center justify-center h-96 bg-white rounded-lg border-2 border-dashed border-gray-300"
            >
                <i class="pi pi-box text-5xl text-gray-400 mb-4"></i>
                <p class="text-lg text-gray-600 font-medium">
                    No products available
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Add some products to get started
                </p>
            </div>
        </div>

        <!-- Pagination -->
        <div
            class="my-4 flex md:flex-row flex-col md:gap-0 gap-2 justify-between items-center w-full"
        >
            <!-- results -->
            <div class="md:order-1 order-2">
                <p class="text-base text-slate-800">
                    Showing
                    <span class="text-teal-600 font-bold text-lg">{{
                        products.from
                    }}</span>
                    to
                    <span class="text-teal-600 font-bold text-lg">{{
                        products.to
                    }}</span>
                    out of {{ products.total }} products
                </p>
            </div>
            <nav class="">
                <div class="flex items-center -space-x-px h-8 text-sm">
                    <template
                        v-for="(link, index) in products.links"
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
                                    index === products.links.length - 1,
                            }"
                        />
                        <p
                            v-else
                            v-html="link.label"
                            class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-slate-200 border border-gray-300"
                            :class="{
                                'rounded-l-lg': index === 0,
                                'rounded-r-lg':
                                    index === products.links.length - 1,
                            }"
                        />
                    </template>
                </div>
            </nav>
        </div>
    </div>
</template>
