<script setup>
import { ref, computed, onMounted } from "vue";
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

const paymentMethod = ref("");
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

onMounted(() => {
    console.log({
        amount:props.transaction.amount.value,
        email:props.transaction.meta.billingAddress.contact_email,
        paystackkey:props.meta.public,
        reference:props.transaction.reference,
    }, props);
    paymentMethod.value = props.paymentMethod;
});
</script>

<template>
    <div class="payment-page">
        <div
            class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-4xl w-full space-y-8">
                <!-- Invoice Box -->
                <div class="invoice-box bg-white p-8 rounded-xl shadow-lg">
                    <!-- Header -->
                    <div
                        class="flex justify-between items-center border-b pb-6"
                    >
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">
                                Invoice
                            </h1>
                            <p class="text-gray-600">
                                Order Reference: <span v-text="order.id"></span>
                            </p>
                        </div>
                        <div class="text-right">
                            <h2 class="text-xl font-semibold text-gray-900">
                                Your Store
                            </h2>
                            <p class="text-gray-600">
                                123 Commerce St, Dhaka, Bangladesh
                            </p>
                            <p class="text-gray-600">support@yourstore.com</p>
                        </div>
                    </div>

                    <!-- Billing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Billed To
                            </h3>
                            <p class="text-gray-600" v-text="transaction.meta.billingAddress.first_name"></p>
                            <p
                                class="text-gray-600"
                                v-text="transaction.meta.billingAddress.address"
                            ></p>
                            <p class="text-gray-600">
                                City: <span v-text="transaction.meta.billingAddress.city"></span>
                            </p>
                            <p class="text-gray-600">
                                Postal Code:
                                <span v-text="transaction.meta.billingAddress.postcode"></span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-600">
                                Invoice Date: <span v-text="order.placed_at"></span>
                            </p>
                            <p class="text-gray-600">
                                Transaction Date:
                                <span v-text="transaction.created_at"></span>
                            </p>
                            <p class="text-gray-600">
                                Payment Status:
                                <span
                                    class="font-medium"
                                    x-text="order.status"
                                ></span>
                            </p>

                            <p>
                                Change Method:
                                <div class="flex flex-col">
                                    <select
                                        v-model="paymentMethod"
                                        className="w-full border border-gray-300 bg-white px-3 py-2 mt-2 outline-none"
                                    >
                                        <option value="">
                                            select payment mode
                                        </option>
                                        <option
                                            v-for="method in paymentMethods"
                                            :value="method.id"
                                        >
                                            {{ method.name }}
                                        </option>
                                    </select>
                                    <button type="button" @click="changePayment">Proceed to Pay</button>
                                </div>
                            </p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mt-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th
                                            class="py-2 px-4 font-semibold text-gray-900"
                                        >
                                            Description
                                        </th>
                                        <th
                                            class="py-2 px-4 font-semibold text-gray-900"
                                        >
                                            Qty
                                        </th>
                                        <th
                                            class="py-2 px-4 font-semibold text-gray-900"
                                        >
                                            Unit Price
                                        </th>
                                        <th
                                            class="py-2 px-4 font-semibold text სgray-900 text-right"
                                        >
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in order.lines" class="border-b">
                                        <td class="py-2 px-4" v-text="item.description">
                                        </td>
                                        <td class="py-2 px-4" v-text="item.unitQty"></td>
                                        <td
                                            class="py-2 px-4"
                                            v-text="item.unit_price "
                                        ></td>
                                        <td
                                            class="py-2 px-4 text-right"
                                            v-text="item.total.value"
                                        ></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="mt-6 flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span v-text="order.sub_total.value"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping</span>
                                    <span v-text="order.shipping_total.value"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tax (0%)</span>
                                    <span v-text="order.tax_total.value"></span>
                                </div>
                                <div
                                    class="flex justify-between font-semibold text-lg border-t pt-2"
                                >
                                    <span>Total</span>
                                    <span v-text="order.total.value"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Payment Information
                        </h3>
                        <div
                            class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-600"
                        >
                            <div>
                                <p>
                                    Transaction ID:
                                    <span x-text="payment.transactionId"></span>
                                </p>
                                <p>
                                    Amount Paid:
                                    <span
                                        x-text="payment.amountPaid + ' BDT'"
                                    ></span>
                                </p>
                                <p>
                                    Payment Method:
                                    <span x-text="payment.cardType"></span>
                                </p>
                            </div>
                            <div>
                                <p>
                                    Bank Transaction ID:
                                    <span
                                        x-text="payment.bankTransactionId"
                                    ></span>
                                </p>
                                <p>
                                    Transaction Date:
                                    <span
                                        x-text="payment.transactionDate"
                                    ></span>
                                </p>
                                <p>
                                    Payment Status:
                                    <span
                                        class="font-medium"
                                        x-text="payment.status"
                                    ></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-6 text-center text-gray-600">
                        <p>Thank you for your purchase!</p>
                        <p>
                            If you have any questions, contact us at
                            support@yourstore.com
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-center gap-4 no-print">
                    <button
                        @click="window.print()"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition duration-300"
                    >
                        Print Invoice
                    </button>
                    <button
                        @click="downloadPDF()"
                        class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700 transition duration-300"
                    >
                        Download PDF
                    </button>
                    <template v-if="paymentMethod == 'card'">
            <paystack
                :amount="transaction.amount.value"
                :email="transaction.meta.billingAddress.contact_email"
                :paystackkey="meta.public"
                :reference="transaction.reference"
                :callback="processPayment"
                :close="close"
                :embed="false"
            >
                Paystack
            </paystack>
        </template>
                </div>
            </div>
        </div>

    </div>
</template>
