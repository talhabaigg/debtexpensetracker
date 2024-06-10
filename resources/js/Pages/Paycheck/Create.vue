<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Plan Your Paycheck
      </h2>
    </template>

    <div class="max-w-6xl mx-auto mt-8 p-4 bg-white rounded-lg shadow">
      <h3 class="text-lg font-semibold mb-4">Income</h3>
      <div
        v-for="(income, index) in incomes"
        :key="index"
        class="mb-4 flex items-center space-x-1"
      >
        <TextInput
          type="text"
          class="mt-1 block w-3/4"
          v-model.number="income.name"
        />
        <TextInput
          type="number"
          class="mt-1 block w-1/4"
          v-model.number="income.amount"
        />

        <DangerButton @click="removeIncome(index)">Remove</DangerButton>
      </div>

      <PrimaryButton @click="addIncome">Add Income</PrimaryButton>
      <h3 class="text-lg font-semibold mt-8 mb-4">Expenses</h3>
      <div
        v-for="(expense, index) in expenses"
        :key="index"
        class="mb-4 flex items-center space-x-1"
      >
        <TextInput
          type="text"
          class="mt-1 block w-3/4"
          v-model.number="expense.name"
        />
        <TextInput
          type="number"
          class="mt-1 block w-1/4"
          v-model.number="expense.amount"
        />

        <DangerButton @click="removeExpense(index)">Remove</DangerButton>
      </div>

      <PrimaryButton @click="addExpense">Add Expense</PrimaryButton>
      <div class="mt-8 p-4 bg-gray-100 rounded-lg">
        <p class="text-lg"><strong>Total Income:</strong> ${{ totalIncome }}</p>
        <p class="text-lg">
          <strong>Total Expenses:</strong> ${{ totalExpenses }}
        </p>
        <p class="text-lg"><strong>Savings:</strong> ${{ savings }}</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
  
  <script setup>
import { ref, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextInput from "@/Components/TextInput.vue";

const incomes = ref([{ name: "", amount: 0 }]);
const expenses = ref([{ name: "", amount: 0 }]);

const addIncome = () => {
  incomes.value.push({ name: "", amount: 0 });
};

const removeIncome = (index) => {
  incomes.value.splice(index, 1);
};

const addExpense = () => {
  expenses.value.push({ name: "", amount: 0 });
};

const removeExpense = (index) => {
  expenses.value.splice(index, 1);
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
</script>
  
  <style scoped>
/* Add any necessary styling here */
</style>
  