<script setup>
import { ref, onMounted } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

const props = defineProps({
    mission: Object,
});

const content = JSON.parse(JSON.stringify(props.mission.content));

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

const missionRef = ref(null);

// Animation function
function setupScrollAnimations() {
    // Mission section animation
    gsap.from(".mission-title", {
        scrollTrigger: {
            trigger: missionRef.value,
            start: "top 80%",
        },
        y: 30,
        opacity: 0,
        duration: 0.8,
    });

    gsap.from(".mission-text", {
        scrollTrigger: {
            trigger: missionRef.value,
            start: "top 70%",
        },
        y: 30,
        opacity: 0,
        duration: 0.8,
        delay: 0.3,
    });
}

// Lifecycle hooks
onMounted(() => {
    setupScrollAnimations();
});
</script>

<template>
    <section id="mission" ref="missionRef" class="py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-6">
                <h2
                    class="mission-title text-2xl md:text-3xl font-bold mb-8 text-teal-800 leading-tight font-BlueOcean [word-spacing:1rem]"
                >
                    {{ content.title }}
                </h2>
                <p
                    class="mission-text text-xl leading-relaxed text-gray-700 font-BeinNormal"
                >
                    {{ content.description }}
                </p>
            </div>
        </div>
    </section>
</template>
