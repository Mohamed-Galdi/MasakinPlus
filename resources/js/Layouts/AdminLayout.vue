<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const isSidebarCollapsed = ref(false);
const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

const navigationItems = [
    {
        title: "Orders",
        path: route("admin.orders.index"),
        icon: "pi-shopping-cart",
    },
    {
        title: "Products",
        icon: "pi-box",
        subItems: [
            {
                title: "Stock",
                path: route("admin.products.index"),
                icon: "pi-list",
            },
            {
                title: "Packages",
                path: route("admin.packages.index"),
                icon: "pi-gift",
            },
        ],
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

const user = computed(() => usePage().props.auth.user || { name: "John Doe" });

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside
            :class="[
                'flex flex-col bg-slate-800 text-slate-200 transition-all duration-300 overflow-x-hidden fixed h-full z-50 md:static md:h-auto',
                {
                    'w-0 md:w-[70px]': isSidebarCollapsed,
                    'w-[250px]': !isSidebarCollapsed,
                },
            ]"
        >
            <!-- Header (fixed at top) -->
            <div
                class="flex items-center justify-between p-3 border-b border-slate-700 gap-1 min-h-[64px] flex-shrink-0"
            >
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-[30px] h-[30px] relative">
                        <img
                            src="../../../public/assets/images/logo.png"
                            alt="Logo"
                            class="absolute inset-0 w-full h-full object-contain"
                        />
                    </div>
                    <span
                        v-if="!isSidebarCollapsed"
                        class="font-semibold text-lg whitespace-nowrap"
                        >Admin Panel</span
                    >
                </div>
                <button
                    @click="toggleSidebar"
                    class="bg-transparent border-none py-4 text-slate-200 cursor-pointer w-6 h-6 flex items-center justify-center rounded hover:bg-slate-700"
                >
                    <i
                        :class="[
                            'pi',
                            isSidebarCollapsed
                                ? 'pi-angle-right'
                                : 'pi-angle-left',
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
                                        ? 'text-blue-500 bg-blue-500/10'
                                        : 'hover:bg-slate-700',
                                ]"
                                @click="toggleGroup(item.title)"
                            >
                                <i
                                    :class="[
                                        'pi',
                                        item.icon,
                                        'w-5 h-5 flex items-center justify-center',
                                    ]"
                                ></i>
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
                                        ? 'text-blue-500 bg-blue-500/10'
                                        : 'hover:bg-slate-700',
                                ]"
                                @click="toggleGroup(item.title)"
                            >
                                <i :class="['pi', item.icon, 'w-5 h-5']"></i>
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
                                            'flex items-center gap-3 p-2 mx-2 rounded text-slate-200 no-underline transition-colors',
                                            isSidebarCollapsed
                                                ? 'justify-center'
                                                : 'justify-start',
                                            {
                                                'bg-blue-500 text-white':
                                                    isPathActive(subItem.path),
                                                'hover:bg-slate-700':
                                                    !isPathActive(subItem.path),
                                            },
                                        ]"
                                    >
                                        <i
                                            :class="[
                                                'pi',
                                                subItem.icon,
                                                'w-5 h-5',
                                            ]"
                                        ></i>
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
                                'flex items-center gap-3 p-2 mx-2 rounded text-slate-200 no-underline transition-colors',
                                isSidebarCollapsed
                                    ? 'justify-center'
                                    : 'justify-start',
                                {
                                    'bg-blue-500 text-white': isPathActive(
                                        item.path
                                    ),
                                    'hover:bg-slate-700': !isPathActive(
                                        item.path
                                    ),
                                },
                            ]"
                        >
                            <i :class="['pi', item.icon, 'w-5 h-5']"></i>
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
            <div class="border-t border-slate-700 p-2 flex-shrink-0">
                <!-- Expanded state -->
                <div v-if="!isSidebarCollapsed" class="space-y-2">
                    <!-- Account Link -->
                    <Link
                        :href="route('admin.account.index')"
                        class="flex items-center justify-between w-full px-3 py-2 bg-slate-700 rounded-lg text-slate-200 no-underline transition-colors hover:bg-slate-600"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-blue-500 w-8 h-8 rounded-full flex items-center justify-center overflow-hidden"
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
                        class="flex items-center justify-between w-full px-3 py-2 bg-red-900/50 border-none rounded-lg text-slate-200 cursor-pointer transition-colors hover:bg-red-900"
                    >
                        <span class="font-medium">Logout</span>
                        <i class="pi pi-sign-out"></i>
                    </button>
                </div>

                <!-- Collapsed state -->
                <div v-else class="flex flex-col items-center gap-2">
                    <!-- Account Link -->
                    <Link
                        :href="route('admin.account.index')"
                        class="bg-slate-700 rounded-full text-slate-200 no-underline w-8 h-8 flex items-center justify-center transition-colors hover:bg-slate-600 overflow-hidden"
                    >
                        <img
                            v-if="user.image"
                            :src="user.image"
                            alt="User avatar"
                            class="w-full h-full object-cover"
                        />
                        <i v-else class="pi pi-user"></i>
                    </Link>

                    <!-- Logout Button -->
                    <button
                        @click="logout"
                        class="bg-red-900/50 border-none rounded-full text-slate-200 cursor-pointer w-8 h-8 flex items-center justify-center transition-colors hover:bg-red-900"
                    >
                        <i class="pi pi-sign-out"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Mobile Navbar -->
        <nav
            class="bg-slate-50 w-full h-14 py-2 px-4 fixed md:hidden shadow-md"
        >
            <button
                v-if="isSidebarCollapsed"
                @click="toggleSidebar"
                class="top-4 left-4 z-[60] bg-blue-500 text-white border-none rounded w-10 h-10 text-xl cursor-pointer flex items-center justify-center shadow-lg md:hidden"
            >
                <i class="pi pi-bars"></i>
            </button>
        </nav>

        <!-- Main Content -->
        <main
            class="flex-1 bg-slate-100 p-6 overflow-y-auto md:ml-0 md:mt-0 mt-12"
        >
            <slot></slot>
        </main>
    </div>
</template>

<style scoped>
aside {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.nav-title {
    flex: 1;
}

.overflow-y-auto {
    scrollbar-width: thin; /* Firefox */
    scrollbar-color: #4b5563 #1e293b; /* thumb: slate-600, track: slate-800 */
}

/* Webkit browsers (Chrome, Safari, Edge) */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px; /* Slim scrollbar */
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #1e293b; /* slate-800 */
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #4b5563; /* slate-600 */
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #64748b; /* slate-500 for hover effect */
}

/* Hide scrollbar for all browsers */
/* .overflow-y-auto {
    -ms-overflow-style: none; 
    scrollbar-width: none; 
}
.overflow-y-auto::-webkit-scrollbar {
    display: none;
} */
</style>
