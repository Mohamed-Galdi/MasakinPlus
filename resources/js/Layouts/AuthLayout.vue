<script setup>
import { ref, onMounted, onUnmounted, nextTick } from "vue";
import { Link } from "@inertiajs/vue3";
import Footer from "@/Components/Home/Partials/Footer.vue";
import HomeButton from "@/Components/Home/HomeButton.vue";
import { gsap } from "gsap";

// Reactive paths array
const paths = ref([]);

// Function to check if device is mobile
const isMobile = ref(window.matchMedia("(max-width: 767px)").matches);

// Watch for screen size changes (optional, but useful for resizing)
const checkScreenSize = () => {
    isMobile.value = window.matchMedia("(max-width: 767px)").matches;
};

// Generate paths only if not on mobile
const generatePaths = () => {
    if (isMobile.value) return; // Skip path generation on mobile

    const tempPaths = [];

    for (let i = 0; i < 36; i++) {
        tempPaths.push({
            id: i,
            position: "pos",
            d: `M-${380 - i * 5} -${189 + i * 6}C-${380 - i * 5} -${
                189 + i * 6
            } -${312 - i * 5} ${216 - i * 6} ${152 - i * 5} ${343 - i * 6}C${
                616 - i * 5
            } ${470 - i * 6} ${684 - i * 5} ${875 - i * 6} ${684 - i * 5} ${
                875 - i * 6
            }`,
            width: 0.5 + i * 0.03,
            opacity: 0.1 + i * 0.03,
        });
    }

    for (let i = 0; i < 18; i++) {
        tempPaths.push({
            id: i,
            position: "neg",
            d: `M-${380 + i * 10} -${189 + i * 12}C-${380 + i * 10} -${
                189 + i * 12
            } -${312 + i * 10} ${216 - i * 12} ${152 + i * 10} ${
                343 - i * 12
            }C${616 + i * 10} ${470 - i * 12} ${684 + i * 10} ${875 - i * 12} ${
                684 + i * 10
            } ${875 - i * 12}`,
            width: 0.5 + i * 0.05,
            opacity: 0.1 + i * 0.02,
        });
    }

    paths.value = tempPaths;
};

// Animate paths only if not on mobile
const animatePaths = () => {
    if (isMobile.value) return; // Skip animation on mobile

    nextTick(() => {
        const pathElements = gsap.utils.toArray(".bg-path");

        pathElements.forEach((path, index) => {
            const length = path.getTotalLength();

            gsap.set(path, {
                strokeDasharray: length,
                strokeDashoffset: length * 0.7,
                opacity: 0.6,
            });

            gsap.to(path, {
                strokeDashoffset: -length, // Moves continuously
                opacity: [0.3, 0.6, 0.3],
                duration: 15 + index * 0.3, // Ensure paths animate smoothly
                repeat: -1, // Keep looping
                ease: "none",
            });
        });
    });
};

// Initialize paths and animations only if not on mobile
onMounted(() => {
    checkScreenSize(); // Check screen size on mount
    if (!isMobile.value) {
        requestAnimationFrame(generatePaths);
        setTimeout(animatePaths, 50);
    }

    // Listen for screen resize events
    window.addEventListener("resize", checkScreenSize);
});

// Cleanup event listener on unmount
onUnmounted(() => {
    window.removeEventListener("resize", checkScreenSize);
});
</script>

<template>
    <div class="relative min-h-screen w-full overflow-hidden bg-gray-50">
        <!-- Navbar -->
        <nav
            class="relative flex justify-between items-center text-white md:px-24 px-4 py-4 mx-auto z-50 w-full bg-gradient-to-tl from-teal-700 to-teal-900"
        >
            <Link :href="route('home')" class="nav-logo">
                <img
                    src="/assets/logos/logo_white.png"
                    alt="MasakinPlus"
                    class="w-16 h-16"
                />
            </Link>
            <div class="nav-join-btn"><HomeButton /></div>
        </nav>

        <!-- Main Content -->
        <div class="relative min-h-screen z-10">
            <!-- Background paths (hidden on mobile) -->
            <div v-if="!isMobile" class="absolute inset-0 pointer-events-none z-0">
                <svg
                    class="w-full h-full text-teal-700/40"
                    viewBox="0 0 696 316"
                    fill="none"
                    style="will-change: transform, opacity"
                >
                    <title>Background Paths</title>
                    <path
                        v-for="path in paths"
                        :key="`${path.position}-${path.id}`"
                        class="bg-path"
                        :d="path.d"
                        stroke="currentColor"
                        :stroke-width="path.width"
                        :stroke-opacity="path.opacity"
                        fill="none"
                    />
                </svg>
            </div>
            <slot />
        </div>

        <!-- Footer -->
        <Footer />
    </div>
</template>
