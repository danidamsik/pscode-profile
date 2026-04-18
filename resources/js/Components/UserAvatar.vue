<script setup>
import { computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        default: null,
    },
    size: {
        type: String,
        default: 'md',
    },
});

const sizeClasses = computed(() => ({
    sm: 'h-9 w-9 text-xs',
    md: 'h-11 w-11 text-sm',
    lg: 'h-14 w-14 text-base',
}[props.size] ?? 'h-11 w-11 text-sm'));

const avatarUrl = computed(() => props.user?.avatar_url ?? props.user?.avatar ?? null);
const initials = computed(() => props.user?.initials || props.user?.name?.slice(0, 2) || 'U');
</script>

<template>
    <span
        class="inline-flex shrink-0 items-center justify-center overflow-hidden rounded-full bg-emerald-100 font-bold uppercase text-emerald-800 dark:bg-tokyo-blue/15 dark:text-tokyo-blue"
        :class="sizeClasses"
    >
        <img
            v-if="avatarUrl"
            :src="avatarUrl"
            :alt="user?.name ?? 'User avatar'"
            class="h-full w-full object-cover"
        />
        <span v-else>{{ initials }}</span>
    </span>
</template>
