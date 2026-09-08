<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Info } from 'lucide-vue-next';

const props = defineProps({
    user: { type: Object, default: () => ({}) },
});

const page = usePage();
const avatarFailed = ref(false);

const currentUser = computed(() => {
    return page.props.auth?.user || props.user || {};
});

const userInitials = computed(() => {
    const name = currentUser.value.name ?? 'M A';
    return name
        .split(' ')
        .filter(Boolean)
        .map((w) => w[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();
});
</script>

<template>
    <Head title="Profile Saya" />
    <AppLayout title="Profile Saya">
        <div class="glass-panel mx-auto max-w-2xl rounded-3xl p-6 sm:p-10">
            <div class="mb-8">
                <h2 class="font-display text-2xl font-bold text-ink-900">Profile Saya</h2>
                <p class="mt-1 text-sm text-ink-500">Informasi akun utama Anda yang terhubung dengan Google OAuth.</p>
            </div>

            <!-- Read-Only Profile Card Content -->
            <div class="flex flex-col items-center text-center sm:flex-row sm:items-center sm:text-left gap-6 sm:gap-8 rounded-2xl bg-white/50 p-6 sm:p-8 border border-ink-300/20 shadow-sm">
                <!-- Large Avatar with Fallback Initials -->
                <div class="relative grid h-24 w-24 sm:h-28 sm:w-28 place-items-center rounded-full bg-forest-700 text-3xl font-bold text-white shadow-md overflow-hidden shrink-0 ring-4 ring-forest-600/10">
                    <img
                        v-if="currentUser.avatar && !avatarFailed"
                        :src="currentUser.avatar"
                        :alt="currentUser.name || 'User Avatar'"
                        class="h-full w-full object-cover"
                        @error="avatarFailed = true"
                    />
                    <span v-else>
                        {{ userInitials }}
                    </span>
                </div>

                <!-- User Name & Email Info -->
                <div class="flex flex-col min-w-0">
                    <h3 class="font-display text-xl sm:text-2xl font-bold text-ink-900 truncate">
                        {{ currentUser.name || 'Mahasiswa' }}
                    </h3>
                    <div class="mt-2 flex items-center justify-center sm:justify-start gap-2 text-sm text-ink-600">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 text-forest-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        <span class="truncate font-medium">{{ currentUser.email || '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Info Note Banner -->
            <div class="mt-6 flex items-start gap-3 rounded-2xl bg-blue-100/60 border border-blue-300/60 p-4 text-xs sm:text-sm text-blue-700">
                <Info class="h-5 w-5 shrink-0 text-blue-600 mt-0.5" />
                <p class="leading-relaxed">
                    Data ini diambil otomatis dari akun Google Anda. Untuk mengubah nama atau foto, silakan perbarui melalui pengaturan akun Google Anda.
                </p>
            </div>
        </div>
    </AppLayout>
</template>
