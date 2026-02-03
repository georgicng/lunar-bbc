<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    categories: {
        type: Object,
        required: true,
    },
});
console.log(props);
</script>

<template>
    <div class="flex flex-wrap items-center justify-center gap-6">
        <div
            v-for="category in categories.data"
            :key="category.id"
            class="flex flex-wrap items-center justify-center gap-8 pt-12"
        >
            <div
                class="max-w-72 w-full hover:-translate-y-0.5 transition duration-300"
            >
                <img
                    class="rounded-xl"
                    :src="category[0]?.image?.url ?? '//placehold.co/400'"
                    :alt="category[0]?.image?.name ?? 'placeholder'"
                />
                <h3 class="text-base text-slate-900 font-medium mt-3">
                    {{ category.data.name.en }}
                </h3>
                <Link
                    :href="`categories/${category.id}`"
                    class="text-xs text-indigo-600 font-medium mt-1"
                >
                    View Products
                </Link>
            </div>
        </div>
    </div>
</template>
