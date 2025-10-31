<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">Dashboard Administrateur</h2>
        <p class="text-sm text-gray-500 mt-1">Bienvenue, {{ user?.fullname }}</p>
      </div>
      <div class="flex items-center gap-3">
        <span class="px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg font-semibold text-sm">
          Admin
        </span>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm opacity-90 mb-1">Employés</p>
            <h3 class="text-4xl font-bold mb-2">{{ stats.total_employees || 0 }}</h3>
            <p class="text-xs opacity-75">Total actif</p>
          </div>
          <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </div>
      </div>

      <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm opacity-90 mb-1">Paies</p>
            <h3 class="text-4xl font-bold mb-2">{{ stats.total_paies || 0 }}</h3>
            <p class="text-xs opacity-75">Total générées</p>
          </div>
          <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
      </div>

      <div class="stat-card bg-gradient-to-br from-purple-500 to-purple-600 text-white">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm opacity-90 mb-1">Déclarations</p>
            <h3 class="text-4xl font-bold mb-2">{{ stats.total_declarations || 0 }}</h3>
            <p class="text-xs opacity-75">CNSS + IR</p>
          </div>
          <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
      </div>

      <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600 text-white">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm opacity-90 mb-1">Congés</p>
            <h3 class="text-4xl font-bold mb-2">{{ stats.total_conges || 0 }}</h3>
            <p class="text-xs opacity-75">En attente</p>
          </div>
          <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-4">Actions rapides</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <router-link to="/dashboard/employees" class="p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all group">
          <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          <p class="text-sm font-semibold text-gray-700 group-hover:text-blue-600">Ajouter employé</p>
        </router-link>

        <router-link to="/dashboard/payroll" class="p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all group">
          <svg class="w-8 h-8 text-gray-400 group-hover:text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          <p class="text-sm font-semibold text-gray-700 group-hover:text-green-600">Générer paies</p>
        </router-link>

        <router-link to="/dashboard/declarations" class="p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all group">
          <svg class="w-8 h-8 text-gray-400 group-hover:text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-sm font-semibold text-gray-700 group-hover:text-purple-600">Déclarations</p>
        </router-link>

        <router-link to="/dashboard/leaves" class="p-4 border-2 border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-all group">
          <svg class="w-8 h-8 text-gray-400 group-hover:text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <p class="text-sm font-semibold text-gray-700 group-hover:text-orange-600">Gérer congés</p>
        </router-link>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Vue d'ensemble</h3>
        <div class="space-y-3">
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-700">Employés actifs</span>
            <span class="font-bold text-gray-900">{{ stats.total_employees }}</span>
          </div>
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-700">Masse salariale mensuelle</span>
            <span class="font-bold text-green-700">Calculé automatiquement</span>
          </div>
          <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-700">Congés à valider</span>
            <span class="font-bold text-orange-700">{{ stats.total_conges }}</span>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4"> Accès rapide</h3>
        <div class="space-y-2">
          <router-link to="/dashboard/employees" class="block p-3 hover:bg-gray-50 rounded-lg transition-colors">
            <p class="text-sm font-semibold text-gray-900">Gestion du personnel</p>
            <p class="text-xs text-gray-500">Gérer les employés, contrats, informations</p>
          </router-link>
          <router-link to="/dashboard/payroll" class="block p-3 hover:bg-gray-50 rounded-lg transition-colors">
            <p class="text-sm font-semibold text-gray-900">Paie et bulletins</p>
            <p class="text-xs text-gray-500">Générer, consulter, télécharger</p>
          </router-link>
          <router-link to="/dashboard/declarations" class="block p-3 hover:bg-gray-50 rounded-lg transition-colors">
            <p class="text-sm font-semibold text-gray-900">Déclarations sociales</p>
            <p class="text-xs text-gray-500">CNSS, IR, obligations fiscales</p>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import api from '../services/api/axios';

const authStore = useAuthStore();
const user = authStore.user;
const stats = ref({
  total_employees: 0,
  total_paies: 0,
  total_declarations: 0,
  total_conges: 0
});

onMounted(async () => {
  try {
    const response = await api.get('/dashboard');
    stats.value = response.data.stats || {};
  } catch (error) {
    console.error('Erreur chargement stats:', error);
  }
});
</script>