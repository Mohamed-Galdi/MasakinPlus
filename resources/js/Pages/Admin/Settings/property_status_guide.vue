<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminSettingsLayout from "@/Layouts/AdminSettingsLayout.vue";
import { ref } from "vue";
import PropertyStatusGuide from "@/Components/AdminSettings/PropertyStatusGuide.vue";

defineOptions({
    layout: (h, page) =>
        h(AdminLayout, {}, () => h(AdminSettingsLayout, {}, () => page)),
});

const props = defineProps({
    propertyStatusOptions: Array,
    propertyStatusDescriptions: Object,
});
</script>

<template>
    <div>
        <!-- Your existing PropertyStatusGuide content -->
        <div class="p-6">
            <PropertyStatusGuide  :statuses="props.propertyStatusOptions"
            :descriptions="props.propertyStatusDescriptions" />
        </div>
    </div>
</template>