<script setup>
defineProps({
    icon: {
        type: String,
        default: "pi-home",
    },
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        required: true,
    },
    isFontAwesome: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <header
        class="relative bg-gradient-to-r from-teal-600 to-teal-800 text-white rounded-xl shadow-lg p-4  mb-8 overflow-hidden "
    >
        <!-- Background Decorative Elements -->
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 left-0 w-24 h-24 sm:w-32 sm:h-32 bg-white rounded-full -translate-x-12 -translate-y-12 sm:-translate-x-16 sm:-translate-y-16"
            ></div>
            <div
                class="absolute bottom-0 right-0 w-32 h-32 sm:w-48 sm:h-48 bg-white rounded-full translate-x-16 translate-y-16 sm:translate-x-24 sm:translate-y-24"
            ></div>
        </div>

        <div
            class="flex flex-col sm:flex-row items-start sm:items-center justify-between relative z-10 gap-4"
        >
            <!-- Left Section: Icon, Title, Subtitle -->
            <div class="flex items-center gap-4 sm:gap-5 w-full sm:w-auto">
                <div class="flex-shrink-0">
                    <i v-if="!isFontAwesome"
                        :class="`pi ${icon} text-white`"
                        style="font-size: 2.5rem;"
                    ></i>
                    <Icon v-else :icon="icon" class="text-white w-10 h-10" />
                </div>
                <div class="max-w-full">
                    <h1
                        class="text-xl sm:text-2xl font-Bein truncate"
                    >
                        {{ title }}
                    </h1>
                    <p
                        class="text-teal-50 text-xs sm:text-sm mt-1 max-w-full sm:max-w-md"
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
