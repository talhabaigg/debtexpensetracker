<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DollarValue from '@/Components/DollarValue.vue'; // Adjust the path as necessary
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
  debts: {
    type: Array,
  }
})

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

        <div class="p-2">
            
            <div class="relative mx-auto max-w-6xl space-x-2">
               
                
            </div>
                <div class="mt-2 relative overflow-x-auto mx-auto max-w-6xl rounded-lg hidden sm:block ">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
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
                        <tr v-for="debt in debts" :key="debt.id" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <Link :href="route('debts.show',debt.id)">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ debt.debt_name }}</th>
                            </Link>
                            <DollarValue :amount="debt.debt_amount" />
                            <span v-if="debt.remaining_balance === 0" class="px-6 py-4 text-green-700 dark:text-green-400">Paid</span>
                            <DollarValue v-else :amount="debt.remaining_balance" class="font-normal text-red-700 dark:text-red-400" />
                            <td class="px-6 py-4">{{ debt.debt_supplier }}</td>
                            <td class="px-6 py-4">{{ debt.debt_start_date }}</td>
                            <td class="px-6 py-4">{{ debt.expected_end_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-2 block sm:hidden">
            <div v-for="debt in debts" :key="debt.id" class="mt-2 flex justify-center">
                <div class="w-full max-w-6xl p-3 bg-white border border-gray-200 rounded-lg shadow dark:hover:bg-gray-700 hover:bg-gray-300 dark:bg-gray-800 dark:border-gray-700 flex justify-between items-center">
                    <div class="min-w-0">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">$ {{debt.debt_amount}}</h5>
                        
                        <p class=" font-normal text-gray-700 dark:text-gray-400 truncate">{{debt.debt_supplier}}</p>
                        <span v-if="debt.remaining_balance === 0" class="text-green-700 dark:text-green-400">Paid</span>
                        
                        <p v-else class=" font-normal text-gray-700 dark:text-gray-400">Balance <span><DollarValue :amount="debt.remaining_balance" class="font-normal text-red-700 dark:text-red-400" /></span></p>
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
    
    </AuthenticatedLayout>
</template>
