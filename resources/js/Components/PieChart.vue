<!-- resources/js/Components/PieChart.vue -->
<template>
    <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
      <Pie :data="chartData" :options="chartOptions" />
      
    </div>
  </template>
  
  <script>
  import { Pie } from 'vue-chartjs';
  import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';
  import { defineComponent } from 'vue';
  
  ChartJS.register(Title, Tooltip, Legend, ArcElement);
  
  export default defineComponent({
    name: 'PieChart',
    components: {
      Pie,
    },
    props: {
      debts: {
        type: Array,
        required: true,
      },
    },
    computed: {
      chartData() {
        return {
          labels: this.debts.map(debt => debt.debt_supplier),
          datasets: [
            {
              backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
              data: this.debts.map(debt => debt.debt_amount),
            },
          ],
        };
      },
      chartOptions() {
        return {
          responsive: true,
          maintainAspectRatio: false,
        };
      },
    },
  });
  </script>
  
  <style scoped>
  /* TailwindCSS classes for styling are applied directly in the template */
  </style>
  