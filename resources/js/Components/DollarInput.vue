<template>
  <div>
    <div class="relative mt-1 rounded-md shadow-sm">
      <div
        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
      >
        <span class="text-gray-500 sm:text-sm">$</span>
      </div>
      <input
        type="number"
        name="price"
        id="price"
        class="block w-full rounded-md py-2 pl-7 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
        :value="modelValue"
        @input="updateValue"
        placeholder="0.00"
        ref="input"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

// Define props
const props = defineProps({
  modelValue: {
    type: [String, Number],
    required: true,
  },
});

// Emit events
const emit = defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

// Expose a focus method
defineExpose({ focus: () => input.value.focus() });

const updateValue = (event) => {
  emit("update:modelValue", event.target.value);
};
</script>

<style scoped>
/* Add any additional styling here */
</style>
