<script setup>
import InvestorLayout from "@/Layouts/InvestorLayout.vue";
import OwnerLayout from "@/Layouts/OwnerLayout.vue";
import TenantLayout from "@/Layouts/TenantLayout.vue";
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.user;
const DynamicLayout = computed(() => {
  switch(user.type) {
    case 'investor': return InvestorLayout;
    case 'owner': return OwnerLayout;
    default: return TenantLayout;
  }
});
</script>

<template>
  <component :is="DynamicLayout">
    <slot />
  </component>
</template>