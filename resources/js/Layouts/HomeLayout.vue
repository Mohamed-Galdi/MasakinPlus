<script setup>
import Footer from "@/Components/Home/Footer.vue";
import HomeButton from "@/Components/Home/HomeButton.vue";
</script>

<template>
    <div class="relative">
        <!-- navbar -->
        <nav class="absolute flex justify-between items-center text-white px-24 py-4 mx-auto z-50 w-full ">
            <div class="nav-logo">
                <img
                    src="/assets/logos/logo_white.png"
                    alt="MasakinPlus"
                    class="w-16 h-16"
                />
            </div>
            <div class="nav-join-btn"><HomeButton /></div>
        </nav>

        <!-- main content -->
        <div class="bg-gray-50">
            <slot />
        </div>

        <!-- Footer -->
        <Footer />
    </div>
</template>

<style scoped></style>
