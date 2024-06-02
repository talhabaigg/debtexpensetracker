<template>
    <span>{{ formattedDate }}</span>
  </template>
  
  <script setup>
  import { computed, defineProps } from 'vue';
  
  const props = defineProps({
    date: {
      type: String,
      required: true,
    },
  });
  
  const formattedDate = computed(() => {
    const date = new Date(props.date);
    const day = date.getDate();
    const month = date.toLocaleString('default', { month: 'long' });
    const year = date.getFullYear();
  
    let daySuffix = 'th';
    if (day === 1 || day === 21 || day === 31) {
      daySuffix = 'st';
    } else if (day === 2 || day === 22) {
      daySuffix = 'nd';
    } else if (day === 3 || day === 23) {
      daySuffix = 'rd';
    }
  
    return `${day}${daySuffix} ${month}, ${year}`;
  });
  </script>
  