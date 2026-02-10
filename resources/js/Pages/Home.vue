<script setup>
import { ref, computed, onMounted } from "vue";
import Blocks from "../components/Blocks.vue";

const props = defineProps({
    data: {
        type: Object,
        default: () => ({}),
    },
});

//console.log(props.data);
</script>

<template>
    <main>
        <Blocks :blocks="data.blocks" />
    </main>
</template>
