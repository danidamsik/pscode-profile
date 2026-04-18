<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useTheme } from '@/Composables/useTheme';
import UserAvatar from '@/Components/UserAvatar.vue';
import BaseButton from './BaseButton.vue';

defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();
const isOpen = ref(false);
const activeSection = ref('beranda');
let removeFinishListener = null;
let removeSectionListeners = null;
let scrollFrame = null;

const { initTheme, isDark, themeLabel, toggleTheme } = useTheme();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === 'admin');
const currentPath = computed(() => (page.url || '/').split('#')[0].split('?')[0] || '/');
const isHomePage = computed(() => currentPath.value === '/');
const dashboardHref = computed(() => {
    if (!user.value) {
        return null;
    }

    return isAdmin.value ? route('admin.dashboard') : null;
});

const navLinks = [
    { label: 'Home', href: '/#beranda', sectionId: 'beranda' },
    { label: 'Tentang', href: '/#tentang', sectionId: 'tentang' },
    { label: 'Layanan', href: '/#layanan', sectionId: 'layanan' },
    { label: 'Alur', href: '/#alur', sectionId: 'alur' },
    { label: 'Portfolio', href: '/#portfolio', sectionId: 'portfolio' },
    { label: 'Testimoni', href: '/#testimoni', sectionId: 'testimoni' },
    { label: 'Blog', href: '/#blog', sectionId: 'blog', activePath: '/blog' },
    { label: 'Kontak', href: '/#kontak', sectionId: 'kontak' },
];

const sectionIds = navLinks.map((link) => link.sectionId).filter(Boolean);

const isLinkActive = (link) => {
    if (link.activePath && currentPath.value.startsWith(link.activePath)) {
        return true;
    }

    return isHomePage.value && activeSection.value === link.sectionId;
};

const navLinkClasses = (link) => [
    'relative text-sm font-semibold transition hover:text-emerald-700',
    isLinkActive(link)
        ? 'text-emerald-700 after:absolute after:-bottom-2 after:left-0 after:h-0.5 after:w-full after:rounded-full after:bg-emerald-600 dark:text-tokyo-blue dark:after:bg-tokyo-blue'
        : 'text-zinc-700 dark:text-tokyo-muted dark:hover:text-tokyo-blue',
];

const mobileNavLinkClasses = (link) => [
    'rounded-lg px-3 py-2 text-sm font-semibold transition',
    isLinkActive(link)
        ? 'bg-emerald-50 text-emerald-800 dark:bg-tokyo-blue/15 dark:text-tokyo-blue'
        : 'text-zinc-700 hover:bg-zinc-100 dark:text-tokyo-text dark:hover:bg-tokyo-panel',
];

const requestedSectionFromHash = () => {
    if (typeof window === 'undefined') {
        return null;
    }

    const sectionId = window.location.hash.replace('#', '');

    return sectionIds.includes(sectionId) ? sectionId : null;
};

const scrollToSection = (sectionId, behavior = 'smooth') => {
    const target = document.getElementById(sectionId);

    if (!target) {
        return false;
    }

    target.scrollIntoView({ behavior, block: 'start' });
    activeSection.value = sectionId;

    return true;
};

const updateActiveSection = () => {
    if (typeof window === 'undefined' || !isHomePage.value) {
        return;
    }

    const marker = 88 + Math.round(window.innerHeight * 0.18);
    let currentSection = sectionIds[0];

    sectionIds.forEach((sectionId) => {
        const section = document.getElementById(sectionId);

        if (section && section.getBoundingClientRect().top <= marker) {
            currentSection = sectionId;
        }
    });

    const isAtPageEnd = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 2;

    activeSection.value = isAtPageEnd ? sectionIds[sectionIds.length - 1] : currentSection;
};

const requestActiveSectionUpdate = () => {
    if (scrollFrame) {
        return;
    }

    scrollFrame = window.requestAnimationFrame(() => {
        scrollFrame = null;
        updateActiveSection();
    });
};

const removeTracking = () => {
    removeSectionListeners?.();
    removeSectionListeners = null;

    if (scrollFrame) {
        window.cancelAnimationFrame(scrollFrame);
        scrollFrame = null;
    }
};

const setupSectionTracking = () => {
    if (typeof window === 'undefined') {
        return;
    }

    removeTracking();

    if (!isHomePage.value) {
        return;
    }

    window.addEventListener('scroll', requestActiveSectionUpdate, { passive: true });
    window.addEventListener('resize', requestActiveSectionUpdate);

    removeSectionListeners = () => {
        window.removeEventListener('scroll', requestActiveSectionUpdate);
        window.removeEventListener('resize', requestActiveSectionUpdate);
    };

    const requestedSection = requestedSectionFromHash();

    if (requestedSection) {
        window.requestAnimationFrame(() => {
            scrollToSection(requestedSection);
        });
    }

    requestActiveSectionUpdate();
};

const handleHashChange = () => {
    const requestedSection = requestedSectionFromHash();

    if (isHomePage.value && requestedSection) {
        scrollToSection(requestedSection);
    }
};

const handleNavClick = (event, link) => {
    isOpen.value = false;

    if (typeof window === 'undefined' || !link.sectionId) {
        return;
    }

    const targetPath = link.href.split('#')[0] || '/';

    if (window.location.pathname !== '/' || targetPath !== '/') {
        return;
    }

    event.preventDefault();

    if (scrollToSection(link.sectionId)) {
        window.history.pushState(null, '', `/#${link.sectionId}`);
    }
};

onMounted(() => {
    initTheme();
    nextTick(setupSectionTracking);

    removeFinishListener = router.on('finish', () => {
        nextTick(setupSectionTracking);
    });

    window.addEventListener('hashchange', handleHashChange);
});

watch(
    () => page.url,
    () => nextTick(setupSectionTracking),
);

onBeforeUnmount(() => {
    removeTracking();
    removeFinishListener?.();
    window.removeEventListener('hashchange', handleHashChange);
});
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-zinc-200 bg-white/95 backdrop-blur dark:border-tokyo-border dark:bg-tokyo-bg/95">
        <nav class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a
                href="/#beranda"
                class="inline-flex items-center gap-3 font-bold text-zinc-950 dark:text-tokyo-text"
                @click="handleNavClick($event, navLinks[0])"
            >
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-950 text-sm text-white dark:bg-tokyo-blue dark:text-tokyo-bg">
                    PS
                </span>
                <span>PSCode</span>
            </a>

            <div class="hidden items-center gap-6 lg:flex">
                <a
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    :class="navLinkClasses(link)"
                    :aria-current="isLinkActive(link) ? 'location' : undefined"
                    @click="handleNavClick($event, link)"
                >
                    {{ link.label }}
                </a>
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                <div v-if="user" class="flex items-center gap-3">
                    <UserAvatar :user="user" size="sm" />
                    <BaseButton v-if="isAdmin" :href="dashboardHref" variant="secondary">Dashboard</BaseButton>
                    <BaseButton v-else :href="route('logout')" method="post" as="button" variant="secondary">
                        Logout
                    </BaseButton>
                </div>
                <template v-else-if="canLogin">
                    <Link :href="route('login')" class="text-sm font-semibold text-zinc-700 hover:text-emerald-700 dark:text-tokyo-muted dark:hover:text-tokyo-blue">
                        Masuk
                    </Link>
                    <BaseButton v-if="canRegister" :href="route('register')">Daftar</BaseButton>
                </template>
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-300 text-zinc-800 transition hover:border-emerald-500 hover:text-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-tokyo-border dark:text-tokyo-text dark:hover:border-tokyo-blue dark:hover:text-tokyo-blue dark:focus:ring-tokyo-blue dark:focus:ring-offset-tokyo-bg"
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
            </div>

            <div class="flex items-center gap-2 lg:hidden">
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-300 text-zinc-800 transition hover:border-emerald-500 hover:text-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-tokyo-border dark:text-tokyo-text dark:hover:border-tokyo-blue dark:hover:text-tokyo-blue dark:focus:ring-tokyo-blue dark:focus:ring-offset-tokyo-bg"
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
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-300 text-zinc-800 dark:border-tokyo-border dark:text-tokyo-text"
                    aria-label="Buka navigasi"
                    @click="isOpen = !isOpen"
                >
                    <span class="text-xl leading-none">{{ isOpen ? 'x' : '=' }}</span>
                </button>
            </div>
        </nav>

        <div v-if="isOpen" class="border-t border-zinc-200 bg-white px-4 py-4 dark:border-tokyo-border dark:bg-tokyo-bg lg:hidden">
            <div class="mx-auto grid max-w-7xl gap-3">
                <a
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    :class="mobileNavLinkClasses(link)"
                    :aria-current="isLinkActive(link) ? 'location' : undefined"
                    @click="handleNavClick($event, link)"
                >
                    {{ link.label }}
                </a>

                <div class="mt-2 flex flex-wrap gap-3 border-t border-zinc-200 pt-4 dark:border-tokyo-border">
                    <div v-if="user" class="flex w-full flex-wrap items-center gap-3">
                        <UserAvatar :user="user" size="sm" />
                        <div>
                            <p class="text-sm font-bold text-zinc-950 dark:text-tokyo-text">{{ user.name }}</p>
                            <p class="text-xs font-semibold text-zinc-500 dark:text-tokyo-comment">{{ user.email }}</p>
                        </div>
                        <BaseButton v-if="isAdmin" :href="dashboardHref" variant="secondary" @click="isOpen = false">
                            Dashboard
                        </BaseButton>
                        <BaseButton
                            v-else
                            :href="route('logout')"
                            method="post"
                            as="button"
                            variant="secondary"
                            @click="isOpen = false"
                        >
                            Logout
                        </BaseButton>
                    </div>
                    <template v-else-if="canLogin">
                        <BaseButton :href="route('login')" variant="secondary">Masuk</BaseButton>
                        <BaseButton v-if="canRegister" :href="route('register')">Daftar</BaseButton>
                    </template>
                </div>
            </div>
        </div>
    </header>
</template>
