<script setup>
import AdminResourcePage from '@/Components/Admin/AdminResourcePage.vue';

defineProps({
    items: {
        type: Object,
        required: true,
    },
});

const columns = [
    { label: 'Foto', key: 'photo', type: 'image' },
    { label: 'Nama', key: 'name' },
    { label: 'Role', key: 'role' },
    { label: 'Aktif', key: 'is_active', type: 'boolean' },
    { label: 'Urutan', key: 'sort_order' },
];

const fields = [
    { name: 'name', label: 'Nama' },
    { name: 'role', label: 'Role' },
    { name: 'photo_file', label: 'Upload Foto', type: 'file', accept: 'image/*', help: 'Upload JPG, PNG, atau WebP maksimal 4 MB.' },
    { name: 'description', label: 'Deskripsi', type: 'textarea' },
    { name: 'linkedin', label: 'LinkedIn', source: 'social_links.linkedin' },
    { name: 'github', label: 'GitHub', source: 'social_links.github' },
    { name: 'sort_order', label: 'Urutan', type: 'number', default: 0 },
    { name: 'is_active', label: 'Aktif', type: 'checkbox', default: true },
];
</script>

<template>
    <AdminResourcePage
        title="Tim"
        description="Kelola anggota tim yang tampil di section tentang kami."
        route-name="admin.team-members"
        :items="items"
        :columns="columns"
        :fields="fields"
        create-label="Tambah Anggota"
    />
</template>
