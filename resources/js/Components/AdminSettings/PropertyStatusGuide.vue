<script setup>
import { ref } from "vue";

const props = defineProps({
    statuses: {
        type: Array,
        required: true,
    },
    descriptions: {
        type: Object,
        required: true,
    },
});

const expandedStatus = ref(null);

// Toggle expanded state for a status
const toggleExpand = (statusValue) => {
    expandedStatus.value =
        expandedStatus.value === statusValue ? null : statusValue;
};

// Professional, subdued color palette
const getStatusStyle = (statusValue) => {
    const styles = {
        draft: {
            bg: "bg-[#f7f9fc]",
            border: "border-[#e3e8f0]",
            text: "text-[475569]",
            dot: "text-[#64748b]",
            icon: "pi pi-file",
        },
        investment_requested: {
            bg: "bg-[#f8f7f7]",
            border: "border-[#e5e2e0]",
            text: "text-[57534e]",
            dot: "text-[#78716c]",
            icon: "pi pi-list",
        },
        investment_offer_sent: {
            bg: "bg-[#f6f8fa]",
            border: "border-[#dfe5ec]",
            text: "text-[334155]",
            dot: "text-[#64748b]",
            icon: "pi pi-send",
        },
        open_for_investment: {
            bg: "bg-[#f5f9f7]",
            border: "border-[#dae9e2]",
            text: "text-[2d5749]",
            dot: "text-[#4b7865]",
            icon: "pi pi-chart-line",
        },
        funded_under_maintenance: {
            bg: "bg-[#f7f7fa]",
            border: "border-[#e4e3eb]",
            text: "text-[4a4865]",
            dot: "text-[#6b6996]",
            icon: "pi pi-wrench",
        },
        rent_requested: {
            bg: "bg-[#f7f8fa]",
            border: "border-[#dfe3e8]",
            text: "text-[3e5170]",
            dot: "text-[#5d7299]",
            icon: "pi pi-check-square",
        },
        rent_rejected: {
            bg: "bg-[#f9f7f7]",
            border: "border-[#e8dfdf]",
            text: "text-[6e4848]",
            dot: "text-[#8c6565]",
            icon: "pi pi-times",
        },
        listed_for_rent: {
            bg: "bg-[#f6f9fa]",
            border: "border-[#dce7e9]",
            text: "text-[2c5a63]",
            dot: "text-[#4d7c85]",
            icon: "pi pi-list-check",
        },
        rented: {
            bg: "bg-[#f5f8f6]",
            border: "border-[#dae6df]",
            text: "text-[3c6b50]",
            dot: "text-[#5d8c70]",
            icon: "pi pi-home",
        },
        lease_ended: {
            bg: "bg-[#f9f8f6]",
            border: "border-[#e9e5dc]",
            text: "text-[6b5c3e]",
            dot: "text-[#8c7b5c]",
            icon: "pi pi-calendar-times",
        },
        unlisted: {
            bg: "bg-[#f7f7f7]",
            border: "border-[#e5e5e5]",
            text: "text-[525252]",
            dot: "text-[#737373]",
            icon: "pi pi-eye-slash",
        },
    };

    return styles[statusValue] || styles.unlisted;
};
</script>

<template>
    <div class="py-6">
        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div
                v-for="status in statuses"
                :key="status.value"
                class="status-card bg-white rounded-lg border shadow-sm overflow-hidden transition-all hover:shadow"
                :class="[getStatusStyle(status.value).border]"
            >
                <!-- Card Header -->
                <div
                    class="p-4 cursor-pointer"
                    :class="[getStatusStyle(status.value).bg]"
                    @click="toggleExpand(status.value)"
                >
                    <div class="flex items-center justify-between">
                        <!-- Status Badge with Icon -->
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-2">
                                <i
                                    :class="[
                                        getStatusStyle(status.value).icon,
                                        getStatusStyle(status.value).text,
                                    ]"
                                ></i>
                                <h3
                                    class="text-base font-bold"
                                    :class="[getStatusStyle(status.value).text]"
                                >
                                    {{ status.label }}
                                </h3>
                            </div>
                        </div>

                        <!-- Toggle Icon -->
                        <i
                            class="pi pi-sort-down-fill transition-transform"
                            :class="{
                                'rotate-180': expandedStatus === status.value,
                                [getStatusStyle(status.value).dot]: true,
                            }"
                        ></i>
                    </div>
                </div>

                <!-- Description (Expandable) -->
                <div
                    class="overflow-hidden transition-all duration-300"
                    :style="{
                        maxHeight:
                            expandedStatus === status.value ? '500px' : '0',
                        opacity: expandedStatus === status.value ? 1 : 0,
                    }"
                >
                    <div class="p-4 border-t border-gray-100">
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ descriptions[status.value] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.status-card {
    transition: all 0.2s ease;
    height: fit-content;
}

.status-card:hover {
    transform: translateY(-2px);
}
</style>
