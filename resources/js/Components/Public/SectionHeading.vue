<script setup lang="ts">
defineProps<{
    tag?: string;
    title: string;
    subtitle?: string;
    align?: 'left' | 'center' | 'right';
    theme?: 'light' | 'dark';
    compact?: boolean;
}>();
</script>

<template>
    <div :class="[
        'space-y-2.5',
        compact ? 'mb-5' : 'mb-10',
        align === 'center' ? 'text-center mx-auto max-w-2xl' : align === 'right' ? 'text-right' : 'text-left max-w-2xl'
    ]">
        <!-- Badge Tag -->
        <div v-if="tag" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-poppins font-semibold uppercase tracking-wider"
            :class="theme === 'dark' ? 'bg-olive/20 text-cream border border-olive/30' : 'bg-softgreen text-forest border border-forest/10'">
            <span class="w-1.5 h-1.5 rounded-full bg-moodles"></span>
            <span>{{ tag }}</span>
        </div>

        <!-- Headline (Poppins) -->
        <h2 :class="[
            'font-poppins text-2xl sm:text-3xl font-bold leading-[1.3] tracking-tight',
            theme === 'dark' ? 'text-cream' : 'text-forest'
        ]">
            {{ title }}
        </h2>

        <!-- Subtitle / Pengantar -->
        <p v-if="subtitle" :class="[
            'font-sans text-sm sm:text-base leading-relaxed',
            theme === 'dark' ? 'text-cream/85' : 'text-darkbrown/75'
        ]">
            {{ subtitle }}
        </p>
    </div>
</template>
