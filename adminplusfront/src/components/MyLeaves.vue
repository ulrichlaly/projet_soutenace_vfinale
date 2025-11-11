<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 p-6">
    <div class="max-w-7xl mx-auto space-y-6">
      
      <!-- 🎯 Header Hero Section -->
      <div class="bg-gradient-to-r from-green-600 via-teal-600 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
        
        <div class="relative z-10 flex items-center justify-between flex-wrap gap-6">
          <div>
            <p class="text-sm text-white/80 mb-1">Gestion de vos congés</p>
            <h1 class="text-4xl font-black mb-2">Mes demandes de congé</h1>
            <p class="text-green-100">Gérez vos congés et consultez votre solde</p>
          </div>
          
          <button @click="showModal = true" class="bg-white text-green-600 px-6 py-3 rounded-xl font-bold hover:scale-105 transition-transform shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nouvelle demande
          </button>
        </div>
      </div>

      <!-- Message de succès -->
      <transition name="fade">
        <div v-if="successMessage" class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg flex items-center gap-4">
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="font-bold text-lg">Succès !</p>
            <p class="text-white/90">{{ successMessage }}</p>
          </div>
          <button @click="successMessage = ''" class="ml-auto p-2 hover:bg-white/20 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </transition>

      <!-- 📊 Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-green-500">
          <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <span class="text-5xl font-black text-green-600">{{ soldeConges }}</span>
          </div>
          <h3 class="text-gray-700 font-bold text-lg">Solde disponible</h3>
          <p class="text-sm text-gray-500">Jours de congé restants</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-orange-500">
          <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="text-5xl font-black text-orange-600">{{ stats.enAttente }}</span>
          </div>
          <h3 class="text-gray-700 font-bold text-lg">En attente</h3>
          <p class="text-sm text-gray-500">Demandes en cours</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-blue-500">
          <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="text-5xl font-black text-blue-600">{{ stats.approuvees }}</span>
          </div>
          <h3 class="text-gray-700 font-bold text-lg">Approuvées</h3>
          <p class="text-sm text-gray-500">Cette année</p>
        </div>
      </div>

      <!-- 🔍 Filtres -->
      <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center gap-4 flex-wrap">
          <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-bold text-gray-700 mb-2">Statut</label>
            <select v-model="filters.statut" @change="loadConges" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none font-medium">
              <option value="">Tous les statuts</option>
              <option value="en_attente">En attente</option>
              <option value="approuvé">Approuvé</option>
              <option value="refusé">Refusé</option>
            </select>
          </div>

          <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-bold text-gray-700 mb-2">Type</label>
            <select v-model="filters.type" @change="loadConges" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none font-medium">
              <option value="">Tous les types</option>
              <option value="annuel">Congé annuel</option>
              <option value="maladie">Congé maladie</option>
              <option value="maternite">Congé maternité</option>
              <option value="autre">Autre</option>
            </select>
          </div>

          <div class="pt-7">
            <button @click="loadConges" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-bold hover:shadow-lg transition-all flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Actualiser
            </button>
          </div>
        </div>
      </div>

      <!-- 📋 Liste des congés -->
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- Loader -->
        <div v-if="loading" class="flex items-center justify-center py-20">
          <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent mb-4"></div>
            <p class="text-gray-600 font-semibold">Chargement de vos congés...</p>
          </div>
        </div>

        <!-- Table -->
        <div v-else class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-50 to-blue-50 border-b-2 border-gray-200">
              <tr>
                <th class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase tracking-wider">Type</th>
                <th class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase tracking-wider">Date début</th>
                <th class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase tracking-wider">Date fin</th>
                <th class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase tracking-wider">Durée</th>
                <th class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase tracking-wider">Statut</th>
                <th class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase tracking-wider">Motif</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="conges.length === 0">
                <td colspan="6" class="py-20 text-center">
                  <div class="flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mb-4">
                      <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                    <p class="text-xl font-black text-gray-700 mb-2">Aucune demande de congé</p>
                    <p class="text-gray-500 mb-4">Créez votre première demande pour commencer</p>
                    <button @click="showModal = true" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors">
                      Créer une demande
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-for="(conge, index) in conges" :key="conge.id" 
                  :class="['border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all', index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50']">
                <td class="py-5 px-6">
                  <span :class="['px-4 py-2 rounded-xl text-xs font-bold shadow-sm', conge.typeClass]">
                    {{ conge.typeLabel }}
                  </span>
                </td>
                <td class="py-5 px-6 text-sm font-semibold text-gray-700">{{ formatDate(conge.date_debut) }}</td>
                <td class="py-5 px-6 text-sm font-semibold text-gray-700">{{ formatDate(conge.date_fin) }}</td>
                <td class="py-5 px-6">
                  <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                      <span class="text-lg font-black text-blue-600">{{ conge.nb_jours }}</span>
                    </div>
                    <span class="text-sm text-gray-600 font-medium">jour(s)</span>
                  </div>
                </td>
                <td class="py-5 px-6">
                  <span :class="['px-4 py-2 rounded-xl text-xs font-bold shadow-sm', conge.statutClass]">
                    {{ conge.statutLabel }}
                  </span>
                </td>
                <td class="py-5 px-6">
                  <p class="text-sm text-gray-600 line-clamp-2 max-w-xs">{{ conge.motif || 'Aucun motif fourni' }}</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600 font-medium">
              Affichage de <span class="font-bold text-gray-900">{{ conges.length }}</span> demande(s)
            </p>
            <div class="flex items-center gap-2">
              <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
              <span class="text-xs text-gray-500">Mis à jour</span>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Modal -->
    <CongeModal
      v-if="showModal"
      :show="showModal"
      :collaborator-id="collaboratorId"
      @close="showModal = false"
      @submit="handleSubmit"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import CongeModal from '../components/CongeModal.vue';
import api from '../services/api/axios';

const authStore = useAuthStore();
const conges = ref<any[]>([]);
const loading = ref(false);
const showModal = ref(false);
const successMessage = ref('');
const collaboratorId = ref<number | null>(null);

const filters = ref({
  statut: '',
  type: ''
});

const stats = computed(() => {
  return {
    enAttente: conges.value.filter(c => c.statut === 'en_attente').length,
    approuvees: conges.value.filter(c => c.statut === 'approuvé').length,
  };
});

const soldeConges = computed(() => {
  const joursPris = conges.value
    .filter(c => c.statut === 'approuvé')
    .reduce((sum, c) => sum + c.nb_jours, 0);
  return Math.max(0, 30 - joursPris);
});

onMounted(async () => {
  await loadCollaboratorInfo();
  await loadConges();
});

const loadCollaboratorInfo = async () => {
  try {
    const userResponse = await api.get('/me');
    const userId = userResponse.data.id;

    const collabResponse = await api.get('/collaborators');
    const collabData = Array.isArray(collabResponse.data) ? collabResponse.data : collabResponse.data.data || [];
    const myCollab = collabData.find((c: any) => c.user_id === userId);

    if (myCollab) {
      collaboratorId.value = myCollab.id;
    }
  } catch (error) {
    console.error('Erreur chargement info collaborateur:', error);
  }
};

const loadConges = async () => {
  if (!collaboratorId.value) return;

  loading.value = true;
  try {
    const response = await api.get(`/collaborators/${collaboratorId.value}/conges`);
    const data = Array.isArray(response.data) ? response.data : response.data.historique || [];

    conges.value = data
      .filter((c: any) => {
        const matchStatut = !filters.value.statut || c.statut === filters.value.statut;
        const matchType = !filters.value.type || c.type === filters.value.type;
        return matchStatut && matchType;
      })
      .map((c: any) => ({
        id: c.id,
        type: c.type,
        typeLabel: getTypeLabel(c.type),
        typeClass: getTypeClass(c.type),
        date_debut: c.date_debut,
        date_fin: c.date_fin,
        nb_jours: c.nb_jours || 0,
        statut: c.statut,
        statutLabel: getStatutLabel(c.statut),
        statutClass: getStatutClass(c.statut),
        motif: c.motif
      }))
      .sort((a: any, b: any) => {
        return new Date(b.date_debut).getTime() - new Date(a.date_debut).getTime();
      });
  } catch (error) {
    console.error('Erreur chargement congés:', error);
  } finally {
    loading.value = false;
  }
};

const handleSubmit = async (data: any) => {
  try {
    await api.post('/conges', {
      ...data,
      collaborator_id: collaboratorId.value
    });
    successMessage.value = 'Demande de congé envoyée avec succès !';
    showModal.value = false;
    await loadConges();

    setTimeout(() => {
      successMessage.value = '';
    }, 5000);
  } catch (error: any) {
    console.error('Erreur:', error);
    alert(error.response?.data?.message || 'Erreur lors de l\'envoi de la demande');
  }
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return 'N/A';
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
};

const getTypeLabel = (type: string) => {
  const labels: Record<string, string> = {
    'annuel': 'Congé annuel',
    'maladie': 'Congé maladie',
    'maternite': 'Congé maternité',
    'autre': 'Autre'
  };
  return labels[type] || type;
};

const getTypeClass = (type: string) => {
  const classes: Record<string, string> = {
    'annuel': 'bg-blue-100 text-blue-700',
    'maladie': 'bg-red-100 text-red-700',
    'maternite': 'bg-pink-100 text-pink-700',
    'autre': 'bg-orange-100 text-orange-700'
  };
  return classes[type] || 'bg-gray-100 text-gray-700';
};

const getStatutLabel = (statut: string) => {
  const labels: Record<string, string> = {
    'en_attente': 'En attente',
    'approuvé': 'Approuvé',
    'refusé': 'Refusé'
  };
  return labels[statut] || statut;
};

const getStatutClass = (statut: string) => {
  const classes: Record<string, string> = {
    'en_attente': 'bg-orange-100 text-orange-700',
    'approuvé': 'bg-green-100 text-green-700',
    'refusé': 'bg-red-100 text-red-700'
  };
  return classes[statut] || 'bg-gray-100 text-gray-700';
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
