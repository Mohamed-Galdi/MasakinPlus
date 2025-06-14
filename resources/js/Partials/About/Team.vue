<script setup>
import { ref, onMounted } from "vue";
import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

const props = defineProps({
    team: Object,
});

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

const teamRef = ref(null);
const team = JSON.parse(JSON.stringify(props.team.content.team));

// Animation function
function setupScrollAnimations() {
    // Team members animation
    gsap.from(".team-member", {
        scrollTrigger: {
            trigger: teamRef.value,
            start: "top 70%",
        },
        y: 50,
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
    <section ref="teamRef" class="py-24 bg-white">
        <div class="container mx-auto px-4">
            <h2
                class="text-3xl md:text-4xl font-bold mb-20 text-center text-teal-800 font-BlueOcean"
            >
                فريقنا
            </h2>

            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 font-Bein"
            >
                <div
                    v-for="(member, index) in team"
                    :key="index"
                    class="team-member group"
                >
                    <div class="relative overflow-hidden rounded-3xl shadow-lg">
                        <!-- Image with overlay -->
                        <div class="aspect-[3/4] relative overflow-hidden">
                            <img
                                :src="member.image"
                                :alt="member.name"
                                class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-110"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            >
                                <div
                                    class="absolute bottom-0 left-0 w-full p-6"
                                >
                                    <p
                                        class="text-white text-lg font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100"
                                    >
                                        "{{ member.quote }}"
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Info card -->
                        <div
                            class="absolute bottom-0 left-0 w-full bg-slate-200 p-4 transform translate-y-0 group-hover:translate-y-full transition-transform duration-300"
                        >
                            <h3 class="text-xl font-bold text-teal-800">
                                {{ member.name }}
                            </h3>
                            <p class="text-gray-600">{{ member.position }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
