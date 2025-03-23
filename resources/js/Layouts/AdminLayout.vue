<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const isSidebarCollapsed = ref(false);
const toggleSidebar = () => {
    isSidebarCollapsed.value = !isSidebarCollapsed.value;
};

// Track expanded menu groups
const expandedGroups = ref({});
const toggleGroup = (groupTitle) => {
    expandedGroups.value[groupTitle] = !expandedGroups.value[groupTitle];
};
const isGroupExpanded = (groupTitle) => {
    return expandedGroups.value[groupTitle] || false;
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

// Get current user
const user = computed(() => usePage().props.auth.user || { name: "John Doe" });

// Logout
const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div class="flex min-h-screen">
        <!-- Mobile sidebar toggle button - visible only when sidebar is collapsed on mobile -->
        <button
            v-if="isSidebarCollapsed"
            @click="toggleSidebar"
            class="mobile-toggle-button"
        >
            <i class="pi pi-bars"></i>
        </button>

        <aside :class="['sidebar', { collapsed: isSidebarCollapsed }]">
            <div class="sidebar-header">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 overflow-hidden">
                        <img
                            src="../../../public/assets/images/logo.png"
                            alt="Logo"
                            class="w-full h-full object-contain"
                        />
                    </div>
                    <span v-if="!isSidebarCollapsed" class="project-name"
                        >Admin Panel</span
                    >
                </div>
                <button @click="toggleSidebar" class="pt-2 ps-1" >
                    <i
                    style="font-size: 1.5rem"
                    class=""
                        :class="[
                            'pi',
                            isSidebarCollapsed
                                ? 'pi-angle-right'
                                : 'pi-angle-left',
                        ]"
                    ></i>
                </button>
            </div>

            <div class="navigation">
                <ul class="nav-list">
                    <li
                        v-for="item in navigationItems"
                        :key="item.title"
                        :class="['nav-item', { active: isGroupActive(item) }]"
                    >
                        <!-- Items with subitems -->
                        <template v-if="item.subItems">
                            <!-- Group header (only shown in expanded state) -->
                            <div
                                v-if="!isSidebarCollapsed"
                                :class="[
                                    'nav-link group-header',
                                    { 'group-active': isGroupActive(item) },
                                ]"
                                @click="toggleGroup(item.title)"
                            >
                                <i :class="['pi', item.icon]"></i>
                                <span
                                    :class="[
                                        'nav-title',
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
                                        'pi pi-chevron-down expand-icon',
                                        {
                                            'rotate-180': isGroupExpanded(
                                                item.title
                                            ),
                                        },
                                    ]"
                                ></i>
                            </div>

                            <!-- Group header (collapsed state) - just shows icon -->
                            <div
                                v-else
                                :class="[
                                    'nav-link group-header',
                                    { 'group-active': isGroupActive(item) },
                                ]"
                                @click="toggleGroup(item.title)"
                            >
                                <i :class="['pi', item.icon]"></i>
                            </div>

                            <!-- Subitems for expanded sidebar -->
                            <ul
                                v-if="
                                    !isSidebarCollapsed &&
                                    isGroupExpanded(item.title)
                                "
                                class="subnav-list"
                            >
                                <li
                                    v-for="subItem in item.subItems"
                                    :key="subItem.title"
                                    :class="[
                                        'subnav-item',
                                        { active: isPathActive(subItem.path) },
                                    ]"
                                >
                                    <Link :href="subItem.path" class="nav-link">
                                        <i :class="['pi', subItem.icon]"></i>
                                        <span class="nav-title">{{
                                            subItem.title
                                        }}</span>
                                    </Link>
                                </li>
                            </ul>

                            <!-- Subitems for collapsed sidebar - show directly under the group -->
                            <template
                                v-else-if="
                                    isSidebarCollapsed &&
                                    isGroupExpanded(item.title)
                                "
                            >
                                <li
                                    v-for="subItem in item.subItems"
                                    :key="subItem.title"
                                    class="nav-item"
                                >
                                    <Link
                                        :href="subItem.path"
                                        :class="[
                                            'nav-link',
                                            {
                                                active: isPathActive(
                                                    subItem.path
                                                ),
                                            },
                                        ]"
                                    >
                                        <i :class="['pi', subItem.icon]"></i>
                                    </Link>
                                </li>
                            </template>
                        </template>

                        <!-- Regular items -->
                        <Link
                            v-else
                            :href="item.path"
                            :class="[
                                'nav-link',
                                { active: isPathActive(item.path) },
                            ]"
                        >
                            <i :class="['pi', item.icon]"></i>
                            <span
                                v-if="!isSidebarCollapsed"
                                class="nav-title"
                                >{{ item.title }}</span
                            >
                        </Link>
                    </li>
                </ul>
            </div>

            <div class="user-section">
                <!-- Regular user section for expanded state -->
                <button
                    v-if="!isSidebarCollapsed"
                    @click="logout"
                    class="logout-btn"
                >
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="pi pi-user"></i>
                        </div>
                        <div class="flex flex-col gap-1 items-start justify-start text-start">
                            <p class=" text-sm">{{ user.name }}</p>
                            <p class="text-xs">{{ user.email }}</p>
                        </div>
                    </div>
                    <i class="pi pi-sign-out logout-icon"></i>
                </button>

                <!-- Stacked user avatar and logout for collapsed state -->
                <div v-else class="collapsed-user-section">
                    <div class="user-avatar">
                        <i class="pi pi-user"></i>
                    </div>
                    <button @click="logout" class="collapsed-logout-btn">
                        <i class="pi pi-sign-out"></i>
                    </button>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <slot></slot>
        </main>
    </div>
</template>

<style scoped>
.sidebar {
    display: flex;
    flex-direction: column;
    width: 250px;
    background-color: #1e293b;
    color: #e2e8f0;
    transition: width 0.3s ease;
    overflow-x: hidden;
}

.sidebar.collapsed {
    width: 70px;
}

.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid #334155;
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.project-name {
    font-weight: 600;
    font-size: 1.1rem;
    white-space: nowrap;
}

.toggle-button {
    background: transparent;
    border: none;
    color: #e2e8f0;
    cursor: pointer;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.toggle-button:hover {
    background-color: #334155;
}

.navigation {
    flex: 1;
    overflow-y: auto;
    padding: 1rem 0;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 0.25rem;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    text-decoration: none;
    color: #e2e8f0;
    border-radius: 4px;
    margin: 0 0.5rem;
    transition: background-color 0.2s;
}

.nav-link:hover {
    background-color: #334155;
}

.nav-link.active,
.subnav-item.active .nav-link {
    background-color: #3b82f6;
    color: white;
}

/* Styles for active group */
.group-active {
    color: #3b82f6;
}

.group-active:not(.active) {
    background-color: rgba(59, 130, 246, 0.1);
}

.font-semibold {
    font-weight: 600;
}

.group-header {
    cursor: pointer;
    justify-content: space-between;
}

.group-header .expand-icon {
    margin-left: auto;
    transition: transform 0.2s;
}

.group-header .expand-icon.rotate-180 {
    transform: rotate(180deg);
}

.subnav-list {
    list-style: none;
    padding: 0.25rem 0 0.25rem 1.5rem;
    margin: 0;
}

.subnav-item .nav-link {
    padding: 0.5rem 1rem;
}

.user-section {
    border-top: 1px solid #334155;
    padding: 1rem;
}

.logout-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 0.75rem 1rem;
    background-color: #334155;
    border: none;
    border-radius: 8px;
    color: #e2e8f0;
    cursor: pointer;
    transition: background-color 0.2s;
}

.logout-btn:hover {
    background-color: #475569;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    background-color: #3b82f6;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-name {
    font-weight: 500;
    white-space: nowrap;
}

/* Collapsed user section */
.collapsed-user-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.collapsed-logout-btn {
    background-color: #334155;
    border: none;
    border-radius: 50%;
    color: #e2e8f0;
    cursor: pointer;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
}

.collapsed-logout-btn:hover {
    background-color: #475569;
}

.main-content {
    flex: 1;
    background-color: #f1f5f9;
    padding: 1.5rem;
    overflow-y: auto;
}

/* Mobile toggle button */
.mobile-toggle-button {
    display: none;
    position: fixed;
    top: 1rem;
    left: 1rem;
    z-index: 60;
    background-color: #3b82f6;
    color: white;
    border: none;
    border-radius: 4px;
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        z-index: 50;
        height: 100%;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    .sidebar.collapsed {
        width: 0;
        padding: 0;
    }

    .main-content {
        margin-left: 0;
        width: 100%;
    }

    .mobile-toggle-button {
        display: flex;
    }
}
</style>
