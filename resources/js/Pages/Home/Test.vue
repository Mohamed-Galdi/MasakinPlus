<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref, onMounted } from "vue";

defineOptions({
    layout: AdminLayout,
});
</script>

<template>
    <header
        class="bg-white border-l-4 border-amber-400 shadow-sm p-4 sm:p-6 mb-8 rounded-r-lg transition-transform hover:-translate-y-1"
    >
        <div
            class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
        >
            <!-- Left Section: Icon, Title, Subtitle -->
            <div class="flex items-center gap-4 sm:gap-5 w-full sm:w-auto">
                <div class="flex-shrink-0 bg-amber-100 p-2 rounded-full">
                    <i
                        :class="`pi ${icon} text-amber-600`"
                    ></i>
                </div>
                <div class="max-w-full">
                    <h1
                        class="text-lg sm:text-xl font-semibold text-gray-800 truncate"
                    >
                        {{ title }}
                    </h1>
                    <p
                        class="text-gray-500 text-xs sm:text-sm mt-1 max-w-full sm:max-w-md"
                    >
                        {{ subtitle }}
                    </p>
                </div>
            </div>

            <!-- Right Section: Slot for Optional Content -->
            <div class="w-full sm:w-auto">
                <slot></slot>
            </div>
        </div>
    </header>
</template>

<style scoped></style>
