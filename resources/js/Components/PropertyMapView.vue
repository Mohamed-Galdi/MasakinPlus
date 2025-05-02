<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from "vue";
import mapboxgl from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";

const props = defineProps({
    latitude: { type: Number, required: true },
    longitude: { type: Number, required: true },
});

const map = ref(null);
const marker = ref(null);

const initMap = () => {
    if (map.value) return;

    mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN;

    map.value = new mapboxgl.Map({
        container: "map-view",
        style: "mapbox://styles/mapbox/streets-v11",
        center: [props.longitude, props.latitude],
        zoom: 14,
        language: "ar",
        attributionControl: false,
    });

    map.value.setMaxBounds([
        [34.0, 16.0], // SW
        [56.0, 33.0], // NE
    ]);

    marker.value = new mapboxgl.Marker({ draggable: false })
        .setLngLat([props.longitude, props.latitude])
        .addTo(map.value);

    mapboxgl.setRTLTextPlugin(
        "https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.3.0/mapbox-gl-rtl-text.js",
        null,
        true
    );
};

onMounted(() => {
    nextTick(() => {
        setTimeout(() => {
            initMap();
        }, 100);
    });
});

onBeforeUnmount(() => {
    if (map.value) map.value.remove();
});

const resizeMap = () => {
    if (map.value) {
        nextTick(() => {
            setTimeout(() => {
                map.value.resize();
            }, 50);
        });
    }
};

defineExpose({ resizeMap });
</script>

<template>
    <div class="w-full h-[500px] relative">
        <div id="map-view" class="w-full h-full rounded-lg shadow-md"></div>
    </div>
</template>

<style scoped>
:deep(.mapboxgl-ctrl-logo),
:deep(.mapboxgl-ctrl-attrib) {
    display: none !important;
}
</style>
