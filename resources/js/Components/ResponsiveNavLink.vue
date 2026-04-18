<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-emerald-400 text-start text-base font-medium text-emerald-700 bg-emerald-50 focus:outline-none focus:text-emerald-800 focus:bg-emerald-100 focus:border-emerald-700 transition duration-150 ease-in-out dark:bg-tokyo-blue/15 dark:text-tokyo-blue dark:border-tokyo-blue dark:focus:bg-tokyo-blue/15 dark:focus:text-tokyo-blue dark:focus:border-tokyo-blue'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out dark:text-tokyo-muted dark:hover:text-tokyo-blue dark:hover:bg-tokyo-panel dark:hover:border-tokyo-border dark:focus:bg-tokyo-panel dark:focus:text-tokyo-blue dark:focus:border-tokyo-border'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
