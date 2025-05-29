<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminSettingsLayout from "@/Layouts/AdminSettingsLayout.vue";
import CMSLayout from "@/Layouts/CMSLayout.vue";
import Accordion from "primevue/accordion";
import AccordionTab from "primevue/accordiontab";
import HomeHeroSection from "@/Components/AdminSettings/CMS/HomeHeroSection.vue";
import HomeUsersCardsSection from "@/Components/AdminSettings/CMS/HomeUsersCardsSection.vue";
import HomeFeaturesSection from "@/Components/AdminSettings/CMS/HomeFeaturesSection.vue";

defineOptions({
    layout: (h, page) =>
        h(AdminLayout, {}, () =>
            h(AdminSettingsLayout, {}, () => h(CMSLayout, {}, () => page))
        ),
});

const props = defineProps({
    hero: Object,
    usersCards: Object,
    features: Object,
});
</script>

<template>
    <div class="space-y-6">
        <Accordion :multiple="true" :activeIndex="[2]">
            <!-- HERO SECTION -->
            <AccordionTab header="القسم الرئيسي">
                <HomeHeroSection :hero="props.hero" />
            </AccordionTab>

            <AccordionTab header="بطاقات المستخدمين">
                <HomeUsersCardsSection :usersCards="props.usersCards" />
            </AccordionTab>

            <AccordionTab header="مميزات المنصة">
                <HomeFeaturesSection :features="props.features" />
            </AccordionTab>

        </Accordion>
    </div>
</template>
