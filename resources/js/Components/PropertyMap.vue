<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from "vue";
import mapboxgl from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";

const props = defineProps({
    initialLat: { type: Number, default: 24.7136 },
    initialLng: { type: Number, default: 46.6753 },
});

const emit = defineEmits(["update:coordinates"]);

const map = ref(null);
const marker = ref(null);
const coordinates = ref(null);
const mapContainer = ref(null);

const initMap = () => {
    if (map.value) return;

    mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN;

    map.value = new mapboxgl.Map({
        container: "map", // Using the id instead of the ref
        style: "mapbox://styles/mapbox/navigation-day-v1",
        center: [props.initialLng, props.initialLat],
        zoom: 11,
        language: "ar",
    });

    map.value.addControl(new mapboxgl.NavigationControl());

    map.value.on("click", (e) => {
        const { lng, lat } = e.lngLat;

        if (marker.value) marker.value.remove();

        marker.value = new mapboxgl.Marker()
            .setLngLat([lng, lat])
            .addTo(map.value);

        coordinates.value = { lat, lng };
        emit("update:coordinates", coordinates.value);
    });

    mapboxgl.setRTLTextPlugin(
        "https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.3.0/mapbox-gl-rtl-text.js",
        null,
        true
    );
};

onMounted(() => {
    // Delay map initialization slightly to ensure container is properly sized
    nextTick(() => {
        setTimeout(() => {
            initMap();
        }, 100);
    });
});

onBeforeUnmount(() => {
    if (map.value) map.value.remove();
});

watch(coordinates, (newVal) => {
    emit("update:coordinates", newVal);
});

// Expose the resizeMap method to the parent component
const resizeMap = () => {
    if (map.value) {
        nextTick(() => {
            setTimeout(() => {
                map.value.resize();
            }, 50);
        });
    }
};

// Define the exposed methods
defineExpose({ resizeMap });
</script>

<template>
    <div class="w-full h-96 relative">
        <div id="map" class="w-full h-full rounded-lg shadow-md"></div>
        <div
            v-if="coordinates"
            class="absolute top-4 left-4 bg-white p-2 rounded shadow text-sm text-gray-700"
        >
            {{ $t("coordinates") }}: {{ coordinates.lat.toFixed(6) }},
            {{ coordinates.lng.toFixed(6) }}
        </div>
    </div>
</template>
