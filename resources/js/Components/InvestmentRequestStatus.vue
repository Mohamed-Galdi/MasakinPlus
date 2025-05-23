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
                bg: "bg-blue-100",
                text: "text-blue-800",
                border: "border-blue-400",
                darkBg: "bg-blue-900",
                darkText: "text-blue-300",
            },
            {
                bg: "bg-yellow-100",
                text: "text-yellow-800",
                border: "border-yellow-400",
                darkBg: "bg-yellow-900",
                darkText: "text-yellow-300",
            },
            {
                bg: "bg-green-100",
                text: "text-green-800",
                border: "border-green-400",
                darkBg: "bg-green-900",
                darkText: "text-green-300",
            },
            {
                bg: "bg-purple-100",
                text: "text-purple-800",
                border: "border-purple-400",
                darkBg: "bg-purple-900",
                darkText: "text-purple-300",
            },
            {
                bg: "bg-gray-100",
                text: "text-gray-800",
                border: "border-gray-400",
                darkBg: "bg-gray-900",
                darkText: "text-gray-300",
            },
        ];
        const color = colors[index % colors.length];

        styles[option.value] = {
            label: option.label,
            ...color,
        };
    });
    return styles;
});

// Find the current state style and label
const currentState = computed(() => {
    return (
        statusStyles.value[props.status] || {
            label: "غير معروف",
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
        dir="ltr"
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
