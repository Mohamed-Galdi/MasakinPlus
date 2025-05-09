<script setup>
import InvestorLayout from "@/Layouts/InvestorLayout.vue";

defineOptions({
    layout: InvestorLayout,
});

const props = defineProps({
    properties: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div>
        <h1>Offers</h1>
        <ul>
            <li v-for="property in props.properties" :key="property.id">
                {{ property.title }}
            </li>
        </ul>
    </div>
</template>
