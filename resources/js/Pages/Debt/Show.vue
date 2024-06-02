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

// Reactive variable to control form visibility
const showForm = ref(false);

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

// Function to create a new payment
const createPayment = () => {
  form.balance = balance;
  console.log(form);
  form.post(route('payment.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
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
    <div class="p-2 block sm:hidden">
      <div class="mt-2 flex justify-center">
        <div class="w-full max-w-6xl p-3 bg-white border border-gray-200 rounded-lg shadow dark:hover:bg-gray-700 hover:bg-gray-300 dark:bg-gray-800 dark:border-gray-700 flex justify-between items-center">
          <div class="min-w-0">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">$ {{debt.debt_amount}}</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400 truncate">{{debt.debt_supplier}}</p>
            <span v-if="debt.remaining_balance === 0" class="text-green-700 dark:text-green-400">Paid</span>
            <p v-else class="font-normal text-gray-700 dark:text-gray-400">Balance <span><DollarValue :amount="debt.remaining_balance" class="font-normal text-red-700 dark:text-red-400" /></span></p>
          </div>
          <div>
            <!-- Button to toggle the form visibility -->
            <button @click="showForm = !showForm" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Payment</button>
          </div>
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
      <div class="mt-2">
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

    <!-- Display the debt information in a table for larger screens -->
    <div class="p-2 hidden sm:block">
      <div class="relative overflow-x-auto mx-auto max-w-6xl rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">Name</th>
              <th scope="col" class="px-6 py-3">Amount</th>
              <th scope="col" class="px-6 py-3">Remaining Balance</th>
              <th scope="col" class="px-6 py-3">Supplier</th>
              <th scope="col" class="px-6 py-3">Start Date</th>
              <th scope="col" class="px-6 py-3">Expected End Date</th>
            </tr>
          </thead>
          <tbody>
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
              <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ debt.debt_name }}</th>
              <DollarValue :amount="debt.debt_amount" />
              <DollarValue :amount="debt.remaining_balance" />
              <td class="px-6 py-4">{{ debt.debt_supplier }}</td>
              <td class="px-6 py-4">{{ debt.debt_start_date }}</td>
              <td class="px-6 py-4">{{ debt.expected_end_date }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Display the payments information in a table for larger screens -->
    <div class="p-2 hidden sm:block">
      <div class="relative overflow-x-auto mx-auto max-w-6xl rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">Amount</th>
              <th scope="col" class="px-6 py-3">Type</th>
              <th scope="col" class="px-6 py-3">Date</th>
              <th scope="col" class="px-6 py-3">Balance</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="payment in debt.payments" :key="payment.id" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
              <DollarValue :amount="payment.payment_amount" />
              <td class="px-6 py-4">{{ payment.type }}</td>
              <DateDisplay :date="payment.paid_at" />
              <DollarValue :amount="payment.balance" />
            </tr>

            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
              <td>
                <div class="relative">
                  <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                  <input
                    id="payment_amount"
                    v-model.number="form.payment_amount"
                    type="number"
                    class="block w-full pl-10 pr-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    autocomplete="payment-amount"
                  />
                  <InputError :message="form.errors.payment_amount" class="mt-2" />
                </div>
              </td>
              <td>
                <TextInput
                  id="type"
                  v-model="form.type"
                  type="text"
                  class="block w-full"
                  autocomplete="type"
                />
                <InputError :message="form.errors.type" class="mt-2" />
              </td>
              <td>
                <TextInput
                  id="paid_at"
                  v-model="form.paid_at"
                  type="date"
                  class="block w-full"
                  autocomplete="paid-at"
                />
                <InputError :message="form.errors.paid_at" class="mt-2" />
              </td>
              <td>
                <TextInput
                  id="balance"
                  v-model.number="form.balance" :value="balance"
                  type="number"
                  class="block w-full"
                  autocomplete="balance"
                />
                <InputError :message="form.errors.balance" class="mt-2" />
              </td>
            </tr>
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
              <td colspan="4" class="px-6 py-4 text-right">
                <!-- Button to submit the form in the table -->
                <PrimaryButton @click="createPayment" type="button">Add Payment</PrimaryButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
