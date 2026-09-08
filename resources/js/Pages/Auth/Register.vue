<script setup>
import { onMounted } from 'vue';

onMounted(() => {
    window.location.href = route('login');
});
</script>

<template>
    <div class="grid min-h-screen place-items-center bg-surface p-6 text-sm text-ink-500">
        Mengarahkan ke halaman login...
    </div>
</template>
