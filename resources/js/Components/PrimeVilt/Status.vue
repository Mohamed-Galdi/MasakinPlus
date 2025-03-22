<script setup>
import { computed } from "vue";

const props = defineProps({
    status: String,
});

// Define styles and labels for all statuses
const options = {
    yes: {
        label: "بسي",
        bg: "bg-green-100",
        text: "text-green-800",
        border: "border-green-400",
        darkBg: "bg-green-900",
        darkText: "text-green-300",
    },
    no: {
        label: "No",
        bg: "bg-red-100",
        text: "text-red-800",
        border: "border-red-400",
        darkBg: "bg-red-900",
        darkText: "text-red-300",
    },
    undefined: {
        label: "Undefined",
        bg: "bg-blue-100",
        text: "text-blue-800",
        border: "border-blue-400",
        darkBg: "bg-blue-900",
        darkText: "text-blue-300",
    },
    unused: {
        label: "Unused",
        bg: "bg-gray-100",
        text: "text-gray-800",
        border: "border-gray-400",
        darkBg: "bg-gray-900",
        darkText: "text-gray-300",
    },
    pending: {
        label: "Pending",
        bg: "bg-yellow-100",
        text: "text-yellow-800",
        border: "border-yellow-400",
        darkBg: "bg-yellow-900",
        darkText: "text-yellow-300",
    },
};

// Computed property to determine the current state
const currentState = options[props.status] || options.unused; // Fallback to "unused" if status is unknown
</script>

<template>
    <div
        dir="ltr"
        :class="`inline-flex gap-2 items-center ${currentState.bg} ${currentState.text} border ${currentState.border} text-xs font-medium px-2.5 py-0.5 rounded-full `"
    >
        <!-- Animated Ping Effect -->
        <span
            class="flex size-2 items-center justify-center rounded-full"
            :class="currentState.darkBg"
            aria-label="notification"
        >
            <span
                class="size-2 animate-ping rounded-full motion-reduce:animate-none"
                :class="currentState.darkBg"
            ></span>
        </span>
        <p>{{ currentState.label }}</p>
    </div>
</template>
