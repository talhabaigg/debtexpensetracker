<template>
    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
      <div :class="barClass" class="h-2.5 rounded-full" :style="{ width: percentage + '%' }"></div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ProgressBar',
    props: {
      percentage: {
        type: Number,
        required: true
      }
    },
    computed: {
      barClass() {
        return this.percentage >= 50 ? 'bg-green-600' : 'bg-blue-600';
      }
    }
  };
  </script>
  
  <style scoped>
  /* Add any component-specific styles here */
  </style>
  