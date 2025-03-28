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

onMounted(() => {
    if (!sectionRef.value || !imageRef.value || !textRefs.value.length) return;

    const isMobile = window.innerWidth < 768;

    if (!isMobile) {
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: sectionRef.value,
                start: "top top",
                end: `+=${(slides.length - 1) * 100}%`,
                scrub: 0.5,
                pin: true,
                onUpdate: (self) => {
                    const progress = self.progress;
                    const slideIndex = Math.round(
                        progress * (slides.length - 1)
                    );
                    if (slideIndex !== lastSlideIndex) {
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
                        lastSlideIndex = slideIndex;
                    }
                },
            },
        });

        gsap.set(imageRef.value, { xPercent: 0, left: 0, right: "auto" });
        gsap.set(textRefs.value[0], { yPercent: 0, opacity: 1 });
        textRefs.value
            .slice(1)
            .forEach((textEl) =>
                gsap.set(textEl, { yPercent: 100, opacity: 0 })
            );

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
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: sectionRef.value,
                start: "top 80%",
                end: "bottom 20%",
                scrub: 1,
                onUpdate: (self) => {
                    const progress = self.progress;
                    const slideIndex = Math.round(
                        progress * (slides.length - 1)
                    );
                    if (slideIndex !== lastSlideIndex) {
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
                                gsap.to(imageRef.value, {
                                    opacity: 1,
                                    duration: 0.3,
                                });
                            },
                        });
                        lastSlideIndex = slideIndex;
                    }
                },
            },
        });

        textRefs.value.forEach((textEl, index) => {
            gsap.set(textEl, {
                position: "relative",
                opacity: index === 0 ? 1 : 0,
            });
        });
    }
});

onUnmounted(() => {
    const scrollTrigger = ScrollTrigger.getById(sectionRef.value?.id);
    if (scrollTrigger) scrollTrigger.kill();
    gsap.killTweensOf([imageRef.value, textRefs.value]);
});
</script>

<template>
    <section
        ref="sectionRef"
        class="relative min-h-screen overflow-hidden bg-gradient-to-br from-teal-900 via-teal-800 to-teal-700"
    >
        <div
            class="absolute inset-0 opacity-30 pointer-events-none overflow-hidden"
        >
            <div
                class="absolute w-96 h-96 bg-white rounded-full filter blur-3xl -top-48 -left-48 animate-pulse-slow md:w-72 md:h-72"
            ></div>
            <div
                class="absolute w-72 h-72 bg-teal-500 rounded-full filter blur-2xl top-0 right-1/3 animate-pulse-slow delay-1000 md:w-56 md:h-56"
            ></div>
            <div
                class="absolute w-64 h-64 bg-white rounded-full filter blur-xl -bottom-32 right-44 animate-pulse-slow delay-500 md:w-48 md:h-48"
            ></div>
        </div>

        <div
            class="container mx-auto px-4 py-16 h-screen flex items-center justify-between max-w-7xl relative z-10 flex-col md:flex-row"
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
                            class="leading-[5rem] text-6xl font-Bein text-white "
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
                class="relative w-full md:w-3/4 z-10 mt-8 md:mt-0"
            >
                <img
                    :src="currentSlide.image"
                    :key="currentSlide.type"
                    alt="Feature Image"
                    class="w-full h-auto max-w-md mx-auto md:max-w-full"
                />
            </div>
        </div>
    </section>
</template>

<style scoped>
.text-slide {
    height: 100%;
}

@keyframes pulse-slow {
    0%,
    100% {
        transform: scale(1);
        opacity: 0.3;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.5;
    }
}

.animate-pulse-slow {
    animation: pulse-slow 6s infinite ease-in-out;
}

.delay-500 {
    animation-delay: 0.5s;
}

.delay-1000 {
    animation-delay: 1s;
}
</style>
