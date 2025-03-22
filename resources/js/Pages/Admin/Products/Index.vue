<script setup>
import { router } from '@inertiajs/vue3';

const logout = () => {
    router.post(route("logout"));
}
</script>

<template>
    <p>Products</p>
    <button @click="logout">logout</button>
</template>
