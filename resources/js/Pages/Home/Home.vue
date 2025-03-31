<script setup>
import Hero from "@/Components/Home/Partials/Hero.vue";
import Features from "@/Components/Home/Partials/Features.vue";
import UsersCards from "@/Components/Home/Partials/UsersCards.vue";
import OurNumbers from "@/Components/Home/Partials/OurNumbers.vue";
import Teaser from "@/Components/Home/Partials/Teaser.vue";
import FAQs from "@/Components/Home/Partials/FAQs.vue";
import Footer from "@/Components/Home/Partials/Footer.vue";
</script>

<template>
    <div>
        <!-- hero -->
        <Hero />
        <!-- <div class="h-screen bg-white w-full"></div> -->

        <!-- Teaser -->
        <Teaser />

        <!-- Users Cards -->
        <UsersCards />

        <!-- Features -->
        <Features />

        <!-- Our Numbers -->
        <OurNumbers />

        <!-- FAQs -->
        <FAQs />

        <!-- Footer -->
         <Footer />
    </div>
</template>

<style scoped></style>
