<script setup>
defineProps({
    message: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <Transition
        enter-active-class="transition duration-200"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-150"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div
            v-if="message"
            class="fixed bottom-5 right-5 z-50 rounded-lg bg-zinc-950 px-5 py-4 text-sm font-semibold text-white shadow-lg"
        >
            {{ message }}
        </div>
    </Transition>
</template>
