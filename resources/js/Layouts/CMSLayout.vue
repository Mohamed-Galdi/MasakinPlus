<script setup>
import { ref, watch } from "vue";
import { usePage, Link } from "@inertiajs/vue3";

// Selected page state
const selectedPage = ref("home");

// Page selection based on route
const pages = [
    { name: "home", label: "الصفحة الرئيسية", route: "admin.settings.content.home", icon: "pi-home" },
    { name: "about", label: "من نحن", route: "admin.settings.content.about", icon: "pi-users" },
    { name: "privacy", label: "سياسة الخصوصية", route: "admin.settings.content.privacy", icon: "pi-shield" },
];

const setSelectedPage = () => {
    const currentRouteName = usePage().props.routeName || route().current();
    const activePage = pages.find((page) => currentRouteName === page.route);
    selectedPage.value = activePage ? activePage.name : "home";
};

watch(() => usePage().props.routeName || route().current(), () => {
    setSelectedPage();
});

setSelectedPage();

// Get last update time (placeholder)
const getLastUpdate = () => {
    return new Date().toLocaleDateString("ar-SA");
};
</script>

<template>
    <div class="flex h-full min-h-[70vh] bg-slate-50">
        <!-- Main Content Area -->
        <div class="flex-1 p-6 min-h-[500px]">
            <slot />
        </div>

        <!-- Sidebar Navigation (Right Side) -->
        <div class="w-64 bg-white flex-shrink-0">
            <nav class="p-2">
                <ul class="space-y-1">
                    <li v-for="page in pages" :key="page.name">
                        <Link
                            :href="route(page.route)"
                            :class="[
                                'w-full text-right p-3 rounded-lg transition-colors duration-200 flex items-center space-x-3 space-x-reverse',
                                selectedPage === page.name
                                    ? 'bg-teal-50 text-teal-700 border border-teal-200'
                                    : 'text-slate-700 hover:bg-slate-50',
                            ]"
                        >
                            <div
                                :class="[
                                    'w-8 h-8 rounded-lg flex items-center justify-center',
                                    selectedPage === page.name
                                        ? 'bg-teal-100 text-teal-600'
                                        : 'bg-slate-100 text-slate-500',
                                ]"
                            >
                                <i :class="['pi', page.icon, 'text-sm']"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium">{{ page.label }}</div>
                                <div class="text-xs opacity-75">
                                    {{ page.name === "home" ? "الصفحة الأولى للموقع" : page.name === "about" ? "معلومات عن الشركة" : "شروط الاستخدام" }}
                                </div>
                            </div>
                        </Link>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>