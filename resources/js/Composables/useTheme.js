import { computed, ref } from 'vue';

const storageKey = 'pscode-theme';
const isDark = ref(false);
let initialized = false;

const prefersDark = () => window.matchMedia('(prefers-color-scheme: dark)').matches;

const applyTheme = (value, persist = true) => {
    isDark.value = Boolean(value);
    document.documentElement.classList.toggle('dark', isDark.value);

    if (persist) {
        localStorage.setItem(storageKey, isDark.value ? 'dark' : 'light');
    }
};

const initTheme = () => {
    if (initialized || typeof window === 'undefined') {
        return;
    }

    initialized = true;

    const storedTheme = localStorage.getItem(storageKey);
    const currentTheme = storedTheme ? storedTheme === 'dark' : document.documentElement.classList.contains('dark') || prefersDark();

    applyTheme(currentTheme, false);
};

export const useTheme = () => {
    const toggleTheme = () => {
        applyTheme(!isDark.value);
    };

    return {
        isDark,
        initTheme,
        themeLabel: computed(() => (isDark.value ? 'Mode terang' : 'Mode gelap')),
        toggleTheme,
    };
};
