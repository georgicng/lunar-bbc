<script setup>
import { ref, computed, onMounted } from "vue";
import Blocks from "../components/Blocks.vue";

const props = defineProps({
    blocks: {
        type: Object,
        default: () => [],
    },
});
</script>

<template>
    <main>
        <Blocks :blocks="blocks" />
    </main>
</template>
