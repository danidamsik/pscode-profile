<script setup>
import BaseButton from '@/Components/Site/BaseButton.vue';
import BaseCard from '@/Components/Site/BaseCard.vue';
import BaseInput from '@/Components/Site/BaseInput.vue';
import Modal from '@/Components/Modal.vue';
import SectionContainer from '@/Components/Site/SectionContainer.vue';
import SectionHeading from '@/Components/Site/SectionHeading.vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    services: {
        type: Array,
        default: () => [],
    },
    orderSteps: {
        type: Array,
        default: () => [],
    },
    portfolios: {
        type: Array,
        default: () => [],
    },
    testimonials: {
        type: Array,
        default: () => [],
    },
    latestBlogs: {
        type: Array,
        default: () => [],
    },
    teamMembers: {
        type: Array,
        default: () => [],
    },
});

const selectedTeamMember = ref(null);
const selectedPortfolio = ref(null);
const selectedPortfolioImageIndex = ref(0);
const page = usePage();

const testimonialForm = useForm({
    rating: 5,
    comment: '',
});

const contactForm = useForm({
    name: '',
    email: '',
    message: '',
});

const fallbackTeamImages = [
    'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=500&q=80',
];

const fallbackPortfolioImages = {
    website: [
        'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=900&q=80',
    ],
    mobile: [
        'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1551650975-87deedd944c3?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1526498460520-4c246339dccb?auto=format&fit=crop&w=900&q=80',
    ],
    other: [
        'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=900&q=80',
    ],
};

const fallbackBlogImages = [
    'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=900&q=80',
    'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=900&q=80',
];

const fallbackPortfolios = [
    {
        id: 'fallback-booking',
        title: 'Sistem Booking Klinik',
        category: 'website',
        client_name: 'Klinik Sehat Sentosa',
        short_description: 'Platform reservasi dokter dan manajemen jadwal klinik.',
        description:
            'Sistem booking klinik dengan fitur jadwal dokter, antrian pasien, notifikasi, dan dashboard admin.',
        technologies: ['Laravel', 'Vue', 'Inertia', 'Tailwind CSS', 'MySQL'],
        thumbnail: null,
        project_url: null,
        images: [{ image: null, alt_text: 'Dashboard booking klinik' }],
    },
    {
        id: 'fallback-inventory',
        title: 'Aplikasi Inventory Gudang',
        category: 'website',
        client_name: 'Nusantara Logistic',
        short_description: 'Dashboard stok barang, mutasi gudang, dan laporan real-time.',
        description:
            'Aplikasi inventory untuk memantau stok, barang masuk keluar, level minimum, dan laporan gudang.',
        technologies: ['Laravel', 'Vue', 'MySQL', 'Tailwind CSS'],
        thumbnail: null,
        project_url: null,
        images: [{ image: null, alt_text: 'Halaman stok barang' }],
    },
    {
        id: 'fallback-laundry',
        title: 'Mobile App Pemesanan Laundry',
        category: 'mobile',
        client_name: 'FreshWash',
        short_description: 'Aplikasi pemesanan laundry dengan tracking status order.',
        description:
            'Aplikasi mobile untuk pemesanan pickup laundry, kalkulasi layanan, tracking status, dan riwayat transaksi.',
        technologies: ['Flutter', 'Laravel API', 'MySQL'],
        thumbnail: null,
        project_url: null,
        images: [{ image: null, alt_text: 'Home mobile laundry' }],
    },
];

const fallbackTestimonials = [
    {
        id: 'fallback-sarah',
        rating: 5,
        comment: 'Proses pengerjaan jelas, revisi cepat, dan dashboard admin mudah digunakan.',
        user: {
            name: 'Sarah Wijaya',
            avatar_url: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=240&q=80',
        },
    },
    {
        id: 'fallback-client',
        rating: 5,
        comment: 'Tim PSCode komunikatif dan hasil websitenya rapi sesuai kebutuhan bisnis kami.',
        user: {
            name: 'Client Demo',
            avatar_url: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=240&q=80',
        },
    },
];

const fallbackLatestBlogs = [
    {
        id: 'fallback-company-profile',
        title: 'Mengapa Company Profile Penting untuk Bisnis Digital',
        slug: 'mengapa-company-profile-penting-untuk-bisnis-digital',
        excerpt: 'Website company profile membantu calon client memahami bisnis, layanan, dan kredibilitas perusahaan.',
        thumbnail: null,
        published_at: new Date().toISOString(),
    },
    {
        id: 'fallback-brief',
        title: 'Tips Menyiapkan Brief Project Website',
        slug: 'tips-menyiapkan-brief-project-website',
        excerpt: 'Brief yang jelas membantu proses desain dan development berjalan lebih cepat dan terarah.',
        thumbnail: null,
        published_at: new Date().toISOString(),
    },
    {
        id: 'fallback-web-app',
        title: 'Perbedaan Company Profile dan Web Application',
        slug: 'perbedaan-website-company-profile-dan-web-application',
        excerpt: 'Keduanya sama-sama berbasis web, tetapi tujuan dan kompleksitas fiturnya berbeda.',
        thumbnail: null,
        published_at: new Date().toISOString(),
    },
];

const enrichedTeamMembers = computed(() =>
    props.teamMembers.map((member, index) => ({
        ...member,
        photo_url: member.photo?.startsWith('http') ? member.photo : fallbackTeamImages[index % fallbackTeamImages.length],
    })),
);

const activeTeamMember = computed(() => selectedTeamMember.value);
const activePortfolio = computed(() => selectedPortfolio.value);
const authUser = computed(() => page.props.auth.user);
const canSubmitTestimonial = computed(() => authUser.value?.role === 'user');

const resolvePortfolioImage = (path, category = 'website', index = 0) => {
    if (path?.startsWith('http') || path?.startsWith('/')) {
        return path;
    }

    const images = fallbackPortfolioImages[category] ?? fallbackPortfolioImages.other;

    return images[index % images.length];
};

const enrichedPortfolios = computed(() =>
    (props.portfolios.length ? props.portfolios : fallbackPortfolios).map((portfolio) => {
        const sourceImages = portfolio.images?.length
            ? portfolio.images
            : [{ image: portfolio.thumbnail, alt_text: portfolio.title }];

        const images = sourceImages.map((image, index) => ({
            id: image.id ?? `${portfolio.id}-${index}`,
            alt_text: image.alt_text || portfolio.title,
            url: resolvePortfolioImage(image.image, portfolio.category, index),
        }));

        return {
            ...portfolio,
            category_label: portfolio.category === 'mobile' ? 'Mobile App' : portfolio.category === 'website' ? 'Website' : 'Digital Product',
            thumbnail_url: resolvePortfolioImage(portfolio.thumbnail, portfolio.category, 0),
            images,
            technologies: portfolio.technologies ?? [],
        };
    }),
);

const activePortfolioImages = computed(() => activePortfolio.value?.images ?? []);
const activePortfolioImage = computed(() => activePortfolioImages.value[selectedPortfolioImageIndex.value]);
const displayTestimonials = computed(() => (props.testimonials.length ? props.testimonials : fallbackTestimonials));
const displayLatestBlogs = computed(() =>
    (props.latestBlogs.length ? props.latestBlogs : fallbackLatestBlogs).map((blog, index) => ({
        ...blog,
        thumbnail_url: resolveBlogImage(blog.thumbnail, index),
    })),
);

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

const showTeamMember = (member) => {
    selectedTeamMember.value = member;
};

const closeTeamMember = () => {
    selectedTeamMember.value = null;
};

const showPortfolio = (portfolio) => {
    selectedPortfolio.value = portfolio;
    selectedPortfolioImageIndex.value = 0;
};

const closePortfolio = () => {
    selectedPortfolio.value = null;
    selectedPortfolioImageIndex.value = 0;
};

const submitTestimonial = () => {
    testimonialForm.post(route('testimonials.store'), {
        preserveScroll: true,
        onSuccess: () => testimonialForm.reset('comment'),
    });
};

const submitContact = () => {
    contactForm.post(route('contacts.store'), {
        preserveScroll: true,
        onSuccess: () => contactForm.reset(),
    });
};

const showPreviousPortfolioImage = () => {
    if (!activePortfolioImages.value.length) {
        return;
    }

    selectedPortfolioImageIndex.value =
        (selectedPortfolioImageIndex.value - 1 + activePortfolioImages.value.length) % activePortfolioImages.value.length;
};

const showNextPortfolioImage = () => {
    if (!activePortfolioImages.value.length) {
        return;
    }

    selectedPortfolioImageIndex.value = (selectedPortfolioImageIndex.value + 1) % activePortfolioImages.value.length;
};

const companyValues = [
    ['Discovery yang tajam', 'Kebutuhan bisnis, target pengguna, dan prioritas fitur dirapikan sebelum desain dimulai.'],
    ['Eksekusi bertahap', 'Setiap bagian dikerjakan dalam milestone yang mudah direview dan mudah dikoreksi.'],
    ['Produk siap dikelola', 'Struktur admin, konten, dan data disiapkan agar operasional setelah rilis tetap nyaman.'],
];

const fallbackServices = [
    {
        id: 1,
        title: 'Website Company Profile',
        description: 'Branding digital yang rapi, cepat dibaca, dan mudah diarahkan ke calon client.',
        icon: 'monitor',
    },
    {
        id: 2,
        title: 'Web Application',
        description: 'Sistem operasional, dashboard, dan workflow internal yang siap tumbuh bersama bisnis.',
        icon: 'database',
    },
    {
        id: 3,
        title: 'Mobile Application',
        description: 'Aplikasi mobile untuk layanan, pemesanan, tracking, dan interaksi pelanggan.',
        icon: 'smartphone',
    },
];

const fallbackOrderSteps = [
    { id: 1, title: 'Konsultasi', description: 'Tujuan, fitur, dan prioritas project dirapikan sejak awal.' },
    { id: 2, title: 'Rancangan', description: 'Flow, struktur halaman, dan kebutuhan teknis disusun sebelum development.' },
    { id: 3, title: 'Development', description: 'Frontend, backend, database, dan integrasi dikerjakan bertahap.' },
    { id: 4, title: 'Launch', description: 'Testing, revisi, rilis, dan support awal berjalan dalam satu alur.' },
];

const displayServices = computed(() => (props.services.length ? props.services : fallbackServices));
const displayOrderSteps = computed(() => (props.orderSteps.length ? props.orderSteps : fallbackOrderSteps));

const serviceIconLabel = (icon) => ({
    monitor: 'Web',
    database: 'Sys',
    smartphone: 'App',
    palette: 'UX',
    settings: 'Ops',
}[icon] ?? 'Dev');

</script>

<template>
    <Head title="PSCode - Solusi Digital Modern" />

    <PublicLayout :can-login="canLogin" :can-register="canRegister">
        <section
            id="beranda"
            class="relative isolate min-h-[76svh] scroll-mt-20 overflow-hidden bg-zinc-950 py-20 text-white sm:py-24"
        >
            <img
                src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1800&q=80"
                alt="Tim digital agency sedang berdiskusi di ruang kerja modern"
                class="absolute inset-0 -z-10 h-full w-full object-cover opacity-45"
            />
            <div class="absolute inset-0 -z-10 bg-zinc-950/55"></div>

            <div class="mx-auto grid min-h-[62svh] w-full max-w-7xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
                <div class="max-w-3xl">
                    <p class="text-sm font-bold uppercase tracking-normal text-emerald-300">
                        Solusi Digital Modern untuk Website dan Mobile Apps
                    </p>
                    <h1 class="mt-5 text-4xl font-bold tracking-normal">
                        Bangun platform digital yang dipercaya client sejak kunjungan pertama.
                    </h1>
                    <p class="mt-6 max-w-2xl text-base leading-7 text-zinc-100">
                        PSCode membantu bisnis menampilkan brand, menjelaskan layanan, mengelola portfolio, dan membuka
                        komunikasi dengan calon client melalui website company profile yang profesional.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <BaseButton href="#kontak" variant="light">Mulai Konsultasi</BaseButton>
                        <BaseButton href="#tentang" variant="secondary">Kenali PSCode</BaseButton>
                    </div>
                </div>

                <div class="grid gap-5 rounded-lg border border-white/15 bg-white/10 p-5 backdrop-blur">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-normal text-emerald-300">Fokus Project</p>
                        <p class="mt-3 text-2xl font-bold text-white">
                            Branding, sistem, dan aplikasi dalam satu alur kerja.
                        </p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-lg border border-white/15 p-4">
                            <p class="text-2xl font-bold">01</p>
                            <p class="mt-2 text-sm text-zinc-100">Discovery kebutuhan</p>
                        </div>
                        <div class="rounded-lg border border-white/15 p-4">
                            <p class="text-2xl font-bold">02</p>
                            <p class="mt-2 text-sm text-zinc-100">Desain dan build</p>
                        </div>
                        <div class="rounded-lg border border-white/15 p-4">
                            <p class="text-2xl font-bold">03</p>
                            <p class="mt-2 text-sm text-zinc-100">Rilis dan support</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <SectionContainer id="tentang" tone="light">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                <div>
                    <SectionHeading
                        eyebrow="Tentang Kami"
                        title="Partner pengembangan digital untuk kebutuhan bisnis yang nyata."
                        description="Kami membantu perusahaan, brand, dan pelaku usaha merapikan ide menjadi produk digital yang mudah dipahami pengguna, mudah dikelola admin, dan siap dikembangkan setelah rilis."
                    />
                    <div class="mt-8 rounded-lg border border-zinc-200 bg-zinc-50 p-6">
                        <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">Visi</p>
                        <p class="mt-3 text-xl font-bold text-zinc-950">
                            Membuat solusi digital yang terasa jelas untuk client, rapi untuk tim, dan berguna untuk
                            pertumbuhan bisnis.
                        </p>
                    </div>
                </div>

                <div class="grid gap-4">
                    <BaseCard v-for="value in companyValues" :key="value[0]">
                        <h3 class="text-lg font-bold text-zinc-950">{{ value[0] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-zinc-600">{{ value[1] }}</p>
                    </BaseCard>
                </div>
            </div>

            <div class="mt-14">
                <SectionHeading
                    eyebrow="Tim Pengembang"
                    title="Orang yang menjaga arah, tampilan, kode, dan kualitas project."
                    description="Setiap anggota membawa peran yang saling melengkapi agar proses kerja tetap terukur dari awal sampai rilis."
                />

                <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <button
                        v-for="member in enrichedTeamMembers"
                        :key="member.id"
                        type="button"
                        class="overflow-hidden rounded-lg border border-zinc-200 bg-white text-left shadow-sm transition hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        @click="showTeamMember(member)"
                    >
                        <img :src="member.photo_url" :alt="member.name" class="h-56 w-full object-cover" />
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-zinc-950">{{ member.name }}</h3>
                            <p class="mt-1 text-sm font-semibold text-emerald-700">{{ member.role }}</p>
                            <p class="mt-3 text-sm leading-6 text-zinc-600">
                                {{ member.description }}
                            </p>
                        </div>
                    </button>
                </div>
            </div>
        </SectionContainer>

        <SectionContainer id="layanan" tone="soft">
            <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-end">
                <SectionHeading
                    eyebrow="Layanan"
                    title="Pilih solusi yang paling dekat dengan target bisnismu."
                    description="Setiap layanan disiapkan agar proses branding, operasional, dan interaksi pelanggan berjalan lebih tertata."
                />

                <div class="rounded-lg border border-zinc-200 bg-white p-6">
                    <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">Output yang disiapkan</p>
                    <div class="mt-4 grid gap-3 text-sm font-semibold text-zinc-700 sm:grid-cols-3">
                        <p class="rounded-lg bg-zinc-50 px-4 py-3">Struktur konten</p>
                        <p class="rounded-lg bg-zinc-50 px-4 py-3">Desain responsive</p>
                        <p class="rounded-lg bg-zinc-50 px-4 py-3">Admin-ready</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <BaseCard v-for="service in displayServices" :key="service.id">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-100 text-sm font-bold text-emerald-800"
                    >
                        {{ serviceIconLabel(service.icon) }}
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-zinc-950">{{ service.title }}</h3>
                    <p class="mt-3 text-sm leading-6 text-zinc-600">{{ service.description }}</p>
                    <a href="#kontak" class="mt-5 inline-flex text-sm font-bold text-emerald-700 hover:text-emerald-900">
                        Diskusikan kebutuhan
                    </a>
                </BaseCard>
            </div>
        </SectionContainer>

        <SectionContainer id="alur" tone="light">
            <div class="grid gap-10 lg:grid-cols-[0.78fr_1.22fr] lg:items-start">
                <SectionHeading
                    eyebrow="Alur Pemesanan"
                    title="Dari ide awal ke rilis, langkahnya dibuat terang."
                    description="Calon client tahu apa yang perlu disiapkan, kapan review dilakukan, dan bagaimana project bergerak sampai launch."
                />
                <div class="relative">
                    <div class="absolute left-5 top-5 hidden h-[calc(100%-2.5rem)] w-px bg-zinc-200 sm:block"></div>

                    <div class="grid gap-5">
                        <article
                            v-for="(step, index) in displayOrderSteps"
                            :key="step.id"
                            class="relative rounded-lg border border-zinc-200 bg-white p-5 shadow-sm sm:ms-16"
                        >
                            <div
                                class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-950 text-sm font-bold text-white sm:absolute sm:-left-16 sm:top-5"
                            >
                                {{ String(index + 1).padStart(2, '0') }}
                            </div>
                            <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">
                                Tahap {{ index + 1 }}
                            </p>
                            <h3 class="mt-2 text-lg font-bold text-zinc-950">{{ step.title }}</h3>
                            <p class="mt-3 text-sm leading-6 text-zinc-600">{{ step.description }}</p>
                        </article>
                    </div>
                </div>
            </div>
        </SectionContainer>

        <SectionContainer id="portfolio" tone="dark">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-end">
                <SectionHeading
                    eyebrow="Portfolio"
                    title="Project yang pernah dikerjakan untuk kebutuhan web dan mobile."
                    description="Setiap project ditampilkan dengan gambaran fungsi, kategori, teknologi, dan visual pendukung agar calon client bisa menilai arah solusi dengan cepat."
                />
                <div class="rounded-lg border border-white/10 bg-white/5 p-6">
                    <p class="text-sm font-bold uppercase tracking-normal text-emerald-300">Kategori Project</p>
                    <div class="mt-4 flex flex-wrap gap-3 text-sm font-semibold text-white">
                        <span class="rounded-lg bg-white/10 px-4 py-2">Website</span>
                        <span class="rounded-lg bg-white/10 px-4 py-2">Dashboard</span>
                        <span class="rounded-lg bg-white/10 px-4 py-2">Mobile App</span>
                    </div>
                </div>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                <button
                    v-for="item in enrichedPortfolios"
                    :key="item.id"
                    type="button"
                    class="group overflow-hidden rounded-lg border border-white/10 bg-white/5 text-left transition hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 focus:ring-offset-zinc-950"
                    @click="showPortfolio(item)"
                >
                    <div class="relative">
                        <img :src="item.thumbnail_url" :alt="item.title" class="h-56 w-full object-cover" />
                        <div class="absolute left-4 top-4 rounded-lg bg-zinc-950/80 px-3 py-2 text-xs font-bold text-white">
                            {{ item.category_label }}
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-sm font-semibold text-emerald-300">{{ item.client_name }}</p>
                        <h3 class="mt-2 text-xl font-bold text-white">{{ item.title }}</h3>
                        <p class="mt-3 text-sm leading-6 text-zinc-300">
                            {{ item.short_description }}
                        </p>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <span
                                v-for="technology in item.technologies.slice(0, 3)"
                                :key="technology"
                                class="rounded-lg bg-white/10 px-3 py-1 text-xs font-semibold text-zinc-100"
                            >
                                {{ technology }}
                            </span>
                        </div>
                    </div>
                </button>
            </div>
        </SectionContainer>

        <SectionContainer id="testimoni" tone="light">
            <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
                <div>
                    <SectionHeading
                        eyebrow="Testimoni"
                        title="Review client yang membantu calon partner menilai proses kerja."
                        description="Guest dapat membaca testimoni yang sudah disetujui. User login dapat mengirim komentar dan rating untuk direview admin."
                    />

                    <div class="mt-8 rounded-lg border border-zinc-200 bg-zinc-50 p-6">
                        <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">
                            Rating hanya 1 sampai 5
                        </p>
                        <p class="mt-3 text-sm leading-6 text-zinc-600">
                            Setiap testimoni baru disimpan dengan status menunggu persetujuan agar konten publik tetap
                            rapi dan relevan.
                        </p>
                    </div>
                </div>

                <div class="grid gap-5">
                    <div class="grid gap-5 md:grid-cols-2">
                        <BaseCard v-for="testimonial in displayTestimonials" :key="testimonial.id">
                            <div class="flex gap-1 text-lg text-emerald-600" aria-label="Rating testimoni">
                                <span v-for="star in 5" :key="star">
                                    {{ star <= testimonial.rating ? '★' : '☆' }}
                                </span>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-zinc-700">
                                {{ testimonial.comment }}
                            </p>
                            <div class="mt-5 flex items-center gap-3">
                                <UserAvatar :user="testimonial.user" size="sm" />
                                <p class="text-sm font-bold text-zinc-950">
                                    {{ testimonial.user?.name ?? 'Client' }}
                                </p>
                            </div>
                        </BaseCard>
                    </div>

                    <form
                        v-if="canSubmitTestimonial"
                        class="rounded-lg border border-zinc-200 bg-white p-6 shadow-sm"
                        @submit.prevent="submitTestimonial"
                    >
                        <p class="text-lg font-bold text-zinc-950">Bagikan pengalamanmu</p>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">
                            Komentar akan tampil setelah disetujui admin.
                        </p>

                        <div class="mt-5">
                            <span class="text-sm font-semibold text-zinc-800">Rating</span>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button
                                    v-for="rating in 5"
                                    :key="rating"
                                    type="button"
                                    class="flex h-11 w-11 items-center justify-center rounded-lg border text-lg font-bold transition"
                                    :class="
                                        testimonialForm.rating === rating
                                            ? 'border-emerald-600 bg-emerald-600 text-white'
                                            : 'border-zinc-300 bg-white text-zinc-700 hover:border-emerald-600'
                                    "
                                    @click="testimonialForm.rating = rating"
                                >
                                    {{ rating }}
                                </button>
                            </div>
                            <p v-if="testimonialForm.errors.rating" class="mt-2 text-sm text-red-600">
                                {{ testimonialForm.errors.rating }}
                            </p>
                        </div>

                        <label for="testimonial-comment" class="mt-5 block">
                            <span class="text-sm font-semibold text-zinc-800">Komentar</span>
                            <textarea
                                id="testimonial-comment"
                                v-model="testimonialForm.comment"
                                rows="5"
                                placeholder="Ceritakan pengalaman bekerja sama dengan PSCode"
                                class="mt-2 block w-full rounded-lg border-zinc-300 text-zinc-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            ></textarea>
                        </label>
                        <p v-if="testimonialForm.errors.comment" class="mt-2 text-sm text-red-600">
                            {{ testimonialForm.errors.comment }}
                        </p>

                        <div class="mt-5 flex flex-wrap items-center gap-3">
                            <BaseButton type="submit" :disabled="testimonialForm.processing">
                                Kirim Testimoni
                            </BaseButton>
                            <p v-if="testimonialForm.recentlySuccessful" class="text-sm font-semibold text-emerald-700">
                                Testimoni terkirim dan menunggu persetujuan.
                            </p>
                        </div>
                    </form>

                    <div v-else class="rounded-lg border border-zinc-200 bg-zinc-50 p-6">
                        <p class="text-lg font-bold text-zinc-950">Ingin memberi testimoni?</p>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">
                            Masuk sebagai user untuk mengirim komentar dan rating.
                        </p>
                        <div class="mt-5 flex flex-wrap gap-3">
                            <BaseButton v-if="!authUser" :href="route('login')" variant="secondary">Masuk</BaseButton>
                            <BaseButton v-if="!authUser && canRegister" :href="route('register')">Daftar</BaseButton>
                            <BaseButton v-if="authUser?.role === 'admin'" href="#portfolio" variant="secondary">
                                Lihat Portfolio
                            </BaseButton>
                        </div>
                    </div>
                </div>
            </div>
        </SectionContainer>

        <SectionContainer id="blog" tone="soft">
            <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-end">
                <SectionHeading
                    eyebrow="Blog"
                    title="Catatan singkat untuk keputusan digital yang lebih matang."
                    description="Artikel edukasi membantu calon client memahami kebutuhan, scope, dan pilihan solusi sebelum memulai project."
                />
                <div class="rounded-lg border border-zinc-200 bg-white p-6">
                    <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">Insight terbaru</p>
                    <p class="mt-3 text-sm leading-6 text-zinc-600">
                        Baca artikel lengkap untuk memahami perencanaan, proses kerja, dan pilihan solusi digital.
                    </p>
                    <BaseButton :href="route('blog.index')" variant="secondary" class="mt-5">Lihat Semua Artikel</BaseButton>
                </div>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-3">
                <Link
                    v-for="(post, index) in displayLatestBlogs"
                    :key="post.id"
                    :href="route('blog.show', post.slug)"
                    class="overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm transition hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    <img :src="post.thumbnail_url" :alt="post.title" class="h-48 w-full object-cover" />
                    <div class="p-5">
                        <p class="text-sm font-semibold text-emerald-700">
                            {{ formatDate(post.published_at) }}
                        </p>
                        <h3 class="mt-3 text-lg font-bold leading-7 text-zinc-950">{{ post.title }}</h3>
                        <p class="mt-4 text-sm leading-6 text-zinc-600">
                            {{ post.excerpt }}
                        </p>
                    </div>
                </Link>
            </div>
        </SectionContainer>

        <SectionContainer id="kontak" tone="green">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                <div>
                    <p class="text-sm font-bold uppercase tracking-normal text-emerald-200">Kontak</p>
                    <h2 class="mt-3 text-3xl font-bold tracking-normal text-white">
                        Ceritakan kebutuhan projectmu.
                    </h2>
                    <p class="mt-4 text-base leading-7 text-emerald-50">
                        Mulai dari company profile sederhana, dashboard internal, sampai aplikasi mobile dengan alur
                        pemesanan.
                    </p>
                    <div class="mt-8 rounded-lg border border-white/15 p-5">
                        <p class="text-sm font-semibold text-emerald-100">Email</p>
                        <p class="mt-1 text-lg font-bold text-white">hello@pscode.test</p>
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg border border-white/15 p-5">
                            <p class="text-sm font-semibold text-emerald-100">Jam Diskusi</p>
                            <p class="mt-1 text-base font-bold text-white">Senin-Jumat, 09.00-17.00</p>
                        </div>
                        <div class="rounded-lg border border-white/15 p-5">
                            <p class="text-sm font-semibold text-emerald-100">Respon Awal</p>
                            <p class="mt-1 text-base font-bold text-white">Maksimal 1 hari kerja</p>
                        </div>
                    </div>
                </div>

                <form class="rounded-lg bg-white p-6 text-zinc-950 shadow-sm" @submit.prevent="submitContact">
                    <div class="grid gap-5">
                        <div>
                            <BaseInput
                                id="name"
                                v-model="contactForm.name"
                                label="Nama"
                                placeholder="Nama lengkap"
                            />
                            <p v-if="contactForm.errors.name" class="mt-2 text-sm text-red-600">
                                {{ contactForm.errors.name }}
                            </p>
                        </div>
                        <div>
                            <BaseInput
                                id="email"
                                v-model="contactForm.email"
                                label="Email"
                                type="email"
                                placeholder="nama@email.com"
                            />
                            <p v-if="contactForm.errors.email" class="mt-2 text-sm text-red-600">
                                {{ contactForm.errors.email }}
                            </p>
                        </div>
                        <label for="message" class="block">
                            <span class="text-sm font-semibold text-zinc-800">Pesan</span>
                            <textarea
                                id="message"
                                v-model="contactForm.message"
                                rows="5"
                                placeholder="Ceritakan kebutuhan website atau aplikasimu"
                                class="mt-2 block w-full rounded-lg border-zinc-300 text-zinc-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                            ></textarea>
                        </label>
                        <p v-if="contactForm.errors.message" class="text-sm text-red-600">
                            {{ contactForm.errors.message }}
                        </p>
                        <div class="flex flex-wrap items-center gap-3">
                            <BaseButton type="submit" :disabled="contactForm.processing">Kirim Pesan</BaseButton>
                            <p v-if="contactForm.recentlySuccessful" class="text-sm font-semibold text-emerald-700">
                                Pesan terkirim. Tim kami akan menghubungi kamu secepatnya.
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </SectionContainer>

        <Modal :show="Boolean(activePortfolio)" max-width="2xl" @close="closePortfolio">
            <div v-if="activePortfolio" class="bg-white">
                <div class="relative bg-zinc-950">
                    <img
                        v-if="activePortfolioImage"
                        :src="activePortfolioImage.url"
                        :alt="activePortfolioImage.alt_text"
                        class="h-80 w-full object-cover opacity-95"
                    />

                    <div
                        class="absolute left-4 top-4 rounded-lg bg-zinc-950/80 px-3 py-2 text-xs font-bold uppercase tracking-normal text-white"
                    >
                        {{ activePortfolio.category_label }}
                    </div>

                    <div
                        v-if="activePortfolioImages.length > 1"
                        class="absolute bottom-4 right-4 flex items-center gap-2"
                    >
                        <button
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-white text-zinc-950"
                            aria-label="Gambar sebelumnya"
                            @click="showPreviousPortfolioImage"
                        >
                            &lt;
                        </button>
                        <button
                            type="button"
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-white text-zinc-950"
                            aria-label="Gambar berikutnya"
                            @click="showNextPortfolioImage"
                        >
                            &gt;
                        </button>
                    </div>
                </div>

                <div class="grid gap-8 p-6 lg:grid-cols-[1.1fr_0.9fr]">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">
                            {{ activePortfolio.client_name }}
                        </p>
                        <h3 class="mt-2 text-2xl font-bold text-zinc-950">{{ activePortfolio.title }}</h3>
                        <p class="mt-4 text-sm leading-6 text-zinc-600">
                            {{ activePortfolio.description }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-bold uppercase tracking-normal text-zinc-500">Teknologi</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span
                                v-for="technology in activePortfolio.technologies"
                                :key="technology"
                                class="rounded-lg bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-800"
                            >
                                {{ technology }}
                            </span>
                        </div>

                        <div v-if="activePortfolioImages.length > 1" class="mt-6">
                            <p class="text-sm font-bold uppercase tracking-normal text-zinc-500">Galeri</p>
                            <div class="mt-3 grid grid-cols-3 gap-2">
                                <button
                                    v-for="(image, index) in activePortfolioImages"
                                    :key="image.id"
                                    type="button"
                                    class="overflow-hidden rounded-lg border"
                                    :class="selectedPortfolioImageIndex === index ? 'border-emerald-500' : 'border-zinc-200'"
                                    @click="selectedPortfolioImageIndex = index"
                                >
                                    <img :src="image.url" :alt="image.alt_text" class="h-20 w-full object-cover" />
                                </button>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <BaseButton
                                v-if="activePortfolio.project_url"
                                :href="activePortfolio.project_url"
                                variant="dark"
                            >
                                Buka Project
                            </BaseButton>
                            <BaseButton type="button" variant="secondary" @click="closePortfolio">Tutup</BaseButton>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal :show="Boolean(activeTeamMember)" max-width="lg" @close="closeTeamMember">
            <div v-if="activeTeamMember" class="bg-white">
                <img
                    :src="activeTeamMember.photo_url"
                    :alt="activeTeamMember.name"
                    class="h-72 w-full object-cover"
                />
                <div class="p-6">
                    <p class="text-sm font-bold uppercase tracking-normal text-emerald-700">
                        {{ activeTeamMember.role }}
                    </p>
                    <h3 class="mt-2 text-2xl font-bold text-zinc-950">{{ activeTeamMember.name }}</h3>
                    <p class="mt-4 text-sm leading-6 text-zinc-600">
                        {{ activeTeamMember.description }}
                    </p>

                    <div class="mt-6 grid gap-3 text-sm text-zinc-700">
                        <a
                            v-if="activeTeamMember.social_links?.linkedin"
                            :href="activeTeamMember.social_links.linkedin"
                            target="_blank"
                            rel="noreferrer"
                            class="font-semibold text-emerald-700 hover:text-emerald-900"
                        >
                            LinkedIn
                        </a>
                        <a
                            v-if="activeTeamMember.social_links?.github"
                            :href="activeTeamMember.social_links.github"
                            target="_blank"
                            rel="noreferrer"
                            class="font-semibold text-emerald-700 hover:text-emerald-900"
                        >
                            GitHub
                        </a>
                    </div>

                    <div class="mt-6">
                        <BaseButton type="button" variant="secondary" @click="closeTeamMember">Tutup</BaseButton>
                    </div>
                </div>
            </div>
        </Modal>
    </PublicLayout>
</template>
