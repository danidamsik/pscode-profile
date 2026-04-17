<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
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
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value?.role === 'admin');
</script>

<template>
    <Head title="Profile" />

    <AdminLayout v-if="isAdmin">
        <div class="grid gap-8 xl:grid-cols-[0.85fr_1.15fr]">
            <aside class="rounded-lg border border-zinc-200 bg-white p-6">
                <div class="flex items-center gap-4">
                    <UserAvatar :user="user" size="lg" />
                    <div>
                        <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">Profile Admin</p>
                        <h1 class="mt-1 text-2xl font-bold text-zinc-950">{{ user.name }}</h1>
                        <p class="mt-1 text-sm font-semibold text-zinc-500">{{ user.email }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 text-sm">
                    <div class="rounded-lg bg-zinc-50 p-4">
                        <p class="font-semibold text-zinc-500">Role</p>
                        <p class="mt-1 font-bold capitalize text-zinc-950">{{ user.role }}</p>
                    </div>
                    <div class="rounded-lg bg-zinc-50 p-4">
                        <p class="font-semibold text-zinc-500">Akses</p>
                        <p class="mt-1 font-bold text-zinc-950">Dashboard dan manajemen konten</p>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <Link
                        :href="route('admin.dashboard')"
                        class="rounded-lg bg-zinc-950 px-4 py-2 text-sm font-bold text-white hover:bg-zinc-800"
                    >
                        Dashboard
                    </Link>
                    <Link
                        href="/"
                        class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-bold text-zinc-700 hover:border-zinc-950"
                    >
                        Website
                    </Link>
                </div>
            </aside>

            <div class="grid gap-6">
                <section class="rounded-lg border border-zinc-200 bg-white p-6">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                    />
                </section>

                <section class="rounded-lg border border-zinc-200 bg-white p-6">
                    <UpdatePasswordForm />
                </section>

                <section class="rounded-lg border border-red-100 bg-white p-6">
                    <DeleteUserForm />
                </section>
            </div>
        </div>
    </AdminLayout>

    <AuthenticatedLayout v-else>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
