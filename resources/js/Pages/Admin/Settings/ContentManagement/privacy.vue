<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminSettingsLayout from "@/Layouts/AdminSettingsLayout.vue";
import CMSLayout from "@/Layouts/CMSLayout.vue";

defineOptions({
    layout: (h, page) =>
        h(AdminLayout, {}, () =>
            h(AdminSettingsLayout, {}, () => h(CMSLayout, {}, () => page))
        ),
});
</script>

<template>
    <div class="text-center text-slate-500 mt-20">
        <i class="pi pi-shield text-4xl mb-4 text-slate-300"></i>
        <h3 class="text-lg font-medium mb-2 text-slate-700">
            محتوى سياسة الخصوصية
        </h3>
        <p class="text-slate-500">
            سيتم إضافة نموذج تحرير محتوى سياسة الخصوصية هنا
        </p>
    </div>
</template>