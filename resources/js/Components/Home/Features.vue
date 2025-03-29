<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import HomeButton from "./HomeButton.vue";

gsap.registerPlugin(ScrollTrigger);

const slides = [
    {
        type: "Owners",
        title: "تريد تحقيق اقصى استفادة من عقارك؟",
        subtitle: "اعرض عقارك واستمتع بإدارة احترافية",
        image: "/assets/home_images/owner.svg",
        cta: "اعرض الآن",
    },
    {
        type: "Investors",
        title: "تريد الاستثمار في العقار دون امتلاكه؟",
        subtitle: "استثمر بذكاء مع عوائد مضمونة",
        image: "/assets/home_images/investor.svg",
        cta: "استثمر الآن",
    },
    {
        type: "Tenants",
        title: "تبحث عن أفضل سكن بأفضل ثمن؟",
        subtitle: "ابحث عن منزلك المثالي بسهولة",
        image: "/assets/home_images/tenant.svg",
        cta: "ابحث الآن",
    },
];

const sectionRef = ref(null);
const imageRef = ref(null);
const textRefs = ref([]);
const currentSlide = ref(slides[0]);
let lastSlideIndex = 0;
let lastUpdateTime = 0;
const debounceDelay = 100; // ms

const animateImage = (slideIndex) => {
    gsap.to(imageRef.value, {
        scale: 0.8,
        rotation: 10,
        opacity: 0,
        duration: 0.5,
        ease: "power2.in",
        onComplete: () => {
            currentSlide.value = slides[slideIndex];
            gsap.to(imageRef.value, {
                scale: 1,
                rotation: 0,
                opacity: 1,
                duration: 0.5,
                ease: "power2.out",
            });
        },
    });
};

const animateMobileImage = (slideIndex) => {
    gsap.to(imageRef.value, {
        opacity: 0,
        duration: 0.3,
        onComplete: () => {
            currentSlide.value = slides[slideIndex];
            textRefs.value.forEach((textEl, idx) => {
                gsap.to(textEl, {
                    opacity: idx === slideIndex ? 1 : 0,
                    duration: 0.3,
                });
            });
            gsap.to(imageRef.value, { opacity: 1, duration: 0.3 });
        },
    });
};

onMounted(() => {
    if (!sectionRef.value || !imageRef.value || !textRefs.value.length) return;

    const isMobile = window.innerWidth < 768;

    if (!isMobile) {
        gsap.set(imageRef.value, { xPercent: 0, left: 0, right: "auto" });
        gsap.set(textRefs.value[0], { yPercent: 0, opacity: 1 });
        textRefs.value
            .slice(1)
            .forEach((textEl) =>
                gsap.set(textEl, { yPercent: 100, opacity: 0 })
            );

        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: sectionRef.value,
                start: "top top",
                end: `+=${(slides.length - 1) * 100}%`,
                scrub: 0.5,
                pin: true,
                onUpdate: (self) => {
                    const now = Date.now();
                    if (now - lastUpdateTime < debounceDelay) return;
                    const progress = self.progress;
                    const slideIndex = Math.round(
                        progress * (slides.length - 1)
                    );
                    if (slideIndex !== lastSlideIndex) {
                        animateImage(slideIndex);
                        lastSlideIndex = slideIndex;
                        lastUpdateTime = now;
                    }
                },
            },
        });

        slides.slice(1).forEach((slide, index) => {
            tl.to(
                textRefs.value[index],
                {
                    yPercent: -100,
                    opacity: 0,
                    duration: 0.6,
                    ease: "power2.in",
                },
                index * 0.8
            ).fromTo(
                textRefs.value[index + 1],
                { yPercent: 100, opacity: 0 },
                { yPercent: 0, opacity: 1, duration: 0.6, ease: "power2.out" },
                index * 0.8 + 0.4
            );
        });
    } else {
        textRefs.value.forEach((textEl, index) => {
            gsap.set(textEl, {
                position: "relative",
                opacity: index === 0 ? 1 : 0,
            });
        });

        gsap.timeline({
            scrollTrigger: {
                trigger: sectionRef.value,
                start: "top 80%",
                end: "bottom 20%",
                scrub: 1,
                onUpdate: (self) => {
                    const now = Date.now();
                    if (now - lastUpdateTime < debounceDelay) return;
                    const progress = self.progress;
                    const slideIndex = Math.round(
                        progress * (slides.length - 1)
                    );
                    if (slideIndex !== lastSlideIndex) {
                        animateMobileImage(slideIndex);
                        lastSlideIndex = slideIndex;
                        lastUpdateTime = now;
                    }
                },
            },
        });
    }
});

onUnmounted(() => {
    ScrollTrigger.getAll().forEach((trigger) => {
        if (trigger.trigger === sectionRef.value) trigger.kill();
    });
    gsap.killTweensOf([imageRef.value, ...textRefs.value]);
});
</script>

<template>
    <section
        ref="sectionRef"
        class="relative min-h-screen overflow-hidden bg-gradient-to-l from-teal-700 to-teal-900"
    >
        <div
            class="absolute -left-12 md:opacity-40 opacity-20 pointer-events-none"
        >
            <img
                src="/assets/home_images/mosiac.png"
                alt="Mosaic Pattern"
                class="h-screen"
            />
        </div>

        <div
            class="container mx-auto px-4 py-8 h-screen flex items-center justify-between max-w-7xl relative z-10 flex-col md:flex-row"
        >
            <div
                class="relative w-full md:w-1/3 z-20 h-full flex items-center justify-center"
            >
                <div
                    v-for="(slide, index) in slides"
                    :key="slide.type"
                    ref="textRefs"
                    class="text-slide absolute md:absolute inset-0 flex items-center justify-center"
                >
                    <div
                        class="space-y-4 text-center flex flex-col items-center"
                    >
                        <h2
                            class="leading-[5rem] text-6xl font-Bein text-white"
                        >
                            {{ slide.title }}
                        </h2>
                        <p
                            class="text-slate-300 font-BeinNormal text-xl md:text-2xl font-light"
                        >
                            {{ slide.subtitle }}
                        </p>
                        <HomeButton :title="slide.cta" />
                    </div>
                </div>
            </div>
            <div
                ref="imageRef"
                class="relative w-full md:w-3/4 z-10 mt-8 md:mt-0 h-full flex items-end"
            >
                <img
                    :src="currentSlide.image"
                    :key="currentSlide.type"
                    alt="Feature Image"
                    class="w-full h-auto max-w-md mx-auto md:max-w-full"
                />
            </div>
        </div>
        <!-- diveder -->
        <div style="overflow: hidden" class="bg-white relative">
            <h2
                class="w-full text-center text-4xl font-Bein text-teal-800 py-12 absolute z-10 md:top-12 top-16"
            >
                إكتشف منصة مساكن <span class="text-yellow-500">بلس</span>
            </h2>
            <svg
                preserveAspectRatio="none"
                viewBox="0 0 1200 120"
                xmlns="http://www.w3.org/2000/svg"
                style="width: 100%; height: 150px"
                class="md:h-[237px] h-[150px]"
            >
                <defs>
                    <linearGradient
                        id="gradient"
                        x1="100%"
                        y1="0%"
                        x2="0%"
                        y2="0%"
                    >
                        <stop offset="0%" style="stop-color: #0f766e" />
                        <stop offset="100%" style="stop-color: #134e4a" />
                    </linearGradient>
                </defs>
                <path
                    d="M321.39 56.44c58-10.79 114.16-30.13 172-41.86 82.39-16.72 168.19-17.73 250.45-.39C823.78 31 906.67 72 985.66 92.83c70.05 18.48 146.53 26.09 214.34 3V0H0v27.35a600.21 600.21 0 00321.39 29.09z"
                    fill="url(#gradient)"
                />
            </svg>
        </div>
    </section>
</template>

<style scoped>
.text-slide {
    height: 100%;
}
</style>
