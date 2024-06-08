<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import DollarValue from '@/Components/DollarValue.vue'; // Adjust the path as necessary
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, computed } from 'vue';
import PieChart from '@/Components/PieChart.vue';
import { usePercentageComplete } from '@/Composables/usePercentageComplete';
import ProgressBar from '@/Components/ProgressBar.vue'; // Corrected import statement
import UnderlineLink from '@/Components/UnderlineLink.vue';
import DateDisplay from '@/Components/DateDisplay.vue';
import { Inertia } from '@inertiajs/inertia';

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

const handleDelete = (debtId) => {
  if (confirm('Are you sure you want to delete this debt?')) {
    Inertia.delete(route('debts.destroy', debtId), {
      onSuccess: () => {
        // Handle success, e.g., remove the debt from the paginatedDebts array
        paginatedDebts.value = paginatedDebts.value.filter(debt => debt.id !== debtId);
      },
      onError: () => {
        // Handle error
        alert('An error occurred while deleting the debt.');
      }
    });
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
      
      <!-- <div class="m-4 text-center bg-cyan-500 rounded-lg py-4 shadow-lg shadow-cyan-500/50">
        <h2 class="text-2xl font-extrabold text-gray-200 dark:text-white">TOTAL DEBT: ${{ totalDebt }}</h2>
      </div> -->
    </div>   

    <div class="p-2">
      
      <div class="relative mx-auto max-w-6xl space-x-2">
        <div class="mt-4 flex items-center space-x-4 mx-auto max-w-7xl">
          <div class="relative w-full">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
              <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
              </svg>
            </div>
            <input 
              v-model="searchQuery" 
              type="text" 
              placeholder="Search by name" 
              class="block w-full rounded-full border border-gray-300 px-4 py-2 pl-10 placeholder:text-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            />
          </div>
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
              <th scope="col" class=""></th>
 
            </tr>
          </thead>
          <tbody>
            <tr v-for="debt in paginatedDebts" :key="debt.id" 
      class="bg-white dark:bg-gray-900 border-b dark:border-gray-700 hover:bg-gray-300 relative group">
    <th scope="row">
      <UnderlineLink :href="route('debts.show', debt.id)">{{ debt.debt_name }}</UnderlineLink> 
    </th>
    <td class="px-6 py-2">
      <span v-if="Number(debt.remaining_balance) === 0" 
            class="text-white dark:text-green-400 bg-green-700 rounded-lg px-4 py-1">
        Paid
      </span>
      <div v-else>
        <ProgressBar :percentage="percentageComplete(debt)" />
        <p>${{ debt.remaining_balance }} remaining out of ${{ debt.debt_amount }}</p>
      </div>
    </td>
    <td class="px-6 py-4">{{ debt.debt_supplier }}</td>
    <td class="px-6 py-4"><DateDisplay :date="debt.debt_start_date" /></td>
    <td class="m-2">
      <form @submit.prevent="handleDelete(debt.id)">
        <button type="submit" class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-red-500 text-white px-2 py-1 rounded hidden group-hover:block z-10">
          <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="16" height="16" viewBox="0,0,256,256">
            <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(8.53333,8.53333)"><path d="M14.98438,2.48633c-0.55152,0.00862 -0.99193,0.46214 -0.98437,1.01367v0.5h-5.5c-0.26757,-0.00363 -0.52543,0.10012 -0.71593,0.28805c-0.1905,0.18793 -0.29774,0.44436 -0.29774,0.71195h-1.48633c-0.36064,-0.0051 -0.69608,0.18438 -0.87789,0.49587c-0.18181,0.3115 -0.18181,0.69676 0,1.00825c0.18181,0.3115 0.51725,0.50097 0.87789,0.49587h18c0.36064,0.0051 0.69608,-0.18438 0.87789,-0.49587c0.18181,-0.3115 0.18181,-0.69676 0,-1.00825c-0.18181,-0.3115 -0.51725,-0.50097 -0.87789,-0.49587h-1.48633c0,-0.26759 -0.10724,-0.52403 -0.29774,-0.71195c-0.1905,-0.18793 -0.44836,-0.29168 -0.71593,-0.28805h-5.5v-0.5c0.0037,-0.2703 -0.10218,-0.53059 -0.29351,-0.72155c-0.19133,-0.19097 -0.45182,-0.29634 -0.72212,-0.29212zM6,9l1.79297,15.23438c0.118,1.007 0.97037,1.76563 1.98438,1.76563h10.44531c1.014,0 1.86538,-0.75862 1.98438,-1.76562l1.79297,-15.23437z"></path></g></g>
            </svg>
        </button>
      </form>
    </td>
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
