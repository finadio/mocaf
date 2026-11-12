<script setup lang="ts">
withDefaults(
    defineProps<{
        variant?: 'forest' | 'olive' | 'moodles' | 'cream' | 'gray';
        size?: 'sm' | 'md';
    }>(),
    {
        variant: 'forest',
        size: 'sm',
    }
);
</script>

<template>
    <span
        :class="[
            'inline-flex items-center gap-1.5 font-poppins font-semibold uppercase tracking-wider rounded-full shadow-sm',
            size === 'sm' ? 'px-2.5 py-0.5 text-[11px]' : 'px-3.5 py-1 text-xs',
            variant === 'forest' ? 'bg-forest text-cream' :
            variant === 'olive' ? 'bg-softgreen text-forest border border-forest/10' :
            variant === 'moodles' ? 'bg-moodles text-white' :
            variant === 'cream' ? 'bg-cream text-darkbrown' :
            'bg-gray-100 text-gray-700'
        ]"
    >
        <slot />
    </span>
</template>

