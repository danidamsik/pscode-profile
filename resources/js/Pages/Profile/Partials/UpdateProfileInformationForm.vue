<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const avatarPreview = ref(user.avatar_url);

const form = useForm({
    name: user.name,
    email: user.email,
    avatar_file: null,
});

const previewUser = computed(() => ({
    ...user,
    name: form.name,
    avatar_url: avatarPreview.value,
}));

const setAvatarFile = (event) => {
    const file = event.target.files?.[0] ?? null;
    form.avatar_file = file;
    avatarPreview.value = file ? URL.createObjectURL(file) : user.avatar_url;
};

const submitProfile = () => {
    form.transform((data) => ({
        ...data,
        _method: 'patch',
    })).post(route('profile.update'), {
        forceFormData: true,
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-bold text-zinc-950">Informasi Profile</h2>

            <p class="mt-1 text-sm leading-6 text-zinc-600">
                Perbarui nama, email, dan avatar akun yang tampil di sistem.
            </p>
        </header>

        <form @submit.prevent="submitProfile" class="mt-6 space-y-6">
            <div class="flex items-center gap-4">
                <UserAvatar :user="previewUser" size="lg" />
                <div class="text-sm text-zinc-600">
                    <p class="font-bold text-zinc-950">{{ form.name }}</p>
                    <p>Preview avatar</p>
                </div>
            </div>

            <div>
                <InputLabel for="name" value="Nama" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="avatar_file" value="Avatar" />

                <input
                    id="avatar_file"
                    type="file"
                    accept="image/*"
                    class="mt-1 block w-full"
                    @change="setAvatarFile"
                />

                <InputError class="mt-2" :message="form.errors.avatar_file" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Email kamu belum terverifikasi.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Kirim ulang email verifikasi.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    Link verifikasi baru sudah dikirim ke email kamu.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Simpan</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-zinc-600">Tersimpan.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
