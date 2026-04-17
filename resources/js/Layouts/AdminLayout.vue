<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import UserAvatar from '@/Components/UserAvatar.vue';

const sidebarOpen = ref(false);

const links = [
    { label: 'Dashboard', routeName: 'admin.dashboard' },
    { label: 'Portfolio', routeName: 'admin.portfolios.index' },
    { label: 'Testimoni', routeName: 'admin.testimonials.index' },
    { label: 'Blog', routeName: 'admin.blogs.index' },
    { label: 'Kontak', routeName: 'admin.contacts.index' },
    { label: 'Tim', routeName: 'admin.team-members.index' },
];
</script>

<template>
    <div class="min-h-screen bg-zinc-100 text-zinc-950">
        <aside
            class="fixed inset-y-0 left-0 z-40 w-72 border-r border-zinc-200 bg-white px-4 py-5 transition lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex items-center justify-between">
                <Link :href="route('admin.dashboard')" class="flex items-center gap-3 font-bold">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-950 text-sm text-white">
                        PS
                    </span>
                    <span>Admin PSCode</span>
                </Link>
                <button type="button" class="rounded-lg border border-zinc-300 px-3 py-2 lg:hidden" @click="sidebarOpen = false">
                    x
                </button>
            </div>

            <nav class="mt-8 grid gap-2">
                <Link
                    v-for="link in links"
                    :key="link.routeName"
                    :href="route(link.routeName)"
                    class="rounded-lg px-4 py-3 text-sm font-semibold transition"
                    :class="route().current(link.routeName) ? 'bg-emerald-600 text-white' : 'text-zinc-700 hover:bg-zinc-100'"
                >
                    {{ link.label }}
                </Link>
            </nav>

            <div class="mt-8 border-t border-zinc-200 pt-5">
                <Link :href="route('profile.edit')" class="block rounded-lg px-4 py-3 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
                    Profile
                </Link>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="mt-2 block w-full rounded-lg px-4 py-3 text-left text-sm font-semibold text-zinc-700 hover:bg-zinc-100"
                >
                    Log Out
                </Link>
            </div>
        </aside>

        <div class="lg:pl-72">
            <header class="sticky top-0 z-30 border-b border-zinc-200 bg-white">
                <div class="flex min-h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                    <button type="button" class="rounded-lg border border-zinc-300 px-3 py-2 lg:hidden" @click="sidebarOpen = true">
                        Menu
                    </button>
                    <div class="flex items-center gap-3">
                        <UserAvatar :user="$page.props.auth.user" size="sm" />
                        <div>
                            <p class="text-sm text-zinc-500">Admin Panel</p>
                            <p class="font-bold">{{ $page.props.auth.user.name }}</p>
                        </div>
                    </div>
                    <Link href="/" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 hover:border-zinc-950">
                        Website
                    </Link>
                </div>
            </header>

            <main class="px-4 py-8 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>
    </div>
</template>
