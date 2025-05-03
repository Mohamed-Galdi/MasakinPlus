<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, onMounted } from "vue";

defineOptions({
    layout: AdminLayout,
});
</script>

<template>
    <header
        class="relative bg-gradient-to-r from-teal-600 to-teal-800 text-white rounded-xl shadow-lg p-4 mb-8 overflow-hidden"
    >
        <!-- Background Decorative Elements -->
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 left-0 w-32 h-32 bg-white rounded-full -translate-x-16 -translate-y-16"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-48 h-48 bg-white rounded-full translate-x-24 translate-y-24"
            ></div>
        </div>

        <div class="flex items-center justify-between relative z-10">
            <!-- Left Section: Icon, Title, Subtitle -->
            <div class="flex items-center gap-5">
                <div class="flex-shrink-0 relative">
                    <i
                        class="pi pi-home text-white"
                        style="font-size: 2.5rem"
                    ></i>
                </div>
                <div>
                    <h1 class="text-3xl">العقارات</h1>
                    <p class="text-teal-100 text-sm mt-1 max-w-md">
                        إدارة عقارات النظام المتاحة للإستثمار و الكراء
                    </p>
                </div>
            </div>

            <!-- Right Section: Button -->
            <div>
                <button
                    class="flex items-center gap-2 bg-white text-teal-700 px-5 py-2.5 rounded-full font-medium hover:bg-amber-100 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1"
                >
                    <span>إضافة عقار</span>
                    <i class="pi pi-plus"></i>
                </button>
            </div>
        </div>
    </header>
</template>

<style scoped></style>
