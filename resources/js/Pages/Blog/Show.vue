<script setup>
import BaseButton from '@/Components/Site/BaseButton.vue';
import SectionContainer from '@/Components/Site/SectionContainer.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
    blog: {
        type: Object,
        required: true,
    },
    latestBlogs: {
        type: Array,
        default: () => [],
    },
});

const fallbackBlogImages = [
    'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=80',
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

const contentParagraphs = computed(() =>
    props.blog.content
        .split(/\n+/)
        .map((paragraph) => paragraph.trim())
        .filter(Boolean),
);

const galleryImages = computed(() => props.blog.images ?? []);
</script>

<template>
    <Head :title="blog.title" />

    <PublicLayout :can-login="canLogin" :can-register="canRegister">
        <SectionContainer tone="light">
            <article class="mx-auto max-w-4xl">
                <BaseButton :href="route('blog.index')" variant="secondary">Kembali ke Blog</BaseButton>

                <p class="mt-10 text-sm font-bold uppercase tracking-normal text-emerald-700 dark:text-tokyo-blue">
                    {{ formatDate(blog.published_at) }}
                </p>
                <h1 class="mt-4 text-4xl font-bold tracking-normal text-zinc-950 dark:text-tokyo-text">{{ blog.title }}</h1>
                <p v-if="blog.excerpt" class="mt-5 text-lg leading-8 text-zinc-600 dark:text-tokyo-muted">{{ blog.excerpt }}</p>

                <img
                    :src="resolveBlogImage(blog.thumbnail)"
                    :alt="blog.title"
                    class="mt-10 h-80 w-full rounded-lg object-cover"
                />

                <div class="mt-10 grid gap-5 text-base leading-8 text-zinc-700 dark:text-tokyo-muted">
                    <p v-for="paragraph in contentParagraphs" :key="paragraph">
                        {{ paragraph }}
                    </p>
                </div>

                <div v-if="galleryImages.length" class="mt-10 grid gap-4 sm:grid-cols-2">
                    <img
                        v-for="(image, index) in galleryImages"
                        :key="image"
                        :src="resolveBlogImage(image, index + 1)"
                        :alt="`${blog.title} ${index + 1}`"
                        class="h-56 w-full rounded-lg object-cover"
                    />
                </div>
            </article>
        </SectionContainer>

        <SectionContainer v-if="latestBlogs.length" tone="soft">
            <div class="mx-auto max-w-4xl">
                <h2 class="text-2xl font-bold text-zinc-950 dark:text-tokyo-text">Artikel Lainnya</h2>
                <div class="mt-6 grid gap-4">
                    <Link
                        v-for="latest in latestBlogs"
                        :key="latest.id"
                        :href="route('blog.show', latest.slug)"
                        class="rounded-lg border border-zinc-200 bg-white p-5 transition hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-tokyo-border dark:bg-tokyo-surface dark:focus:ring-tokyo-blue dark:focus:ring-offset-tokyo-bg"
                    >
                        <p class="text-sm font-semibold text-emerald-700 dark:text-tokyo-blue">{{ formatDate(latest.published_at) }}</p>
                        <h3 class="mt-2 text-lg font-bold text-zinc-950 dark:text-tokyo-text">{{ latest.title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-tokyo-muted">{{ latest.excerpt }}</p>
                    </Link>
                </div>
            </div>
        </SectionContainer>
    </PublicLayout>
</template>
