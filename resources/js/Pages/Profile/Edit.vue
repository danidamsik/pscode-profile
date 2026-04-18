<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const page = usePage();
const isAdmin = computed(() => page.props.auth.user?.role === 'admin');
</script>

<template>
    <Head title="Profile" />

    <AdminLayout v-if="isAdmin">
        <div class="space-y-6">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">Profile</p>
                <h1 class="mt-2 text-2xl font-bold text-zinc-950">Pengaturan Akun Admin</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600">
                    Kelola identitas akun, password, dan keamanan akun dari satu halaman yang tertata.
                </p>
            </div>

            <div class="grid gap-6 xl:grid-cols-2 xl:items-start">
                <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm sm:p-8 xl:col-span-2">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                    />
                </section>

                <section class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm sm:p-8">
                    <UpdatePasswordForm />
                </section>

                <section class="rounded-lg border border-red-100 bg-white p-6 shadow-sm sm:p-8">
                    <DeleteUserForm />
                </section>
            </div>
        </div>
    </AdminLayout>

    <AuthenticatedLayout v-else>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-tokyo-text">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white p-4 shadow dark:bg-tokyo-surface sm:rounded-lg sm:p-8">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="bg-white p-4 shadow dark:bg-tokyo-surface sm:rounded-lg sm:p-8">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="bg-white p-4 shadow dark:bg-tokyo-surface sm:rounded-lg sm:p-8">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
