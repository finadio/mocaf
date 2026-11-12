<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';
import { Phone, ArrowUpRight } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        phoneNumber?: string;
        message?: string;
        label?: string;
        size?: 'sm' | 'md' | 'lg';
        variant?: 'primary' | 'secondary' | 'outline';
    }>(),
    {
        label: 'Pesan via WhatsApp',
        size: 'md',
        variant: 'primary',
    }
);

const page = usePage<PageProps>();

const finalPhone = computed(() => {
    return props.phoneNumber || page.props.site_settings?.whatsapp_number || '6281234567890';
});

const defaultMessage = computed(() => {
    return props.message || 'Halo Admin Moodles Banjarnegara, saya ingin memesan mi instan MOCAF.';
});

const waLink = computed(() => {
    return `https://wa.me/${finalPhone.value.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(defaultMessage.value)}`;
});
</script>

<template>
    <a
        :href="waLink"
        target="_blank"
        rel="noopener noreferrer"
        :class="[
            'inline-flex items-center justify-center gap-2 font-medium rounded-full transition-all duration-300 shadow-sm focus:outline-none transform hover:-translate-y-0.5',
            size === 'sm' ? 'px-3.5 py-1.5 text-xs' : size === 'lg' ? 'px-7 py-3.5 text-base shadow-md' : 'px-5 py-2.5 text-sm',
            variant === 'primary' ? 'bg-moodles text-white hover:bg-moodles-hover shadow-warm' :
            variant === 'secondary' ? 'bg-forest text-cream hover:bg-deep' :
            'border-2 border-forest text-forest hover:bg-forest hover:text-white'
        ]"
    >
        <Phone :class="size === 'sm' ? 'w-3.5 h-3.5' : 'w-4 h-4'" />
        <span>{{ label }}</span>
        <ArrowUpRight :class="size === 'sm' ? 'w-3 h-3' : 'w-4 h-4'" />
    </a>
</template>
