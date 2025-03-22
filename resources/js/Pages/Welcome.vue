<script setup>
import { Head } from "@inertiajs/vue3";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import Toast from "primevue/toast";
import ConfirmDialog from "primevue/confirmdialog";
import Tooltip from "primevue/tooltip";
import FileUpload from "@/Components/PrimeVilt/FileUpload.vue";
import { ref } from "vue";
import Status from "@/Components/PrimeVilt/Status.vue";

const toast = useToast();
const confirm = useConfirm();

const showToast = () => {
    toast.add({
        severity: "info",
        summary: "Success",
        detail: "Toast Message",
        life: 3000,
    });
};

const showConfirm = () => {
    confirm.require({
        header: "Confirm",
        message: "Are you sure you want to delete this?",
        icon: "pi pi-exclamation-triangle",
        accept: () => {
            confirm.accept();
        },
        reject: () => {
            confirm.reject();
        },
    });
};

// ######################################## file upload
// image
const imageTempFile = ref(null);
function handleImageFileUploaded(fileFolder) {
    imageTempFile.value = fileFolder;
}

function handleImageFileReverted() {
    imageTempFile.value = null;
}
</script>

<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <Head title="| Your page title" />
        <Toast />
        <ConfirmDialog></ConfirmDialog>
        <h1 class="w-full text-center text-2xl font-semibold text-primary">
            Prime Vilt
        </h1>
        <InputText class="w-full mt-4" placeholder="Search..." />
        <Button @click="showToast" class="mt-4">Show Toast</Button>
        <Button @click="showConfirm" class="mt-4">Show Confirm</Button>
        <InputText
            v-tooltip="'Enter your username'"
            type="text"
            placeholder="Right"
        />
        <div class="w-[500px]">
            <p class="ms-1 text-sm text-slate-600">Project Logo</p>

            <FileUpload
                @file-uploaded="handleImageFileUploaded"
                @file-reverted="handleImageFileReverted"
            />
        </div>
        <Status :status="'yes'" />
        <i class="pi pi-user"></i>
    </div>
</template>
