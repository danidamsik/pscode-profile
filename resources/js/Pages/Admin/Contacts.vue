<script setup>
import AdminResourcePage from '@/Components/Admin/AdminResourcePage.vue';

defineProps({
    items: {
        type: Object,
        required: true,
    },
});

const columns = [
    { label: 'Nama', key: 'name' },
    { label: 'Email', key: 'email' },
    { label: 'Status', key: 'status' },
    { label: 'Pesan', key: 'message' },
];

const fields = [
    { name: 'name', label: 'Nama' },
    { name: 'email', label: 'Email', type: 'email' },
    { name: 'message', label: 'Pesan', type: 'textarea' },
    {
        name: 'status',
        label: 'Status',
        type: 'select',
        default: 'unread',
        options: [
            { label: 'Unread', value: 'unread' },
            { label: 'Read', value: 'read' },
            { label: 'Replied', value: 'replied' },
        ],
    },
];
</script>

<template>
    <AdminResourcePage
        title="Kontak"
        description="Lihat pesan dari form kontak dan ubah status follow-up."
        route-name="admin.contacts"
        :items="items"
        :columns="columns"
        :fields="fields"
        :allow-create="false"
    />
</template>
