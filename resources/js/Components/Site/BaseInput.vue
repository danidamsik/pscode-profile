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
        <span class="text-sm font-semibold text-zinc-800 dark:text-tokyo-text">{{ label }}</span>
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            class="mt-2 block min-h-11 w-full rounded-lg border-zinc-300 text-zinc-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-tokyo-border dark:bg-tokyo-surface dark:text-tokyo-text dark:placeholder:text-tokyo-comment dark:focus:border-tokyo-blue dark:focus:ring-tokyo-blue"
            @input="$emit('update:modelValue', $event.target.value)"
        />
    </label>
</template>
