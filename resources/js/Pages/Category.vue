<script setup>
import { Link } from "@inertiajs/vue3";
defineProps({
    categories: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        v-for="category in categories"
        :key="category.id"
        class="flex flex-wrap items-center justify-center gap-8 pt-12"
    >
        <div
            class="max-w-72 w-full hover:-translate-y-0.5 transition duration-300"
        >
            <img class="rounded-xl" :src="category.media.link" alt="" />
            <h3 class="text-base text-slate-900 font-medium mt-3">
                {{ category.attribute_data.name.en }}
            </h3>
            <Link
                :href="`products/${category.id}`"
                class="text-xs text-indigo-600 font-medium mt-1"
            >
                UI/UX design
            </Link>
        </div>
    </div>
</template>
