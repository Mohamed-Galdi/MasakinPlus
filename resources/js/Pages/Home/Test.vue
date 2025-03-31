<script setup>
import { ref } from "vue";
import Select from "primevue/select";

const testoptions = ref([
    { label: "One", value: "One" },
    { label: "Two", value: "Two" },
    { label: "Three", value: "Three" },
]);

const selectedOp = ref(null);
</script>

<template>
    <div class="p-24 grid place-items-center h-screen bg-gray-500">
        <div>
            <Select
                dir="ltr"
                v-model="selectedOp"
                :options="testoptions"
                optionLabel="label"
                placeholder="Select a City"
                class="w-full md:w-56"
            />
        </div>
    </div>
</template>

<style>

</style>
