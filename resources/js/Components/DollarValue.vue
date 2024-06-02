<template>
    <td class="px-6 py-4">{{ formattedAmount }}</td>
  </template>
  
  <script>
  export default {
    props: {
      amount: {
        type: Number,
        required: true
      }
    },
    computed: {
      formattedAmount() {
        return new Intl.NumberFormat('en-AU', { style: 'currency', currency: 'AUD' }).format(this.amount);
      }
    }
  }
  </script>
  
  <style scoped>
  /* Add any specific styles for the component here if needed */
  </style>
  