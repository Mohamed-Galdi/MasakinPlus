<script setup lang="ts">
import { ref, onMounted } from "vue";

const props = defineProps({
    howItWorks: Object,
});

const content = JSON.parse(JSON.stringify(props.howItWorks.content.cards));

const connectionsRef = ref<HTMLElement | null>(null);

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("animate-in");
                }
            });
        },
        { threshold: 0.1 }
    );

    const elements = document.querySelectorAll(".animate-on-scroll");
    elements.forEach((el) => observer.observe(el));
});
</script>

<template>
    <section
        class="py-20 bg-gradient-to-b from-teal-950 to-gray-900 text-white overflow-hidden relative font-BeinNormal"
    >
        <!-- Center Platform Logo (Visible on Desktop, Smaller on Mobile) -->
        <div
            class="absolute top-[46%]  left-1/2 -translate-x-1/2 -translate-y-1/2 animate-on-scroll z-20 md:block hidden"
        >
            <img
                src="/assets/logos/logo_white.png"
                alt="MasakinPlus"
                class="w-44 h-44 object-contain bg-teal-700 p-2 md:p-8 shadow-green-500 shadow-xl rounded-[3rem]  border-2 border-white"
            />
        </div>

        <!-- Connection Lines (Hidden on Mobile) -->
        <svg
            class="absolute inset-0 w-full h-full hidden md:block"
            style="z-index: 10"
        >
            <line x1="50%" y1="50%" x2="25%" y2="30%" class="connection-line" />
            <line x1="50%" y1="50%" x2="75%" y2="30%" class="connection-line" />
            <line x1="50%" y1="50%" x2="50%" y2="75%" class="connection-line" />
        </svg>

        <!-- Cards Container (Stacked on Mobile, Positioned on Desktop) -->
        <div
            class="relative z-20 flex flex-col items-center gap-8 md:gap-0 md:h-screen px-4"
        >
            <!-- Property Owners Card -->
            <div
                class="animate-on-scroll md:absolute md:top-[15%] md:left-[24%] md:-translate-x-1/2"
            >
                <div
                    class="bg-slate-200 rounded-2xl p-6 w-80 text-center hover:scale-105 transition-transform shadow-2xl border border-teal-900/30"
                >
                    <Icon
                        icon="fa-solid fa-house"
                        class="w-12 h-12 text-teal-800 mb-4"
                    />
                    <h3 class="text-2xl font-bold text-teal-800 mb-3">
                        {{ content[1].title }}
                    </h3>
                    <p class="text-gray-800">
                        {{ content[1].description }}
                    </p>
                </div>
            </div>

            <!-- Investors Card -->
            <div
                class="animate-on-scroll md:absolute md:top-[15%] md:right-[24%] md:translate-x-1/2"
            >
                <div
                    class="bg-slate-200 rounded-2xl p-6 w-80 text-center hover:scale-105 transition-transform shadow-2xl border border-teal-900/30"
                >
                    <Icon
                        icon="fa-solid fa-coins"
                        class="w-12 h-12 text-teal-800 mb-4"
                    />
                    <h3 class="text-2xl font-bold text-teal-800 mb-3">
                        {{ content[0].title }}
                    </h3>
                    <p class="text-gray-800">
                        {{ content[0].description }}
                    </p>
                </div>
            </div>

            <!-- Tenants Card -->
            <div
                class="animate-on-scroll md:absolute md:bottom-[1rem] md:left-1/2 md:-translate-x-1/2"
            >
                <div
                    class="bg-slate-200 rounded-2xl p-6 w-80 text-center hover:scale-105 transition-transform shadow-2xl border border-teal-900/30"
                >
                    <Icon
                        icon="fa-solid fa-user"
                        class="w-12 h-12 text-teal-800 mb-4"
                    />
                    <h3 class="text-2xl font-bold text-teal-800 mb-3">
                        {{ content[2].title }}
                    </h3>
                    <p class="text-gray-800">
                        {{ content[2].description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.connection-line {
    stroke: white;
    stroke-width: 3;
    stroke-dasharray: 8;
    opacity: 0.6;
    animation: dash 20s linear infinite;
}

@keyframes dash {
    to {
        stroke-dashoffset: -1000;
    }
}

/* Mobile Adjustments */
@media (max-width: 767px) {
    .logo-center {
        position: static;
        transform: none;
        margin: 0 auto 2rem;
    }

    section {
        height: auto; /* Remove fixed height on mobile */
        padding: 2rem 0; /* Adjust padding for mobile */
    }

    .flex-col {
        padding-top: 0; /* Ensure no extra padding at the top */
    }
}

/* Desktop Adjustments */
@media (min-width: 768px) {
    .logo-center {
        position: absolute;
    }
}
</style>
