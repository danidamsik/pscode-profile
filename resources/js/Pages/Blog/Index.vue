<script setup>
import BaseButton from '@/Components/Site/BaseButton.vue';
import SectionContainer from '@/Components/Site/SectionContainer.vue';
import SectionHeading from '@/Components/Site/SectionHeading.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
    blogs: {
        type: Object,
        required: true,
    },
});

const fallbackBlogImages = [
    'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=900&q=80',
];

const resolveBlogImage = (path, index = 0) => {
    if (path?.startsWith('http') || path?.startsWith('/')) {
        return path;
    }

    return fallbackBlogImages[index % fallbackBlogImages.length];
};

const formatDate = (date) =>
    new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(new Date(date));

const cleanPaginationLabel = (label) =>
    label
        .replace('&laquo; Previous', 'Sebelumnya')
        .replace('Next &raquo;', 'Berikutnya');
</script>

<template>
    <Head title="Blog" />

    <PublicLayout :can-login="canLogin" :can-register="canRegister">
        <SectionContainer tone="soft">
            <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-end">
                <SectionHeading
                    eyebrow="Blog"
                    title="Artikel untuk memahami kebutuhan digital sebelum mulai project."
                    description="Baca insight tentang company profile, web application, brief project, dan proses kerja digital agency."
                />
                <div class="rounded-lg border border-zinc-200 bg-white p-6">
                    <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">Artikel dipublikasikan</p>
                    <p class="mt-3 text-3xl font-bold text-zinc-950">{{ blogs.total }}</p>
                </div>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                <Link
                    v-for="(blog, index) in blogs.data"
                    :key="blog.id"
                    :href="route('blog.show', blog.slug)"
                    class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm transition hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    <img :src="resolveBlogImage(blog.thumbnail, index)" :alt="blog.title" class="h-52 w-full object-cover" />
                    <div class="p-5">
                        <p class="text-sm font-semibold text-emerald-700">{{ formatDate(blog.published_at) }}</p>
                        <h2 class="mt-3 text-xl font-bold leading-7 text-zinc-950">{{ blog.title }}</h2>
                        <p class="mt-4 text-sm leading-6 text-zinc-600">{{ blog.excerpt }}</p>
                    </div>
                </Link>
            </div>

            <div v-if="blogs.links.length > 3" class="mt-10 flex flex-wrap gap-2">
                <component
                    :is="link.url ? Link : 'span'"
                    v-for="link in blogs.links"
                    :key="link.label"
                    :href="link.url"
                    class="rounded-lg border px-4 py-2 text-sm font-semibold"
                    :class="
                        link.active
                            ? 'border-emerald-600 bg-emerald-600 text-white'
                            : link.url
                              ? 'border-zinc-300 bg-white text-zinc-800 hover:border-zinc-950'
                              : 'border-zinc-200 bg-zinc-100 text-zinc-400'
                    "
                >
                    {{ cleanPaginationLabel(link.label) }}
                </component>
            </div>

            <div class="mt-10">
                <BaseButton href="/#kontak" variant="secondary">Diskusikan Project</BaseButton>
            </div>
        </SectionContainer>
    </PublicLayout>
</template>
