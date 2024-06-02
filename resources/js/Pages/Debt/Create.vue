<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    debt_name: '',
    debt_amount: '',
    debt_supplier: '',
    remaining_balance: '',
    debt_start_date: '',
    expected_end_date: '',
});

const createDebt = () => {
    
    form.post(route('debts.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Add Debt" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Debt</h2>
        </template>

        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <form @submit.prevent="createDebt" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="debt_name" value="Debt Name" />
                        <TextInput
                            id="debt_name"
                            v-model="form.debt_name"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="debt_name"
                        />
                        <InputError :message="form.errors.debt_name" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="debt_amount" value="Amount" />
                        <TextInput
                            id="debt_amount"
                            v-model.number="form.debt_amount"
                            type="number"
                            class="mt-1 block w-full"
                            autocomplete="debt_amount"
                        />
                        <InputError :message="form.errors.debt_amount" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="remaining_balance" value="Remaining Balance" />
                        <TextInput
                            id="remaining_balance"
                            v-model.number="form.remaining_balance"
                            type="number"
                            class="mt-1 block w-full"
                            autocomplete="remaining_balance"
                        />
                        <InputError :message="form.errors.minimum_payment" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="debt_supplier" value="Supplier" />
                        <TextInput
                            id="debt_supplier"
                            v-model="form.debt_supplier"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="debt_supplier"
                        />
                        <InputError :message="form.errors.debt_supplier" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="debt_start_date" value="Start Date" />
                        <TextInput
                            id="debt_start_date"
                            v-model="form.debt_start_date"
                            type="date"
                            class="mt-1 block w-full"
                            autocomplete="debt_start_date"
                        />
                        <InputError :message="form.errors.debt_start_date" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="expected_end_date" value="Expected End Date" />
                        <TextInput
                            id="expected_end_date"
                            v-model="form.expected_end_date"
                            type="date"
                            class="mt-1 block w-full"
                            autocomplete="expected_end_date"
                        />
                        <InputError :message="form.errors.expected_end_date" class="mt-2" />
                    </div>

                    <div class="mb-2">
                        <PrimaryButton>Save</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
