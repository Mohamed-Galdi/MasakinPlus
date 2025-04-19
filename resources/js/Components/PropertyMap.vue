<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from "vue";
import mapboxgl from "mapbox-gl";
import MapboxGeocoder from "@mapbox/mapbox-gl-geocoder";

import "mapbox-gl/dist/mapbox-gl.css";
import "@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.css";

const props = defineProps({
    initialLat: { type: Number, default: 24.7136 },
    initialLng: { type: Number, default: 46.6753 },
});

const emit = defineEmits(["update:coordinates"]);

const map = ref(null);
const marker = ref(null);
const coordinates = ref({ lat: props.initialLat, lng: props.initialLng });

const initMap = () => {
    if (map.value) return;

    mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN;

    map.value = new mapboxgl.Map({
        container: "map",
        style: "mapbox://styles/mapbox/streets-v11",
        center: [props.initialLng, props.initialLat],
        zoom: 9,
        language: "ar",
        attributionControl: false, // disables default attribution
    });

    map.value.setMaxBounds([
        [34.0, 16.0], // SW
        [56.0, 33.0], // NE
    ]);

    // Geocoder (search)
    const geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl,
        placeholder: "ابحث عن الموقع...",
        language: "ar",
        countries: "SA",
    });

    document
        .getElementById("geocoder-container")
        ?.appendChild(geocoder.onAdd(map.value));

    geocoder.on("result", (e) => {
        const [lng, lat] = e.result.center;
        updateMarker(lat, lng);
    });

    // Initial marker
    updateMarker(props.initialLat, props.initialLng);

    map.value.on("click", (e) => {
        const { lng, lat } = e.lngLat;
        updateMarker(lat, lng);
    });

    mapboxgl.setRTLTextPlugin(
        "https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.3.0/mapbox-gl-rtl-text.js",
        null,
        true
    );
};

const updateMarker = (lat, lng) => {
    if (!lat || !lng || isNaN(lat) || isNaN(lng)) return;

    if (marker.value) marker.value.remove();

    marker.value = new mapboxgl.Marker({ draggable: true })
        .setLngLat([lng, lat])
        .addTo(map.value);

    coordinates.value = { lat, lng };
    emit("update:coordinates", coordinates.value);

    marker.value.on("dragend", () => {
        const { lng, lat } = marker.value.getLngLat();
        coordinates.value = { lat, lng };
        emit("update:coordinates", coordinates.value);
    });
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

watch(coordinates, (newVal) => {
    emit("update:coordinates", newVal);
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
    <div class="w-full h-96 relative">
        <div
            id="geocoder-container"
            class="absolute top-2 right-2 z-10 w-full max-w-xs"
        ></div>
        <div id="map" class="w-full h-full rounded-lg shadow-md"></div>
    </div>
</template>

<style scoped>
:deep(.mapboxgl-ctrl-geocoder) {
    min-width: 100%;
    font-family: inherit;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
}

/* hide default attributions */
:deep(.mapboxgl-ctrl-logo),
:deep(.mapboxgl-ctrl-attrib) {
  display: none !important;
}
</style>
