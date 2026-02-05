<script setup>
const props = defineProps({
    block: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mt-10">
        <div class="size-[520px] top-0 left-1/2 -translate-x-1/2 rounded-full absolute blur-[300px] -z-10 bg-[#FBFFE1]/70"></div>
        <div v-for="feature in block" class="flex flex-col items-center justify-center max-w-80">
            <div class="p-6 aspect-square bg-violet-100 rounded-full">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 18.667V24.5m4.668-8.167V24.5m4.664-12.833V24.5m2.333-21L15.578 13.587a.584.584 0 0 1-.826 0l-3.84-3.84a.583.583 0 0 0-.825 0L2.332 17.5M4.668 21v3.5m4.664-8.167V24.5" stroke="#7F22FE" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="mt-5 space-y-2 text-center">
                <h3 class="text-base font-semibold text-slate-700">{{  feature.title }}</h3>
                <p class="text-sm text-slate-600">{{  feature.caption }}</p>
            </div>
        </div>
    </div>
</template>
