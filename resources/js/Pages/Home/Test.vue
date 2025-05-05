<script setup>
import { ref } from 'vue'
import PropertyStatus from "@/Components/PropertyStatus.vue";


defineProps({
    statusOptions: {
        type: Object,
        required: true,
    },
})
</script>


<template>
    <div class='grid grid-cols-4 gap-4 p-8'>
        <div v-for="status in statusOptions" :key="status.value" class="">
            <PropertyStatus :status="status.value" :status-options="statusOptions" class="w-fit" />
        </div>
    </div>
</template>


<style scoped></style>