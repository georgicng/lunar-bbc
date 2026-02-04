<script setup>
import { Link, router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import { mapToId } from "../lib";
import Modal from "@/components/Modal.vue";

const props = defineProps({
    cart: {
        type: Object,
        required: true,
    },
});
console.log(props.cart);
const showAddress = ref(false);
const contact = ref(
    props.cart.data.addresses?.billingAddress ?? {
        country_id: null,
        first_name: "George",
        last_name: "Ikpugbu",
        company_name: null,
        line_one: "",
        city: "Lekki",
        state: null,
        postcode: "12345",
        contact_email: "gaiprojectz@gmail.com",
        contact_phone: "08189067546",
    }
);
const showAddressForm = ref(false);
const address = computed(() => {
    if (!props.cart.data.addresses?.billingAddress) {
        return "No address added yet";
    }
    return `${contact.value.line_one}, ${contact.value.city}`;
});
const cityMap = computed(() => {
    if (!props.cart.data.meta?.cities) {
        return {};
    }
    return mapToId(props.cart.data.meta.cities, "name");
});
const setAddress = async () => {
    await router.post("/cart/current/address", {
        address: {
            ...contact.value,
            country_id: cityMap.value[contact.value.city].country_id,
            state: cityMap.value[contact.value.city].state_id,
        },
    });
    showAddressForm.value = false;
};
const shippingMethod = ref(props.cart.data.shipping);
watch(shippingMethod, async (val) => {
    console.log("shipping", val);
    await router.put("/cart/current/shipping", {
        shipping: val,
    });
});
const paymentMethod = ref("");
const submit = async () => {
    await router.get(`/checkout/${paymentMethod.value}`);
};

const canSubmit = computed(() => {
    return (
        paymentMethod.value &&
        shippingMethod.value &&
        props.cart.data.addresses?.billingAddress
    );
});
</script>

<template>
    <div
        className="flex flex-col md:flex-row py-16 max-w-6xl w-full px-6 mx-auto"
    >
        <div className="flex-1 max-w-4xl">
            <h1 className="text-3xl font-medium mb-6">
                Shopping Cart
                <span className="text-sm text-indigo-500">{{
                    cart.lines?.length
                }}</span>
            </h1>

            <div
                className="grid grid-cols-[2fr_1fr_1fr_1fr] text-gray-500 text-base font-medium pb-3"
            >
                <p className="text-left">Product Details</p>
                <p className="text-center">Unit Price</p>
                <p className="text-center">Subtotal</p>
                <p className="text-center">Action</p>
            </div>

            <div
                v-for="(product, index) in cart.data.lines"
                :key="index"
                className="grid grid-cols-[2fr_1fr_1fr_1fr] text-gray-500 items-center text-sm md:text-base font-medium pt-3"
            >
                <div className="flex items-center md:gap-6 gap-3">
                    <div
                        className="cursor-pointer w-24 h-24 flex items-center justify-center border border-gray-300 rounded overflow-hidden"
                    >
                        <img
                            className="max-w-full h-full object-cover"
                            :src="product.image"
                            :alt="product.name"
                        />
                    </div>
                    <div>
                        <p className="hidden md:block font-semibold">
                            {{ product.name }}
                        </p>
                        <div className="font-normal text-gray-500/70">
                            <p>
                                <span>{{ product.option || "N/A" }}</span>
                            </p>
                            <div className="flex items-center">
                                <p>Qty:</p>
                                <select
                                    :value="product.quantity"
                                    className="outline-none"
                                >
                                    <option
                                        v-for="(_, index) in Array(5).fill('')"
                                        :key="index"
                                        :value="index + 1"
                                    >
                                        {{ index + 1 }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <p className="text-center">{{ product.unitPrice }}</p>
                <p className="text-center">{{ product.subTotal }}</p>
                <button className="cursor-pointer mx-auto">
                    <svg
                        width="20"
                        height="20"
                        viewBox="0 0 20 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="m12.5 7.5-5 5m0-5 5 5m5.833-2.5a8.333 8.333 0 1 1-16.667 0 8.333 8.333 0 0 1 16.667 0"
                            stroke="#FF532E"
                            strokeWidth="1.5"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                        />
                    </svg>
                </button>
            </div>

            <Link
                href="/products"
                className="group cursor-pointer flex items-center mt-8 gap-2 text-indigo-500 font-medium"
            >
                <svg
                    width="15"
                    height="11"
                    viewBox="0 0 15 11"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M14.09 5.5H1M6.143 10 1 5.5 6.143 1"
                        stroke="#615fff"
                        strokeWidth="1.5"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                    />
                </svg>
                Continue Shopping
            </Link>
        </div>

        <div
            className="max-w-[360px] w-full bg-gray-100/40 p-5 max-md:mt-16 border border-gray-300/70"
        >
            <h2 className="text-xl md:text-xl font-medium">Order Summary</h2>
            <hr className="border-gray-300 my-5" />

            <div className="mb-6">
                <p className="text-sm font-medium uppercase">
                    Delivery Address
                </p>
                <div className="relative flex justify-between items-start mt-2">
                    <p className="text-gray-500">
                        {{ address }}
                    </p>
                    <button
                        @click="showAddressForm = true"
                        className="text-indigo-500 hover:underline cursor-pointer"
                    >
                        {{
                            cart.data.addresses?.billingAddress
                                ? "Change"
                                : "Add"
                        }}
                    </button>

                    <div
                        v-if="showAddress"
                        className="absolute top-12 py-1 bg-white border border-gray-300 text-sm w-full"
                    >
                        <p
                            v-for="address in cart.data.addresses"
                            className="text-gray-500 p-2
                            hover:bg-gray-100"
                        >
                            {{ address }}
                        </p>
                        <button
                            @click="showAddressForm = true"
                            type="button"
                            className="text-indigo-500
                            text-center cursor-pointer p-2
                            hover:bg-indigo-500/10"
                        >
                            Add address
                        </button>
                    </div>
                </div>
                <template v-if="cart.data.addresses?.billingAddress">
                    <p className="text-sm font-medium uppercase mt-6">
                        Shipping Method
                    </p>

                    <select
                        v-model="shippingMethod"
                        className="w-full border border-gray-300 bg-white px-3 py-2 mt-2 outline-none"
                    >
                        <option value="">select shipping</option>
                        <option
                            v-for="method in cart.data.shippingMethods"
                            :value="method.identifier"
                        >
                            {{ method.name }}
                        </option>
                    </select>
                </template>
                <template v-if="shippingMethod">
                    <p className="text-sm font-medium uppercase mt-6">
                        Payment Method
                    </p>

                    <select
                        v-model="paymentMethod"
                        className="w-full border border-gray-300 bg-white px-3 py-2 mt-2 outline-none"
                    >
                        <option value="">select payment mode</option>
                        <option
                            v-for="method in cart.data.paymentMethods"
                            :value="method.id"
                        >
                            {{ method.name }}
                        </option>
                    </select>
                </template>
            </div>

            <hr className="border-gray-300" />

            <div className="text-gray-500 mt-4 space-y-2">
                <p className="flex justify-between">
                    <span>Price</span
                    ><span>{{ cart.data.totals.subTotal }}</span>
                </p>
                <p className="flex justify-between">
                    <span>Shipping Fee</span
                    ><span className="text-green-600">{{
                        cart.data.totals.shippingTotal
                    }}</span>
                </p>
                <p className="flex justify-between">
                    <span>Tax (2%)</span
                    ><span>{{ cart.data.totals.taxTotal }}</span>
                </p>
                <p className="flex justify-between text-lg font-medium mt-3">
                    <span>Total Amount:</span
                    ><span>{{ cart.data.totals.total }}</span>
                </p>
            </div>

            <button
                @click="submit"
                :disabled="!canSubmit"
                class="w-full py-3 mt-6 text-white font-medium"
                :class="{
                    'bg-indigo-500 cursor-pointer  hover:bg-indigo-600 transition': canSubmit,
                    'bg-indigo-300 cursor-not-allowed': !canSubmit,
                }"
            >
                Place Order
            </button>
        </div>
        <Modal
            title="Address Modal"
            :show="showAddressForm"
            @close="showAddressForm = false"
        >
            <form
                class="bg-white text-gray-500 max-w-96 mx-4 md:p-6 p-4 text-left text-sm rounded-lg shadow-lg"
            >
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm text-gray-500 mb-2"
                            >First name</label
                        >
                        <input
                            type="text"
                            v-model="contact.first_name"
                            placeholder="David"
                            class="w-full px-3 py-3 border border-gray-300 rounded-lg text-sm outline-none focus:border-indigo-500 transition-colors"
                        />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-500 mb-2"
                            >Last name</label
                        >
                        <input
                            type="text"
                            v-model="contact.last_name"
                            placeholder="Andrew"
                            class="w-full px-3 py-3 border border-gray-300 rounded-lg text-sm outline-none focus:border-indigo-500 transition-colors"
                        />
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm text-gray-500 mb-2"
                        >Email id</label
                    >
                    <input
                        type="email"
                        v-model="contact.contact_email"
                        placeholder="david@company.com"
                        class="w-full px-3 py-3 border border-gray-300 rounded-lg text-sm outline-none focus:border-indigo-500 transition-colors"
                    />
                </div>

                <div class="mb-5">
                    <label class="block text-sm text-gray-500 mb-2"
                        >Phone number</label
                    >
                    <div
                        class="flex border border-gray-300 rounded-lg overflow-hidden focus-within:border-indigo-500 transition-colors"
                    >
                        <input
                            type="tel"
                            v-model="contact.contact_phone"
                            placeholder="+1 342 123-456"
                            class="flex-1 px-3 py-3 text-sm outline-none"
                        />
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm text-gray-500 mb-2"
                        >Post codee</label
                    >
                    <div
                        class="flex border border-gray-300 rounded-lg overflow-hidden focus-within:border-indigo-500 transition-colors"
                    >
                        <input
                            type="text"
                            v-model="contact.postcode"
                            placeholder="123456"
                            class="flex-1 px-3 py-3 text-sm outline-none"
                        />
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm text-gray-500 mb-2"
                        >Address</label
                    >
                    <textarea
                        rows="4"
                        v-model="contact.line_one"
                        class="w-full px-3 py-3 border border-gray-300 rounded-lg text-sm outline-none resize-y focus:border-indigo-500 transition-colors"
                    ></textarea>
                </div>
                <div class="mb-5">
                    <label class="block text-sm text-gray-500 mb-2">City</label>
                    <select
                        v-model="contact.city"
                        class="px-3 py-3 text-sm outline-none cursor-pointer text-gray-500 bg-white border-r border-gray-300"
                    >
                        <option value="">Select city</option>
                        <option
                            v-for="city in cart.data.meta.cities"
                            :value="city.name"
                        >
                            {{ city.name }}
                        </option>
                    </select>
                </div>
                <button
                    @click="setAddress"
                    type="button"
                    class="w-full my-3 bg-indigo-500 hover:bg-indigo-600/90 active:scale-95 transition py-2.5 rounded text-white"
                >
                    Save
                </button>
            </form>
        </Modal>
    </div>
</template>
