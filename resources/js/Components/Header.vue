<script setup>
defineProps({
    icon: {
        type: String,
        default: "pi-users",
    },
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        required: true,
    },
    isPrimeVue: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <header
        class="relative bg-teal-800 text-white p-2 sm:p-4 mb-8 rounded-lg shadow-xl overflow-hidden"
    >
        <!-- Diagonal Overlay -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-teal-600/30 to-transparent transform -skew-y-6"
        ></div>

        <div
            class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
        >
            <!-- Left Section: Icon, Title, Subtitle -->
            <div class="flex items-center gap-4 sm:gap-5 w-full sm:w-auto">
                <div class="flex-shrink-0 relative">
                    <Icon
                        v-if="!isPrimeVue"
                        :icon="icon"
                        class="text-white w-10 h-10"
                    />
                    <i
                        v-else
                        :class="`pi ${icon} text-white`"
                        style="font-size: 2rem"
                    ></i>
                </div>
                <div class="max-w-full">
                    <h1
                        class="text-lg sm:text-2xl font-Bein truncate text-slate-100"
                    >
                        {{ title }}
                    </h1>
                    <p
                        class="text-slate-200 text-xs sm:text-sm mt-1 max-w-full sm:max-w-md font-BeinNormal "
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
