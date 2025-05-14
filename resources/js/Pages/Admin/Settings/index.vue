<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Header from "@/Components/AdminDashboard/Header.vue";
import PropertyStatusGuide from "@/Components/AdminSettings/PropertyStatusGuide.vue";
import Tabs from "primevue/tabs";
import TabList from "primevue/tablist";
import Tab from "primevue/tab";
import TabPanels from "primevue/tabpanels";
import TabPanel from "primevue/tabpanel";
import { ref } from "vue";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    propertyStatusOptions: Array,
    propertyStatusDescriptions: Object,
});

const propertyStatusOptions = ref(props.propertyStatusOptions);
const propertyStatusDescriptions = ref(props.propertyStatusDescriptions);

</script>

<template>
    <div>
        <!-- Header Section -->
        <Header
            icon="pi-cog"
            title="الاعدادات"
            subtitle=" إدارة إعدادات المنصة"
        >
        </Header>

        <!--  -->
        <div
                    class="bg-white border border-slate-500/50 shadow-sm min-h-[80vh] rounded-lg overflow-auto"
                >
                    <Tabs value="0">
                        <TabList>
                            <Tab value="1"> الصفحة الرئيسيية</Tab>
                            <Tab value="0">دليل العقارات</Tab>
                        </TabList>
                        <TabPanels>
                            <TabPanel value="0">
                                <PropertyStatusGuide  :statuses="propertyStatusOptions"
                                :descriptions="propertyStatusDescriptions" />
                           
                            </TabPanel>

                            <TabPanel value="1">
                               
                                    <p>2</p>
                            </TabPanel>
                        </TabPanels>
                    </Tabs>
                </div>
    </div>
</template>
