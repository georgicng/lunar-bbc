<script setup>
const props = defineProps({
    block: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="mt-10">
        <h1
            class="text-3xl font-medium text-slate-800 text-center mb-2 font-poppins"
        >
            {{ block.title }}
        </h1>
        <p class="text-slate-600 mb-10 font-poppins text-center">
            {{ block.subtitle }}
        </p>
        <section class="flex flex-wrap items-center justify-center gap-6">
            <a v-for="product in block.products" href="#" class="group w-56">
                <img
                    class="rounded-lg w-full group-hover:shadow-xl hover:-translate-y-0.5 duration-300 transition-all h-72 object-cover object-top"
                    :src="product.image"
                    alt="image"
                />
                <p class="text-sm mt-2">{{ product.name }}</p>
                <p class="text-xl">$ {{ product.price }}</p>
            </a>
        </section>
    </div>
</template>
