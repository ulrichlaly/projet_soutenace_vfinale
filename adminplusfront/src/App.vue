<template>
  <router-view />
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

onMounted(() => {
  // Vérifier si l'utilisateur est connecté au chargement
  const token = localStorage.getItem('token');
  const currentPath = window.location.pathname;
  
  if (!token && currentPath !== '/login') {
    router.push('/login');
  } else if (token && currentPath === '/login') {
    router.push('/dashboard');
  }
});
</script>
