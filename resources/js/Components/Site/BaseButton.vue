<script setup>
import { Link } from '@inertiajs/vue3';
import { computed, useAttrs } from 'vue';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    href: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: 'button',
    },
    variant: {
        type: String,
        default: 'primary',
    },
});

const baseClasses =
    'inline-flex min-h-11 items-center justify-center rounded-lg px-5 py-3 text-sm font-semibold transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2';

const variantClasses = computed(() => ({
    primary: 'bg-emerald-600 text-white hover:bg-emerald-700',
    secondary: 'border border-zinc-300 bg-white text-zinc-900 hover:border-zinc-900',
    dark: 'bg-zinc-950 text-white hover:bg-zinc-800',
    light: 'bg-white text-zinc-950 hover:bg-zinc-100',
}[props.variant]));

const attrs = useAttrs();
const classes = computed(() => `${baseClasses} ${variantClasses.value}`);
const hasInertiaMethod = computed(() => Boolean(attrs.method));
const isAnchor = computed(() => !hasInertiaMethod.value && (props.href?.startsWith('#') || props.href?.startsWith('http')));
</script>

<template>
    <a v-if="href && isAnchor" :href="href" :class="classes" v-bind="attrs">
        <slot />
    </a>

    <Link v-else-if="href" :href="href" :class="classes" v-bind="attrs">
        <slot />
    </Link>

    <button v-else :type="type" :class="classes" v-bind="attrs">
        <slot />
    </button>
</template>
