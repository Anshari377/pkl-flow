<script setup>
import { ref, computed, watch } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    divisions: { type: Array, default: () => [] },
});

const page = usePage();
const authName = page.props.auth?.user?.name ?? '';

const initialDivision = new URLSearchParams(window.location.search).get('division') || '';

const form = useForm({
    division_id: initialDivision,
    start_date: '',
    end_date: '',
    tipe: 'individu',
    ketua: {
        name: authName,
        nim: '',
        school: '',
        major: '',
        phone: '',
    },
    document: null,
});

const members = ref([]);
const frontErrors = ref({});
const availability = ref(null);
const checking = ref(false);

const addMember = () => {
    members.value.push({ name: '', nim: '', school: '', major: '', phone: '' });
};

const removeMember = (index) => {
    members.value.splice(index, 1);
};

const selectedDivision = computed(() => {
    return props.divisions.find((d) => String(d.id) === String(form.division_id)) || null;
});

const formatReadableDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date.getTime())) return dateString;
    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

const checkAvailability = async () => {
    if (!form.division_id || !form.start_date || !form.end_date) return;

    frontErrors.value = {};
    availability.value = null;
    checking.value = true;

    try {
        const response = await window.axios.get(route('pengajuan.check-availability'), {
            params: {
                division_id: form.division_id,
                start_date: form.start_date,
                end_date: form.end_date,
            },
        });
        availability.value = response.data;
    } catch (error) {
        availability.value = null;
    } finally {
        checking.value = false;
    }
};

watch(
    () => [form.division_id, form.start_date, form.end_date],
    () => {
        availability.value = null;
        if (form.division_id && form.start_date && form.end_date && form.end_date >= form.start_date) {
            checkAvailability();
        }
    }
);

const invalidDateRange = computed(() => {
    return form.start_date && form.end_date && form.end_date < form.start_date;
});

const onFilePicked = (event) => {
    const file = event.target.files[0];
    if (!file) {
        form.document = null;
        return;
    }

    const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
    const isMaxFiveMb = file.size <= 5 * 1024 * 1024;

    if (!isPdf || !isMaxFiveMb) {
        frontErrors.value.document = !isPdf
            ? 'File harus berformat PDF.'
            : 'Ukuran file maksimal 5MB.';
        form.document = null;
        event.target.value = '';
        return;
    }

    frontErrors.value.document = null;
    form.document = file;
    event.target.value = '';
};

const fileName = computed(() => form.document?.name || '');

const stepUpload = computed(() => (form.tipe === 'kelompok' ? 4 : 3));

const validateFrontend = () => {
    const errors = {};

    if (!form.division_id) errors.division_id = 'Pilih bidang PKL terlebih dahulu.';
    if (!form.start_date) errors.start_date = 'Tanggal mulai wajib diisi.';
    if (!form.end_date) errors.end_date = 'Tanggal selesai wajib diisi.';
    if (invalidDateRange.value) errors.end_date = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';

    if (!form.tipe) errors.tipe = 'Pilih tipe pendaftaran.';

    if (!form.ketua.name.trim()) errors['ketua.name'] = 'Nama ketua wajib diisi.';
    if (!form.ketua.school.trim()) errors['ketua.school'] = 'Sekolah/Kampus ketua wajib diisi.';
    if (!form.ketua.major.trim()) errors['ketua.major'] = 'Jurusan ketua wajib diisi.';
    if (!form.ketua.phone.trim()) errors['ketua.phone'] = 'No HP ketua wajib diisi.';

    if (form.tipe === 'kelompok') {
        if (members.value.length === 0) {
            errors.members = 'Minimal tambahkan 1 anggota kelompok.';
        } else {
            const invalidIdx = members.value.findIndex(
                (m) => !m.name.trim() || !m.school.trim() || !m.major.trim() || !m.phone.trim()
            );
            if (invalidIdx >= 0) {
                errors.members = `Data anggota ke-${invalidIdx + 1} belum lengkap.`;
            }
        }
    }

    if (!form.document) {
        errors.document = 'Surat Pengantar / Proposal (PDF) wajib diunggah.';
    }

    frontErrors.value = errors;
    return Object.keys(errors).length === 0;
};

const canSubmit = computed(() => {
    return !checking.value && availability.value?.available !== false;
});

const submit = () => {
    if (!validateFrontend()) return;
    if (availability.value?.available === false) return;

    form.transform((data) => ({
        ...data,
        ketua: { ...data.ketua },
        members: members.value,
    })).post(route('pengajuan.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            members.value = [];
        },
    });
};
</script>

<template>
    <Head title="Pengajuan PKL" />
    <AppLayout title="Pengajuan PKL">
        <form @submit.prevent="submit" enctype="multipart/form-data" class="mx-auto max-w-5xl space-y-6">
            <!-- Server / global error -->
            <div
                v-if="form.errors.message"
                class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700"
            >
                {{ form.errors.message }}
            </div>

            <!-- 1. Pemilihan Bidang + 2. Rentang Tanggal -->
            <section class="glass-panel p-6 sm:p-8">
                <div class="mb-6 flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-forest-600/10 text-sm font-bold text-forest-700">1</span>
                    <div>
                        <h2 class="font-display text-lg font-bold text-ink-900">Bidang PKL &amp; Rentang Tanggal</h2>
                        <p class="text-xs text-ink-500">Pilih bidang yang kamu minati beserta periode pelaksanaan PKL.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div class="md:col-span-3">
                        <label class="field-label" for="division_id">Bidang PKL</label>
                        <select id="division_id" v-model="form.division_id" class="field-input">
                            <option value="">-- Pilih Bidang PKL --</option>
                            <option v-for="division in divisions" :key="division.id" :value="String(division.id)">
                                {{ division.nama }} — {{ division.instansi }} (kuota sisa {{ division.kuota_sisa }}
                                dari {{ division.kuota }})
                            </option>
                        </select>
                        <p v-if="frontErrors.division_id || form.errors.division_id" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ frontErrors.division_id || form.errors.division_id }}
                        </p>
                    </div>

                    <div>
                        <label class="field-label" for="start_date">Tanggal Mulai</label>
                        <input id="start_date" v-model="form.start_date" type="date" class="field-input" />
                        <p v-if="frontErrors.start_date || form.errors.start_date" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ frontErrors.start_date || form.errors.start_date }}
                        </p>
                    </div>

                    <div>
                        <label class="field-label" for="end_date">Tanggal Selesai</label>
                        <input id="end_date" v-model="form.end_date" type="date" class="field-input" />
                        <p v-if="frontErrors.end_date || form.errors.end_date" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ frontErrors.end_date || form.errors.end_date }}
                        </p>
                    </div>

                    <!-- Real-time availability -->
                    <div class="md:col-span-3">
                        <div v-if="checking" class="flex items-center gap-2 rounded-xl border border-ink-300/40 bg-ink-50 px-4 py-3 text-sm text-ink-600">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 12a9 9 0 11-6.219-8.56" stroke-linecap="round"/>
                            </svg>
                            Memeriksa ketersediaan kuota...
                        </div>

                        <div
                            v-else-if="availability?.available === true"
                            class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
                        >
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Kuota tersedia untuk periode
                            {{ formatReadableDate(form.start_date) }} – {{ formatReadableDate(form.end_date) }}.
                        </div>

                        <div
                            v-else-if="availability?.available === false"
                            class="flex items-start gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700"
                        >
                            <svg viewBox="0 0 24 24" class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            <span>
                                {{ availability.message }} untuk {{ selectedDivision?.nama ?? 'bidang terpilih' }}.
                                Periode berikutnya tersedia mulai
                                <strong>{{ formatReadableDate(availability.next_available_date) }}</strong>.
                                Ubah rentang tanggal atau bidang untuk melanjutkan.
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. Tipe Pendaftaran + 4. Data Ketua -->
            <section class="glass-panel p-6 sm:p-8">
                <div class="mb-6 flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-forest-600/10 text-sm font-bold text-forest-700">2</span>
                    <div>
                        <h2 class="font-display text-lg font-bold text-ink-900">Tipe Pendaftaran &amp; Data Ketua</h2>
                        <p class="text-xs text-ink-500">Pilih tipe pendaftaran dan lengkapi data ketua (pemohon utama).</p>
                    </div>
                </div>

                <div class="mb-6 flex gap-6">
                    <label class="flex cursor-pointer items-center gap-2.5 text-sm font-medium text-ink-800">
                        <input v-model="form.tipe" type="radio" value="individu" class="h-4 w-4 accent-forest-600" />
                        Individu
                    </label>
                    <label class="flex cursor-pointer items-center gap-2.5 text-sm font-medium text-ink-800">
                        <input v-model="form.tipe" type="radio" value="kelompok" class="h-4 w-4 accent-forest-600" />
                        Kelompok
                    </label>
                </div>
                <p v-if="frontErrors.tipe || form.errors.tipe" class="mb-4 -mt-3 text-xs font-medium text-red-600">
                    {{ frontErrors.tipe || form.errors.tipe }}
                </p>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="field-label" for="ketua.name">Nama Ketua</label>
                        <input id="ketua.name" v-model="form.ketua.name" type="text" class="field-input" />
                        <p v-if="frontErrors['ketua.name'] || form.errors['ketua.name']" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ frontErrors['ketua.name'] || form.errors['ketua.name'] }}
                        </p>
                    </div>
                    <div>
                        <label class="field-label" for="ketua.nim">NIM / NISN <span class="text-ink-400">(opsional)</span></label>
                        <input id="ketua.nim" v-model="form.ketua.nim" type="text" class="field-input" />
                        <p v-if="form.errors['ketua.nim']" class="mt-1.5 text-xs font-medium text-red-600">{{ form.errors['ketua.nim'] }}</p>
                    </div>
                    <div>
                        <label class="field-label" for="ketua.school">Sekolah / Kampus</label>
                        <input id="ketua.school" v-model="form.ketua.school" type="text" class="field-input" />
                        <p v-if="frontErrors['ketua.school'] || form.errors['ketua.school']" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ frontErrors['ketua.school'] || form.errors['ketua.school'] }}
                        </p>
                    </div>
                    <div>
                        <label class="field-label" for="ketua.major">Jurusan</label>
                        <input id="ketua.major" v-model="form.ketua.major" type="text" class="field-input" />
                        <p v-if="frontErrors['ketua.major'] || form.errors['ketua.major']" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ frontErrors['ketua.major'] || form.errors['ketua.major'] }}
                        </p>
                    </div>
                    <div>
                        <label class="field-label" for="ketua.phone">No HP</label>
                        <input id="ketua.phone" v-model="form.ketua.phone" type="text" inputmode="tel" class="field-input" />
                        <p v-if="frontErrors['ketua.phone'] || form.errors['ketua.phone']" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ frontErrors['ketua.phone'] || form.errors['ketua.phone'] }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- 5. Data Anggota Kelompok -->
            <section v-if="form.tipe === 'kelompok'" class="glass-panel p-6 sm:p-8">
                <div class="mb-6 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 place-items-center rounded-xl bg-forest-600/10 text-sm font-bold text-forest-700">3</span>
                        <div>
                            <h2 class="font-display text-lg font-bold text-ink-900">Data Anggota Kelompok</h2>
                            <p class="text-xs text-ink-500">Tambahkan anggota lain selain ketua kelompok.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-secondary shrink-0" @click="addMember">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        + Tambah Anggota
                    </button>
                </div>

                <p v-if="frontErrors.members || form.errors.members" class="mb-4 rounded-lg bg-red-50 px-4 py-2.5 text-xs font-medium text-red-600">
                    {{ frontErrors.members || form.errors.members }}
                </p>
                <p v-if="form.errors['members.0.name'] || form.errors['members.0.school'] || form.errors['members.0.major'] || form.errors['members.0.phone']" class="mb-4 rounded-lg bg-red-50 px-4 py-2.5 text-xs font-medium text-red-600">
                    Data anggota kelompok belum lengkap atau tidak valid.
                </p>

                <div v-if="members.length === 0" class="rounded-xl border border-dashed border-ink-300/60 py-10 text-center text-sm text-ink-500">
                    Belum ada anggota. Klik "+ Tambah Anggota" untuk menambahkan baris.
                </div>

                <div
                    v-for="(member, index) in members"
                    :key="index"
                    class="mb-6 rounded-xl border border-ink-300/40 bg-white/60 p-5"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-sm font-bold text-ink-900">Anggota {{ index + 1 }}</p>
                        <button type="button" class="inline-flex items-center gap-1 text-xs font-semibold text-status-danger hover:underline" @click="removeMember(index)">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                            </svg>
                            Hapus
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="field-label" :for="`members.${index}.name`">Nama Lengkap</label>
                            <input :id="`members.${index}.name`" v-model="member.name" type="text" class="field-input" />
                            <p v-if="form.errors[`members.${index}.name`]" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors[`members.${index}.name`] }}
                            </p>
                        </div>
                        <div>
                            <label class="field-label" :for="`members.${index}.nim`">NIM / NISN <span class="text-ink-400">(opsional)</span></label>
                            <input :id="`members.${index}.nim`" v-model="member.nim" type="text" class="field-input" />
                            <p v-if="form.errors[`members.${index}.nim`]" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors[`members.${index}.nim`] }}
                            </p>
                        </div>
                        <div>
                            <label class="field-label" :for="`members.${index}.school`">Sekolah / Kampus</label>
                            <input :id="`members.${index}.school`" v-model="member.school" type="text" class="field-input" />
                            <p v-if="form.errors[`members.${index}.school`]" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors[`members.${index}.school`] }}
                            </p>
                        </div>
                        <div>
                            <label class="field-label" :for="`members.${index}.major`">Jurusan</label>
                            <input :id="`members.${index}.major`" v-model="member.major" type="text" class="field-input" />
                            <p v-if="form.errors[`members.${index}.major`]" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors[`members.${index}.major`] }}
                            </p>
                        </div>
                        <div>
                            <label class="field-label" :for="`members.${index}.phone`">No HP</label>
                            <input :id="`members.${index}.phone`" v-model="member.phone" type="text" inputmode="tel" class="field-input" />
                            <p v-if="form.errors[`members.${index}.phone`]" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors[`members.${index}.phone`] }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 6. Upload Berkas -->
            <section class="glass-panel p-6 sm:p-8">
                <div class="mb-6 flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-forest-600/10 text-sm font-bold text-forest-700">{{ stepUpload }}</span>
                    <div>
                        <h2 class="font-display text-lg font-bold text-ink-900">Upload Berkas</h2>
                        <p class="text-xs text-ink-500">Unggah surat pengantar / proposal dalam satu file PDF.</p>
                    </div>
                </div>

                <label class="field-label" for="document">Surat Pengantar / Proposal (PDF)</label>
                <div class="flex flex-wrap items-center gap-3">
                    <label
                        for="document"
                        class="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-ink-300 bg-white px-4 py-2.5 text-sm font-medium text-ink-700 transition hover:bg-surface focus:outline-none focus:ring-2 focus:ring-forest-500 focus:ring-offset-2"
                    >
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        {{ fileName ? 'Ganti File' : 'Pilih File PDF' }}
                    </label>
                    <input id="document" type="file" accept=".pdf,application/pdf" class="hidden" @change="onFilePicked" />
                    <span v-if="fileName" class="text-sm font-medium text-forest-700">✓ {{ fileName }}</span>
                </div>
                <p class="mt-1.5 text-xs text-ink-500">Format PDF, maksimal 5MB.</p>
                <p v-if="frontErrors.document || form.errors.document" class="mt-1.5 text-xs font-medium text-red-600">
                    {{ frontErrors.document || form.errors.document }}
                </p>
            </section>

            <!-- Submit -->
            <section class="glass-panel p-6 sm:p-8">
                <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-between">
                    <p v-if="form.tipe === 'kelompok'" class="text-xs text-ink-500">
                        Total slot dibutuhkan: ketua (1) + anggota ({{ members.length }}) = <strong>{{ 1 + members.length }}</strong>
                    </p>
                    <p v-else class="text-xs text-ink-500">Total slot dibutuhkan: 1 orang.</p>

                    <div class="flex flex-col items-center gap-2 sm:items-end">
                        <button
                            type="submit"
                            class="btn-primary px-8 py-3 disabled:cursor-not-allowed disabled:opacity-50"
                            :disabled="form.processing || !canSubmit"
                        >
                            {{ form.processing ? 'Mengirim...' : 'Kirim Pengajuan' }}
                        </button>
                        <p v-if="availability?.available === false" class="text-center text-xs font-semibold text-red-600 sm:text-right">
                            Kuota tidak tersedia untuk periode ini. Ubah bidang atau rentang tanggal untuk mengaktifkan tombol kirim.
                        </p>
                    </div>
                </div>
            </section>
        </form>
    </AppLayout>
</template>