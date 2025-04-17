<script setup>
import { onMounted, ref } from "vue";
import mapboxgl from "mapbox-gl";

const props = defineProps({
    modelValue: Object, // expects: { lng: Number, lat: Number }
});

const emit = defineEmits(["update:modelValue"]);

const mapContainer = ref(null);
let map = null;
let marker = null;

mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN

onMounted(() => {
    map = new mapboxgl.Map({
        container: mapContainer.value,
        style: "mapbox://styles/mapbox/streets-v11",
        center: [
            props.modelValue?.lng || 46.6753,
            props.modelValue?.lat || 24.7136,
        ], // Default: Riyadh
        zoom: 10,
    });

    marker = new mapboxgl.Marker({ draggable: true })
        .setLngLat(map.getCenter())
        .addTo(map);

    marker.on("dragend", () => {
        const lngLat = marker.getLngLat();
        emit("update:modelValue", { lng: lngLat.lng, lat: lngLat.lat });
    });

    // Set initial marker if location provided
    if (props.modelValue) {
        marker.setLngLat([props.modelValue.lng, props.modelValue.lat]);
    }
});
</script>
<template>
    <div ref="mapContainer" class="w-full h-96 rounded-lg"></div>
</template>

<style scoped>
.mapboxgl-canvas {
    border-radius: 12px;
}
</style>
