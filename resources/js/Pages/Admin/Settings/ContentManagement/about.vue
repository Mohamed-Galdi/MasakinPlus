<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AdminSettingsLayout from "@/Layouts/AdminSettingsLayout.vue";
import CMSLayout from "@/Layouts/CMSLayout.vue";
import Accordion from "primevue/accordion";
import AccordionTab from "primevue/accordiontab";
import AboutHeroSection from "@/Components/AdminSettings/CMS/AboutHeroSection.vue";
import AboutMissionSection from "@/Components/AdminSettings/CMS/AboutMissionSection.vue";
import AboutHowItWorksSection from "@/Components/AdminSettings/CMS/AboutHowItWorksSection.vue";
import AboutTimeLine from "@/Components/AdminSettings/CMS/AboutTimeLine.vue";
import AboutTeam from "@/Components/AdminSettings/CMS/AboutTeam.vue";
import AboutContact from "@/Components/AdminSettings/CMS/AboutContact.vue";

defineOptions({
    layout: (h, page) =>
        h(AdminLayout, {}, () =>
            h(AdminSettingsLayout, {}, () => h(CMSLayout, {}, () => page))
        ),
});

const props = defineProps({
    hero: Object,
    mission: Object,
    howItWorks: Object,
    timeline: Object,
    team: Object,
    contact: Object,
});
</script>

<template>
    <div class="space-y-6">
        <Accordion :multiple="true" :activeIndex="[]">
            <!-- HERO SECTION -->
            <AccordionTab header="القسم الرئيسي">
                <AboutHeroSection :hero="props.hero" />
            </AccordionTab>

            <AccordionTab header=" رسالة المنصة">
                <AboutMissionSection :mission="props.mission" />
            </AccordionTab>

            <AccordionTab header="كيف تعمل المنصة">
                <AboutHowItWorksSection :howItWorks="props.howItWorks" />
            </AccordionTab>

            <AccordionTab header="رحلتنا">
                <AboutTimeLine :timeline="props.timeline" />
            </AccordionTab>

            <AccordionTab header="فريقنا">
                <AboutTeam :team="props.team" />
            </AccordionTab>

            <AccordionTab header="معلومات التواصل">
                <AboutContact :contact="props.contact" />
            </AccordionTab>

        </Accordion>
    </div>
</template>
