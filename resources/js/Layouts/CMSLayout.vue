<script setup>
import { ref, watch } from "vue";
import { usePage, Link } from "@inertiajs/vue3";

// Selected page state
const selectedPage = ref("home");

// Page selection based on route
const pages = [
    {
        name: "home",
        label: "الصفحة الرئيسية",
        route: "admin.settings.content.home",
        icon: "pi-home",
        description: "الصفحة الأولى للموقع",
    },
    {
        name: "about",
        label: "من نحن",
        route: "admin.settings.content.about",
        icon: "pi-users",
        description: "معلومات عن الشركة",
    },
    {
        name: "privacy",
        label: "سياسة الخصوصية",
        route: "admin.settings.content.privacy",
        icon: "pi-shield",
        description: "شروط الاستخدام",
    },
];

const setSelectedPage = () => {
    const currentRouteName = usePage().props.routeName || route().current();
    const activePage = pages.find((page) => currentRouteName === page.route);
    selectedPage.value = activePage ? activePage.name : "home";
};

watch(
    () => usePage().props.routeName || route().current(),
    () => {
        setSelectedPage();
    }
);

setSelectedPage();

// Get current page object
const getCurrentPage = () => {
    return pages.find((page) => page.name === selectedPage.value) || pages[0];
};

// Get last update time (placeholder)
const getLastUpdate = () => {
    return new Date().toLocaleDateString("ar-SA");
};
</script>

<template>
    <div class="flex flex-col lg:flex-row h-full min-h-[70vh] bg-slate-50">
        <!-- Mobile Header -->
        <div class="lg:hidden bg-white border-b p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div
                        class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center"
                    >
                        <i
                            :class="[
                                'pi',
                                getCurrentPage().icon,
                                'text-teal-600',
                            ]"
                        ></i>
                    </div>
                    <div>
                        <h1 class="font-semibold text-slate-900">
                            {{ getCurrentPage().label }}
                        </h1>
                        <p class="text-sm text-slate-500">
                            {{ getCurrentPage().description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 p-4 lg:p-6 min-h-[500px] order-2 lg:order-1">
            <slot />
        </div>

        <!-- Sidebar Navigation -->
        <div
            class="w-full lg:w-64 bg-white flex-shrink-0 order-1 lg:order-2 border-t lg:border-t-0 lg:border-l"
        >
            <!-- Desktop Navigation -->
            <nav class="p-2 hidden lg:block">
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
                                    {{ page.description }}
                                </div>
                            </div>
                        </Link>
                    </li>
                </ul>
            </nav>

            <!-- Mobile Navigation (Bottom Tabs) -->
            <nav class="lg:hidden">
                <ul class="flex">
                    <li v-for="page in pages" :key="page.name" class="flex-1">
                        <Link
                            :href="route(page.route)"
                            :class="[
                                'w-full p-4 transition-colors duration-200 flex flex-col items-center justify-center space-y-1',
                                selectedPage === page.name
                                    ? 'text-teal-600 bg-teal-50'
                                    : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50',
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
                                <i :class="['pi', page.icon, 'text-lg']"></i>
                            </div>
                            <!-- Hidden text on mobile as requested -->
                        </Link>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
