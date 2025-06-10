<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick, watch } from "vue";
import mapboxgl from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";
import { gsap } from "gsap";

const props = defineProps({
    properties: {
        type: Array,
        required: true,
    },
});

const map = ref(null);
const markers = ref([]);
const popup = ref(null);
const activeProperty = ref(null);

const formatPrice = (price) => parseFloat(price).toLocaleString("ar-SA");

const dummyInvestment = {
    percentageFunded: 40,
    currentFunded: 40000,
    totalRequired: 100000,
    remaining: 60000,
    expectedRevenue: 12000,
};


const initMap = () => {
    if (map.value) return;

    mapboxgl.accessToken = import.meta.env.VITE_MAPBOX_TOKEN;
    if (!mapboxgl.accessToken) {
        console.error("Mapbox token missing");
        return;
    }

    if (mapboxgl.getRTLTextPluginStatus() === 'unavailable') {
        mapboxgl.setRTLTextPlugin(
            "https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.3.0/mapbox-gl-rtl-text.js",
            null,
            true
        );
    }

    map.value = new mapboxgl.Map({
        container: "map-view",
        language: "ar",
        style: "mapbox://styles/mapbox/streets-v11",
        attributionControl: false,
    });

    map.value.setMaxBounds([
        [34.0, 16.0],
        [56.0, 33.0],
    ]);

    map.value.on("load", () => {
        addMarkers();
    });

    map.value.on("click", hidePopup);
};

const addMarkers = () => {
    if (!map.value || !props.properties.length) return;

    const bounds = new mapboxgl.LngLatBounds();

    props.properties.forEach((property) => {
        const lng = parseFloat(property.longitude);
        const lat = parseFloat(property.latitude);
        if (isNaN(lng) || isNaN(lat)) return;

        const el = document.createElement("div");
        el.innerHTML = `<img src="/assets/property-on-map.svg" alt="" class="w-8 h-8 hover:scale-125 ease-in-out duration-150">`;
        el.style.cursor = "pointer";

        const markerEl = el.firstChild;

        const marker = new mapboxgl.Marker({ element: markerEl })
            .setLngLat([lng, lat])
            .addTo(map.value);

        markers.value.push({ marker, property });
        bounds.extend([lng, lat]);

        markerEl.addEventListener("click", (e) => {
            e.stopPropagation();
            if (activeProperty.value?.id === property.id) {
                hidePopup();
            } else {
                showPopup(property, marker);
                activeProperty.value = property;
            }
        });
    });

    if (!bounds.isEmpty()) {
        map.value.fitBounds(bounds, { padding: 50, maxZoom: 15 });
    }
};

const showPopup = (property, marker) => {
    if (popup.value) popup.value.remove();

    const {
        percentageFunded,
        currentFunded,
        totalRequired,
        remaining,
        expectedRevenue,
    } = dummyInvestment;

    const popupContent = `
    <div class="popup-card relative w-72 bg-white border-2 border-teal-800 rounded-lg overflow-hidden font-BeinNormal">
      <button class="close-btn absolute top-2 right-2 bg-slate-800/60 hover:bg-slate-800/90 p-2 rounded-full grid place-items-center z-10">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      <img src="/${property.images[0]?.path || "/placeholder.jpg"}" alt="${
        property.title
    }" class="w-full h-[9rem] object-cover rounded-t-lg">
      <div class="p-2 space-y-3">
        <p class="text-slate-700 font-BeinNormal text-start">${
            property.title
        }</p>
        <div class="max-w-md mx-auto p-2">
          <div class="relative mb-1">
            <div class="absolute -top-4" style="left: ${
                90 - percentageFunded
            }%;">
              <div class="flex items-start gap-1 text-sm">
                <p class="text-teal-700">${formatPrice(currentFunded)}</p>
                <img src="/assets/rs-green.svg" alt="" class="w-4 h-4" />
              </div>
            </div>
          </div>
          <div class="relative h-4 rounded-full bg-slate-200 overflow-hidden">
            <div class="h-full bg-gradient-to-l from-teal-800 to-teal-400 flex items-center justify-center" style="width: ${percentageFunded}%;">
              <span class="text-xs font-bold text-white">${percentageFunded}%</span>
            </div>
          </div>
          <div class="flex justify-between text-sm mt-1">
            <div class="flex items-start gap-1">
              <p class="text-teal-700">${formatPrice(totalRequired)}</p>
              <img src="/assets/rs-green.svg" alt="" class="w-4 h-4" />
            </div>
            <div class="flex items-start gap-1">
              <p class="text-teal-700">${formatPrice(remaining)}</p>
              <img src="/assets/rs-green.svg" alt="" class="w-4 h-4" />
            </div>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <p class="text-xs text-slate-600">مداخل العقار المتوقعة (شهريا):</p>
          <div class="flex items-center justify-center gap-1 text-xl">
            <p class="text-teal-700">${formatPrice(expectedRevenue)}</p>
            <img src="/assets/rs-green.svg" alt="" class="w-4 h-4" />
          </div>
        </div>
        <button class="popup-button relative w-full text-teal-700 overflow-hidden bg-slate-50 rounded-full transition-all duration-400 ease-in-out hover:scale-100 hover:text-white border border-teal-700">
          <a href="/investor/offers/${
              property.id
          }" class="flex justify-center py-1 px-4">إستكشف العقار</a>
        </button>
      </div>
    </div>
  `;

    popup.value = new mapboxgl.Popup({
        closeButton: false,
        offset: 25,
        className: "custom-popup",
    })
        .setLngLat([property.longitude, property.latitude])
        .setHTML(popupContent)
        .addTo(map.value);

    nextTick(() => {
        const el = popup.value.getElement();

        el.querySelector(".close-btn")?.addEventListener("click", (e) => {
            e.stopPropagation();
            hidePopup();
        });

        const btn = el.querySelector(".popup-button");
        if (btn) {
            btn.addEventListener("mouseenter", () => {
                btn.style.setProperty("--tw-gradient-from", "#2dd4bf");
                btn.style.setProperty("--tw-gradient-to", "#0f766e");
                btn.style.backgroundImage =
                    "linear-gradient(to right, var(--tw-gradient-from), var(--tw-gradient-to))";
            });

            btn.addEventListener("mouseleave", () => {
                btn.style.backgroundImage = "";
            });
        }

        gsap.from(el.querySelector(".popup-card"), {
            opacity: 0,
            y: 10,
            duration: 0.3,
            ease: "power2.out",
        });
    });
};

const hidePopup = () => {
    if (popup.value) {
        const el = popup.value.getElement();
        gsap.to(el.querySelector(".popup-card"), {
            opacity: 0,
            y: 10,
            duration: 0.2,
            ease: "power2.in",
            onComplete: () => {
                popup.value?.remove();
                popup.value = null;
                activeProperty.value = null;
            },
        });
    }
};

onMounted(() => {
    nextTick(initMap);
});

onBeforeUnmount(() => {
    if (map.value) map.value.remove();
    markers.value = [];
    popup.value = null;
});

const resizeMap = () => {
    nextTick(() => {
        map.value?.resize();
    });
};

defineExpose({ resizeMap });
</script>

<template>
    <div class="w-full h-[800px] relative">
        <div id="map-view" class="w-full h-full rounded-lg shadow-md"></div>
    </div>
</template>

<style scoped>
:deep(.mapboxgl-ctrl-logo),
:deep(.mapboxgl-ctrl-attrib) {
    display: none !important;
}

:deep(.custom-popup .mapboxgl-popup-content) {
    padding: 0;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    background: white;
    direction: rtl;
    max-width: none;
}

:deep(.custom-popup .mapboxgl-popup-tip) {
    border-top-color: white;
}

:deep(.popup-button::before) {
    content: "";
    position: absolute;
    top: 0;
    right: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, #2dd4bf, #0f766e);
    transition: all 0.5s ease-in-out;
    z-index: -1;
    border-radius: 9999px;
}

:deep(.popup-button:hover::before) {
    right: 0;
}
</style>
