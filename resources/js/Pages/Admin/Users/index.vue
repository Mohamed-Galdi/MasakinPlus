<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";


defineOptions({
    layout: AdminLayout,
});
const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
});

</script>

<template>
    <p class="text-4xl">المستخدمين</p>
    <div>
        <div v-for="user in props.users.data" :key="user.id" >
            <p>{{ user.name}} </p>
        </div>
    </div>
</template>
