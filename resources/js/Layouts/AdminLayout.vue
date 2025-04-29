<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const isSidebarCollapsed = ref(
    localStorage.getItem("sidebarCollapsed") === "true"
);
const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
    localStorage.setItem("sidebarCollapsed", isSidebarCollapsed.value);
};

const navigationItems = [
    // {
    //     title: "المستخدمين",
    //     icon: "pi-users",
    //     subItems: [
    //         {
    //             title: "الملاك",
    //             path: route("home"),
    //             iconType: "primevue",
    //             icon: "pi-users",
    //         },
    //         {
    //             title: "المستثمرين",
    //             path: route("home"),
    //             iconType: "primevue",
    //             icon: "pi-briefcase",
    //         },
    //         {
    //             title: "المستأجرين",
    //             path: route("home"),
    //             iconType: "fontawesome",
    //             icon: "fa-solid fa-user-group",
    //         },
    //     ],
    // },

    {
        title: "المستخدمين",
        path: route("admin.users.index"),
        iconType: "primevue",
        icon: "pi-users",
    },
    {
        title: "العقارات",
        path: route("admin.properties"),
        iconType: "primevue",
        icon: "pi-home",
    },
    {
        title: "طلبات فتح الاستثمار",
        path: route("admin.investment-requests.index"),
        iconType: "fontawesome",
        icon: "fa-regular fa-file-zipper",
    },
    {
        title: "الدعم",
        path: route("admin.support.index"),
        iconType: "primevue",
        icon: "pi-comments",
    },
];

// Track expanded menu groups - initialize with all groups closed by default
const expandedGroups = ref(
    Object.fromEntries(
        navigationItems
            .filter((item) => item.subItems)
            .map((item) => [item.title, false])
    )
);

const toggleGroup = (groupTitle) => {
    expandedGroups.value[groupTitle] = !expandedGroups.value[groupTitle];
};
const isGroupExpanded = (groupTitle) => {
    return expandedGroups.value[groupTitle] || false;
};

const currentPath = computed(() => {
    const path = usePage().url;
    return path.split("?")[0].split("#")[0];
});

const isPathActive = (path) => {
    if (!path) return false;
    try {
        const urlPath = new URL(path).pathname;
        const cleanUrlPath = urlPath.split("?")[0].split("#")[0];
        return currentPath.value.startsWith(cleanUrlPath);
    } catch {
        const cleanPath = path.split("?")[0].split("#")[0];
        return currentPath.value.startsWith(cleanPath);
    }
};

const isGroupActive = (item) => {
    if (item.path && isPathActive(item.path)) return true;
    if (item.subItems) {
        return item.subItems.some((subItem) => isPathActive(subItem.path));
    }
    return false;
};

const user = computed(() => usePage().props.user || { name: " غير متوفر" });

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div class="flex min-h-screen font-BeinNormal" dir="rtl">
        <!-- Sidebar -->
        <aside
            :class="[
                'flex flex-col sidebar-bg sidebar-text transition-all duration-300 overflow-x-hidden fixed h-screen z-[999]', // Changed h-full to h-screen, removed md:static and md:h-auto
                {
                    'w-0 md:w-[70px]': isSidebarCollapsed,
                    'w-[250px]': !isSidebarCollapsed,
                },
            ]"
        >
            <!-- Header (fixed at top) -->
            <div
                class="flex items-center justify-between p-3 sidebar-border-bottom gap-1 min-h-[64px] flex-shrink-0"
            >
                <Link :href="route('home')" class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-[30px] h-[30px] relative">
                        <img
                            src="/assets/logos/logo.png"
                            alt="Logo"
                            class="absolute inset-0 w-full h-full object-contain"
                        />
                    </div>
                    <span
                        v-if="!isSidebarCollapsed"
                        class="text-lg whitespace-nowrap"
                        >لوحة المشرف</span
                    >
                </Link>
                <button
                    @click="toggleSidebar"
                    class="bg-transparent border-none py-4 sidebar-text cursor-pointer w-6 h-6 flex items-center justify-center rounded sidebar-hover"
                >
                    <i
                        :class="[
                            'pi',
                            isSidebarCollapsed
                                ? 'pi-angle-left'
                                : 'pi-angle-right',
                        ]"
                    ></i>
                </button>
            </div>

            <!-- Scrollable navigation items -->
            <div class="flex-1 overflow-y-auto py-4">
                <ul class="list-none p-0 m-0">
                    <li
                        v-for="item in navigationItems"
                        :key="item.title"
                        class="mb-1"
                    >
                        <template v-if="item.subItems">
                            <!-- Group header (expanded state) -->
                            <div
                                :title="item.title"
                                v-if="!isSidebarCollapsed"
                                :class="[
                                    'flex items-center gap-2 p-2 mx-2 rounded cursor-pointer transition-colors',
                                    isGroupActive(item)
                                        ? 'active-subtle'
                                        : 'sidebar-hover',
                                ]"
                                @click="toggleGroup(item.title)"
                            >
                                <i
                                    v-if="item.iconType === 'primevue'"
                                    :class="[
                                        'pi',
                                        item.icon,
                                        'w-5 h-5 flex items-center justify-center',
                                    ]"
                                ></i>
                                <Icon
                                    v-else
                                    :icon="item.icon"
                                    class="ml-2 mb-1 h-4 w-4 text-gray-600"
                                />
                                <span
                                    :class="[
                                        'nav-title flex-1',
                                        {
                                            'font-semibold':
                                                isGroupActive(item),
                                        },
                                    ]"
                                >
                                    {{ item.title }}
                                </span>
                                <i
                                    :class="[
                                        'pi pi-chevron-down transition-transform w-5 h-5 flex items-center justify-center',
                                        {
                                            'rotate-180': isGroupExpanded(
                                                item.title
                                            ),
                                        },
                                    ]"
                                ></i>
                            </div>

                            <!-- Group header (collapsed state) -->
                            <div
                                :title="item.title"
                                v-else
                                :class="[
                                    'flex items-center justify-center p-2 mx-2 rounded cursor-pointer transition-colors',
                                    isGroupActive(item)
                                        ? 'active-subtle'
                                        : 'sidebar-hover',
                                ]"
                                @click="toggleGroup(item.title)"
                            >
                                <i
                                    v-if="item.iconType === 'primevue'"
                                    :class="[
                                        'pi',
                                        item.icon,
                                        'w-5 h-5 flex items-center justify-center',
                                    ]"
                                ></i>
                                <Icon
                                    v-else
                                    :icon="item.icon"
                                    class="ml-2 mb-1 h-4 w-4 text-gray-600"
                                />
                            </div>

                            <!-- Subitems -->
                            <ul
                                v-if="isGroupExpanded(item.title)"
                                :class="[
                                    'list-none p-1 m-0',
                                    isSidebarCollapsed ? '' : 'pl-6',
                                ]"
                            >
                                <li
                                    v-for="subItem in item.subItems"
                                    :key="subItem.title"
                                    class="mb-1"
                                >
                                    <Link
                                        :title="subItem.title"
                                        :href="subItem.path"
                                        :class="[
                                            'flex items-center gap-3 p-2 mx-2 rounded sidebar-text no-underline transition-colors',
                                            isSidebarCollapsed
                                                ? 'justify-center'
                                                : 'justify-start',
                                            {
                                                'active-item': isPathActive(
                                                    subItem.path
                                                ),
                                                'sidebar-hover': !isPathActive(
                                                    subItem.path
                                                ),
                                            },
                                        ]"
                                    >
                                        <i
                                            v-if="item.iconType === 'primevue'"
                                            :class="[
                                                'pi',
                                                item.icon,
                                                'w-5 h-5 flex items-center justify-center',
                                            ]"
                                        ></i>
                                        <Icon
                                            v-else
                                            :icon="item.icon"
                                            class="ml-2 mb-1 h-4 w-4"
                                        />
                                        <span
                                            v-if="!isSidebarCollapsed"
                                            class="nav-title"
                                            >{{ subItem.title }}</span
                                        >
                                    </Link>
                                </li>
                            </ul>
                        </template>

                        <!-- Regular items -->
                        <Link
                            :title="item.title"
                            v-else
                            :href="item.path"
                            :class="[
                                'flex items-center gap-3 p-2 mx-2 rounded sidebar-text no-underline transition-colors',
                                isSidebarCollapsed
                                    ? 'justify-center'
                                    : 'justify-start',
                                {
                                    'active-item': isPathActive(item.path),
                                    'sidebar-hover': !isPathActive(item.path),
                                },
                            ]"
                        >
                            <i
                                v-if="item.iconType === 'primevue'"
                                :class="[
                                    'pi',
                                    item.icon,
                                    'w-5 h-5 flex items-center justify-center',
                                ]"
                            ></i>
                            <Icon
                                v-else
                                :icon="item.icon"
                                class="ml-2 mb-1 h-4 w-4 "
                            />
                            <span
                                v-if="!isSidebarCollapsed"
                                class="nav-title"
                                >{{ item.title }}</span
                            >
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Footer (fixed at bottom) -->
            <div class="sidebar-border-top p-2 flex-shrink-0">
                <!-- Expanded state -->
                <div v-if="!isSidebarCollapsed" class="space-y-2">
                    <!-- Account Link -->
                    <Link
                        :href="route('admin.account.index')"
                        class="flex items-center justify-between w-full px-3 py-2 footer-item rounded-lg sidebar-text no-underline transition-colors footer-hover"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="avatar-bg w-8 h-8 rounded-full flex items-center justify-center overflow-hidden"
                            >
                                <img
                                    v-if="user.image"
                                    :src="user.image"
                                    alt="User avatar"
                                    class="w-full h-full object-cover"
                                />
                                <i v-else class="pi pi-user text-white"></i>
                            </div>
                            <span class="font-medium whitespace-nowrap">{{
                                user.name
                            }}</span>
                        </div>
                        <i class="pi pi-cog"></i>
                    </Link>

                    <!-- Logout Button -->
                    <button
                        @click="logout"
                        class="flex items-center justify-between w-full px-3 py-2 logout-bg border-none rounded-lg sidebar-text cursor-pointer transition-colors logout-hover"
                    >
                        <span class="font-medium whitespace-nowrap"
                            >تسجيل الخروج</span
                        >
                        <i class="pi pi-sign-out"></i>
                    </button>
                </div>

                <!-- Collapsed state -->
                <div v-else class="flex flex-col items-center gap-2">
                    <!-- Account Link -->
                    <Link
                        :href="route('admin.account.index')"
                        class="footer-item rounded-full sidebar-text no-underline w-8 h-8 flex items-center justify-center transition-colors footer-hover overflow-hidden"
                    >
                        <img
                            v-if="user.image"
                            :src="user.image"
                            alt="User avatar"
                            class="w-full h-full object-cover"
                        />
                        <div
                            v-else
                            class="avatar-bg w-8 h-8 rounded-full flex items-center justify-center overflow-hidden"
                        >
                            <i class="pi pi-user text-white"></i>
                        </div>
                    </Link>

                    <!-- Logout Button -->
                    <button
                        @click="logout"
                        class="logout-bg border-none rounded-full sidebar-text cursor-pointer w-8 h-8 flex items-center justify-center transition-colors logout-hover"
                    >
                        <i class="pi pi-sign-out"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Mobile Navbar -->
        <nav
            class="bg-slate-50 w-full h-14 py-2 px-4 fixed md:hidden shadow-md z-[998]"
        >
            <button
                v-if="isSidebarCollapsed"
                @click="toggleSidebar"
                class="top-4 left-4 z-[60] mobile-btn text-white border-none rounded w-10 h-10 text-xl cursor-pointer flex items-center justify-center shadow-lg md:hidden"
            >
                <i class="pi pi-bars"></i>
            </button>
        </nav>

        <!-- Main Content -->
        <main
            dir=""
            :class="[
                'flex-1 bg-slate-100 p-6 overflow-y-auto', // Kept overflow-y-auto
                isSidebarCollapsed ? ' md:mr-[70px]' : 'md:mr-[250px]', // Dynamic margin-left based on sidebar state
                'mt-12 md:mt-0', // Adjusted for mobile navbar
            ]"
        >
            <slot></slot>
        </main>
    </div>
</template>

<style scoped>
/* Dynamic color classes */
.sidebar-bg {
    background-color: var(--sidebar-bg);
}

.sidebar-text {
    color: var(--sidebar-text);
}

.sidebar-border-bottom,
.sidebar-border-top {
    border-color: var(--sidebar-border);
}

.sidebar-border-bottom {
    border-bottom-width: 1px;
    border-bottom-style: solid;
}

.sidebar-border-top {
    border-top-width: 1px;
    border-top-style: solid;
}

.sidebar-hover:hover {
    background-color: var(--sidebar-hover-bg);
}

.active-item {
    background-color: var(--active-bg) !important;
    color: var(--active-text) !important;
}

.active-subtle {
    color: var(--active-bg);
    background-color: var(--active-bg-opacity);
}

.footer-item {
    background-color: var(--footer-item-bg);
}

.footer-hover:hover {
    background-color: var(--footer-hover-bg);
}

.logout-bg {
    background-color: var(--logout-bg);
}

.logout-hover:hover {
    background-color: var(--logout-hover-bg);
}

.avatar-bg {
    background-color: var(--avatar-bg);
}

.mobile-btn {
    background-color: var(--mobile-btn-bg);
}

aside {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

main {
    height: 100vh; /* Full viewport height */
}

.nav-title {
    flex: 1;
}

/* Webkit browsers (Chrome, Safari, Edge) */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px; /* Slim scrollbar */
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: var(--scrollbar-track);
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: var(--scrollbar-thumb);
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: var(--scrollbar-hover);
}

/* Hide scrollbar for all browsers */
/* .overflow-y-auto {
    -ms-overflow-style: none; 
    scrollbar-width: none; 
}
.overflow-y-auto::-webkit-scrollbar {
    display: none;
} */

@media (max-width: 767px) {
    main {
        height: calc(
            100vh - 3.5rem
        ); /* Adjust for mobile navbar height (h-14 = 3.5rem) */
    }
}
</style>
