<script setup>
import { computed } from "vue";
import NewArrivals from "./blocks/NewArrivals.vue";
import InfoBox from "./blocks/InfoBox.vue";

const props = defineProps({
  blocks: {
    type: Object,
    default: () => ({}),
  },
});



const componentMap = {
  newArrivals: NewArrivals,
  features: InfoBox,
};
</script>


<template>
  <component
    v-for="block in blocks"
    :key="block.type"
    :is="componentMap[block.type]"
    :block="block.data"
  />
</template>

