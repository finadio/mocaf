<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Mail, Lock, LogIn, ArrowLeft } from 'lucide-vue-next';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submitLogin() {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <div class="min-h-screen bg-forest flex flex-col justify-center items-center p-4 selection:bg-moodles selection:text-white relative overflow-hidden">
        <Head title="Masuk Panel Admin - Moodles" />

        <!-- Background Accents -->
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-olive/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-moodles/20 blur-3xl pointer-events-none"></div>

        <div class="w-full max-w-md relative z-10 space-y-6">
            <!-- Brand Header -->
            <div class="text-center space-y-3">
                <div class="flex justify-center">
                    <img
                        src="/assets/brand/moodles-logo.png"
                        alt="Moodles Logo"
                        class="h-16 w-auto object-contain drop-shadow-lg"
                    />
                </div>
                <h1 class="font-serif text-3xl font-black text-cream">
                    Moodles Administrator
                </h1>
                <p class="text-xs font-poppins text-cream/70 uppercase tracking-widest">
                    Pusat Pengelolaan Konten &amp; Pesanan
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-3xl p-8 shadow-2xl border border-cream space-y-6">
                <div class="border-b border-cream pb-3">
                    <h2 class="font-serif font-bold text-xl text-forest">Masuk ke Dashboard</h2>
                    <p class="text-xs text-darkbrown/60 mt-0.5">Silakan masukkan email dan kata sandi admin Anda.</p>
                </div>

                <form @submit.prevent="submitLogin" class="space-y-4">
                    <!-- Email Input -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Email Admin
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-darkbrown/40">
                                <Mail class="w-4 h-4" />
                            </div>
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="admin@moodles.id"
                                class="w-full pl-10 pr-4 py-3 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest transition-colors"
                            />
                        </div>
                        <p v-if="form.errors.email" class="text-xs text-red-600 font-medium">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-darkbrown/40">
                                <Lock class="w-4 h-4" />
                            </div>
                            <input
                                v-model="form.password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-3 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest transition-colors"
                            />
                        </div>
                        <p v-if="form.errors.password" class="text-xs text-red-600 font-medium">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-darkbrown/80 font-medium select-none">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="rounded-lg border-cream text-forest focus:ring-forest"
                            />
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-forest hover:bg-deep text-cream font-poppins font-bold text-sm shadow-card transition-all transform hover:-translate-y-0.5 focus:outline-none disabled:opacity-50"
                        >
                            <LogIn class="w-4 h-4 text-moodles" />
                            <span v-if="form.processing">Memverifikasi...</span>
                            <span v-else>Masuk ke Panel Admin</span>
                        </button>
                    </div>
                </form>

                <div class="pt-2 text-center border-t border-cream">
                    <p class="text-[11px] text-darkbrown/60">
                        Default login seeder: <code class="bg-cream/60 px-1 py-0.5 rounded text-forest font-semibold">admin@moodles.id</code> / <code class="bg-cream/60 px-1 py-0.5 rounded text-forest font-semibold">password123</code>
                    </p>
                </div>
            </div>

            <!-- Back to website link -->
            <div class="text-center">
                <Link
                    href="/"
                    class="inline-flex items-center gap-2 text-xs font-poppins font-semibold text-cream/70 hover:text-cream transition-colors"
                >
                    <ArrowLeft class="w-4 h-4 text-moodles" />
                    <span>Kembali ke Website Moodles</span>
                </Link>
            </div>
        </div>
    </div>
</template>

