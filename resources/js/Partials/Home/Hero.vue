<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { gsap } from "gsap";
import HomeButton from "@/Components/HomeButton.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    hero: {
        type: Object,
        required: true,
    }
});

// Configuration variables
const ANIMATION_CONFIG = {
    initialLoadStaggerDuration: 0.6,
    slideTransitionDuration: 0.8,
    autoSwipeInterval: 5000,
    staggerDelay: 0.2,
};

const slides = JSON.parse(JSON.stringify(props.hero.content.slides));

const currentSlide = ref(0);
const dotsVisible = ref(false);
const slideImage = ref(null);
const slideTitle = ref(null);
const slideDesc = ref(null);
const slideButton = ref(null);
const heroBackground = ref(null);

let autoSwipeInterval;

const changeSlide = (newIndex) => {
    if (newIndex === currentSlide.value) return;
    const elements = [
        slideImage.value,
        slideTitle.value,
        slideDesc.value,
        slideButton.value,
    ];
    gsap.killTweensOf(elements);
    gsap.killTweensOf(heroBackground.value);

    const tl = gsap.timeline({
        onComplete: () => {
            currentSlide.value = newIndex;
            gsap.to(elements, {
                opacity: 1,
                x: 0,
                duration: ANIMATION_CONFIG.slideTransitionDuration,
                stagger: ANIMATION_CONFIG.staggerDelay,
            });
            gsap.to(heroBackground.value, {
                opacity: 1,
                scale: 1,
                duration: ANIMATION_CONFIG.slideTransitionDuration,
            });
        },
    });

    tl.to(elements, {
        opacity: 0,
        x: -50,
        duration: ANIMATION_CONFIG.slideTransitionDuration,
    }).to(
        heroBackground.value,
        {
            opacity: 0.7,
            scale: 1.1,
            duration: ANIMATION_CONFIG.slideTransitionDuration,
        },
        0
    );
};

const startAutoSwipe = () => {
    autoSwipeInterval = setInterval(() => {
        changeSlide((currentSlide.value + 1) % slides.length);
    }, ANIMATION_CONFIG.autoSwipeInterval);
};

onMounted(() => {
    gsap.timeline({
        onComplete: () => {
            dotsVisible.value = true;
            startAutoSwipe();
        },
    })
        .fromTo(
            ".nav-logo",
            { opacity: 0, x: -50 },
            {
                opacity: 1,
                x: 0,
                duration: ANIMATION_CONFIG.initialLoadStaggerDuration,
            }
        )
        .fromTo(
            ".nav-join-btn",
            { opacity: 0, x: 50 },
            {
                opacity: 1,
                x: 0,
                duration: ANIMATION_CONFIG.initialLoadStaggerDuration,
            },
            "-=0.3"
        )
        .fromTo(
            slideImage.value,
            { opacity: 0, x: 100, scale: 0.9 },
            {
                opacity: 1,
                x: 0,
                scale: 1,
                duration: ANIMATION_CONFIG.initialLoadStaggerDuration + 0.2,
            }
        )
        .fromTo(
            [slideTitle.value, slideDesc.value, slideButton.value],
            { opacity: 0, x: -50 },
            {
                opacity: 1,
                x: 0,
                duration: ANIMATION_CONFIG.initialLoadStaggerDuration,
                stagger: 0.2,
            },
            "-=0.4"
        );
});

onUnmounted(() => clearInterval(autoSwipeInterval));
</script>

<template>
    <div class="hero-section relative min-h-screen overflow-hidden">
        <div
            ref="heroBackground"
            class="hero-background absolute inset-0 bg-cover bg-center"
            :style="{ backgroundImage: `url('${slides[currentSlide].image}')` }"
        >
            <div
                class="absolute inset-0 bg-teal-900 opacity-70 backdrop-blur-sm"
            ></div>
        </div>
        <div
            class="relative z-10 container mx-auto px-4 flex flex-col justify-between h-screen pb-8"
        >
            <nav class="flex justify-between items-center py-4 text-white">
                <Link href="/" class="nav-logo">
                    <img
                        src="/assets/logos/logo_white.png"
                        alt="MasakinPlus"
                        class="w-16 h-16"
                    />
                </Link>
                <Link :href="route('login')" class="nav-join-btn"><HomeButton /></Link>
            </nav>
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div class="text-right text-white">
                    <h2
                        ref="slideTitle"
                        class="md:text-6xl text-4xl font-bold mb-4 font-Bein"
                    >
                        {{ slides[currentSlide].title }}
                    </h2>
                    <p
                        ref="slideDesc"
                        class="text-2xl mb-6 font-BeinNormal md:pe-32"
                    >
                        {{ slides[currentSlide].description }}
                    </p>
                    <button
                        ref="slideButton"
                        class="btn-primary transition-colors duration-300 ease-in-out"
                    >
                        <Link :href="route('register')" class="font-BlueOcean text-xs pt-1">
                            {{ slides[currentSlide].buttonText }}
                        </Link>
                    </button>
                </div>
                <div class="md:block">
                    <img
                        ref="slideImage"
                        :src="slides[currentSlide].image"
                        :alt="slides[currentSlide].type"
                        class="rounded-2xl shadow-lg w-full"
                    />
                </div>
            </div>
            <div class="h-8 flex justify-center items-center">
                <div
                    :class="[
                        dotsVisible
                            ? 'flex justify-center space-x-4'
                            : 'hidden',
                    ]"
                >
                    <div
                        v-for="(slide, index) in slides"
                        :key="index"
                        @click="changeSlide(index)"
                        @keydown.enter="changeSlide(index)"
                        tabindex="0"
                        role="button"
                        :aria-label="`Go to slide ${index + 1}`"
                        class="cursor-pointer h-3 transition-all duration-300 ease-in-out transform mx-2"
                        :class="[
                            index === currentSlide
                                ? 'bg-white w-12 rounded-full shadow-lg'
                                : 'bg-white/50 w-3 rounded-full',
                        ]"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.btn-primary {
    @apply bg-white hover:bg-teal-600 text-teal-700 hover:text-white border-2 border-white px-6 py-3 rounded-full;
}
</style>
