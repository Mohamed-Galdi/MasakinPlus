<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const properties = [
    {
        title: "فيلا راقية بتصميم عصري",
        location: "الرياض، حي الياسمين",
        rentPrice: "16,000 ريال/شهر",
        image: "assets/home_images/teaser/p1.jpg",
        type: "فيلا",
        rating: "4.9/5",
    },
    {
        title: "شقة مفروشة بإطلالة بانورامية",
        location: "جدة، حي الحمراء",
        rentPrice: "7,500 ريال/شهر",
        image: "assets/home_images/teaser/p2.jpg",
        type: "شقة",
        rating: "4.8/5",
    },
    {
        title: "دوبلكس واسع مع حديقة",
        location: "الدمام، حي الشاطئ",
        rentPrice: "9,000 ريال/شهر",
        image: "assets/home_images/teaser/p3.jpg",
        type: "دوبلكس",
        rating: "4.9/5",
    },
    {
        title: "استوديو فاخر في موقع حيوي",
        location: "الرياض، حي الملز",
        rentPrice: "3,500 ريال/شهر",
        image: "assets/home_images/teaser/p4.jpg",
        type: "استوديو",
        rating: "4.7/5",
    },
    {
        title: "فيلا واسعة مع مسبح خاص",
        location: "الخبر، حي العقربية",
        rentPrice: "20,000 ريال/شهر",
        image: "assets/home_images/teaser/p5.jpg",
        type: "فيلا",
        rating: "4.8/5",
    },
    {
        title: "شقة أنيقة بموقع مميز",
        location: "تبوك، حي المروج",
        rentPrice: "5,000 ريال/شهر",
        image: "assets/home_images/teaser/p6.jpg",
        type: "شقة",
        rating: "4.7/5",
    },
    {
        title: "استوديو مريح بأسعار مناسبة",
        location: "الطائف، حي شهار",
        rentPrice: "3,000 ريال/شهر",
        image: "assets/home_images/teaser/p7.jpg",
        type: "استوديو",
        rating: "4.8/5",
    },
    {
        title: "فيلا فخمة بتشطيب راقٍ",
        location: "بريدة، حي النهضة",
        rentPrice: "18,500 ريال/شهر",
        image: "assets/home_images/teaser/p8.jpg",
        type: "فيلا",
        rating: "4.9/5",
    },
    {
        title: "شقة راقية قريبة من الخدمات",
        location: "أبها، حي السد",
        rentPrice: "4,500 ريال/شهر",
        image: "assets/home_images/teaser/p9.jpg",
        type: "شقة",
        rating: "4.8/5",
    },
];

const triggers = ref([]);

onMounted(() => {
    const cards = document.querySelectorAll(".property-card");

    gsap.utils.toArray(cards).forEach((card, index) => {
        // Set initial state
        gsap.set(card, { opacity: 0, y: 30 });

        const trigger = ScrollTrigger.create({
            trigger: card,
            start: "top 85%", // Trigger slightly earlier for smoother reveal
            onEnter: () => {
                gsap.to(card, {
                    opacity: 1,
                    y: 0,
                    duration: 0.6,
                    ease: "power1.out",
                    delay: index * 0.15, // Staggered delay for one-by-one effect
                });
            },
            once: true, // Animation runs only once
        });
        triggers.value.push(trigger);
    });
});

onBeforeUnmount(() => {
    triggers.value.forEach((trigger) => trigger.kill());
    ScrollTrigger.getAll().forEach((st) => st.kill());
});
</script>

<template>
    <section class="py-16 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2
                class="text-teal-900 font-Bein text-3xl sm:text-4xl md:text-5xl text-center mb-12"
            >
                لمحة عن <span class="text-yellow-500">عقاراتنا</span>
            </h2>

            <div class="relative">
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-h-[80rem] overflow-hidden"
                >
                    <div
                        v-for="property in properties"
                        :key="property.id"
                        class="property-card relative rounded-2xl overflow-hidden shadow-md bg-white h-[28rem]"
                    >
                        <div class="absolute top-4 right-4 z-10">
                            <span
                                class="bg-white text-slate-800 px-4 py-1 rounded-full text-sm font-Bein shadow-sm"
                            >
                                {{ property.type }}
                            </span>
                        </div>

                        <div class="w-full h-full overflow-hidden">
                            <img
                                :src="property.image"
                                :alt="property.title"
                                class="w-full h-full object-cover transition-transform duration-500"
                                loading="lazy"
                            />
                        </div>

                        <div
                            class="absolute bottom-4 left-4 right-4 bg-white p-4 rounded-lg shadow-md"
                        >
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3
                                        class="font-Bein text-xl text-slate-800 leading-tight"
                                    >
                                        {{ property.title }}
                                    </h3>
                                    <div
                                        class="flex items-center mt-1.5 text-sm text-slate-500"
                                    >
                                        <Icon
                                            icon="fa-solid fa-location-dot"
                                            class="w-4 h-4 ml-1 text-blue-500"
                                        />
                                        <span>{{ property.location }}</span>
                                    </div>
                                </div>
                                <Icon
                                    icon="fa-regular fa-bookmark"
                                    class="h-5 w-5 text-blue-600"
                                />
                            </div>

                            <div class="flex justify-between items-center mt-3">
                                <div class="font-Bein text-lg text-slate-800">
                                    {{ property.rentPrice }}
                                </div>
                                <div
                                    class="flex items-center text-amber-500 gap-1"
                                >
                                    <Icon
                                        icon="fa-solid fa-star"
                                        class="h-4 w-4"
                                    />
                                    <span
                                        class="text-sm text-slate-700 font-Bein pt-1"
                                    >
                                        {{ property.rating }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shadow Overlay preserved -->
                <div
                    class="absolute bottom-0 left-0 right-0 h-48 bg-gradient-to-t from-slate-50 via-slate-50/90 to-transparent pointer-events-none"
                ></div>
            </div>

            <div class="text-center mt-8 relative z-10">
                <button
                    class="bg-teal-600 text-white font-BeinNormal text-lg py-3 px-8 rounded-full hover:bg-teal-700 transition-colors duration-300 flex mx-auto items-center gap-2"
                >
                    <Icon icon="fa-solid fa-arrow-right" class="w-6 h-6" />
                    <span>شاهد المزيد</span>
                </button>
            </div>
        </div>
    </section>
</template>

<style scoped>
.property-card {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
}
</style>
