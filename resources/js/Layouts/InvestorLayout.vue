<script setup>
import { ref, watch } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { useTextHelpers } from "@/plugins/textHelpers";

const textHelpers = useTextHelpers();
const user = usePage().props.user;

// Reactive state for mobile menu and profile dropdown
const isMobileMenuOpen = ref(false);
const isProfileOpen = ref(false);

// Get current page URL from Inertia
const currentPath = ref(usePage().url);

// Navigation items using Ziggy routes
const navigationItems = ref([
    {
        name: "عقاراتي ",
        href: route("investor.dashboard"),
        icon: "fa-solid fa-building",
    },
    {
        name: "الإيجارات ",
        href: route("investor.properties"),
        icon: "fa-solid fa-file-contract",
    },
    {
        name: "التقارير",
        href: route("test"),
        icon: "fa-solid fa-chart-pie",
    },
    {
        name: "المحفظة",
        href: route("wallet.index"),
        icon: "fa-solid fa-wallet",
    },
    {
        name: "الدعم",
        href: route("support.index"),
        icon: "fa-solid fa-headset",
    }
]);

// Function to determine if a navigation item is active (from second component)
const isActive = (menuItems) => {
    return menuItems.some((item) =>
        currentPath.value.startsWith(
            new URL(item.href, window.location.origin).pathname
        )
    );
};

// Close dropdowns when clicking outside
function closeDropdowns(event) {
    if (!event.target.closest(".relative")) {
        isProfileOpen.value = false;
    }
}

// Add event listener for clicks outside the dropdown
window.addEventListener("click", closeDropdowns);

// Watch for route changes
watch(
    () => usePage().url,
    (newUrl) => {
        currentPath.value = newUrl;
    }
);

// Logout
function logout() {
    router.post(route("logout"));
}
</script>
<template>
    <div class="min-h-screen bg-gray-100 font-Bein">
        <!-- Top Navigation Bar -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto">
                <div
                    class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8"
                >
                    <!-- Logo -->
                    <Link
                        :href="route('home')"
                        class="flex-shrink-0 flex flex-col items-center"
                    >
                        <img
                            class="h-12 w-auto"
                            src="/assets/logos/logo.png"
                            alt="الشعار"
                        />
                    </Link>

                    <!-- Desktop Navigation -->
                    <div
                        class="hidden md:flex md:items-center md:space-x-reverse md:space-x-8"
                    >
                        <Link
                            v-for="item in navigationItems"
                            :key="item.name"
                            :href="item.href"
                            :class="[
                                isActive([item])
                                    ? 'text-teal-700 border-b-2 border-teal-700'
                                    : 'text-gray-500 hover:text-gray-700',
                                'inline-flex items-center px-1 pt-1 text-sm font-medium h-16',
                            ]"
                        >
                            <Icon :icon="item.icon" class="ml-2 mb-1 h-4 w-4" />
                            {{ item.name }}
                        </Link>
                    </div>

                    <!-- Account Card -->
                    <div class="hidden md:block">
                        <div
                            class="flex items-center bg-gray-50 rounded-md border border-gray-200"
                        >
                            <!-- User info -->
                            <div class="px-3 py-2">
                                <p class="text-sm text-slate-800">
                                    {{ textHelpers.limitText(user.name, 20) }}
                                </p>
                                <p class="text-xs font-medium text-teal-700">
                                    حساب مستثمر
                                </p>
                            </div>

                            <!-- Divider -->
                            <div class="h-10 w-px bg-gray-200"></div>

                            <!-- User avatar -->
                            <div class="relative px-3">
                                <img
                                    class="h-8 w-8 rounded-full"
                                    :src="user.image"
                                    alt="المستخدم"
                                />
                                <button
                                    @click="isProfileOpen = !isProfileOpen"
                                    class="absolute inset-0 w-full h-full rounded-full focus:outline-none"
                                >
                                    <span class="sr-only"
                                        >فتح قائمة المستخدم</span
                                    >
                                </button>

                                <!-- Dropdown Menu -->
                                <div
                                    v-if="isProfileOpen"
                                    class="origin-top-left absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                                >
                                    <Link
                                        :href="route('account.index')"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >الملف الشخصي</Link
                                    >
                                    <div
                                        class="border-t border-gray-100 my-1"
                                    ></div>
                                    <button
                                        @click="logout"
                                        class="block text-start px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full"
                                    >
                                        تسجيل الخروج
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex md:hidden">
                        <button
                            @click="isMobileMenuOpen = !isMobileMenuOpen"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-teal-700 hover:bg-gray-100 focus:outline-none"
                        >
                            <span class="sr-only">فتح القائمة الرئيسية</span>
                            <Icon
                                v-if="!isMobileMenuOpen"
                                icon="fa-solid fa-bars"
                                class="block h-5 w-5"
                            />
                            <Icon
                                v-else
                                icon="fa-solid fa-xmark"
                                class="block h-5 w-5"
                            />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div
                v-if="isMobileMenuOpen"
                class="md:hidden border-t border-gray-200"
            >
                <!-- Mobile Navigation Links -->
                <div class="pt-2 pb-3 space-y-1">
                    <Link
                        v-for="item in navigationItems"
                        :key="item.name"
                        :href="item.href"
                        :class="[
                            isActive([item])
                                ? 'bg-teal-50 border-r-4 border-teal-700 text-teal-700'
                                : 'border-r-4 border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                            'block pr-3 pl-4 py-2 text-base font-medium',
                        ]"
                    >
                        <div class="flex items-center">
                            <Icon :icon="item.icon" class="ml-3 h-4 w-4" />
                            {{ item.name }}
                        </div>
                    </Link>
                </div>

                <!-- Mobile Profile -->
                <div class="pt-4 pb-3 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <img
                                class="h-10 w-10 rounded-full"
                                :src="user.image"
                                alt="المستخدم"
                            />
                        </div>
                        <div class="mr-3">
                            <div class="px-3 py-2">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ textHelpers.limitText(user.name, 40) }}
                                </p>
                                <p class="text-xs font-medium text-teal-700">
                                    حساب مستثمر
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <Link
                            :href="route('account.index')"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                        >
                            الملف الشخصي
                        </Link>
                        <button
                            @click="logout"
                            class="block text-start px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full"
                        >
                            تسجيل الخروج
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
