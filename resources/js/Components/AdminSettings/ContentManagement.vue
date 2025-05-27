<script setup>
import { ref } from "vue";

// Selected page state
const selectedPage = ref("home");

// Page selection method
const selectPage = (page) => {
    selectedPage.value = page;
};

// Get last update time (placeholder)
const getLastUpdate = () => {
    return new Date().toLocaleDateString("ar-SA");
};
</script>

<template>
    <div class="flex h-full min-h-[70vh] bg-slate-50">
        <!-- Main Content Area -->
        <div class="flex-1">
            <!-- Home Page Content -->
            <div
                v-if="selectedPage === 'home'"
                class="  p-6 min-h-[500px]"
            >
                <div class="text-center text-slate-500 mt-20">
                    <i class="pi pi-home text-4xl mb-4 text-slate-300"></i>
                    <h3 class="text-lg font-medium mb-2 text-slate-700">
                        محتوى الصفحة الرئيسية
                    </h3>
                    <p class="text-slate-500">
                        سيتم إضافة نموذج تحرير محتوى الصفحة الرئيسية هنا
                    </p>
                </div>
            </div>

            <!-- About Page Content -->
            <div
                v-if="selectedPage === 'about'"
                class=" p-6 min-h-[500px]"
            >
                <div class="text-center text-slate-500 mt-20">
                    <i class="pi pi-users text-4xl mb-4 text-slate-300"></i>
                    <h3 class="text-lg font-medium mb-2 text-slate-700">
                        محتوى صفحة من نحن
                    </h3>
                    <p class="text-slate-500">
                        سيتم إضافة نموذج تحرير محتوى صفحة من نحن هنا
                    </p>
                </div>
            </div>

            <!-- Privacy Policy Content -->
            <div
                v-if="selectedPage === 'privacy'"
                class="p-6 min-h-[500px]"
            >
                <div class="text-center text-slate-500 mt-20">
                    <i class="pi pi-shield text-4xl mb-4 text-slate-300"></i>
                    <h3 class="text-lg font-medium mb-2 text-slate-700">
                        محتوى سياسة الخصوصية
                    </h3>
                    <p class="text-slate-500">
                        سيتم إضافة نموذج تحرير محتوى سياسة الخصوصية هنا
                    </p>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation (Right Side) -->
        <div class="w-64 bg-white flex-shrink-0">

            <nav class="p-2">
                <ul class="space-y-1">
                    <!-- Home Button -->
                    <li>
                        <button
                            @click="selectPage('home')"
                            :class="[
                                'w-full text-right p-3 rounded-lg transition-colors duration-200 flex items-center space-x-3 space-x-reverse',
                                selectedPage === 'home'
                                    ? 'bg-teal-50 text-teal-700 border border-teal-200'
                                    : 'text-slate-700 hover:bg-slate-50',
                            ]"
                        >
                            <div
                                :class="[
                                    'w-8 h-8 rounded-lg flex items-center justify-center',
                                    selectedPage === 'home'
                                        ? 'bg-teal-100 text-teal-600'
                                        : 'bg-slate-100 text-slate-500',
                                ]"
                            >
                                <i class="pi pi-home text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium">الصفحة الرئيسية</div>
                                <div class="text-xs opacity-75">
                                    الصفحة الأولى للموقع
                                </div>
                            </div>
                        </button>
                    </li>

                    <!-- About Button -->
                    <li>
                        <button
                            @click="selectPage('about')"
                            :class="[
                                'w-full text-right p-3 rounded-lg transition-colors duration-200 flex items-center space-x-3 space-x-reverse',
                                selectedPage === 'about'
                                    ? 'bg-teal-50 text-teal-700 border border-teal-200'
                                    : 'text-slate-700 hover:bg-slate-50',
                            ]"
                        >
                            <div
                                :class="[
                                    'w-8 h-8 rounded-lg flex items-center justify-center',
                                    selectedPage === 'about'
                                        ? 'bg-teal-100 text-teal-600'
                                        : 'bg-slate-100 text-slate-500',
                                ]"
                            >
                                <i class="pi pi-users text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium">من نحن</div>
                                <div class="text-xs opacity-75">
                                    معلومات عن الشركة
                                </div>
                            </div>
                        </button>
                    </li>

                    <!-- Privacy Policy Button -->
                    <li>
                        <button
                            @click="selectPage('privacy')"
                            :class="[
                                'w-full text-right p-3 rounded-lg transition-colors duration-200 flex items-center space-x-3 space-x-reverse',
                                selectedPage === 'privacy'
                                    ? 'bg-teal-50 text-teal-700 border border-teal-200'
                                    : 'text-slate-700 hover:bg-slate-50',
                            ]"
                        >
                            <div
                                :class="[
                                    'w-8 h-8 rounded-lg flex items-center justify-center',
                                    selectedPage === 'privacy'
                                        ? 'bg-teal-100 text-teal-600'
                                        : 'bg-slate-100 text-slate-500',
                                ]"
                            >
                                <i class="pi pi-shield text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="font-medium">سياسة الخصوصية</div>
                                <div class="text-xs opacity-75">
                                    شروط الاستخدام
                                </div>
                            </div>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
