<script setup>
defineProps({
  title: String,
  show: Boolean
});
</script>

<template>

<!-- The Modal Overlay and Content -->
<div
  v-if="show"
  class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
>
  <div
    class="bg-white p-8 rounded-lg shadow-xl max-w-sm w-full m-4"
  >
    <!-- Modal Header -->
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">{{  title }}</h2>
      <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
        &times;
      </button>
    </div>

    <!-- Modal Body (Form) -->
    <slot></slot>
  </div>
</div>
</template>
