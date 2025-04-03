<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { gsap } from "gsap";
import HomeButton from "@/Components/HomeButton.vue";
import { Link } from "@inertiajs/vue3";

// Refs
const heroRef = ref(null);
let tl = null;

// Optimized animation configuration
const animationConfig = {
    shape: {
        scale: 0,
        opacity: 0,
        duration: 1.2,
        ease: "elastic.out(1, 0.5)",
        stagger: 0.1,
    },
    title: { opacity: 0, y: 50, duration: 0.8, ease: "power3.out" },
    subtitle: { opacity: 0, y: 20, duration: 0.8, ease: "power3.out" },
    cta: { opacity: 0, y: 20, duration: 0.6, ease: "power3.out" },
    image: { opacity: 0, scale: 0.9, duration: 1, ease: "power3.out" },
    scroll: { opacity: 0, y: -20, duration: 0.6, ease: "power3.out" },
};

const animateHero = () => {
    // Kill existing timeline if it exists
    if (tl) tl.kill();

    tl = gsap
        .timeline()
        .from(".hero-shape", animationConfig.shape)
        .from(".hero-title", animationConfig.title, "-=0.8")
        .from(".hero-subtitle", animationConfig.subtitle, "-=0.4")
        .from(".hero-cta", animationConfig.cta, "-=0.6")
        .from(".hero-image", animationConfig.image, "-=0.4")
        .from(".hero-scroll-indicator", animationConfig.scroll, "-=0.2");
};

// Lifecycle hooks with cleanup
onMounted(() => {
    // Only animate if element exists
    if (heroRef.value) {
        animateHero();
    }
});

onBeforeUnmount(() => {
    // Cleanup animations
    if (tl) {
        tl.kill();
        tl = null;
    }
});
</script>

<template>
    <section
        ref="heroRef"
        class="hero relative min-h-screen flex items-center overflow-hidden bg-black"
    >
        <nav
            class="absolute top-0 z-50 w-full flex justify-between items-center text-white px-4 py-4 md:px-24"
        >
            <Link :href="route('home')" class="nav-logo">
                <img
                    src="/assets/logos/logo_white.png"
                    alt="MasakinPlus"
                    class="w-16 h-16 object-contain"
                    loading="eager"
                />
            </Link>
            <Link :href="route('register')">
                <HomeButton />
            </Link>
        </nav>

        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                v-for="i in 3"
                :key="i"
                class="hero-shape absolute rounded-full bg-gradient-to-br blur-3xl"
                :class="{
                    'top-[10%] left-[15%] w-64 h-64 from-teal-500/30 to-teal-700/30':
                        i === 1,
                    'bottom-[20%] right-[10%] w-80 h-80 from-teal-600/30 to-teal-800/30':
                        i === 2,
                    'top-[40%] right-[20%] w-40 h-40 from-teal-400/30 to-teal-600/30':
                        i === 3,
                }"
            ></div>
        </div>

        <div
            class="container mx-auto px-4 z-10 flex flex-col lg:flex-row items-center gap-12 lg:gap-20 pt-24 lg:pt-0"
        >
            <div class="lg:w-1/2 text-right order-2 lg:order-1 space-y-6">
                <h1
                    class="hero-title font-BlueOcean text-white text-3xl lg:text-5xl"
                >
                    مساكن بلس
                </h1>
                <p
                    class="hero-subtitle text-xl lg:text-3xl text-gray-300 max-w-lg font-BeinNormal"
                >
                    نعيد تعريف الاستثمار العقاري في المملكة العربية السعودية من
                    خلال منصة مبتكرة تربط بين أصحاب العقارات والمستثمرين
                    والمستأجرين
                </p>
                <div class="hero-cta flex flex-wrap gap-4 font-Bein">
                    <Link href="#mission"
                        class="group relative overflow-hidden rounded-full bg-gradient-to-r from-teal-600 to-teal-800 px-8 py-4 font-bold transition-all duration-300"
                    >
                        <span
                            class="relative z-10 text-white group-hover:text-teal-700"
                            >اكتشف رؤيتنا</span
                        >
                        <span
                            class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-300"
                        ></span>
                    </Link>
                    <Link href="#contact"
                        class="group hover:text-teal-800 relative overflow-hidden rounded-full border-2 border-white px-8 py-4 font-bold text-white transition-all duration-300"
                    >
                        <span class="relative z-10 flex items-center gap-2">
                            تواصل معنا
                            <i class="pi pi-arrow-left w-4 h-4" />
                        </span>
                        <span
                            class="absolute inset-0 bg-white translate-y-full group-hover:translate-y-0 transition-transform duration-300"
                        ></span>
                    </Link>
                </div>
            </div>

            <div class="lg:w-1/2 order-1 lg:order-2 px-4 lg:px-0">
                <div class="hero-image relative">
                    <div
                        class="relative w-full aspect-video rounded-3xl overflow-hidden"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-teal-600/20 to-teal-800/20 mix-blend-overlay"
                        ></div>
                        <img
                            src="/assets/about_us_images/about_us.jpeg"
                            alt="مساكن بلس العقارية"
                            class="w-full h-full object-cover"
                            loading="eager"
                        />
                    </div>
                    <div
                        class="absolute font-Bein bg-gradient-to-br text-white px-6 py-3 rounded-full shadow-lg"
                        :class="['-bottom-6 -left-6 from-teal-600 to-teal-800']"
                    >
                        <span class="font-bold">منصة رائدة</span>
                    </div>
                    <div
                        class="absolute font-Bein bg-gradient-to-br text-white px-6 py-3 rounded-full shadow-lg"
                        :class="['-top-6 -right-6 from-teal-700 to-teal-900']"
                    >
                        <span class="font-bold">تقنية متطورة</span>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="hero-scroll-indicator absolute bottom-10 left-1/2 -translate-x-1/2 hidden lg:flex flex-col items-center"
        >
            <span class="text-white/70 text-sm mb-2 font-BeinNormal"
                >اكتشف المزيد</span
            >
            <div
                class="w-6 h-10 rounded-full border-2 border-white/30 flex items-start justify-center p-1"
            >
                <div
                    class="w-1.5 h-1.5 bg-white rounded-full animate-bounce"
                ></div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@keyframes bounce {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(10px);
    }
}

.animate-bounce {
    animation: bounce 2s infinite;
}

/* Remove unused float animation */
</style>
