<script setup>
import Hero from "@/Partials/Home/Hero.vue";
import Features from "@/Partials/Home/Features.vue";
import UsersCards from "@/Partials/Home/UsersCards.vue";
import OurNumbers from "@/Partials/Home/OurNumbers.vue";
import Teaser from "@/Partials/Home/Teaser.vue";
import FAQs from "@/Partials/Home/FAQs.vue";
import Footer from "@/Partials/Home/Footer.vue";

const props = defineProps({
    hero: Object,
    usersCards: Object,
    features: Object,
});
</script>

<template>
    <div>
        <!-- hero -->
        <Hero :hero="props.hero" />
        <!-- <div class="h-screen bg-white w-full"></div> -->

        <!-- Teaser -->
        <Teaser />

        <!-- Users Cards -->
        <UsersCards :usersCards="props.usersCards" />

        <!-- Features -->
        <Features :features="props.features" />

        <!-- Our Numbers -->
        <OurNumbers />

        <!-- FAQs -->
        <FAQs />

        <!-- Footer -->
        <Footer />
    </div>
</template>

<style scoped></style>
