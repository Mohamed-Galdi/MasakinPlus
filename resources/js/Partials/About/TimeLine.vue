<script setup>
import { ref, onMounted } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

const timelineRef = ref(null);
const milestones = ref([
    {
        year: "2022",
        title: "انطلاقة الرؤية",
        description: "بدأت فكرة مساكن بلس بهدف إحداث ثورة في سوق العقارات عبر حلول رقمية مبتكرة.",
    },
    {
        year: "2023",
        title: "إطلاق المنصة رسميًا",
        description: "تم إطلاق منصتنا الرقمية، موفرةً تجربة سلسة لأصحاب العقارات والمستثمرين والمستأجرين.",
    },
    {
        year: "2024",
        title: "التوسع على مستوى المملكة",
        description: "وسعنا نطاق خدماتنا لتشمل مختلف المدن السعودية، مما عزز من فرص الاستثمار والتأجير.",
    },
    {
        year: "2025",
        title: "شراكات استراتيجية كبرى",
        description: "عقدنا تحالفات مع كبرى الشركات العقارية والمستثمرين لتعزيز نمو السوق وزيادة العوائد.",
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
                class="text-2xl md:text-4xl font-bold mb-20 text-center text-teal-800 font-BlueOcean"
            >
                رحلتنا
            </h2>

            <div class="relative text-BeinNormal">
                <!-- Timeline line -->
                <div
                    class="absolute top-8 left-1/2 w-1 h-[90%] bg-teal-800 transform -translate-x-1/2 hidden md:block"
                ></div>

                <div class="space-y-24 ">
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

                            <!-- Content -->
                            <div
                                class="md:w-1/2 rounded-3xl shadow-lg overflow-hidden mt-8 md:mt-0 relative  flex justify-between items-center border-2 border-teal-800 font-Bein mx-[2px]"
                                :class="
                                index % 2 !== 0
                                    ? 'md:flex-row'
                                    : 'md:flex-row-reverse'
                            "
                            >
                                <div class="bg-teal-800 text-white w-1/6 h-[6rem] items-center flex justify-center">
                                    {{ milestone.year }}
                                </div>

                                <div class="px-6 w-5/6">
                                    <h3
                                        class="md:text-2xl text-xl font-bold mb-2 text-teal-800"
                                    >
                                        {{ milestone.title }}
                                    </h3>
                                    <p class="text-gray-600 md:text-base text-sm">
                                        {{ milestone.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
