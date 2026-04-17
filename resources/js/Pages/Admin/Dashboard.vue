<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: {
        type: Object,
        required: true,
    },
});

const cards = [
    { label: 'Portfolio', key: 'portfolios', routeName: 'admin.portfolios.index' },
    { label: 'Testimoni', key: 'testimonials', routeName: 'admin.testimonials.index' },
    { label: 'Testimoni Pending', key: 'pendingTestimonials', routeName: 'admin.testimonials.index' },
    { label: 'Blog', key: 'blogs', routeName: 'admin.blogs.index' },
    { label: 'Kontak', key: 'contacts', routeName: 'admin.contacts.index' },
    { label: 'Kontak Belum Dibaca', key: 'unreadContacts', routeName: 'admin.contacts.index' },
    { label: 'Tim', key: 'teamMembers', routeName: 'admin.team-members.index' },
];
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div>
            <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">Dashboard Admin</p>
            <h1 class="mt-3 text-3xl font-bold tracking-normal text-zinc-950">Ringkasan konten website</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-600">
                Kelola portfolio, testimoni, blog, pesan kontak, dan data tim dari satu area yang terproteksi admin.
            </p>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <Link
                v-for="card in cards"
                :key="card.key"
                :href="route(card.routeName)"
                class="rounded-lg border border-zinc-200 bg-white p-6 transition hover:border-emerald-300"
            >
                <p class="text-sm font-semibold text-zinc-600">{{ card.label }}</p>
                <p class="mt-3 text-3xl font-bold text-zinc-950">{{ stats[card.key] }}</p>
            </Link>
        </div>
    </AdminLayout>
</template>
