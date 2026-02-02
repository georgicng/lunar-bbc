<script setup>
import { ref, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import {
    useField,
    configure,
    defineRule,
    Field,
    Form,
    FieldArray,
    ErrorMessage,
} from "vee-validate";
import Tags from "@/components/Tags.vue";
import { useDynamicPricing } from "../composables/useDynamicPricing";
import "vue-multiselect/dist/vue-multiselect.css";

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const { getMarkup, getDynamicMarkup } = useDynamicPricing(
    props.product.rules,
    props.product.customisations,
);
const model = ref({});
const quantity = ref(1);

const increment = computed(() => {
    if (props.product.dynamic_pricing) {
        return getDynamicMarkup(model.value);
    }
    return getMarkup(model.value);
});

const getRules = ({ id, required, attribute, min, max }) => {
    const multi = ["checkbox", "multiselect"].includes(attribute.type);
    return [
        ...(multi && min != "" && max != "" ? ["minmax"] : ["min", "max"]),
        "required",
    ]
        .map((key) => {
            switch (key) {
                case "required":
                    return required == "1" ? "required" : "";
                case "min":
                    return min != "" ? `min:${min}` : "";
                case "max":
                    return max != "" ? `max:${max}` : "";
                case "minmax":
                    return `minMax:${min},${max}`;
            }
        })
        .filter((item) => !!item)
        .join("|");
};

const form = computed(() => ({
    quantity: quantity.value,
    id: props.product.id,
    meta: { customisations: model.value },
}));

const submit = () => {
    router.post(`/product/${props.product.id}/add-to-cart`, form.value);
};
</script>

<template>
    <div class="max-w-6xl w-full px-6">
        <p>
            <span>Home</span> / <span> Products</span> / <span> Sports</span> /
            <span class="text-indigo-500"> Casual Shoes</span>
        </p>

        <div class="flex flex-col md:flex-row gap-16 mt-4">
            <div class="flex gap-3">
                <div class="flex flex-col gap-3">
                    <div
                        v-for="image in product.images"
                        class="border max-w-24 border-gray-500/30 rounded overflow-hidden cursor-pointer"
                    >
                        <img :src="image.small" alt="Thumbnail 1" />
                    </div>
                </div>

                <div
                    class="border border-gray-500/30 max-w-100 rounded overflow-hidden"
                >
                    <img
                        :src="product.images[0]?.large"
                        alt="Selected product"
                    />
                </div>
            </div>

            <div class="text-sm w-full md:w-1/2">
                <h1 class="text-3xl font-medium">{{ product.data.name.en }}</h1>

                <div class="flex items-center gap-0.5 mt-1">
                    <svg
                        width="14"
                        height="13"
                        viewBox="0 0 18 17"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M8.049.927c.3-.921 1.603-.921 1.902 0l1.294 3.983a1 1 0 0 0 .951.69h4.188c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 0 0-.364 1.118l1.295 3.983c.299.921-.756 1.688-1.54 1.118L9.589 13.63a1 1 0 0 0-1.176 0l-3.389 2.46c-.783.57-1.838-.197-1.539-1.118L4.78 10.99a1 1 0 0 0-.363-1.118L1.028 7.41c-.783-.57-.38-1.81.588-1.81h4.188a1 1 0 0 0 .95-.69z"
                            fill="#615fff"
                        />
                    </svg>
                    <svg
                        width="14"
                        height="13"
                        viewBox="0 0 18 17"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M8.049.927c.3-.921 1.603-.921 1.902 0l1.294 3.983a1 1 0 0 0 .951.69h4.188c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 0 0-.364 1.118l1.295 3.983c.299.921-.756 1.688-1.54 1.118L9.589 13.63a1 1 0 0 0-1.176 0l-3.389 2.46c-.783.57-1.838-.197-1.539-1.118L4.78 10.99a1 1 0 0 0-.363-1.118L1.028 7.41c-.783-.57-.38-1.81.588-1.81h4.188a1 1 0 0 0 .95-.69z"
                            fill="#615fff"
                        />
                    </svg>
                    <svg
                        width="14"
                        height="13"
                        viewBox="0 0 18 17"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M8.049.927c.3-.921 1.603-.921 1.902 0l1.294 3.983a1 1 0 0 0 .951.69h4.188c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 0 0-.364 1.118l1.295 3.983c.299.921-.756 1.688-1.54 1.118L9.589 13.63a1 1 0 0 0-1.176 0l-3.389 2.46c-.783.57-1.838-.197-1.539-1.118L4.78 10.99a1 1 0 0 0-.363-1.118L1.028 7.41c-.783-.57-.38-1.81.588-1.81h4.188a1 1 0 0 0 .95-.69z"
                            fill="#615fff"
                        />
                    </svg>
                    <svg
                        width="14"
                        height="13"
                        viewBox="0 0 18 17"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M8.049.927c.3-.921 1.603-.921 1.902 0l1.294 3.983a1 1 0 0 0 .951.69h4.188c.969 0 1.371 1.24.588 1.81l-3.388 2.46a1 1 0 0 0-.364 1.118l1.295 3.983c.299.921-.756 1.688-1.54 1.118L9.589 13.63a1 1 0 0 0-1.176 0l-3.389 2.46c-.783.57-1.838-.197-1.539-1.118L4.78 10.99a1 1 0 0 0-.363-1.118L1.028 7.41c-.783-.57-.38-1.81.588-1.81h4.188a1 1 0 0 0 .95-.69z"
                            fill="#615fff"
                        />
                    </svg>
                    <svg
                        width="14"
                        height="13"
                        viewBox="0 0 18 17"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M8.04894 0.927049C8.3483 0.00573802 9.6517 0.00574017 9.95106 0.927051L11.2451 4.90983C11.379 5.32185 11.763 5.60081 12.1962 5.60081H16.3839C17.3527 5.60081 17.7554 6.84043 16.9717 7.40983L13.5838 9.87132C13.2333 10.126 13.0866 10.5773 13.2205 10.9894L14.5146 14.9721C14.8139 15.8934 13.7595 16.6596 12.9757 16.0902L9.58778 13.6287C9.2373 13.374 8.7627 13.374 8.41221 13.6287L5.02426 16.0902C4.24054 16.6596 3.18607 15.8934 3.48542 14.9721L4.7795 10.9894C4.91338 10.5773 4.76672 10.126 4.41623 9.87132L1.02827 7.40983C0.244561 6.84043 0.647338 5.60081 1.61606 5.60081H5.8038C6.23703 5.60081 6.62099 5.32185 6.75486 4.90983L8.04894 0.927049Z"
                            fill="#615fff"
                            fill-opacity="0.35"
                        />
                    </svg>
                    <p class="text-base ml-2">(4)</p>
                </div>

                <div class="mt-6">
                    <p class="text-gray-500/70 line-through">
                        NGN: {{ product.price }}
                    </p>
                    <p class="text-2xl font-medium">
                        NGN: {{ parseFloat(product.price) + parseFloat(increment) }}
                    </p>
                    <span class="text-gray-500/70"
                        >(inclusive of all taxes)</span
                    >
                </div>

                <Form>
                    <div
                        v-for="option in product.customisations"
                        class="pt-[20px]"
                    >
                        <h3
                            v-if="
                                [
                                    'Lunar\\FieldTypes\\Text',
                                    'textarea',
                                ].includes(option.attribute.type)
                            "
                            class="mb-[15px] text-[20px] max-sm:text-[16px]"
                            v-text="option.attribute.name.en"
                        ></h3>

                        <!-- Dropdown Options -->
                        <Tags
                            v-if="
                                'Lunar\\FieldTypes\\Dropdown' ==
                                option.attribute.type
                            "
                            :label="option.attribute.name.en"
                            :rules="getRules(option)"
                            :name="option.attribute.handle"
                            v-model="model[option.attribute_id]"
                            :options="
                                option.attribute_data.map((item) => ({
                                    id: item.id,
                                    name: item.name,
                                }))
                            "
                        />
                        <Tags
                            v-if="'multiselect' == option.attribute.type"
                            :label="option.attribute.name.en"
                            :rules="getRules(option)"
                            :name="option.attribute.handle"
                            v-model="model[option.attribute_id]"
                            :multiple="true"
                            :options="
                                option.attribute_data.map((item) => ({
                                    id: item.id,
                                    name: item.name,
                                }))
                            "
                        />
                        <Field
                            v-if="
                                option.attribute.type ==
                                'Lunar\\FieldTypes\\Text'
                            "
                            type="text"
                            :name="option.attribute.handle"
                            v-model="model[option.attribute_id]"
                            :rules="getRules(option)"
                            class="w-full h-[50px] py-[5px] px-[20px] outline-[0] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] twxt-[#777] text-[14px]"
                        />

                        <Field
                            v-if="option.type == 'textarea'"
                            type="textarea"
                            :name="option.attribute.handle"
                            v-model="model[option.attribute_id]"
                            :rules="getRules(option)"
                            class="w-full h-[150px] mb-[15px] p-[20px] bg-transparent text-[14px] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] text-[#777] outline-[0]"
                        />
                    </div>
                    <div class="pt-[20px]">
                        <h3 class="mb-[15px] text-[20px] max-sm:text-[16px]">
                            Qty
                        </h3>
                        <Field
                            type="number"
                            name="quantity"
                            v-model="quantity"
                            rules="required|min:1"
                            class="w-full h-[50px] py-[5px] px-[20px] outline-[0] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] twxt-[#777] text-[14px]"
                        />
                    </div>

                    <div class="flex items-center mt-10 gap-4 text-base">
                        <button
                            class="w-full py-3.5 font-medium bg-gray-100 text-gray-800/80 hover:bg-gray-200 transition cursor-pointer"
                            type="button"
                            @click="submit()"
                        >
                            Add to Cart
                        </button>
                        <button
                            class="w-full py-3.5 font-medium bg-indigo-500 text-white hover:bg-indigo-600 transition cursor-pointer"
                            type="button"
                        >
                            Buy now
                        </button>
                    </div>
                </Form>
            </div>
        </div>

        <div class="mt-8">
            <div
                class="flex space-x-2 bg-white p-1 border border-gray-500/50 rounded-md text-sm"
            >
                <div class="flex items-center">
                    <input
                        type="radio"
                        name="options"
                        id="html"
                        class="hidden peer"
                        checked
                    />
                    <label
                        for="html"
                        class="cursor-pointer rounded py-2 px-8 text-gray-500 transition-colors duration-200 peer-checked:bg-indigo-600 peer-checked:text-white"
                        >INFORMATION</label
                    >
                </div>
            </div>
            <div>
                <div v-html="product.data.description.en"></div>
            </div>
        </div>
    </div>
</template>
