<template>
  <div class="relative">
    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
    <input :id="id"
      :ref="ref"
      v-model="modelValue"
      :type="type"
      class="block w-full pl-10 pr-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
      :autocomplete="autocomplete" />
    <InputError :message="errorMessage" class="mt-2" />
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      required: true
    },
    ref: {
      type: String,
      required: true
    },
    modelValue: {
      type: [String, Number],
      required: true
    },
    type: {
      type: String,
      default: 'number'
    },
    autocomplete: {
      type: String,
      default: 'current-password'
    },
    errorMessage: {
      type: String,
      default: ''
    }
  },
  emits: ['update:modelValue']
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
