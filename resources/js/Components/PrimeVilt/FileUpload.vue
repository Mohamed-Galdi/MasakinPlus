<script setup>
import { ref, onMounted } from "vue";
import vueFilePond from "vue-filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";
import "filepond/dist/filepond.min.css";
import { router, usePage } from "@inertiajs/vue3";
import { setOptions } from "vue-filepond";
import ar_AR from "filepond/locale/ar-ar";

// Define component props
const props = defineProps({
    initialFiles: {
        type: [String, Array],
        default: null,
    },
    maxFileSize: {
        type: Number,
        default: 5 * 1024 * 1024, // 5 MB
    },
    allowedFileTypes: {
        type: Array,
        default: () => [
            "image/jpeg",
            "image/png",
            "image/gif",
            "image/svg+xml",
            "image/webp",
            "image/avif",
        ], // Common image formats
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

// Apply locale settings
setOptions(props.locale);

// Load initial files on mount
onMounted(() => {
    if (props.initialFiles) {
        const initialFiles = Array.isArray(props.initialFiles)
            ? props.initialFiles
            : [props.initialFiles];
        files.value = initialFiles.map((file) => ({
            source: file,
            options: { type: "local" },
        }));
    }
});

const emit = defineEmits(["fileUploaded", "fileReverted", "fileRemoved"]);

// Handle file upload response
function handleFileUpload(response) {
    files.value.push(response);
    emit("fileUploaded", response);
    return response;
}

// Handle revert for newly uploaded files
function handleFileRevert(uniqueId, load) {
    emit("fileReverted", uniqueId);
    router.post(
        route("file.revert", { id: uniqueId }),
        {},
        {
            onSuccess: () => load(),
        }
    );
}

// Handle file removal (initial files)
function handleRemoveFile(error, file) {
    if (
        file &&
        props.initialFiles &&
        props.initialFiles.includes(file.source)
    ) {
        emit("fileRemoved", file.source);
    }
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
    load: (source, load, error) => {
        fetch(source)
            .then((response) => response.blob())
            .then(load)
            .catch(error);
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
            @removefile="handleRemoveFile"
        />
    </div>
</template>
