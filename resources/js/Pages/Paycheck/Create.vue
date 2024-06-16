<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Paychecks
        </h2>
        <Link>
          <PrimaryButton>Manage Budget Items</PrimaryButton>
        </Link>
      </div>
    </template>

    <div class="max-w-6xl mx-auto mt-2 p-4 bg-white rounded-lg shadow">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8 p-4 rounded-lg">
        <div class="p-4 bg-white rounded-lg shadow-lg">
          <p class="text-lg">
            <strong>Total Income:</strong> ${{ totalIncome }}
          </p>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-lg">
          <p class="text-lg">
            <strong>Total Expenses:</strong> ${{ totalExpenses }}
          </p>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-lg">
          <p class="text-lg"><strong>Savings:</strong> ${{ savings }}</p>
        </div>
      </div>
      <form @submit.prevent="submitForm">
        <div class="space-y-4 my-2 w-full">
          <InputLabel for="date" value="Date" />
          <TextInput
            id="date"
            type="date"
            class="block w-1/4"
            v-model="form.paycheck.date"
          />
        </div>

        <h3 class="text-lg font-semibold mb-4">Income</h3>
        <div
          v-for="(income, index) in incomes"
          :key="index"
          class="mb-4 flex items-center space-x-1"
        >
          <TextInput
            type="text"
            class="mt-1 block w-3/4"
            v-model="income.name"
          />
          <DollarInput v-model.number="income.amount" />
          <DangerButton @click="removeIncome(index)">Remove</DangerButton>
        </div>

        <PrimaryButton type="button" @click="addIncome"
          >Add Income</PrimaryButton
        >

        <h3 class="text-lg font-semibold mt-8 mb-4">Budget Items</h3>
        <div
          v-for="(item, index) in budgetItems"
          :key="index"
          class="flex justify-between space-x-1 mb-2"
        >
          <h3 class="py-2 text-md font-base">{{ item }}</h3>
          <DollarInput v-model.number="expenses[index].amount" />
        </div>

        <div class="my-2 flex justify-between">
          <PrimaryButton type="submit">Save</PrimaryButton>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextInput from "@/Components/TextInput.vue";
import DollarInput from "@/Components/DollarInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const incomes = ref([{ name: "", amount: 0 }]);

const budgetItems = [
  "Rent",
  "Grocery",
  "Phone Bill",
  "Internet Bill",
  "Electricity Bill",
  "iPad Bill",
  "Youtube Subscription",
  "Balance Correction",
  "Rego",
  "Car Insurance",
  "Date Budget",
  "Fuel",
  "Personal Takeaway",
  "Sana Loan",
  "Zip Money",
  "NAB Personal Loan",
  "Tech Gadgets",
  "Car Fund",
  "Stake Fund",
  "Business Account",
  "GST Witholding",
  "Credit Card Pay",
];

// Initialize expenses with same length as budgetItems, default values
const expenses = ref(budgetItems.map((item) => ({ name: item, amount: 0 })));

const addIncome = () => {
  incomes.value.push({ name: "", amount: 0 });
};

const removeIncome = (index) => {
  incomes.value.splice(index, 1);
};

const totalIncome = computed(() => {
  return incomes.value.reduce((total, income) => total + income.amount, 0);
});

const totalExpenses = computed(() => {
  return expenses.value.reduce((total, expense) => total + expense.amount, 0);
});

const savings = computed(() => {
  return totalIncome.value - totalExpenses.value;
});

// Initialize the form using useForm
const form = useForm({
  paycheck: {
    date: new Date().toISOString().substr(0, 10), // Default to today's date
    amount: 0,
    expense: 0,
    balance: 0,
  },
  paycheckItems: [],
});

const submitForm = () => {
  // Calculate paycheck values
  form.paycheck.amount = totalIncome.value;
  form.paycheck.expense = totalExpenses.value;
  form.paycheck.balance = savings.value;

  // Create paycheck items
  form.paycheckItems = [
    ...incomes.value.map((income) => ({
      item_name: income.name,
      item_amount: income.amount,
      item_type: "income",
    })),
    ...expenses.value.map((expense) => ({
      item_name: expense.name,
      item_amount: expense.amount,
      item_type: "expense",
    })),
  ];

  // Submit the form to the Laravel backend
  form.post(route("paychecks.store"));
};
</script>

<style scoped>
.dropdown {
  border: 1px solid #ccc;
  max-height: 150px;
  overflow-y: auto;
  list-style-type: none;
  padding: 0;
  margin: 0;
  position: absolute;
  background-color: white;
  width: 75%;
}

.dropdown li {
  padding: 10px;
  cursor: pointer;
}

.dropdown li.highlighted {
  background-color: #ddd;
}
</style>
