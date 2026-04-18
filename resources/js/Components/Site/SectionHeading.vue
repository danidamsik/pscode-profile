<script setup>
defineProps({
    eyebrow: {
        type: String,
        default: '',
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
    align: {
        type: String,
        default: 'left',
    },
});
</script>

<template>
    <div :class="align === 'center' ? 'mx-auto text-center' : ''" class="max-w-3xl">
        <p v-if="eyebrow" class="text-sm font-bold uppercase tracking-normal text-emerald-600 dark:text-tokyo-purple">
            {{ eyebrow }}
        </p>
        <h2 class="mt-3 text-3xl font-bold tracking-normal text-zinc-950 dark:text-tokyo-text">
            {{ title }}
        </h2>
        <p v-if="description" class="mt-4 text-base leading-7 text-zinc-600 dark:text-tokyo-muted">
            {{ description }}
        </p>
    </div>
</template>
