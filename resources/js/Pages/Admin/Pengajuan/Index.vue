<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { getStatusLabel, getStatusBadgeClass, formatDate } from '@/utils/statusLabel';

const search = ref('');
const statusFilter = ref('');

const pengajuanList = ref([
    { id: 1, nama: 'Ahmad Rizky Pratama', email: 'ahmad.rizky@student.unmul.ac.id', instansi: 'Universitas Mulawarman', bidang: 'Aplikasi E-Government', posisi: 'Web Developer', tanggal: '2026-09-05', status: 'pending' },
    { id: 2, nama: 'Siti Nurhaliza', email: 'siti.nurhaliza@student.unmul.ac.id', instansi: 'Universitas Mulawarman', bidang: 'Infrastruktur Jaringan', posisi: 'Network Admin', tanggal: '2026-09-04', status: 'accepted' },
    { id: 3, nama: 'Muhammad Fadil', email: 'm.fadil@student.uin-sgu.ac.id', instansi: 'UIN Sultan Aji Muhammad Sulaiman', bidang: 'Sekretariat', posisi: 'Admin Data', tanggal: '2026-09-03', status: 'pending' },
    { id: 4, nama: 'Rina Wati', email: 'rina.wati@student.itk.ac.id', instansi: 'Institut Teknologi Kalimantan', bidang: 'Aplikasi E-Government', posisi: 'UI/UX Designer', tanggal: '2026-09-02', status: 'rejected' },
    { id: 5, nama: 'Dedi Kurniawan', email: 'dedi.k@student.unmul.ac.id', instansi: 'Universitas Mulawarman', bidang: 'Infrastruktur Jaringan', posisi: 'Tech Support', tanggal: '2026-09-01', status: 'accepted' },
    { id: 6, nama: 'Putri Ayu Lestari', email: 'putri.ayu@student.itk.ac.id', instansi: 'Institut Teknologi Kalimantan', bidang: 'Diseminasi Informasi', posisi: 'Content Creator', tanggal: '2026-08-30', status: 'pending' },
    { id: 7, nama: 'Bayu Firmansyah', email: 'bayu.f@student.unmul.ac.id', instansi: 'Universitas Mulawarman', bidang: 'Aplikasi E-Government', posisi: 'Mobile App Developer', tanggal: '2026-08-29', status: 'accepted' },
    { id: 8, nama: 'Anisa Putri Ramadhani', email: 'anisa.pr@student.uin-sgu.ac.id', instansi: 'UIN Sultan Aji Muhammad Sulaiman', bidang: 'Sekretariat', posisi: 'Admin Data', tanggal: '2026-08-28', status: 'rejected' },
]);

const filteredPengajuan = computed(() => {
    return pengajuanList.value.filter((item) => {
        const matchSearch = !search.value || item.nama.toLowerCase().includes(search.value.toLowerCase());
        const matchStatus = !statusFilter.value || item.status === statusFilter.value;
        return matchSearch && matchStatus;
    });
});

const handleAccept = (item) => {
    item.status = 'accepted';
};

const handleReject = (item) => {
    item.status = 'rejected';
};
</script>

<template>
    <Head title="Pengajuan Masuk" />
    <AppLayout title="Pengajuan Masuk">
        <div class="mb-6">
            <h2 class="font-display text-xl font-bold text-ink-900">Pengajuan PKL Masuk</h2>
            <p class="mt-1 text-sm text-ink-500">Tinjau dan proses pengajuan praktik kerja lapangan yang masuk.</p>
        </div>

        <!-- Search & Filter Bar -->
        <div class="glass-panel mb-6 p-4">
            <form @submit.prevent class="flex flex-col gap-3 md:flex-row md:items-center">
                <div class="flex-1">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari nama pelamar..."
                        class="field-input"
                    />
                </div>
                <div class="w-full md:w-52">
                    <select v-model="statusFilter" class="field-input">
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu</option>
                        <option value="accepted">Diterima</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Pengajuan Table -->
        <section class="glass-panel p-6 sm:p-8">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-left text-sm">
                    <thead class="border-b border-ink-300/35 text-xs uppercase tracking-wider text-ink-500">
                        <tr>
                            <th class="px-4 py-3 w-12">No</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Asal Instansi</th>
                            <th class="px-4 py-3">Bidang</th>
                            <th class="px-4 py-3">Posisi</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-300/20">
                        <tr v-for="(item, index) in filteredPengajuan" :key="item.id" class="transition hover:bg-forest-50/60">
                            <td class="px-4 py-4 text-ink-500">{{ index + 1 }}</td>
                            <td class="px-4 py-4">
                                <div class="font-semibold text-ink-900">{{ item.nama }}</div>
                                <div class="text-xs text-ink-500">{{ item.email }}</div>
                            </td>
                            <td class="px-4 py-4 text-ink-700">{{ item.instansi }}</td>
                            <td class="px-4 py-4 font-medium text-ink-800">{{ item.bidang }}</td>
                            <td class="px-4 py-4 text-ink-700">{{ item.posisi }}</td>
                            <td class="px-4 py-4 text-ink-500">{{ formatDate(item.tanggal) }}</td>
                            <td class="px-4 py-4">
                                <span :class="getStatusBadgeClass(item.status)" class="badge">
                                    {{ getStatusLabel(item.status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <Link :href="route('admin.pengajuan.show', item.id)" class="btn-secondary px-3 py-1.5 text-xs">
                                        Detail
                                    </Link>
                                    <template v-if="item.status === 'pending'">
                                        <button @click="handleAccept(item)" class="btn-success px-3 py-1.5 text-xs">
                                            Terima
                                        </button>
                                        <button @click="handleReject(item)" class="btn-danger px-3 py-1.5 text-xs">
                                            Tolak
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredPengajuan.length === 0">
                            <td colspan="8" class="px-4 py-10 text-center text-ink-500">
                                Tidak ada pengajuan yang ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </AppLayout>
</template>
