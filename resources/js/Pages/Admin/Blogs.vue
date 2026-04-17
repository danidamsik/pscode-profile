<script setup>
import AdminResourcePage from '@/Components/Admin/AdminResourcePage.vue';

defineProps({
    items: {
        type: Object,
        required: true,
    },
});

const columns = [
    { label: 'Thumbnail', key: 'thumbnail', type: 'image' },
    { label: 'Judul', key: 'title' },
    { label: 'Slug', key: 'slug' },
    { label: 'Published', key: 'is_published', type: 'boolean' },
    { label: 'Tanggal', key: 'published_at' },
];

const fields = [
    { name: 'title', label: 'Judul' },
    { name: 'slug', label: 'Slug', help: 'Kosongkan untuk generate otomatis dari judul.' },
    { name: 'excerpt', label: 'Ringkasan' },
    { name: 'content', label: 'Isi Artikel', type: 'textarea' },
    { name: 'thumbnail_file', label: 'Upload Thumbnail', type: 'file', accept: 'image/*', help: 'Upload JPG, PNG, atau WebP maksimal 4 MB.' },
    {
        name: 'gallery_images',
        label: 'Upload Galeri Artikel',
        type: 'files',
        accept: 'image/*',
        help: 'Bisa pilih lebih dari satu gambar. Saat edit, upload baru akan mengganti galeri lama.',
    },
    { name: 'published_at', label: 'Tanggal Publish', type: 'datetime-local' },
    { name: 'is_published', label: 'Published', type: 'checkbox', default: true },
];
</script>

<template>
    <AdminResourcePage
        title="Blog"
        description="Kelola artikel edukasi yang tampil di halaman blog."
        route-name="admin.blogs"
        resource-key="slug"
        :items="items"
        :columns="columns"
        :fields="fields"
        create-label="Tambah Artikel"
    />
</template>
