<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const selectedTipe = ref(null);

const form = useForm({
    tipe: '',
});

const handleSelect = (tipe) => {
    selectedTipe.value = tipe;
    form.tipe = tipe;
};

const handleSubmit = () => {
    if (!selectedTipe.value) return;
    form.post(route('onboarding.tipe.store'));
};
</script>

<template>
    <Head title="Pilih Tipe Pendaftaran" />

    <main class="flex min-h-screen items-center justify-center p-4 sm:p-6">
        <div class="glass-panel w-full max-w-2xl p-6 sm:p-10 shadow-2xl space-y-8">
            <div class="text-center">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-forest-600/10 px-3 py-1 text-xs font-semibold text-forest-700 mb-3">
                    Langkah Terakhir
                </span>
                <h1 class="font-display text-3xl font-extrabold text-ink-900 tracking-tight">
                    Daftar sebagai apa?
                </h1>
                <p class="mt-2 text-sm text-ink-500">
                    Pilih salah satu untuk melanjutkan ke dashboard PKL Flow.
                </p>
            </div>

            <!-- 2 Card Pilihan Besar -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Card Individu -->
                <button
                    type="button"
                    @click="handleSelect('individu')"
                    :class="[
                        selectedTipe === 'individu'
                            ? 'border-2 border-forest-600 bg-forest-50 shadow-md ring-2 ring-forest-500/20'
                            : 'border border-ink-300/60 bg-white/80 hover:border-forest-500/50 hover:bg-white'
                    ]"
                    class="flex flex-col items-center text-center p-6 rounded-2xl transition duration-150 cursor-pointer group"
                >
                    <div
                        :class="[
                            selectedTipe === 'individu'
                                ? 'bg-forest-600 text-white'
                                : 'bg-forest-600/10 text-forest-700 group-hover:bg-forest-600 group-hover:text-white'
                        ]"
                        class="grid h-16 w-16 place-items-center rounded-2xl mb-4 transition"
                    >
                        <svg viewBox="0 0 24 24" class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-lg font-bold text-ink-900">Individu</h3>
                    <p class="mt-2 text-xs text-ink-500 leading-relaxed">
                        Mendaftar PKL sendiri tanpa kelompok.
                    </p>
                </button>

                <!-- Card Kelompok -->
                <button
                    type="button"
                    @click="handleSelect('kelompok')"
                    :class="[
                        selectedTipe === 'kelompok'
                            ? 'border-2 border-forest-600 bg-forest-50 shadow-md ring-2 ring-forest-500/20'
                            : 'border border-ink-300/60 bg-white/80 hover:border-forest-500/50 hover:bg-white'
                    ]"
                    class="flex flex-col items-center text-center p-6 rounded-2xl transition duration-150 cursor-pointer group"
                >
                    <div
                        :class="[
                            selectedTipe === 'kelompok'
                                ? 'bg-forest-600 text-white'
                                : 'bg-forest-600/10 text-forest-700 group-hover:bg-forest-600 group-hover:text-white'
                        ]"
                        class="grid h-16 w-16 place-items-center rounded-2xl mb-4 transition"
                    >
                        <svg viewBox="0 0 24 24" class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-lg font-bold text-ink-900">Kelompok</h3>
                    <p class="mt-2 text-xs text-ink-500 leading-relaxed">
                        Mendaftar PKL bersama anggota kelompok.
                    </p>
                </button>
            </div>

            <!-- Tombol Lanjutkan -->
            <div class="pt-2">
                <button
                    type="button"
                    @click="handleSubmit"
                    :disabled="!selectedTipe || form.processing"
                    :class="[
                        !selectedTipe || form.processing
                            ? 'opacity-40 cursor-not-allowed'
                            : 'hover:shadow-lg'
                    ]"
                    class="btn-primary w-full py-3.5 text-base font-semibold"
                >
                    Lanjutkan
                </button>
            </div>
        </div>
    </main>
</template>
