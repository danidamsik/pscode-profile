<script setup>
const links = [
    { label: 'Tentang', href: '/#tentang' },
    { label: 'Layanan', href: '/#layanan' },
    { label: 'Portfolio', href: '/#portfolio' },
    { label: 'Blog', href: route('blog.index') },
    { label: 'Kontak', href: '/#kontak' },
];
</script>

<template>
    <footer class="border-t border-zinc-200 bg-white">
        <div class="mx-auto grid w-full max-w-7xl gap-10 px-4 py-10 sm:px-6 md:grid-cols-[1fr_auto] lg:px-8">
            <div>
                <a href="/#beranda" class="inline-flex items-center gap-3 font-bold text-zinc-950">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-950 text-sm text-white">
                        PS
                    </span>
                    <span>PSCode</span>
                </a>
                <p class="mt-4 max-w-md text-sm leading-6 text-zinc-600">
                    Partner digital untuk website, aplikasi, dan sistem bisnis yang jelas arahnya sejak hari pertama.
                </p>
            </div>

            <nav class="flex flex-wrap gap-x-5 gap-y-3 text-sm font-semibold text-zinc-700">
                <a v-for="link in links" :key="link.href" :href="link.href" class="hover:text-emerald-700">
                    {{ link.label }}
                </a>
            </nav>
        </div>
    </footer>
</template>
