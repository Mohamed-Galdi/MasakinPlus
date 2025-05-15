<script setup>
import { computed } from "vue";

const props = defineProps({
    status: String,
    statusOptions: Array,
});

// Create a map of status values to their styles and labels with vibrant, shiny colors
const statusStyles = computed(() => {
    // Predefined styles for each status with vibrant colors
    const styles = {
    draft: {
        bg: "bg-blue-100",
        text: "text-blue-800",
        border: "border-blue-300",
        darkBg: "bg-blue-700",
        darkText: "text-blue-100",
        icon: "pi pi-file",
    },
    investment_requested: {
        bg: "bg-amber-100",
        text: "text-amber-800",
        border: "border-amber-300",
        darkBg: "bg-amber-700",
        darkText: "text-amber-100",
        icon: "pi pi-list",
    },
    investment_offer_sent: {
        bg: "bg-sky-100",
        text: "text-sky-800",
        border: "border-sky-300",
        darkBg: "bg-sky-700",
        darkText: "text-sky-100",
        icon: "pi pi-send",
    },
    open_for_investment: {
        bg: "bg-emerald-100",
        text: "text-emerald-800",
        border: "border-emerald-300",
        darkBg: "bg-emerald-700",
        darkText: "text-emerald-100",
        icon: "pi pi-chart-line",
    },
    funded_under_maintenance: {
        bg: "bg-purple-100",
        text: "text-purple-800",
        border: "border-purple-300",
        darkBg: "bg-purple-700",
        darkText: "text-purple-100",
        icon: "pi pi-wrench",
    },
    rent_requested: {
        bg: "bg-cyan-100",
        text: "text-cyan-800",
        border: "border-cyan-300",
        darkBg: "bg-cyan-700",
        darkText: "text-cyan-100",
        icon: "pi pi-check-square",
    },
    rent_rejected: {
        bg: "bg-red-100",
        text: "text-red-800",
        border: "border-red-300",
        darkBg: "bg-red-700",
        darkText: "text-red-100",
        icon: "pi pi-times",
    },
    listed_for_rent: {
        bg: "bg-teal-100",
        text: "text-teal-800",
        border: "border-teal-300",
        darkBg: "bg-teal-700",
        darkText: "text-teal-100",
        icon: "pi pi-list-box",
    },
    rented: {
        bg: "bg-lime-100",
        text: "text-lime-800",
        border: "border-lime-300",
        darkBg: "bg-lime-700",
        darkText: "text-lime-100",
        icon: "pi pi-home",
    },
    lease_ended: {
        bg: "bg-orange-100",
        text: "text-orange-800",
        border: "border-orange-300",
        darkBg: "bg-orange-700",
        darkText: "text-orange-100",
        icon: "pi pi-calendar-times",
    },
    unlisted: {
        bg: "bg-gray-200",
        text: "text-gray-700",
        border: "border-gray-300",
        darkBg: "bg-gray-600",
        darkText: "text-gray-100",
        icon: "pi pi-eye-slash",
    },
};


    // If statusOptions is provided, map them to styles
    if (props.statusOptions && props.statusOptions.length) {
        const mappedStyles = {};
        props.statusOptions.forEach((option) => {
            if (styles[option.value]) {
                mappedStyles[option.value] = {
                    label: option.label,
                    ...styles[option.value],
                };
            } else {
                // Fallback for any status not in our predefined styles
                mappedStyles[option.value] = {
                    label: option.label,
                    bg: "bg-[#f0f0f0]",
                    text: "text-[#666666]",
                    border: "border-[#b3b3b3]",
                    darkBg: "bg-[#666666]",
                    darkText: "text-[#f0f0f0]",
                    icon: "pi pi-question-circle",
                };
            }
        });
        return mappedStyles;
    }

    return styles;
});

// Find current state based on status
const currentState = computed(() => {
    return (
        statusStyles.value[props.status] || {
            label: "Unknown",
            bg: "bg-[#f0f0f0]",
            text: "text-[#666666]",
            border: "border-[#b3b3b3]",
            darkBg: "bg-[#666666]",
            darkText: "text-[#f0f0f0]",
            icon: "pi pi-question-circle",
        }
    );
});
</script>

<template>
    <div
        dir="rtl"
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

<style scoped>
/* Optional: Add any additional styling here */
.size-2 {
    width: 0.5rem;
    height: 0.5rem;
}

@keyframes ping {
    75%,
    100% {
        transform: scale(2);
        opacity: 0;
    }
}

.animate-ping {
    animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}

.motion-reduce:animate-none {
    animation: none;
}
</style>
