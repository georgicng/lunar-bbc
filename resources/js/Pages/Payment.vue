<script setup>
import { ref, computed } from "vue";
import paystack from "vue-paystack";

const props = defineProps({
    transaction: {
        type: Object,
        required: true,
    },
    order: {
        type: Object,
        required: true,
    },
    paymentMethod: {
        type: String,
        required: true,
    },
    meta: {
        type: Object,
        required: true,
    },
    paymentMethods: {
        type: Array,
        required: true,
    },
});

const paymentMethod = ref(props.paymentMethod);
const changePayment = async () => {
    await router.get(`/payments/${props.order.id}/change-method`, {
        transaction: props.transaction.id,
        method: paymentMethod.value,
    });
};

const processPayment = async (reference) => {
   await router.get(`/payments/${props.order.id}/process`, {
        transaction: props.transaction.id,
        method: paymentMethod.value,
        reference: reference,
    });
};

const close = () => {
    console.log("You closed checkout page");
};

const reference = () => {
    let text = "";
    let possible =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (let i = 0; i < 10; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
};
</script>

<template>
    <div class="payment-page">
        <h1>Make a Payment</h1>

        <form @submit.prevent="changePayment" class="payment-form">
            <select
                v-model="paymentMethod"
                className="w-full border border-gray-300 bg-white px-3 py-2 mt-2 outline-none"
            >
                <option value="">select payment mode</option>
                <option v-for="method in paymentMethods" :value="method.id">
                    {{ method.name }}
                </option>
            </select>
            <button type="submit">Proceed to Pay</button>
        </form>
        <paystack v-if="paymentMethod == 'card'"
            :amount="transaction.amount.value"
            :email="transaction.meta.billingAddress.email"
            :paystackkey="meta.public"
            :reference="transaction.reference"
            :callback="processPayment"
            :close="close"
        >
            Make Payment
        </paystack>
    </div>
</template>
