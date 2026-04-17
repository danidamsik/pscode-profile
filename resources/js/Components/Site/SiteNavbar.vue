<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import BaseButton from './BaseButton.vue';

const props = defineProps({
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

const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === 'admin');
const dashboardHref = computed(() => {
    if (!user.value) {
        return null;
    }

    return isAdmin.value ? route('admin.dashboard') : null;
});

const navLinks = [
    { label: 'Tentang', href: '/#tentang' },
    { label: 'Layanan', href: '/#layanan' },
    { label: 'Alur', href: '/#alur' },
    { label: 'Portfolio', href: '/#portfolio' },
    { label: 'Testimoni', href: '/#testimoni' },
    { label: 'Blog', href: route('blog.index') },
    { label: 'Kontak', href: '/#kontak' },
];
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-zinc-200 bg-white/95 backdrop-blur">
        <nav class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="/#beranda" class="inline-flex items-center gap-3 font-bold text-zinc-950">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-950 text-sm text-white">
                    PS
                </span>
                <span>PSCode</span>
            </a>

            <div class="hidden items-center gap-6 lg:flex">
                <a
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    class="text-sm font-semibold text-zinc-700 hover:text-emerald-700"
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
                    <Link :href="route('login')" class="text-sm font-semibold text-zinc-700 hover:text-emerald-700">
                        Masuk
                    </Link>
                    <BaseButton v-if="canRegister" :href="route('register')">Daftar</BaseButton>
                </template>
            </div>

            <button
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-zinc-300 text-zinc-800 lg:hidden"
                aria-label="Buka navigasi"
                @click="isOpen = !isOpen"
            >
                <span class="text-xl leading-none">{{ isOpen ? 'x' : '=' }}</span>
            </button>
        </nav>

        <div v-if="isOpen" class="border-t border-zinc-200 bg-white px-4 py-4 lg:hidden">
            <div class="mx-auto grid max-w-7xl gap-3">
                <a
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    class="rounded-lg px-3 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100"
                    @click="isOpen = false"
                >
                    {{ link.label }}
                </a>

                <div class="mt-2 flex flex-wrap gap-3 border-t border-zinc-200 pt-4">
                    <div v-if="user" class="flex w-full flex-wrap items-center gap-3">
                        <UserAvatar :user="user" size="sm" />
                        <div>
                            <p class="text-sm font-bold text-zinc-950">{{ user.name }}</p>
                            <p class="text-xs font-semibold text-zinc-500">{{ user.email }}</p>
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
