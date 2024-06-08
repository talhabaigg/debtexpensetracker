// src/composables/usePercentageComplete.js
export function usePercentageComplete(debtAmount, remainingBalance) {
    if (debtAmount === 0) return 0;
    return ((debtAmount - remainingBalance) / debtAmount) * 100;
  }