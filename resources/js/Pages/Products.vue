<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
});
console.log(props)
</script>

<template>
    <div id="menu-page" class="page-wrapper innerpage-section-padding">
        <h1
            class="text-3xl font-medium text-slate-800 text-center mb-2 font-poppins"
        >
            New Arrivals
        </h1>
        <p class="text-slate-600 mb-10 font-poppins text-center">
            Explore the latest additions to our collection.
        </p>
        <section class="flex flex-wrap items-center justify-center gap-6">
            <Link
                v-for="product in products.data"
                :key="product.id"
                :href="`products/${product.id}`"
                class="group w-56"
            >
                <img
                    class="rounded-lg w-full group-hover:shadow-xl hover:-translate-y-0.5 duration-300 transition-all h-72 object-cover object-top"
                    :src="product.images[0]?.url ?? '//placehold.co/400'"
                    :alt="product.images[0]?.name ?? product.data.name.en + 'image'"
                />
                <p class="text-sm mt-2">{{ product.data.name.en }}</p>
                <p class="text-xl">$ {{ product.price }}</p>
            </Link>
        </section>
    </div>
</template>
