<template>
  <div class="mb-4">
    <label :for="label" class="block text-sm text-[#BEBEBE] mb-1">{{ label }}</label>
    <input
      :type="type"
      :placeholder="placeholder"
      :id="label"
      class="w-full px-4 py-2 rounded-xl border border-[#D2D2D2] bg-[#EBECF0] focus:outline-none focus:ring-2 focus:ring-[#F1B2B5]"
      v-model="modelValue"
    />
  </div>
</template>

<script setup>
defineProps(['label', 'type', 'placeholder', 'modelValue'])
defineEmits(['update:modelValue'])
</script>

<script>
export default {
  props: ['label', 'type', 'placeholder', 'modelValue'],
  emits: ['update:modelValue'],
  watch: {
    modelValue(newValue) {
      this.$emit('update:modelValue', newValue)
    }
  }
}
</script>
