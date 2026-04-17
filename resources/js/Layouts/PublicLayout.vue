<script setup>
import SiteFooter from '@/Components/Site/SiteFooter.vue';
import SiteNavbar from '@/Components/Site/SiteNavbar.vue';
import { router } from '@inertiajs/vue3';
import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
});

const mainElement = ref(null);
let observer = null;
let removeFinishListener = null;

const revealSelector = [
    '[data-scroll-reveal]',
    'section > div > *',
    'article > *',
    'section .grid > article',
    'section .grid > a',
    'section .grid > button',
    'section .grid > form',
].join(',');

const revealElements = () => {
    if (!mainElement.value) {
        return;
    }

    observer?.disconnect();

    const elements = [...new Set([...mainElement.value.querySelectorAll(revealSelector)])].filter(
        (element) => !element.closest('[data-scroll-ignore]'),
    );

    if (!('IntersectionObserver' in window)) {
        elements.forEach((element) => {
            element.classList.add('scroll-reveal', 'is-visible');
        });

        return;
    }

    const currentObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                currentObserver.unobserve(entry.target);
            });
        },
        {
            rootMargin: '0px 0px -8% 0px',
            threshold: 0.16,
        },
    );

    observer = currentObserver;

    elements.forEach((element, index) => {
        element.classList.add('scroll-reveal');
        element.style.setProperty('--scroll-delay', `${Math.min((index % 5) * 70, 280)}ms`);
        currentObserver.observe(element);
    });
};

onMounted(() => {
    nextTick(revealElements);

    removeFinishListener = router.on('finish', () => {
        nextTick(revealElements);
    });
});

onBeforeUnmount(() => {
    observer?.disconnect();
    removeFinishListener?.();
});
</script>

<template>
    <div class="min-h-screen bg-white text-zinc-900 antialiased">
        <SiteNavbar :can-login="canLogin" :can-register="canRegister" />
        <main ref="mainElement">
            <slot />
        </main>
        <SiteFooter />
    </div>
</template>
