<script setup>
import { computed } from "vue";
import NewArrivals from "./blocks/NewArrivals.vue";

const props = defineProps({
  blocks: {
    type: Array,
    default: () => [],
  },
});

const sortedBlocks = computed(() => {
  if (!props.blocks) {
    return [];
  }
  return [...props.blocks].sort((a, b) => a.sortOrder - b.sortOrder);
});

const componentMap = {
  newArrivals: NewArrivals,
};
</script>


<template>
  <component
    v-for="block in sortedBlocks"
    :key="block.id"
    :is="componentMap[block.handle]"
    :block="block"
  />
</template>

