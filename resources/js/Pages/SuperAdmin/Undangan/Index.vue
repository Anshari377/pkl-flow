<script setup>
import SuperAdminLayout from '@/Layouts/SuperAdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';

const instansiOptions = ref([
    'Dinas Komunikasi dan Informatika Kaltim',
    'Dinas Pendidikan dan Kebudayaan Kaltim',
    'RSUD Abdul Wahab Sjahranie',
    'Bankaltimtara',
    'PT Pegadaian Cabang Samarinda',
    'Dinas Kesehatan Kaltim',
    'PT Telkom Indonesia Witel Samarinda',
]);

const form = reactive({
    instansi: instansiOptions.value[0],
    email: '',
});

const showSuccess = ref(false);

const riwayatUndangan = ref([
    { id: 1, email: 'budi.santoso@diskominfo.kaltim.go.id', instansi: 'Dinas Komunikasi dan Informatika Kaltim', status: 'claimed', tanggal: '2026-09-05 09:30:00' },
    { id: 2, email: 'siti.aminah@diskominfo.kaltim.go.id', instansi: 'Dinas Komunikasi dan Informatika Kaltim', status: 'claimed', tanggal: '2026-09-04 14:12:00' },
    { id: 3, email: 'rudi.hartono@diskominfo.kaltim.go.id', instansi: 'Dinas Komunikasi dan Informatika Kaltim', status: 'claimed', tanggal: '2026-09-04 14:12:00' },
    { id: 4, email: 'nur.aida@rsudaws.co.id', instansi: 'RSUD Abdul Wahab Sjahranie', status: 'pending', tanggal: '2026-09-08 08:45:00' },
    { id: 5, email: 'yoga.p@bankaltimtara.co.id', instansi: 'Bankaltimtara', status: 'pending', tanggal: '2026-09-07 16:20:00' },
    { id: 6, email: 'lina.m@disdik.kaltimprov.go.id', instansi: 'Dinas Pendidikan dan Kebudayaan Kaltim', status: 'pending', tanggal: '2026-09-06 11:05:00' },
]);

const statusBadge = (status) => {
    if (status === 'pending') return 'badge-warning';
    if (status === 'claimed') return 'badge-success';
    return 'badge-danger';
};

const statusLabel = (status) => {
    if (status === 'pending') return 'Menunggu Klaim';
    if (status === 'claimed') return 'Sudah Diklaim';
    return 'Dibatalkan';
};

const submitUndangan = () => {
    if (!form.email) return;
    riwayatUndangan.value.unshift({
        id: riwayatUndangan.value.length + 1,
        email: form.email,
        instansi: form.instansi,
        status: 'pending',
        tanggal: new Date().toISOString().slice(0, 16).replace('T', ' '),
    });
    form.email = '';
    showSuccess.value = true;
    setTimeout(() => { showSuccess.value = false; }, 3000);
};

const batalkanUndangan = (item) => {
    item.status = 'cancelled';
};
</script>

<template>
    <Head title="Undangan Admin Instansi" />
    <SuperAdminLayout title="Undangan Admin Instansi">
        <div class="mb-6">
            <h2 class="font-display text-xl font-bold text-ink-900">Undang Admin Instansi</h2>
            <p class="mt-1 text-sm text-ink-500">Kirim undangan ke calon admin untuk mengelola portal instansi.</p>
        </div>

        <!-- Success Alert -->
        <div v-if="showSuccess" class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 shadow-sm">
            Undangan berhasil dikirim ke {{ form.email }}! (Demo — data belum tersimpan)
        </div>

        <!-- Form Undangan -->
        <div class="glass-panel mb-6 p-6 sm:p-8">
            <h3 class="mb-4 font-display text-base font-bold text-ink-900">Kirim Undangan Baru</h3>
            <form @submit.prevent="submitUndangan" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="field-label">Pilih Instansi</label>
                    <select v-model="form.instansi" class="field-input">
                        <option v-for="inst in instansiOptions" :key="inst" :value="inst">{{ inst }}</option>
                    </select>
                </div>
                <div>
                    <label class="field-label">Email Calon Admin</label>
                    <input v-model="form.email" type="email" required placeholder="calon.admin@instansi.co.id" class="field-input" />
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" /><polyline points="22,6 12,13 2,6" />
                        </svg>
                        Kirim Undangan
                    </button>
                </div>
            </form>
        </div>

        <!-- Riwayat Undangan -->
        <section class="glass-panel p-6 sm:p-8">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-display text-base font-bold text-ink-900">Riwayat Undangan</h3>
                <span class="badge badge-info">{{ riwayatUndangan.length }} Undangan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="border-b border-ink-300/35 text-xs uppercase tracking-wider text-ink-500">
                        <tr>
                            <th class="px-4 py-3 w-12">No</th>
                            <th class="px-4 py-3">Email Calon Admin</th>
                            <th class="px-4 py-3">Instansi Tujuan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal Dikirim</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-300/20">
                        <tr v-for="(item, index) in riwayatUndangan" :key="item.id" class="transition hover:bg-forest-50/60">
                            <td class="px-4 py-4 text-ink-500">{{ index + 1 }}</td>
                            <td class="px-4 py-4 font-semibold text-ink-900">{{ item.email }}</td>
                            <td class="px-4 py-4 text-ink-700">{{ item.instansi }}</td>
                            <td class="px-4 py-4">
                                <span :class="statusBadge(item.status)" class="badge">
                                    {{ statusLabel(item.status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-ink-500 whitespace-nowrap">{{ item.tanggal }}</td>
                            <td class="px-4 py-4 text-right">
                                <button v-if="item.status === 'pending'" @click="batalkanUndangan(item)" class="btn-danger px-3 py-1.5 text-xs">
                                    Batalkan
                                </button>
                                <span v-else class="text-xs text-ink-400">-</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </SuperAdminLayout>
</template>
