<script setup>
import Hero from "@/Components/Home/Hero.vue";
import Features from "@/Components/Home/Features.vue";
import UsersCards from "@/Components/Home/UsersCards.vue";
import OurNumbers from "@/Components/Home/OurNumbers.vue";
import Teaser from "@/Components/Home/Teaser.vue";
import FAQs from "@/Components/Home/FAQs.vue";
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
    </div>
</template>

<style scoped></style>
