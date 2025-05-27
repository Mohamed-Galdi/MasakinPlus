<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminSettingsLayout from "@/Layouts/AdminSettingsLayout.vue";
import CMSLayout from "@/Layouts/CMSLayout.vue";
import Accordion from "primevue/accordion";
import AccordionTab from "primevue/accordiontab";
import HomeHeroSection from "@/Components/AdminSettings/CMS/HomeHeroSection.vue";

defineOptions({
    layout: (h, page) =>
        h(AdminLayout, {}, () =>
            h(AdminSettingsLayout, {}, () => h(CMSLayout, {}, () => page))
        ),
});

const props = defineProps({
    hero: Object,
});
</script>

<template>
    <div class="space-y-6">
        <Accordion :multiple="true" :activeIndex="[0]">
            <!-- HERO SECTION -->
            <AccordionTab header="القسم الرئيسي">
                <HomeHeroSection :hero="props.hero" />
            </AccordionTab>

            <AccordionTab header="مميزات المنصة">
                <p>تعديل المحتوى الخاص بمميزات المنصة هنا...</p>
            </AccordionTab>
        </Accordion>
    </div>
</template>
