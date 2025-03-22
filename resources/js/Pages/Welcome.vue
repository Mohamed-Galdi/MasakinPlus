<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";

const page = usePage();

const user = page.props.auth.user;


</script>

<template>
    <div class="container w-full h-screen bg-teal-900">
        <Head title="| Welcome" />
        <div v-if="!user" class="flex items-center justify-end p-4 gap-3">
            <Link :href="route('login')" class="bg-lime-200 hover:bg-lime-300 px-3 py-1 rounded-md border border-white text-teal-800 font-semibold">Login</Link>
            <Link :href="route('register')" class="bg-lime-200 hover:bg-lime-300 px-3 py-1 rounded-md border border-white text-teal-800 font-semibold">Register</Link>
        </div>
        <div v-else class="flex items-center justify-end p-4 gap-3">
            <Link :href="route('admin.products.index')" class="bg-lime-200 hover:bg-lime-300 px-3 py-1 rounded-md border border-white text-teal-800 font-semibold">Products</Link>
        </div>
        
        <div class="gird place-items-center pt-44 ">
            <h1 class="text-6xl font-bold text-lime-200">PrimeVilt</h1>
        </div>
    </div>
</template>

<style scoped>
.container {
    background-image: url("../../../public/assets/images/bg-waves.png");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
}
</style>
