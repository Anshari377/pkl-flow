<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    kelompok: { type: Object, default: () => ({ nama_kelompok: 'Kelompok PKL Diskominfo' }) },
    anggota: { type: Array, default: () => [] },
});

const showModal = ref(false);

const form = useForm({
    nama: '',
    nim: '',
});

const submitAnggota = () => {
    form.post(route('kelompok.anggota.store'), {
        onSuccess: () => {
            form.reset();
            showModal.value = false;
        },
    });
};
</script>

<template>
    <Head title="Kelompok Saya" />
    <AppLayout title="Kelompok Saya">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Kiri (.glass-card, sempit) -->
            <div class="glass-card p-6 space-y-4 h-fit">
                <h2 class="font-display text-lg font-bold text-ink-900 border-b border-ink-300/30 pb-3">
                    Informasi Kelompok
                </h2>
                <div>
                    <p class="text-xs font-medium text-ink-500 uppercase tracking-wider">Nama Kelompok</p>
                    <p class="mt-1 font-display text-base font-bold text-ink-900">
                        {{ kelompok?.nama_kelompok || 'Kelompok PKL 01' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-ink-500 uppercase tracking-wider">Total Anggota</p>
                    <p class="mt-1 font-display text-2xl font-bold text-forest-700">
                        {{ (anggota.length || 0) + 1 }} Mahasiswa
                    </p>
                </div>
            </div>

            <!-- Kanan (.glass-panel, lebar) -->
            <div class="glass-panel p-6 sm:p-8 lg:col-span-2 relative flex flex-col min-h-[380px]">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="font-display text-xl font-bold text-ink-900">Daftar Anggota</h2>
                        <p class="mt-0.5 text-sm text-ink-500">Kelola anggota kelompok PKL kamu.</p>
                    </div>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-ink-300/30 text-xs uppercase tracking-wider text-ink-500">
                            <tr>
                                <th class="px-4 py-3 w-12">No</th>
                                <th class="px-4 py-3">Nama Lengkap</th>
                                <th class="px-4 py-3">NIM</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink-300/20">
                            <tr v-for="(item, idx) in anggota" :key="item.id || idx" class="transition hover:bg-forest-50/50">
                                <td class="px-4 py-3.5 text-ink-500">{{ idx + 1 }}</td>
                                <td class="px-4 py-3.5 font-medium text-ink-900">{{ item.nama }}</td>
                                <td class="px-4 py-3.5 text-ink-700">{{ item.nim }}</td>
                            </tr>
                            <tr v-if="anggota.length === 0">
                                <td colspan="3" class="px-4 py-8 text-center text-ink-500">
                                    Belum ada anggota lain yang ditambahkan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tombol + Tambah Anggota di kanan bawah -->
                <div class="mt-6 flex justify-end">
                    <button @click="showModal = true" class="btn-primary">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        + Tambah Anggota
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Form Tambah Anggota -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            <div class="glass-panel w-full max-w-md bg-white p-6 shadow-2xl">
                <h3 class="font-display text-lg font-bold text-ink-900 mb-4">Tambah Anggota Kelompok</h3>
                <form @submit.prevent="submitAnggota" class="space-y-4">
                    <div>
                        <label class="field-label">Nama Anggota</label>
                        <input v-model="form.nama" type="text" required placeholder="Masukkan Nama Lengkap" class="field-input" />
                    </div>
                    <div>
                        <label class="field-label">NIM</label>
                        <input v-model="form.nim" type="text" required placeholder="Masukkan NIM" class="field-input" />
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showModal = false" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary" :disabled="form.processing">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
