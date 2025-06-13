<template>
    <div>
        <div ref="paymentForm" class="mysr-form"></div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";

const props = defineProps({
    amount: { type: Number, required: true }, // In halalas
    description: { type: String, required: true },
    currency: { type: String, default: "SAR" },
    callbackUrl: { type: String, required: true }, // route('wallet.index')
});

const paymentForm = ref(null);

onMounted(() => {
    const config = {
        element: paymentForm.value,
        amount: props.amount,
        currency: props.currency,
        description: props.description,
        publishable_api_key: import.meta.env.VITE_MOYASAR_API_KEY,
        callback_url: props.callbackUrl,
        methods: ["creditcard", "stcpay"],
    };

    window.Moyasar.init(config);
});
</script>

<style scoped>
.mysr-form {
    max-width: 500px;
    margin: auto;
}
</style>
