<script setup>
import { onMounted } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const stats = [
    {
        label: "لدينا حتى الآن أكثر من ٦٠ مالك راضٍ",
        number: 45,
        icon: "fa-solid fa-home",
    },
    {
        label: "لدينا أكثر من ٤٥ مستثمر ناجح",
        number: 68,
        icon: "fa-solid fa-coins",
    },
    {
        label: "لدينا أكثر من ١٨٠ مستأجر سعيد",
        number: 180,
        icon: "fa-solid fa-user",
    },
];

const animateNumber = (target, endValue) => {
    gsap.fromTo(
        target,
        { innerText: 0 },
        {
            innerText: endValue,
            duration: 2.5,
            ease: "expo.out", // Fast start, slow finish
            snap: { innerText: 1 },
            onUpdate: function () {
                target.innerText = Math.floor(target.innerText).toLocaleString(
                    "ar"
                );
            },
        }
    );
};

onMounted(() => {
    const statItems = document.querySelectorAll(".stat-item");

    statItems.forEach((item, index) => {
        const numberElement = item.querySelector(".stat-number");

        gsap.fromTo(
            item,
            { opacity: 0, y: 50 },
            {
                opacity: 1,
                y: 0,
                duration: 1.2,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: item,
                    start: "top 85%",
                    toggleActions: "play none none none",
                },
                delay: index * 0.3,
            }
        );

        ScrollTrigger.create({
            trigger: item,
            start: "top 85%",
            onEnter: () =>
                animateNumber(numberElement, numberElement.dataset.endValue),
        });
    });
});
</script>

<template>
    <div class="bg-white pb-12">
        <div class="max-w-7xl mx-auto px-8">
            <h2
                class="text-teal-800 font-Bein text-6xl text-center mb-16"
            >
                إنجازاتنا في <span class="text-yellow-500">أرقام</span>
            </h2>

            <div
                class="flex flex-col md:flex-row justify-between items-center gap-12 md:gap-0"
            >
                <div
                    v-for="(stat, index) in stats"
                    :key="index"
                    class="stat-item flex flex-col items-center text-center space-y-6 opacity-0 relative"
                >
                    <div class="relative">
                        <Icon
                            :icon="stat.icon"
                            class="text-teal-800 w-16 h-16"
                        />
                    </div>

                    <p
                        class="stat-number text-teal-900 font-BlueOcean text-5xl md:text-6xl"
                        :data-end-value="stat.number"
                    >
                        0
                    </p>

                    <p
                        class="text-yellow-500 font-Bein text-xl max-w-xs"
                    >
                        {{ stat.label }}
                    </p>
                </div>
            </div>

            <div class="mt-16 h-1 w-32 bg-teal-800 mx-auto rounded-full"></div>
        </div>
    </div>
</template>

<style scoped></style>
