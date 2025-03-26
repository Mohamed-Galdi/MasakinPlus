<script setup>
import { ref, onMounted } from "vue";
import vueFilePond from "vue-filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import "filepond/dist/filepond.min.css";
import { router, usePage } from "@inertiajs/vue3";
import { setOptions } from "vue-filepond";
import fr_FR from "filepond/locale/fr-fr";
import ar_AR from "filepond/locale/ar-ar";

// Define component props
const props = defineProps({
    initialFile: {
        type: [String, Object],
        default: null,
    },
    maxFileSize: {
        type: Number,
        default: 1 * 1024 * 1024, // 1 MB
    },
    allowedFileTypes: {
        type: Array,
        default: () =>  [ "image/jpeg", "image/png", "image/gif", "image/svg+xml"], 
        // default: () => ["application/pdf"], PDF format
        // default: () => ["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"], Excel format
    },
    allowMultiple: {
        type: Boolean,
        default: false,
    },
    maxFiles: {
        type: Number,
        default: 1,
    },
    locale: {
        type: Object,
        default: () => ar_AR,
    },
});

// Reactive variables
const $page = usePage();
const files = ref([]);
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImagePreview
);
setOptions(props.locale);

// Handle initial file population (for update operations)
onMounted(() => {
    if (props.initialFile) {
        files.value = [{
            source: props.initialFile,
            options: { type: "local" },
        }];
    }
});

const emit = defineEmits(["fileUploaded", "fileReverted"]);

// Handle file upload response
function handleFileUpload(response) {
    files.value.push(response);
    emit("fileUploaded", response);
    return response;
}

// Handle file revert (removal)
function handleFileRevert(uniqueId, load) {
    emit("fileReverted", uniqueId);
    router.post(route("file.revert", { id: uniqueId }));
    load();
}

// FilePond server configuration
const serverOptions = {
    process: {
        url: route("file.upload"),
        method: "POST",
        onload: handleFileUpload,
    },
    revert: handleFileRevert,
    headers: {
        "X-CSRF-TOKEN": $page.props.csrf_token,
    },
    load: (source, load, error, progress, abort, headers) => {
        fetch(source).then(response => response.blob()).then(load);
    },
};
</script>

<template>
    <div>
        <FilePond
            v-model="files"
            :server="serverOptions"
            :max-file-size="maxFileSize"
            :allow-multiple="allowMultiple"
            :accepted-file-types="allowedFileTypes"
            :max-files="maxFiles"
            credits="false"
            :locale="null"
            :files="files"
        />
    </div>
</template>