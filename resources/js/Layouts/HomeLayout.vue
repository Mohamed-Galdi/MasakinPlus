<script setup>
import { Link } from "@inertiajs/vue3";
import Footer from "@/Partials/Home/Footer.vue";
import HomeButton from "@/Components/HomeButton.vue";
</script>

<template>
    <div class="relative">
        <!-- navbar -->
        <nav
            class="absolute flex justify-between items-center text-white md:px-24 px-4 py-4 mx-auto z-50 w-full bg-gradient-to-tl from-teal-700 to-teal-900"
        >
            <Link :href="route('home')" class="nav-logo">
                <img
                    src="/assets/logos/logo_white.png"
                    alt="MasakinPlus"
                    class="w-16 h-16"
                />
            </Link>
            <Link :href="route('register')" class="nav-join-btn"
                ><HomeButton
            /></Link>
        </nav>

        <!-- main content -->
        <div class="bg-gray-50 min-h-screen pt-24">
            <slot />
        </div>

        <!-- Footer -->
        <Footer />
    </div>
</template>

<style scoped></style>
