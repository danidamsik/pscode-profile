<script setup>
import { computed } from 'vue';

const props = defineProps({
    id: {
        type: String,
        default: null,
    },
    tone: {
        type: String,
        default: 'light',
    },
});

const toneClasses = computed(() => ({
    light: 'bg-white',
    soft: 'bg-zinc-50',
    dark: 'bg-zinc-950 text-white',
    green: 'bg-emerald-950 text-white',
}[props.tone]));
</script>

<template>
    <section :id="id" :class="toneClasses" class="scroll-mt-20 py-16 sm:py-20">
        <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <slot />
        </div>
    </section>
</template>
