<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    divisions: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ search: '', instansi: '', bidang: '' }) },
});

const search = ref(props.filters.search || '');
const instansi = ref(props.filters.instansi || '');
const bidang = ref(props.filters.bidang || '');

const handleFilter = () => {
    router.get(route('lowongan.index'), {
        search: search.value,
        instansi: instansi.value,
        bidang: bidang.value,
    }, { preserveState: true });
};
</script>

<template>
    <Head title="Lowongan PKL" />
    <AppLayout title="Lowongan PKL">
        <!-- Search & Filter Bar in One Row -->
        <div class="glass-panel mb-6 p-4">
            <form @submit.prevent="handleFilter" class="flex flex-col gap-3 md:flex-row md:items-center">
                <div class="flex-1">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Cari posisi atau nama instansi..."
                        class="field-input"
                    />
                </div>
                <div class="w-full md:w-52">
                    <select v-model="instansi" class="field-input">
                        <option value="">Semua Instansi</option>
                        <option value="Dinas Komunikasi dan Informatika">Dinas Komunikasi & Informatika</option>
                    </select>
                </div>
                <div class="w-full md:w-52">
                    <select v-model="bidang" class="field-input">
                        <option value="">Semua Bidang</option>
                        <option value="Aplikasi dan Layanan E-Government">Aplikasi dan Layanan E-Government</option>
                        <option value="Sekretariat">Sekretariat</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary shrink-0">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                    </svg>
                    Filter
                </button>
            </form>
        </div>

        <!-- List Card per Lowongan -->
        <div class="grid gap-4">
            <div v-for="item in divisions" :key="item.id">
                <Link
                    :href="route('lowongan.show', item.slug)"
                    class="glass-card flex items-center justify-between p-5 transition hover:border-forest-500/40 hover:shadow-md block group"
                >
                    <div class="flex items-center gap-4">
                        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-forest-600/10 text-forest-700 group-hover:bg-forest-600 group-hover:text-white transition">
                            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display text-base font-bold text-ink-900 group-hover:text-forest-700 transition">
                                {{ item.nama }}
                            </h3>
                            <p class="mt-0.5 text-sm text-ink-500">{{ item.instansi }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="badge badge-info font-medium">
                            Kuota {{ item.terisi_total ?? 0 }}/{{ item.kuota_total ?? 0 }}
                        </span>
                        <svg viewBox="0 0 24 24" class="h-5 w-5 text-ink-300 group-hover:text-forest-600 transition" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </Link>
            </div>

            <div v-if="divisions.length === 0" class="glass-panel p-12 text-center text-ink-500">
                Tidak ada lowongan PKL yang ditemukan.
            </div>
        </div>
    </AppLayout>
</template>
