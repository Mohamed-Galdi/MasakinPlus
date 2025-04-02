<script setup>
import Hero from "@/Partials/About/Hero.vue";
import Mission from "@/Partials/About/Mission.vue";
import HowItWorks from "@/Partials/About/HowItWorks.vue";
import TimeLine from "@/Partials/About/TimeLine.vue";
import Team from "@/Partials/About/Team.vue";
import Contact from "@/Partials/About/Contact.vue";
import Footer from "@/Partials/Home/Footer.vue";
</script>

<template>
    <div class="about-us-page">
        <!-- Modern Hero Section -->
        <Hero />

        <!-- Mission Section -->
        <Mission />

        <!-- How It Works Section - Redesigned -->
        <HowItWorks />

        <!-- Timeline Section - Fixed -->
        <TimeLine />

        <!-- Team Section - Kept as is -->
        <Team />

        <!-- Contact Section - Kept as is -->
        <Contact />
    </div>
    <Footer />
</template>

<style scoped>
/* Hexagon shape */
.hexagon {
    position: relative;
    width: 60px;
    height: 60px;
    background-color: #f0fdfa;
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Animation classes */
@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
    100% {
        transform: translateY(0px);
    }
}

.float {
    animation: float 6s ease-in-out infinite;
}
</style>
