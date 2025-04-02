<script setup>
import { ref, onMounted } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

const timelineRef = ref(null);
const milestones = ref([
    {
        year: "2020",
        title: "تأسيس مساكن بلس",
        description: "بدأنا رحلتنا بفكرة بسيطة لتغيير سوق العقارات",
    },
    {
        year: "2021",
        title: "إطلاق المنصة",
        description: "أطلقنا منصتنا الرقمية الأولى لربط المالكين والمستثمرين",
    },
    {
        year: "2022",
        title: "توسع الخدمات",
        description: "توسعنا لنشمل المزيد من المدن في المملكة العربية السعودية",
    },
    {
        year: "2023",
        title: "شراكات استراتيجية",
        description: "عقدنا شراكات مع كبرى الشركات العقارية والمستثمرين",
    },
]);

// Animation function
function setupScrollAnimations() {
    // Timeline animation
    gsap.from(".timeline-item", {
        scrollTrigger: {
            trigger: timelineRef.value,
            start: "top 70%",
        },
        y: 30,
        opacity: 0,
        stagger: 0.15,
        duration: 0.8,
    });
}

// Lifecycle hooks
onMounted(() => {
    setupScrollAnimations();
});
</script>

<template>
    <section ref="timelineRef" class="py-24 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2
                class="text-4xl md:text-5xl font-bold mb-20 text-center text-teal-800"
            >
                رحلتنا
            </h2>

            <div class="relative">
                <!-- Timeline line -->
                <div
                    class="absolute top-0 left-1/2 w-1 h-full bg-gradient-to-b from-teal-600 to-teal-800 transform -translate-x-1/2 hidden md:block"
                ></div>

                <div class="space-y-24">
                    <div
                        v-for="(milestone, index) in milestones"
                        :key="index"
                        class="timeline-item relative"
                    >
                        <div
                            class="md:flex items-center"
                            :class="
                                index % 2 === 0
                                    ? 'md:flex-row'
                                    : 'md:flex-row-reverse'
                            "
                        >
                            <!-- Year bubble - Fixed positioning -->
                            <div
                                class="md:w-5/12 flex"
                                :class="
                                    index % 2 === 0
                                        ? 'justify-end'
                                        : 'justify-start'
                                "
                            >
                                <div
                                    class="w-16 h-16 rounded-full bg-gradient-to-br from-teal-600 to-teal-800 text-white flex items-center justify-center text-xl font-bold shadow-lg relative z-10"
                                >
                                    {{ milestone.year }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div
                                class="md:w-5/12 bg-white rounded-3xl shadow-lg p-8 mt-8 md:mt-0 relative"
                            >
                                <!-- Arrow pointer -->
                                <div
                                    class="absolute top-1/2 transform -translate-y-1/2 hidden md:block"
                                    :class="
                                        index % 2 === 0
                                            ? 'right-full mr-8'
                                            : 'left-full ml-8'
                                    "
                                >
                                    <div
                                        class="w-8 h-8 bg-white transform rotate-45"
                                    ></div>
                                </div>

                                <h3
                                    class="text-2xl font-bold mb-2 text-teal-800"
                                >
                                    {{ milestone.title }}
                                </h3>
                                <p class="text-gray-600">
                                    {{ milestone.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
