<script setup>
import InvestorLayout from "@/Layouts/InvestorLayout.vue";
import { ref, watch } from "vue";
import PropertiesMapView from "@/Components/Investor/PropertiesMapView.vue";
import PropertiesCardsView from "@/Components/Investor/PropertiesCardsView.vue";

defineOptions({
    layout: InvestorLayout,
});

const props = defineProps({
    properties: {
        type: Object,
        required: true,
    },
    mapProperties: {
        type: Array,
        required: true,
    },
    typeOptions: {
        type: Array,
        required: true,
    },
    cityFilter: {
        type: Array,
        required: false,
        default: "",
    },
    typeFilter: {
        type: String,
        required: false,
        default: "",
    },
    minInvestAmount: {
        type: Number,
        required: false,
        default: 0,
    },
    maxInvestAmount: {
        type: Number,
        required: false,
        default: 0,
    },
    minMonthlyRevenue: {
        type: Number,
        required: false,
        default: 0,
    },
    maxMonthlyRevenue: {
        type: Number,
        required: false,
        default: 0,
    },
    fundedPercentageFilter: {
        type: Number,
        required: false,
        default: 100,
    },
});

const currentView = ref(localStorage.getItem("masakin:currentView") || "cards");
watch(currentView, (newView) => {
    localStorage.setItem("masakin:currentView", newView);
});
</script>

<template>
    <div>
        <!-- Cards/Map Toggle -->
        <div
            class="bg-slate-200 px-1 py-1 rounded-lg flex gap-2 mb-6 w-fit mx-auto border border-slate-400"
        >
            <button
                @click="currentView = 'cards'"
                :class="[
                    'flex justify-center items-center gap-2 p-2 rounded-md transition-all duration-300 font-medium w-44',
                    currentView === 'cards'
                        ? 'bg-teal-700 text-white shadow-lg'
                        : 'text-slate-800 hover:bg-slate-300',
                ]"
            >
                <i class="pi pi-th-large text-lg"></i>
                <span>عرض البطاقات</span>
                <div
                    v-if="currentView === 'cards'"
                    class="w-2 h-2 bg-white rounded-full"
                ></div>
            </button>
            <button
                @click="currentView = 'map'"
                :class="[
                    'flex justify-center items-center gap-2 p-2 rounded-md transition-all duration-300 font-medium w-44',
                    currentView === 'map'
                        ? 'bg-teal-700 text-white shadow-lg'
                        : 'text-slate-800 hover:bg-slate-300',
                ]"
            >
                <i class="pi pi-map text-lg"></i>
                <span>عرض الخريطة</span>
                <div
                    v-if="currentView === 'map'"
                    class="w-2 h-2 bg-white rounded-full"
                ></div>
            </button>
        </div>

        <!-- Cards View -->
        <div v-if="currentView === 'cards'">
            <PropertiesCardsView :properties="props.properties" :type-options="typeOptions" :city-filter="cityFilter" :type-filter="typeFilter" :min-invest-amount="minInvestAmount" :max-invest-amount="maxInvestAmount" :min-monthly-revenue="minMonthlyRevenue" :max-monthly-revenue="maxMonthlyRevenue" :funded-percentage-filter="fundedPercentageFilter" />
        </div>

        <!-- Map View -->
        <keep-alive>
            <PropertiesMapView
                v-if="currentView === 'map'"
                :properties="props.mapProperties"
            />
        </keep-alive>
    </div>
</template>
