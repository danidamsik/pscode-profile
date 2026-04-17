<script setup>
defineProps({
    id: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [String, Number],
        default: '',
    },
    type: {
        type: String,
        default: 'text',
    },
    placeholder: {
        type: String,
        default: '',
    },
});

defineEmits(['update:modelValue']);
</script>

<template>
    <label :for="id" class="block">
        <span class="text-sm font-semibold text-zinc-800">{{ label }}</span>
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            class="mt-2 block min-h-11 w-full rounded-lg border-zinc-300 text-zinc-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            @input="$emit('update:modelValue', $event.target.value)"
        />
    </label>
</template>
