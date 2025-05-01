<script setup>
import { computed } from "vue";

const props = defineProps({
    status: String,
    statusOptions: Array,
});

// Create a map of status values to their styles and labels
const statusStyles = computed(() => {
    const styles = {};
    props.statusOptions.forEach((option, index) => {
        // Define color palette based on status or index
        const colors = [
            {
                bg: "bg-green-100",
                text: "text-green-800",
                border: "border-green-400",
                darkBg: "bg-green-900",
                darkText: "text-green-300",
            },
            {
                bg: "bg-blue-100",
                text: "text-blue-800",
                border: "border-blue-400",
                darkBg: "bg-blue-900",
                darkText: "text-blue-300",
            },
            {
                bg: "bg-red-100",
                text: "text-red-800",
                border: "border-red-400",
                darkBg: "bg-red-900",
                darkText: "text-red-300",
            },
            {
                bg: "bg-yellow-100",
                text: "text-yellow-800",
                border: "border-yellow-400",
                darkBg: "bg-yellow-900",
                darkText: "text-yellow-300",
            },
            {
                bg: "bg-indigo-100",
                text: "text-indigo-800",
                border: "border-indigo-400",
                darkBg: "bg-indigo-900",
                darkText: "text-indigo-300",
            },
            {
                bg: "bg-indigo-100",
                text: "text-indigo-800",
                border: "border-indigo-400",
                darkBg: "bg-indigo-900",
                darkText: "text-indigo-300",
            },
            {
                bg: "bg-gray-100",
                text: "text-gray-800",
                border: "border-gray-400",
                darkBg: "bg-gray-900",
                darkText: "text-gray-300",
            },
        ];
        const color = colors[index % colors.length]; // Cycle through colors if more statuses than colors

        styles[option.value] = {
            label: option.label,
            ...color,
        };
    });
    return styles;
});

// Find current state based on status
const currentState = computed(() => {
    return (
        statusStyles.value[props.status] || {
            label: "Unknown",
            bg: "bg-gray-100",
            text: "text-gray-800",
            border: "border-gray-400",
            darkBg: "bg-gray-900",
            darkText: "text-gray-300",
        }
    );
});
</script>

<template>
    <div
        dir=""
        :class="`inline-flex gap-2 items-center ${currentState.bg} ${currentState.text} border ${currentState.border} text-xs font-medium px-2.5 py-0.5 rounded-full`"
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
