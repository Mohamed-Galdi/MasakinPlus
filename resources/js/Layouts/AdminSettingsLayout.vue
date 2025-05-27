<script setup>
import Header from "@/Components/AdminDashboard/Header.vue";
import { ref, watch } from "vue";
import { usePage, Link } from "@inertiajs/vue3";

const activeTab = ref(0);

const tabs = [
    {
        name: "content_management",
        label: "إدارة المحتوى",
        route: "admin.settings.content.home",
    },
    {
        name: "property_status_guide",
        label: "دليل العقارات",
        route: "admin.settings.property_status_guide",
    },
    {
        name: "setting_tab",
        label: "تبويبة إعدادات",
        route: "admin.settings.setting_tab",
    },
];

const setActiveTab = () => {
    const currentRoute = usePage().url;
    const activeIndex = tabs.findIndex((tab) =>
        currentRoute.includes(tab.name)
    );
    activeTab.value = activeIndex !== -1 ? activeIndex : 0;
};

watch(
    () => usePage().url,
    () => {
        setActiveTab();
    },
    { immediate: true }
);

setActiveTab();
</script>

<template>
    <div>
        <Header
            icon="pi-cog"
            title="الاعدادات"
            subtitle="إدارة إعدادات المنصة"
        />
        <div
            class="bg-white border border-slate-500/50 shadow-sm min-h-[80vh] rounded-lg overflow-auto"
        >
            <ul class="flex border-b border-slate-200" role="tablist" dir="rtl">
                <li
                    v-for="(tab, index) in tabs"
                    :key="index"
                    role="tab"
                    :aria-selected="activeTab === index"
                    class="flex-1 md:flex-none"
                >
                    <Link
                        :href="route(tab.route)"
                        class="block px-8 py-3 text-center md:text-right font-medium text-slate-600 hover:text-teal-800 hover:bg-teal-50 transition-colors duration-200"
                        :class="{
                            'text-teal-800 border-b-2 border-teal-800':
                                activeTab === index,
                        }"
                    >
                        {{ tab.label }}
                    </Link>
                </li>
            </ul>
            <div class="p-4">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
ul {
    margin: 0;
    padding: 0;
    list-style: none;
}
</style>
