<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import DollarValue from '@/Components/DollarValue.vue'; // Adjust the path as necessary
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, computed } from 'vue';
import PieChart from '@/Components/PieChart.vue';
import { usePercentageComplete } from '@/Composables/usePercentageComplete';
import ProgressBar from '@/Components/ProgressBar.vue'; // Corrected import statement

const props = defineProps({
  debts: {
    type: Array,
    default: () => []
  }
});

const searchQuery = ref('');

// Reactive state for pagination
const currentPage = ref(1);
const itemsPerPage = ref(5);

const paidDebts = computed(() => {
  return props.debts.filter(debt => debt.remaining_balance > 0);
});

const totalDebt = computed(() => {
  return paidDebts.value.reduce((total, debt) => total + debt.remaining_balance, 0);
});

const percentageComplete = (debt) => {
  return usePercentageComplete(debt.debt_amount, debt.remaining_balance);
};

// Computed property to filter debts based on search query
const filteredDebts = computed(() => {
  return props.debts.filter(debt => {
    return debt.debt_name.toLowerCase().includes(searchQuery.value.toLowerCase());
  });
});

// Computed property for paginated debts
const paginatedDebts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredDebts.value.slice(start, end);
});

// Computed property for total pages
const totalPages = computed(() => {
  return Math.ceil(filteredDebts.value.length / itemsPerPage.value);
});

// Methods for pagination
const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Debts</h2>
        <Link :href="route('debts.create')">
          <PrimaryButton>Add New Debt</PrimaryButton>
        </Link>
      </div>
    </template>
    <div v-if="paidDebts.length > 0" class="mt-12 max-w-6xl mx-auto hidden sm:block">
      <pie-chart :debts="paidDebts" />
      <div class="m-4 text-center bg-cyan-500 rounded-lg py-4 shadow-lg shadow-cyan-500/50">
        <h2 class="text-2xl font-extrabold text-gray-200 dark:text-white">TOTAL DEBT: ${{ totalDebt }}</h2>
      </div>
    </div>   

    <div class="p-2">
      <div class="relative mx-auto max-w-6xl space-x-2">
        <div class="mt-4 flex items-center space-x-4 mx-auto max-w-7xl">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Search by name" 
            class="block w-full rounded-full border border-gray-300 px-4 py-2 placeholder:text-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
          />
        </div>
      </div>

      <div class="mt-2 relative overflow-x-auto mx-auto max-w-6xl rounded-lg hidden sm:block">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-200 bg-gray-900 dark:bg-gray-900 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">Name</th>
              
              <th scope="col" class="px-6 py-3">Remaining Balance</th>
              <th scope="col" class="px-6 py-3">Supplier</th>
              <th scope="col" class="px-6 py-3">Start Date</th>
              <th scope="col" class="px-6 py-3">Expected End Date</th>
            
            </tr>
          </thead>
          <tbody>
            <tr v-for="debt in paginatedDebts" :key="debt.id" class="bg-white dark:bg-gray-900 border-b dark:border-gray-700">
              <th scope="row" class="px-6 py-4 font-medium text-blue-600 dark:text-blue-500 whitespace-nowrap hover:text-blue-900 hover:underline">
                <Link :href="route('debts.show', debt.id)">{{ debt.debt_name }}</Link>
              </th>
              
              <td class="px-6 py-2">
               
                <span v-if="Number(debt.remaining_balance) === 0" class="text-white dark:text-green-400 bg-green-700 rounded-lg px-4 py-1">Paid</span>
                <div v-else>
                  <ProgressBar :percentage="percentageComplete(debt)" />
                  <p>${{ debt.remaining_balance }} remaining out of ${{ debt.debt_amount}}</p>
                </div>
                
              </td>
              <td class="px-6 py-4">{{ debt.debt_supplier }}</td>
              <td class="px-6 py-4">{{ debt.debt_start_date }}</td>
              <td class="px-6 py-4">{{ debt.expected_end_date }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="p-2 block sm:hidden">
      <div v-for="debt in paginatedDebts" :key="debt.id" class="mt-2 flex justify-center">
        <div class="w-full max-w-6xl p-3 bg-white border border-gray-200 rounded-lg shadow dark:hover:bg-gray-700 hover:bg-gray-300 dark:bg-gray-800 dark:border-gray-700 flex justify-between items-center">
          <div class="min-w-0">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">$ {{ debt.debt_amount }}</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400 truncate">{{ debt.debt_supplier }}</p>
            <span v-if="Number(debt.remaining_balance) === 0" class="text-white p-2 dark:text-green-400 bg-green-700 rounded-lg px-2 py-1">Paid</span>
            <div v-else>
              <ProgressBar :percentage="percentageComplete(debt)" />
              <p>${{ debt.remaining_balance }} remaining out of ${{ debt.debt_amount}}</p>
            </div>
          </div>
          <Link 
            :href="route('debts.show', debt.id)" 
            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Add Transaction
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
          </Link>
        </div>
      </div>
    </div>

    <div class="flex justify-between items-center p-4 mx-auto max-w-7xl">
      <button @click="prevPage" :disabled="currentPage === 1" class="relative inline-flex items-center border px-4 py-2 text-sm font-medium focus:z-20 border-gray-300 bg-white text-gray-500 rounded-l-md disabled:opacity-50">
        Previous
      </button>
      <span class="text-sm text-gray-700 m-2">Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages" class="relative inline-flex items-center border px-4 py-2 text-sm font-medium focus:z-20 border-gray-300 bg-white text-gray-500 hover:bg-gray-50 rounded-r-md">
        Next
      </button>
    </div>
  </AuthenticatedLayout>
</template>
