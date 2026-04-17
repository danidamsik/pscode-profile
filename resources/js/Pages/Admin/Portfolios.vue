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
    { label: 'Kategori', key: 'category' },
    { label: 'Client', key: 'client_name' },
    { label: 'Published', key: 'is_published', type: 'boolean' },
    { label: 'Teknologi', key: 'technologies', type: 'array' },
];

const fields = [
    { name: 'title', label: 'Judul' },
    { name: 'slug', label: 'Slug', help: 'Kosongkan untuk generate otomatis dari judul.' },
    {
        name: 'category',
        label: 'Kategori',
        type: 'select',
        default: 'website',
        options: [
            { label: 'Website', value: 'website' },
            { label: 'Mobile', value: 'mobile' },
            { label: 'Other', value: 'other' },
        ],
    },
    { name: 'client_name', label: 'Nama Client' },
    { name: 'short_description', label: 'Deskripsi Singkat' },
    { name: 'description', label: 'Deskripsi Lengkap', type: 'textarea' },
    { name: 'technologies', label: 'Teknologi', type: 'tags', help: 'Pisahkan dengan koma, contoh: Laravel, Vue, MySQL' },
    { name: 'thumbnail_file', label: 'Upload Thumbnail', type: 'file', accept: 'image/*', help: 'Upload JPG, PNG, atau WebP maksimal 4 MB.' },
    {
        name: 'gallery_images',
        label: 'Upload Galeri Project',
        type: 'files',
        accept: 'image/*',
        help: 'Bisa pilih lebih dari satu gambar. Saat edit, upload baru akan mengganti galeri lama.',
    },
    { name: 'project_url', label: 'Project URL' },
    { name: 'sort_order', label: 'Urutan', type: 'number', default: 0 },
    { name: 'published_at', label: 'Tanggal Publish', type: 'datetime-local' },
    { name: 'is_featured', label: 'Featured', type: 'checkbox', default: false },
    { name: 'is_published', label: 'Published', type: 'checkbox', default: true },
];
</script>

<template>
    <AdminResourcePage
        title="Portfolio"
        description="Kelola project yang tampil di section portfolio publik."
        route-name="admin.portfolios"
        :items="items"
        :columns="columns"
        :fields="fields"
        create-label="Tambah Portfolio"
    />
</template>
