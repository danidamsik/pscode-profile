<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { useTheme } from '@/Composables/useTheme';
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const { initTheme, isDark, themeLabel, toggleTheme } = useTheme();

onMounted(initTheme);
</script>

<template>
    <div class="relative flex min-h-screen flex-col items-center bg-gray-100 pt-6 dark:bg-tokyo-bg sm:justify-center sm:pt-0">
        <button
            type="button"
            class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-300 bg-white text-zinc-800 transition hover:border-emerald-500 hover:text-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-tokyo-border dark:bg-tokyo-surface dark:text-tokyo-text dark:hover:border-tokyo-blue dark:hover:text-tokyo-blue dark:focus:ring-tokyo-blue dark:focus:ring-offset-tokyo-bg"
            :aria-label="themeLabel"
            :title="themeLabel"
            @click="toggleTheme"
        >
            <svg v-if="isDark" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 4V2M12 22v-2M4 12H2M22 12h-2M5.64 5.64 4.22 4.22M19.78 19.78l-1.42-1.42M18.36 5.64l1.42-1.42M4.22 19.78l1.42-1.42" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2" />
            </svg>
            <svg v-else class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20.5 15.5A8.5 8.5 0 0 1 8.5 3.5 7 7 0 1 0 20.5 15.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <div>
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500 dark:text-tokyo-muted" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md dark:bg-tokyo-surface sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
