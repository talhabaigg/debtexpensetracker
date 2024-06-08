<script setup>
// Import necessary components and modules
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DollarValue from '@/Components/DollarValue.vue'; // Adjust the path as necessary
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { computed } from 'vue';
import DateDisplay from '@/Components/DateDisplay.vue';
import { ref } from 'vue';
import ProgressBar from '@/Components/ProgressBar.vue'; 

import { usePercentageComplete } from '@/Composables/usePercentageComplete';
// Reactive variable to control form visibility
const showForm = ref(false);
const isModalVisible = ref(false);

// Define the props the component will receive
const props = defineProps({ 
  debt: {
    type: Object,
    required: true,
  },
  payments: {
    type: Array,
    required: true,
  }
});

// Initialize the form with useForm
const form = useForm({
  debt_id: props.debt.id,
  payment_amount: 0,
  type: '',
  paid_at: '',
  balance: 0,
});

// Computed property to calculate the balance
const balance = computed(() => {
  const debtAmount = parseFloat(props.debt.remaining_balance);
  const paymentAmount = parseFloat(form.payment_amount);
  return (debtAmount - paymentAmount).toFixed(2);
});
const percentageComplete = (debt) => {
  return usePercentageComplete(debt.debt_amount, debt.remaining_balance);
};
// Function to create a new payment
const createPayment = () => {
  form.balance = balance;
  console.log(form);
  form.post(route('payment.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
};
const toggleModal = () => {
  isModalVisible.value = !isModalVisible.value;
};
const submitFormandCloseModal = () => {
  createPayment();
  toggleModal();
};
</script>

<template>
  <!-- Set the page title -->
  <Head title="Dashboard" />

  <!-- Use the authenticated layout -->
  <AuthenticatedLayout>
    <!-- Define the header slot for the layout -->
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Debts</h2>
        <Link :href="route('debts.create')">
          <PrimaryButton>Add New Debt</PrimaryButton>
        </Link>
      </div>
    </template>
    
    <!-- Display the debt information -->
    <div class="p-2 block">
      <div class="mt-2 flex justify-center">
        <div class="w-full max-w-6xl p-3 bg-white border border-gray-200 rounded-lg shadow dark:hover:bg-gray-700 hover:bg-gray-300 dark:bg-gray-800 dark:border-gray-700 flex justify-between items-center">
          <div class="min-w-0">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">$ {{debt.debt_amount}}</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400 truncate">{{debt.debt_supplier}}</p>
            <ProgressBar :percentage="percentageComplete(debt)" />
            <p>${{ debt.remaining_balance }} remaining out of ${{ debt.debt_amount}}</p>
            
          </div>
          <div class="block sm:hidden">
            <!-- Button to toggle the form visibility -->
            <button @click="showForm = !showForm" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Payment</button>
           
          </div>
          <div class="hidden sm:block">
            <button @click="toggleModal" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Payment</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Main modal -->
    <div v-if="isModalVisible" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900 bg-opacity-50">
      <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
          <!-- Modal header -->
          <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              Add Payment
            </h3>
            <button @click="toggleModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <!-- Modal body -->
          <form @submit.prevent="submitForm" class="p-4 md:p-5">
            <div class="grid gap-4 mb-4 grid-cols-2">
              <div class="col-span-2">
                <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                <input type="number" id="amount" v-model.number="form.payment_amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
              </div>
              <div class="col-span-2 sm:col-span-1">
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                <input type="date" id="date" v-model="form.paid_at"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="$2999" required="">
              </div>
              <div class="col-span-2 sm:col-span-1">
                <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
                <select v-model="form.type" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                  <option selected="">Select category</option>
                  <option value="Repayment">Repayment</option>
                  <option value="Interest">Interest</option>
                  <option value="Redraw">Redraw</option>
                </select>
              </div>
              <!-- <div class="col-span-2">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Description</label>
                <input type="number" id="balance" v-model.number="form.balance" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write product description here"/>                    
              </div> -->
            </div>
            <button @click="submitFormandCloseModal" type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
              Add transaction
            </button>
          </form>
        </div>
      </div>
    </div>
    <!-- Display the form to add a new payment -->
    <div class="p-2 block sm:hidden">
      <transition name="fade" enter-active-class="transition-opacity duration-300" leave-active-class="transition-opacity duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-from-class="opacity-100" leave-to-class="opacity-0">

      
      <form v-if="showForm" @submit.prevent="submitForm" class="mt-4 space-y-2">
        <div class="flex flex-col">
          <label for="payment_amount" class="mb-1 text-gray-700">Amount</label>
          <input type="number" id="amount" v-model.number="form.payment_amount" class="form-input border border-gray-300 rounded p-2" />
        </div>
        <div class="flex flex-col">
          <label for="type" class="mb-1 text-gray-700">Payment Type</label>
          <select name="" id="type" v-model="form.type" class="form-input border border-gray-300 rounded p-2">
            <option value="repayment">Repayment</option>
          </select>
        </div>
        <div class="flex flex-col">
          <label for="date" class="mb-1 text-gray-700">Date</label>
          <input type="date" id="date" v-model="form.paid_at" class="form-input border border-gray-300 rounded p-2" />
        </div>
        <div class="flex flex-col">
          <label for="balance" class="mb-1 text-gray-700">Balance</label>
          <input type="number" id="balance" v-model.number="form.balance" class="form-input border border-gray-300 rounded p-2" />
        </div>
        <!-- Button to submit the form -->
        <PrimaryButton @click="createPayment" type="button">Submit</PrimaryButton>
      </form>
    </transition>
      
      <!-- Display the list of payments -->
      <div class="mt-2 block sm:hidden">
        <div v-for="payment in debt.payments" :key="payment.id" class="w-full max-w-6xl p-3 bg-white border border-gray-200 rounded-lg shadow dark:hover:bg-gray-700 hover:bg-gray-300 dark:bg-gray-800 dark:border-gray-700 flex justify-between items-center mb-2">
          <div class="min-w-0">
            <h5 class="mb-1 text-l font-bold tracking-tight text-gray-900 dark:text-white">$ {{payment.payment_amount}}</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400 truncate">{{payment.type}}</p>
            <p class="font-normal text-gray-700 dark:text-gray-400 truncate"><DateDisplay :date="payment.paid_at" /></p>
            <span class="text-green-700 dark:text-green-400">Paid</span>
          </div>
        </div>
      </div>
    </div>

    

    <!-- Display the payments information in a table for larger screens -->
    <div class="p-2 hidden sm:block">
      <div class="relative overflow-x-auto mx-auto max-w-6xl rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-200 bg-gray-900 dark:bg-gray-900 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">Amount</th>
              <th scope="col" class="px-6 py-3">Type</th>
              <th scope="col" class="px-6 py-3">Date</th>
              <th scope="col" class="px-6 py-3">Balance</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="payment in debt.payments" :key="payment.id" class="bg-white dark:bg-gray-900 border-b dark:border-gray-700">
              <td v-if="payment.payment_amount < 0" class="text-red-700 dark:text-green-400"><DollarValue :amount="payment.payment_amount" /></td>
              <td v-else ><DollarValue :amount="payment.payment_amount" /></td>
              <td class="px-6 py-4"> 
                <span v-if=" payment.type === 'Repayment'" class=" text-white dark:text-green-400 bg-green-700 rounded-lg  px-2 py-1">{{ payment.type }}</span>
                <span v-else  class=" text-white  bg-red-700 rounded-lg  px-2 py-1">{{ payment.type }}</span>
              </td>
              <td><DateDisplay :date="payment.paid_at" /></td>
              <DollarValue :amount="payment.balance" />
            </tr>

            
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
