<script setup>
import { Link } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const cards = [
    {
        title: "لملاك العقارات",
        listItems: [
            "وصول فوري للمستأجرين والمستثمرين الجادين",
            "اعرض عقارك بطريقة احترافية وجذابة",
            "تحكم كامل في التأجير والإدارة بكل بساطة",
            "ضاعف أرباحك بأقل جهد وبدون وسيط",
        ],
        icon: "fa-solid fa-home",
    },
    {
        title: "للمستثمرين",
        listItems: [
            "حقق أرباحا مجزية باستثمارات مدروسة",
            "استثمر دون الحاجة لإدارة العقارات بنفسك",
            "استفد من فرص مضمونة بعوائد ثابتة",
            "انضم إلى نخبة المستثمرين في السوق العقاري",
        ],
        icon: "fa-solid fa-coins",
    },
    {
        title: "للباحث عن سكن",
        listItems: [
            "ابحث بسهولة عن شقة تناسب احتياجاتك",
            "احصل على أفضل الأسعار والعروض الحصرية",
            "استمتع بتجربة بحث سلسة وسريعة",
            "تواصل مباشرة مع الملاك والمستثمرين",
        ],
        icon: "fa-solid fa-user",
    },
];

// Function to generate random illustration properties
const generateIllustrations = () => {
    const count = Math.floor(Math.random() * 3) + 2; // 2–4 illustrations per card
    return Array.from({ length: count }, () => {
        const edge = Math.floor(Math.random() * 4);
        let top, left;

        switch (edge) {
            case 0: // Top edge
                top = `${Math.random() * 15}%`;
                left = `${Math.random() * 80 + 10}%`;
                break;
            case 1: // Right edge
                top = `${Math.random() * 80 + 10}%`;
                left = `${85 + Math.random() * 15}%`;
                break;
            case 2: // Bottom edge
                top = `${85 + Math.random() * 15}%`;
                left = `${Math.random() * 80 + 10}%`;
                break;
            case 3: // Left edge
                top = `${Math.random() * 80 + 10}%`;
                left = `${Math.random() * 15}%`;
                break;
        }

        return {
            top,
            left,
            size: `${Math.random() * 24 + 24}px`, // 24–48px
            opacity: Math.random() * 0.4 + 0.2, // 0.2–0.6
        };
    });
};

onMounted(() => {
    const cards = document.querySelectorAll(".content-wrapper");

    cards.forEach((card, index) => {
        const elements = card.querySelectorAll(".animate-element");

        // Set initial state for animated elements
        gsap.set(elements, { opacity: 0, y: 20 });

        gsap.to(elements, {
            opacity: 1,
            y: 0,
            duration: 0.7, // Slower duration (was 0.5)
            stagger: 0.15, // Slightly slower stagger (was 0.1)
            ease: "power2.out",
            scrollTrigger: {
                trigger: card,
                start: "top 85%",
                toggleActions: "play none none none",
            },
            delay: index * 0.2, // Slower delay between cards (was 0.1)
        });
    });
});
</script>

<template>
    <div class="min-h-screen py-20">
        <h2
            class="w-full text-center md:text-5xl text-3xl font-Bein text-teal-800 pb-12"
        >
            إكتشف منصة مساكن <span class="text-yellow-500">بلس</span>
        </h2>
        <div
            class="grid lg:grid-cols-3 grid-cols-1 justify-center w-full max-w-7xl mx-auto md:px-4 px-4"
        >
            <a
                :href="route('about-us')"
                v-for="(card, index) in cards"
                :key="card.id"
                class="group relative cursor-pointer min-h-[30rem]"
            >
                <!-- Background Layer -->
                <div class="absolute w-full h-full z-0 bg-teal-950"></div>

                <!-- Content Layer -->
                <div
                    class="relative flex flex-col justify-between bg-teal-800 pt-6 z-50 border-2 border-slate-800 hover:border-slate-800 w-full h-full hover:translate-x-4 hover:-translate-y-4 transition-all duration-500 ease-in-out space-y-6 overflow-hidden"
                >
                    <!-- Random Illustrations -->
                    <div
                        v-for="(illus, illusIndex) in generateIllustrations()"
                        :key="illusIndex"
                        class="absolute pointer-events-none"
                        :style="{
                            top: illus.top,
                            left: illus.left,
                            width: illus.size,
                            height: illus.size,
                            opacity: illus.opacity,
                        }"
                    >
                        <img
                            src="/assets/home_images/star_mosiac.png"
                            alt="Mosaic Pattern"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <!-- content -->
                    <div
                        class="space-y-6 px-12 content-wrapper"
                        :data-index="index"
                    >
                        <!-- icon -->
                        <div class="flex items-center justify-center">
                            <Icon
                                :icon="card.icon"
                                class="animate-element text-white w-16 h-16 group-hover:text-orange-300"
                            />
                        </div>
                        <!-- title -->
                        <p
                            class="animate-element text-slate-100 font-BlueOcean text-lg text-center group-hover:text-orange-300"
                        >
                            {{ card.title }}
                        </p>
                        <!-- points -->
                        <ul class="animate-element list-disc space-y-3">
                            <li
                                v-for="item in card.listItems"
                                :key="item"
                                class="text-slate-300 font-BeinNormal text-lg font-light"
                            >
                                {{ item }}
                            </li>
                        </ul>
                    </div>
                    <!-- button -->
                    <div>
                        <button
                            class="animate-element flex items-center justify-center text-white w-full gap-3 border-t border-t-slate-100 pt-5 pb-3 border-dotted hover:gap-6 hover:text-orange-300 transition-all duration-500 ease-in-out"
                        >
                            <Icon
                                icon="fa-solid fa-arrow-right"
                                class="w-6 h-6"
                            />
                            <p class="font-BlueOcean text-sm">إكتشف المزيد</p>
                        </button>
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>

<style scoped></style>
