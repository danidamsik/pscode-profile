<script setup>
import AdminResourcePage from '@/Components/Admin/AdminResourcePage.vue';

defineProps({
    items: {
        type: Object,
        required: true,
    },
});

const columns = [
    { label: 'Avatar', key: 'user.avatar_url', type: 'image' },
    { label: 'User', key: 'user.name' },
    { label: 'Email', key: 'user.email' },
    { label: 'Rating', key: 'rating' },
    { label: 'Approved', key: 'is_approved', type: 'boolean' },
    { label: 'Komentar', key: 'comment' },
];

const fields = [
    { name: 'rating', label: 'Rating', type: 'number' },
    { name: 'comment', label: 'Komentar', type: 'textarea' },
    { name: 'is_approved', label: 'Approved', type: 'checkbox', default: false },
];
</script>

<template>
    <AdminResourcePage
        title="Testimoni"
        description="Review, approve, edit, atau hapus testimoni client."
        route-name="admin.testimonials"
        :items="items"
        :columns="columns"
        :fields="fields"
        :allow-create="false"
    />
</template>
