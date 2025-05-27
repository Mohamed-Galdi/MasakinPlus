<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminSettingsLayout from "@/Layouts/AdminSettingsLayout.vue";


defineOptions({
    layout: (h, page) =>
        h(AdminLayout, {}, () => h(AdminSettingsLayout, {}, () => page)),
});
</script>

<template>
    <div>
        <p>صفحة إعدادت فارغة</p>
    </div>
</template>
