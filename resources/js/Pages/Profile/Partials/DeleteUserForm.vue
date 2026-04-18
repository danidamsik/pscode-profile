<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-bold text-red-700">Hapus Akun</h2>

            <p class="mt-1 text-sm leading-6 text-zinc-600 dark:text-tokyo-muted">
                Setelah akun dihapus, data akun tidak dapat dikembalikan. Pastikan keputusan ini memang diperlukan.
            </p>
        </header>

        <div class="flex flex-wrap items-center justify-between gap-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-tokyo-pink/30 dark:bg-tokyo-pink/10">
            <p class="max-w-xl text-sm font-semibold leading-6 text-red-700 dark:text-tokyo-pink">
                Tindakan ini permanen dan membutuhkan konfirmasi password.
            </p>
            <DangerButton @click="confirmUserDeletion">Hapus Akun</DangerButton>
        </div>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-tokyo-text">
                    Yakin ingin menghapus akun?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-tokyo-muted">
                    Masukkan password untuk mengonfirmasi penghapusan akun secara permanen.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Batal </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Hapus Akun
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
