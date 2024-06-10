<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import DollarValue from "@/Components/DollarValue.vue"; // Adjust the path as necessary
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref, computed } from "vue";
import PieChart from "@/Components/PieChart.vue";
import { usePercentageComplete } from "@/Composables/usePercentageComplete";
import ProgressBar from "@/Components/ProgressBar.vue"; // Corrected import statement
import UnderlineLink from "@/Components/UnderlineLink.vue";
import DateDisplay from "@/Components/DateDisplay.vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  paychecks: {
    type: Array,
    default: () => [],
  },
});

const searchQuery = ref("");

// Reactive state for pagination
const currentPage = ref(1);
const itemsPerPage = ref(5);

const percentageComplete = (paycheck) => {
  return usePercentageComplete(paycheck.amount, paycheck.balance);
};

// Computed property to filter paychecks based on search query
const filteredPaychecks = computed(() => {
  return props.paychecks.filter((paycheck) => {
    return paycheck.paycheck_date
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase());
  });
});

// Computed property for paginated paychecks
const paginatedPaychecks = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredPaychecks.value.slice(start, end);
});

// Computed property for total pages
const totalPages = computed(() => {
  return Math.ceil(filteredPaychecks.value.length / itemsPerPage.value);
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

const handleDelete = (paycheckId) => {
  if (confirm("Are you sure you want to delete this paycheck?")) {
    Inertia.delete(route("paychecks.destroy", paycheckId), {
      onSuccess: () => {
        // Handle success, e.g., remove the paycheck from the paginatedPaychecks array
        props.paychecks = props.paychecks.filter(
          (paycheck) => paycheck.id !== paycheckId
        );
      },
      onError: () => {
        // Handle error
        alert("An error occurred while deleting the paycheck.");
      },
    });
  }
};
</script>

<template>
  <Head title="Paychecks" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Paychecks
        </h2>
        <Link :href="route('paychecks.create')">
          <PrimaryButton>Add New Paycheck</PrimaryButton>
        </Link>
      </div>
    </template>

    <div class="p-2">
      <div class="relative mx-auto max-w-6xl space-x-2">
        <div class="mt-4 flex items-center space-x-4 mx-auto max-w-7xl">
          <div class="relative w-full">
            <div
              class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
            >
              <svg
                class="w-4 h-4 text-gray-500 dark:text-gray-400"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 20 20"
              >
                <path
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"
                />
              </svg>
            </div>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search by date"
              class="block w-full rounded-full border border-gray-300 px-4 py-2 pl-10 placeholder:text-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            />
          </div>
        </div>
      </div>

      <div
        class="mt-2 relative overflow-x-auto mx-auto max-w-6xl rounded-lg hidden sm:block"
      >
        <table
          class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
        >
          <thead
            class="text-xs text-gray-200 bg-gray-900 dark:bg-gray-900 dark:text-gray-400"
          >
            <tr>
              <th scope="col" class="px-6 py-3">Paycheck Date</th>
              <th scope="col" class="px-6 py-3">Amount</th>
              <th scope="col" class="px-6 py-3"></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="paycheck in paginatedPaychecks"
              :key="paycheck.id"
              class="bg-white dark:bg-gray-900 border-b dark:border-gray-700 hover:bg-gray-300 relative group"
            >
              <td class="px-6 py-4">
                <UnderlineLink>{{ paycheck.paycheck_date }}</UnderlineLink>
              </td>
              <td class="px-6 py-2">
                <span
                  v-if="Number(paycheck.balance) === 0"
                  class="text-white dark:text-green-400 bg-green-700 rounded-lg px-4 py-1"
                >
                  Paid
                </span>
                <div v-else>
                  <ProgressBar :percentage="percentageComplete(paycheck)" />
                  <p>
                    ${{ paycheck.balance }} remaining out of ${{
                      paycheck.amount
                    }}
                  </p>
                </div>
              </td>
              <td class="px-6 py-4">
                <form @submit.prevent="handleDelete(paycheck.id)">
                  <button
                    type="submit"
                    class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-red-500 text-white px-2 py-1 rounded hidden group-hover:block z-10"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      x="0px"
                      y="0px"
                      width="16"
                      height="16"
                      viewBox="0,0,256,256"
                    >
                      <g
                        fill="#ffffff"
                        fill-rule="nonzero"
                        stroke="none"
                        stroke-width="1"
                        stroke-linecap="butt"
                        stroke-linejoin="miter"
                        stroke-miterlimit="10"
                        stroke-dasharray=""
                        stroke-dashoffset="0"
                        font-family="none"
                        font-weight="none"
                        font-size="none"
                        text-anchor="none"
                        style="mix-blend-mode: normal"
                      >
                        <g transform="scale(8.53333,8.53333)">
                          <path
                            d="M14.98438,2.48633c-0.55152,0.00862 -0.99193,0.46214 -0.98437,1.01367v0.5h-5.5c-0.26757,-0.00363 -0.52543,0.10012 -0.71593,0.28805c-0.1905,0.18793 -0.29774,0.44436 -0.29774,0.71195h-1.48633c-0.36064,-0.0051 -0.69608,0.18438 -0.87789,0.49587c-0.18181,0.3115 -0.18181,0.69676 0,1.00825c0.18181,0.3115 0.51725,0.50097 0.87789,0.49587h18c0.36064,0.0051 0.69608,-0.18438 0.87789,-0.49587c0.18181,-0.3115 0.18181,-0.69676 0,-1.00825c-0.18181,-0.3115 -0.51725,-0.50097 -0.87789,-0.49587h-1.48633c0,-0.26759 -0.10724,-0.52403 -0.29774,-0.71195c-0.1905,-0.18793 -0.44836,-0.29168 -0.71593,-0.28805h-5.5v-0.5c0.0037,-0.2703 -0.10218,-0.53059 -0.29351,-0.72155c-0.19133,-0.19097 -0.45182,-0.29634 -0.72212,-0.29212zM6,9l1.79297,15.23438c0.118,1.007 0.97037,1.76563 1.98438,1.76563h10.44531c1.014,0 1.86538,-0.75862 1.98438,-1.76562l1.79297,-15.23437z"
                          ></path>
                        </g>
                      </g>
                    </svg>
                  </button>
                </form>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="flex justify-between items-center p-4 mx-auto max-w-7xl">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="relative inline-flex items-center border px-4 py-2 text-sm font-medium focus:z-20 border-gray-300 bg-white text-gray-500 rounded-l-md disabled:opacity-50"
      >
        Previous
      </button>
      <span class="text-sm text-gray-700 m-2"
        >Page {{ currentPage }} of {{ totalPages }}</span
      >
      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="relative inline-flex items-center border px-4 py-2 text-sm font-medium focus:z-20 border-gray-300 bg-white text-gray-500 hover:bg-gray-50 rounded-r-md"
      >
        Next
      </button>
    </div>
  </AuthenticatedLayout>
</template>
