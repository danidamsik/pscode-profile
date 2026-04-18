<script setup>
import { onMounted, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import { useTheme } from '@/Composables/useTheme';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const { initTheme, isDark, themeLabel, toggleTheme } = useTheme();

onMounted(initTheme);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-tokyo-bg">
            <nav class="border-b border-gray-100 bg-white dark:border-tokyo-border dark:bg-tokyo-bg">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800 dark:text-tokyo-text"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    v-if="$page.props.auth.user.role === 'admin'"
                                    :href="route('admin.dashboard')"
                                    :active="route().current('admin.dashboard')"
                                >
                                    Admin
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-gray-200 text-gray-600 transition hover:border-emerald-500 hover:text-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-tokyo-border dark:text-tokyo-text dark:hover:border-tokyo-blue dark:hover:text-tokyo-blue dark:focus:ring-tokyo-blue dark:focus:ring-offset-tokyo-bg"
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
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-2 rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-tokyo-bg dark:text-tokyo-muted dark:hover:text-tokyo-blue dark:focus:ring-tokyo-blue dark:focus:ring-offset-tokyo-bg"
                                            >
                                                <UserAvatar :user="$page.props.auth.user" size="sm" />
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center gap-2 sm:hidden">
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-gray-200 text-gray-600 transition hover:border-emerald-500 hover:text-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-tokyo-border dark:text-tokyo-text dark:hover:border-tokyo-blue dark:hover:text-tokyo-blue dark:focus:ring-tokyo-blue dark:focus:ring-offset-tokyo-bg"
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
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-tokyo-muted dark:hover:bg-tokyo-panel dark:hover:text-tokyo-blue dark:focus:bg-tokyo-panel dark:focus:text-tokyo-blue"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden dark:bg-tokyo-bg"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="$page.props.auth.user.role === 'admin'"
                            :href="route('admin.dashboard')"
                            :active="route().current('admin.dashboard')"
                        >
                            Admin
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 pb-1 pt-4 dark:border-tokyo-border">
                        <div class="px-4">
                            <div class="flex items-center gap-3">
                                <UserAvatar :user="$page.props.auth.user" size="sm" />
                                <div>
                                    <div class="text-base font-medium text-gray-800 dark:text-tokyo-text">
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-500 dark:text-tokyo-comment">{{ $page.props.auth.user.email }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow dark:bg-tokyo-surface" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
