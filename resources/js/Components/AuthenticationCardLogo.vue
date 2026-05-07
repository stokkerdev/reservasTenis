<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <Link :href="'/'" class="inline-flex">
        <ApplicationLogo />
    </Link>
</template>
