<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Paychecks
        </h2>
        <Link href="/manage-budget-items">
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8 p-4 rounded-lg">
          <div class="col-span-1 space-y-4 my-2">
            <InputLabel for="date" value="Date" />
            <TextInput
              id="date"
              type="date"
              v-model="form.paycheck.date"
              class="w-full"
            />
            <h3 class="text-lg font-semibold mb-4">Income</h3>
            <div
              v-for="(income, index) in incomes"
              :key="index"
              class="mb-2 flex items-center space-x-1"
            >
              <TextInput
                type="text"
                class="mt-1 block w-3/4"
                v-model="income.name"
              />
              <DollarInput v-model.number="income.amount" />
              <div
                @click="removeIncome(index)"
                class="hover:text-white hover:bg-red-500 py-2 px-2 mt-1 rounded-md"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                >
                  <path
                    fill="currentColor"
                    d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6zM19 4h-3.5l-1-1h-5l-1 1H5v2h14z"
                  />
                </svg>
              </div>
            </div>

            <PrimaryButton type="button" @click="addIncome"
              >Add Income</PrimaryButton
            >
          </div>
          <div class="col-span-1 space-y-4 my-2">
            <h3
              class="text-lg font-semibold mb-4 bg-gray-900 text-white rounded-lg p-2"
            >
              Budget Items
            </h3>

            <div
              v-for="(item, index) in expenses"
              :key="index"
              class="flex justify-between space-x-1 px-2"
            >
              <h3 class="py-2 text-md font-base">{{ item.name }}</h3>
              <DollarInput v-model.number="item.amount" />
            </div>

            <div class="my-2 flex justify-between">
              <PrimaryButton type="submit">Save</PrimaryButton>
            </div>
          </div>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
  
  <script setup>
import { ref, computed, watchEffect } from "vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextInput from "@/Components/TextInput.vue";
import DollarInput from "@/Components/DollarInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import DeleteIcon from "@/Components/InputLabel.vue";

const props = defineProps({
  paycheck: Object,
  paycheckItems: Array,
});

const incomes = ref([]);
const expenses = ref([]);

watchEffect(() => {
  // Initialize incomes and expenses from paycheckItems prop
  incomes.value = props.paycheckItems
    .filter((item) => item.item_type === "income")
    .map((item) => ({ name: item.item_name, amount: item.item_amount }));

  expenses.value = props.paycheckItems
    .filter((item) => item.item_type === "expense")
    .map((item) => ({ name: item.item_name, amount: item.item_amount }));

  // Ensure all budget items are initialized
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

  budgetItems.forEach((item) => {
    if (!expenses.value.find((exp) => exp.name === item)) {
      expenses.value.push({ name: item, amount: 0 });
    }
  });
});

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
    date: props.paycheck.date,
    amount: props.paycheck.amount,
    expense: props.paycheck.expense,
    balance: props.paycheck.balance,
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
  form.put(route("paychecks.update", { id: props.paycheck.id }));
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
  