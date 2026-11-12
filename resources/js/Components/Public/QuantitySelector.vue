<script setup lang="ts">
import { Minus, Plus } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        modelValue: number;
        min?: number;
        max?: number;
        size?: 'sm' | 'md' | 'lg';
    }>(),
    {
        min: 1,
        max: 99,
        size: 'md',
    }
);

const emit = defineEmits<{
    (e: 'update:modelValue', val: number): void;
    (e: 'change', val: number): void;
}>();

function decrease() {
    if (props.modelValue > props.min) {
        const newVal = props.modelValue - 1;
        emit('update:modelValue', newVal);
        emit('change', newVal);
    }
}

function increase() {
    if (props.modelValue < props.max) {
        const newVal = props.modelValue + 1;
        emit('update:modelValue', newVal);
        emit('change', newVal);
    }
}
</script>

<template>
    <div
        :class="[
            'inline-flex items-center rounded-full border border-cream bg-white shadow-sm overflow-hidden',
            size === 'sm' ? 'h-8 px-1 text-xs' : size === 'lg' ? 'h-12 px-3 text-base' : 'h-10 px-2 text-sm'
        ]"
    >
        <button
            type="button"
            @click.prevent="decrease"
            :disabled="modelValue <= min"
            :class="[
                'flex items-center justify-center rounded-full text-darkbrown hover:bg-cream/60 transition-colors focus:outline-none disabled:opacity-30 disabled:cursor-not-allowed',
                size === 'sm' ? 'w-6 h-6' : size === 'lg' ? 'w-9 h-9' : 'w-7 h-7'
            ]"
            title="Kurangi Jumlah"
        >
            <Minus :class="size === 'sm' ? 'w-3 h-3' : 'w-4 h-4'" />
        </button>

        <span
            :class="[
                'font-poppins font-semibold text-center select-none text-forest',
                size === 'sm' ? 'w-7' : size === 'lg' ? 'w-12' : 'w-9'
            ]"
        >
            {{ modelValue }}
        </span>

        <button
            type="button"
            @click.prevent="increase"
            :disabled="modelValue >= max"
            :class="[
                'flex items-center justify-center rounded-full text-darkbrown hover:bg-cream/60 transition-colors focus:outline-none disabled:opacity-30 disabled:cursor-not-allowed',
                size === 'sm' ? 'w-6 h-6' : size === 'lg' ? 'w-9 h-9' : 'w-7 h-7'
            ]"
            title="Tambah Jumlah"
        >
            <Plus :class="size === 'sm' ? 'w-3 h-3' : 'w-4 h-4'" />
        </button>
    </div>
</template>

