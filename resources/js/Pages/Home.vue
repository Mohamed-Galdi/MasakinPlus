<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";

const page = usePage();

const user = page.props.auth.user;
</script>

<template>
    <div
        class="fixed top-0 left-0 w-screen h-screen bg-teal-900 bg-cover bg-center"
        style="background-image: url('/assets/images/bg-waves.png')"
    >
        <Head title="| Home" />
        <div v-if="!user" class="flex items-center justify-end p-4 gap-3">
            <Link
                :href="route('login')"
                class="bg-lime-200 hover:bg-lime-300 px-3 py-1 rounded-md border border-white text-teal-800 font-semibold"
                >Login</Link
            >
            <Link
                :href="route('register')"
                class="bg-lime-200 hover:bg-lime-300 px-3 py-1 rounded-md border border-white text-teal-800 font-semibold"
                >Register</Link
            >
        </div>
        <div v-else class="flex items-center justify-end p-4 gap-3">
            <Link
                :href="route('dashboard')"
                class="bg-lime-200 hover:bg-lime-300 px-3 py-1 rounded-md border border-white text-teal-800 font-semibold"
                >Products</Link
            >
        </div>
        <div class="grid place-items-center pt-44">
            <h1 class="text-6xl font-bold text-lime-200">PrimeVilt</h1>
        </div>
    </div>
</template>
