<script setup>
import Modal from '@/Components/Modal.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AdminToast from '@/Components/Admin/AdminToast.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
    routeName: {
        type: String,
        required: true,
    },
    resourceKey: {
        type: String,
        default: 'id',
    },
    items: {
        type: Object,
        required: true,
    },
    columns: {
        type: Array,
        required: true,
    },
    fields: {
        type: Array,
        required: true,
    },
    allowCreate: {
        type: Boolean,
        default: true,
    },
    createLabel: {
        type: String,
        default: 'Tambah Data',
    },
});

const emptyValues = () =>
    props.fields.reduce((values, field) => {
        if (field.type === 'file') {
            values[field.name] = null;
            return values;
        }

        if (field.type === 'files') {
            values[field.name] = [];
            return values;
        }

        values[field.name] = field.default ?? (field.type === 'checkbox' ? false : '');
        return values;
    }, {});

const form = useForm(emptyValues());
const modalMode = ref('create');
const editingItem = ref(null);
const deletingItem = ref(null);
const showFormModal = ref(false);
const toastMessage = ref('');

const rows = computed(() => props.items.data ?? []);
const hasFileFields = computed(() => props.fields.some((field) => ['file', 'files'].includes(field.type)));

const getValue = (item, path) =>
    path.split('.').reduce((value, segment) => (value === null || value === undefined ? null : value[segment]), item);

const routeParam = (item) => getValue(item, props.resourceKey);

const displayValue = (item, column) => {
    const value = getValue(item, column.key);

    if (column.type === 'boolean') {
        return value ? 'Ya' : 'Tidak';
    }

    if (column.type === 'array') {
        return Array.isArray(value) ? value.join(', ') : value;
    }

    return value ?? '-';
};

const imageSource = (item, column) => {
    const value = getValue(item, column.key);

    if (!value || value.startsWith('http') || value.startsWith('/')) {
        return value;
    }

    return `/${value}`;
};

const normalizeFormValue = (item, field) => {
    const value = getValue(item, field.source ?? field.name);

    if (field.type === 'checkbox') {
        return Boolean(value);
    }

    if (field.type === 'tags') {
        return Array.isArray(value) ? value.join(', ') : value ?? '';
    }

    if (field.type === 'datetime-local' && value) {
        return String(value).replace(' ', 'T').slice(0, 16);
    }

    if (['file', 'files'].includes(field.type)) {
        return field.type === 'files' ? [] : null;
    }

    return value ?? field.default ?? '';
};

const fieldError = (field) => form.errors[field.name] ?? form.errors[`${field.name}.0`];

const setFileValue = (field, event) => {
    const files = Array.from(event.target.files ?? []);

    form[field.name] = field.type === 'files' ? files : files[0] ?? null;
};

const showToast = (message) => {
    toastMessage.value = message;
    window.setTimeout(() => {
        toastMessage.value = '';
    }, 2400);
};

const openCreateModal = () => {
    modalMode.value = 'create';
    editingItem.value = null;
    form.defaults(emptyValues());
    form.reset();
    form.clearErrors();
    showFormModal.value = true;
};

const openEditModal = (item) => {
    modalMode.value = 'edit';
    editingItem.value = item;
    const values = emptyValues();

    props.fields.forEach((field) => {
        values[field.name] = normalizeFormValue(item, field);
    });

    form.defaults(values);
    form.reset();
    form.clearErrors();
    showFormModal.value = true;
};

const closeFormModal = () => {
    showFormModal.value = false;
    editingItem.value = null;
};

const submitForm = () => {
    if (modalMode.value === 'create') {
        form.transform((data) => data);
        form.post(route(`${props.routeName}.store`), {
            preserveScroll: true,
            forceFormData: hasFileFields.value,
            onSuccess: () => {
                closeFormModal();
                showToast('Data berhasil ditambahkan.');
            },
        });
        return;
    }

    form.transform((data) => ({
        ...data,
        _method: 'put',
    }));
    form.post(route(`${props.routeName}.update`, routeParam(editingItem.value)), {
        preserveScroll: true,
        forceFormData: hasFileFields.value,
        onSuccess: () => {
            closeFormModal();
            showToast('Data berhasil diperbarui.');
        },
    });
};

const openDeleteModal = (item) => {
    deletingItem.value = item;
};

const closeDeleteModal = () => {
    deletingItem.value = null;
};

const deleteItem = () => {
    router.delete(route(`${props.routeName}.destroy`, routeParam(deletingItem.value)), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            showToast('Data berhasil dihapus.');
        },
    });
};
</script>

<template>
    <Head :title="title" />

    <AdminLayout>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-normal text-zinc-950">{{ title }}</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600">{{ description }}</p>
            </div>
            <button
                v-if="allowCreate"
                type="button"
                class="rounded-lg bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700"
                @click="openCreateModal"
            >
                {{ createLabel }}
            </button>
        </div>

        <div class="mt-8 overflow-hidden rounded-lg border border-zinc-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-sm">
                    <thead class="bg-zinc-50 text-left text-xs font-bold uppercase tracking-normal text-zinc-500">
                        <tr>
                            <th v-for="column in columns" :key="column.key" class="px-5 py-4">
                                {{ column.label }}
                            </th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200">
                        <tr v-for="item in rows" :key="item.id">
                            <td v-for="column in columns" :key="column.key" class="max-w-xs px-5 py-4 text-zinc-700">
                                <img
                                    v-if="column.type === 'image' && imageSource(item, column)"
                                    :src="imageSource(item, column)"
                                    :alt="displayValue(item, column)"
                                    class="h-11 w-16 rounded-lg object-cover"
                                />
                                <span v-else class="block truncate">{{ displayValue(item, column) }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <button
                                        type="button"
                                        class="rounded-lg border border-zinc-300 px-3 py-2 font-semibold text-zinc-700 hover:border-zinc-950"
                                        @click="openEditModal(item)"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        class="rounded-lg border border-red-200 px-3 py-2 font-semibold text-red-700 hover:border-red-500"
                                        @click="openDeleteModal(item)"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!rows.length">
                            <td :colspan="columns.length + 1" class="px-5 py-10 text-center text-zinc-500">
                                Belum ada data.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="items.links?.length > 3" class="mt-6 flex flex-wrap gap-2">
            <component
                :is="link.url ? Link : 'span'"
                v-for="link in items.links"
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
                {{ link.label.replace('&laquo; Previous', 'Sebelumnya').replace('Next &raquo;', 'Berikutnya') }}
            </component>
        </div>

        <Modal :show="showFormModal" max-width="2xl" @close="closeFormModal">
            <form class="bg-white p-6" @submit.prevent="submitForm">
                <h2 class="text-xl font-bold text-zinc-950">
                    {{ modalMode === 'create' ? createLabel : 'Edit Data' }}
                </h2>

                <div class="mt-6 grid gap-5">
                    <div v-for="field in fields" :key="field.name">
                        <label :for="field.name" class="block text-sm font-semibold text-zinc-800">
                            {{ field.label }}
                        </label>

                        <textarea
                            v-if="field.type === 'textarea'"
                            :id="field.name"
                            v-model="form[field.name]"
                            rows="5"
                            class="mt-2 block w-full rounded-lg border-zinc-300 text-zinc-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        ></textarea>

                        <select
                            v-else-if="field.type === 'select'"
                            :id="field.name"
                            v-model="form[field.name]"
                            class="mt-2 block min-h-11 w-full rounded-lg border-zinc-300 text-zinc-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        >
                            <option v-for="option in field.options" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>

                        <label v-else-if="field.type === 'checkbox'" class="mt-2 flex items-center gap-3">
                            <input
                                v-model="form[field.name]"
                                type="checkbox"
                                class="rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500"
                            />
                            <span class="text-sm text-zinc-600">{{ field.help ?? 'Aktif' }}</span>
                        </label>

                        <input
                            v-else-if="field.type === 'file' || field.type === 'files'"
                            :id="field.name"
                            type="file"
                            :accept="field.accept ?? 'image/*'"
                            :multiple="field.type === 'files'"
                            class="mt-2 block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-bold file:text-emerald-700 hover:file:bg-emerald-100"
                            @change="setFileValue(field, $event)"
                        />

                        <input
                            v-else
                            :id="field.name"
                            v-model="form[field.name]"
                            :type="field.type === 'tags' ? 'text' : field.type ?? 'text'"
                            class="mt-2 block min-h-11 w-full rounded-lg border-zinc-300 text-zinc-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        />

                        <p v-if="field.help && field.type !== 'checkbox'" class="mt-2 text-xs text-zinc-500">
                            {{ field.help }}
                        </p>
                        <p v-if="fieldError(field)" class="mt-2 text-sm text-red-600">
                            {{ fieldError(field) }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        class="rounded-lg border border-zinc-300 px-5 py-3 text-sm font-bold text-zinc-700"
                        @click="closeFormModal"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700"
                        :disabled="form.processing"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </Modal>

        <Modal :show="Boolean(deletingItem)" max-width="md" @close="closeDeleteModal">
            <div class="bg-white p-6">
                <h2 class="text-xl font-bold text-zinc-950">Hapus Data</h2>
                <p class="mt-3 text-sm leading-6 text-zinc-600">
                    Data ini akan dihapus permanen. Aksi ini tidak bisa dibatalkan.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        class="rounded-lg border border-zinc-300 px-5 py-3 text-sm font-bold text-zinc-700"
                        @click="closeDeleteModal"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        class="rounded-lg bg-red-600 px-5 py-3 text-sm font-bold text-white hover:bg-red-700"
                        @click="deleteItem"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </Modal>

        <AdminToast :message="toastMessage" />
    </AdminLayout>
</template>
