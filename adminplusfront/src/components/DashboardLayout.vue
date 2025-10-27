<template>
  <div class="flex h-screen bg-gray-50">
    <Sidebar :is-open="sidebarOpen" @toggle="toggleSidebar" @logout="showLogoutModal" />
    <div class="flex-1 flex flex-col lg:ml-64">
      <Header :page-title="currentPageTitle" @toggle-sidebar="toggleSidebar" />
      <main class="flex-1 overflow-y-auto pt-20 p-6">
        <router-view />
      </main>
    </div>
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
      @click="toggleSidebar"
    ></div>

    <!-- Modal de confirmation -->
    <ConfirmModal
      :show="isLogoutModalOpen"
      title="Confirmation de déconnexion"
      message="Êtes-vous sûr de vouloir vous déconnecter ?"
      confirm-text="Oui, me déconnecter"
      cancel-text="Annuler"
      @confirm="handleLogout"
      @cancel="isLogoutModalOpen = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '../services/api/axios';
import Sidebar from './Sidebar.vue';
import Header from './Header.vue';
import ConfirmModal from './ConfirmModal.vue';

const router = useRouter();
const route = useRoute();
const sidebarOpen = ref(false);
const isLogoutModalOpen = ref(false);

const showLogoutModal = () => {
  isLogoutModalOpen.value = true;
};

const handleLogout = async () => {
  try {
    await api.post('/logout');
  } catch (error) {
    console.error('Erreur lors de la déconnexion:', error);
  } finally {
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    isLogoutModalOpen.value = false;
    router.push('/login');
  }
};

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const currentPageTitle = computed(() => {
  const titles: Record<string, string> = {
    Dashboard: 'Tableau de bord',
    Employees: 'Gestion du personnel',
    Departments: 'Départements et postes',
    Attendance: 'Présences et pointage',
    Payroll: 'Paie et bulletins',
    Leaves: 'Congés et absences',
    Reports: 'Rapports et statistiques',
    Settings: 'Paramètres'
  };
  return titles[route.name as string] || 'Tableau de bord';
});
</script>