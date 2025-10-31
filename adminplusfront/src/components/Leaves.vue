<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Congés et absences</h2>
        <p class="text-sm text-gray-500 mt-1">Gérez les demandes de congés</p>
      </div>
      <button @click="openCreateModal" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Nouvelle demande
      </button>
    </div>

    <!-- Message de succès -->
    <div v-if="successMessage" class="bg-green-50 border-2 border-green-200 rounded-lg p-4 flex items-center gap-3">
      <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <p class="text-green-700 font-semibold">{{ successMessage }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">En attente</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ stats.enAttente }}</h3>
            <p class="text-xs text-orange-600 font-semibold">À traiter</p>
          </div>
          <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Approuvées</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ stats.approuvees }}</h3>
            <p class="text-xs text-green-600 font-semibold">Total</p>
          </div>
          <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">En cours</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ stats.enCours }}</h3>
            <p class="text-xs text-blue-600 font-semibold">Actuellement</p>
          </div>
          <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Refusées</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ stats.refusees }}</h3>
            <p class="text-xs text-red-600 font-semibold">Total</p>
          </div>
          <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Demandes en attente -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">Demandes en attente</h3>
        <div class="flex items-center gap-3">
          <select v-model="filters.type" @change="loadConges" class="input-field w-auto">
            <option value="">Tous les types</option>
            <option value="annuel">Congé annuel</option>
            <option value="maladie">Congé maladie</option>
            <option value="maternité">Congé maternité</option>
            <option value="autre">Autre</option>
          </select>
        </div>
      </div>

      <!-- Loader -->
      <div v-if="loading" class="py-12 flex justify-center">
        <div class="text-center">
          <svg class="animate-spin h-12 w-12 text-primary-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="text-gray-600">Chargement...</p>
        </div>
      </div>

      <div v-else-if="pendingRequests.length === 0" class="py-12 text-center text-gray-500">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <p class="text-lg font-semibold text-gray-700">Aucune demande en attente</p>
      </div>

      <div v-else class="space-y-3">
        <div
          v-for="request in pendingRequests"
          :key="request.id"
          class="flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:border-primary-300 transition-all"
        >
          <div class="flex items-center gap-4">
            <div :class="request.avatarColor" class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold">
              {{ request.initials }}
            </div>
            <div>
              <p class="text-sm font-bold text-gray-900">{{ request.name }}</p>
              <p class="text-xs text-gray-500">{{ request.position }}</p>
            </div>
          </div>
          <div class="flex items-center gap-6">
            <div>
              <p class="text-xs text-gray-500">Type</p>
              <span :class="request.typeClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                {{ request.typeLabel }}
              </span>
            </div>
            <div class="text-center">
              <p class="text-xs text-gray-500">Période</p>
              <p class="text-sm font-semibold text-gray-900">{{ request.period }}</p>
            </div>
            <div class="text-center">
              <p class="text-xs text-gray-500">Durée</p>
              <p class="text-sm font-bold text-gray-900">{{ request.nb_jours }} jour(s)</p>
            </div>
            <div class="flex items-center gap-2">
              <button @click="approveConge(request)" class="p-2 bg-green-500 hover:bg-green-600 rounded-lg transition-colors" title="Approuver">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </button>
              <button @click="rejectConge(request)" class="p-2 bg-red-500 hover:bg-red-600 rounded-lg transition-colors" title="Refuser">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Historique -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">Historique des congés</h3>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Employé</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Type</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Début</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Fin</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Durée</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Statut</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="allConges.length === 0">
              <td colspan="6" class="py-12 text-center text-gray-500">Aucun congé trouvé</td>
            </tr>
            <tr v-for="conge in allConges" :key="conge.id" class="table-row">
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div :class="conge.avatarColor" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ conge.initials }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-900">{{ conge.name }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6">
                <span :class="conge.typeClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ conge.typeLabel }}
                </span>
              </td>
              <td class="py-4 px-6 text-sm text-gray-700">{{ formatDate(conge.date_debut) }}</td>
              <td class="py-4 px-6 text-sm text-gray-700">{{ formatDate(conge.date_fin) }}</td>
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ conge.nb_jours }} jour(s)</td>
              <td class="py-4 px-6">
                <span :class="conge.statusClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ conge.statutLabel }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modals -->
    <CongeModal
      v-if="showModal"
      :show="showModal"
      @close="closeModal"
      @submit="handleSubmit"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import CongeModal from '../components/CongeModal.vue';
import api from '../services/api/axios';

interface Conge {
  id: number;
  name: string;
  position: string;
  initials: string;
  avatarColor: string;
  type: string;
  typeLabel: string;
  typeClass: string;
  date_debut: string;
  date_fin: string;
  nb_jours: number;
  period: string;
  statut: string;
  statutLabel: string;
  statusClass: string;
}

const conges = ref<Conge[]>([]);
const loading = ref(false);
const showModal = ref(false);
const successMessage = ref('');

const filters = ref({
  type: ''
});

const stats = computed(() => {
  const enAttente = conges.value.filter(c => c.statut === 'en_attente').length;
  const approuvees = conges.value.filter(c => c.statut === 'approuvé').length;
  const refusees = conges.value.filter(c => c.statut === 'refusé').length;
  
  // Calculer les congés en cours (date actuelle entre date_debut et date_fin)
  const today = new Date();
  const enCours = conges.value.filter(c => {
    if (c.statut !== 'approuvé') return false;
    const debut = new Date(c.date_debut);
    const fin = new Date(c.date_fin);
    return today >= debut && today <= fin;
  }).length;

  return { enAttente, approuvees, refusees, enCours };
});

const pendingRequests = computed(() => {
  return conges.value.filter(c => c.statut === 'en_attente');
});

const allConges = computed(() => {
  if (filters.value.type) {
    return conges.value.filter(c => c.type === filters.value.type);
  }
  return conges.value;
});

onMounted(() => {
  loadConges();
});

const loadConges = async () => {
  loading.value = true;
  try {
    const response = await api.get('/conges');
    const data = Array.isArray(response.data) ? response.data : response.data.data || [];
    
    conges.value = data.map((c: any) => {
      const collaborator = c.collaborator || {};
      const user = collaborator.user || {};
      const nom = user.fullname || collaborator.nom_complet || 'N/A';
      
      return {
        id: c.id,
        name: nom,
        position: collaborator.poste || 'N/A',
        initials: getInitials(nom),
        avatarColor: getRandomColor(),
        type: c.type,
        typeLabel: getTypeLabel(c.type),
        typeClass: getTypeClass(c.type),
        date_debut: c.date_debut,
        date_fin: c.date_fin,
        nb_jours: c.nb_jours || calculateDays(c.date_debut, c.date_fin),
        period: formatPeriod(c.date_debut, c.date_fin),
        statut: c.statut,
        statutLabel: getStatutLabel(c.statut),
        statusClass: getStatusClass(c.statut)
      };
    });
  } catch (error) {
    console.error('Erreur chargement congés:', error);
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const handleSubmit = async (data: any) => {
  try {
    await api.post('/conges', data);
    successMessage.value = 'Demande de congé créée avec succès !';
    await loadConges();
    closeModal();
    
    setTimeout(() => {
      successMessage.value = '';
    }, 5000);
  } catch (error: any) {
    console.error('Erreur:', error);
    alert(error.response?.data?.message || 'Erreur lors de la création');
  }
};

const approveConge = async (conge: Conge) => {
  if (!confirm(`Approuver la demande de congé de ${conge.name} ?`)) return;
  
  try {
    await api.put(`/conges/${conge.id}`, { statut: 'approuvé' });
    successMessage.value = 'Demande approuvée avec succès !';
    await loadConges();
    setTimeout(() => successMessage.value = '', 3000);
  } catch (error) {
    console.error('Erreur:', error);
    alert('Erreur lors de l\'approbation');
  }
};

const rejectConge = async (conge: Conge) => {
  if (!confirm(`Refuser la demande de congé de ${conge.name} ?`)) return;
  
  try {
    await api.put(`/conges/${conge.id}`, { statut: 'refusé' });
    successMessage.value = 'Demande refusée.';
    await loadConges();
    setTimeout(() => successMessage.value = '', 3000);
  } catch (error) {
    console.error('Erreur:', error);
    alert('Erreur lors du refus');
  }
};

const getInitials = (name: string) => {
  if (!name || name === 'N/A') return 'NA';
  const parts = name.split(' ');
  return parts.map(p => p[0]).join('').toUpperCase().slice(0, 2);
};

const getRandomColor = () => {
  const colors = [
    'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-orange-500',
    'bg-red-500', 'bg-teal-500', 'bg-indigo-500', 'bg-pink-500'
  ];
  return colors[Math.floor(Math.random() * colors.length)];
};

const getTypeLabel = (type: string) => {
  const labels: Record<string, string> = {
    'annuel': 'Congé annuel',
    'maladie': 'Congé maladie',
    'maternité': 'Congé maternité',
    'autre': 'Autre'
  };
  return labels[type] || type;
};

const getTypeClass = (type: string) => {
  const classes: Record<string, string> = {
    'annuel': 'bg-blue-100 text-blue-700',
    'maladie': 'bg-red-100 text-red-700',
    'maternité': 'bg-green-100 text-green-700',
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

const getStatusClass = (statut: string) => {
  const classes: Record<string, string> = {
    'en_attente': 'bg-orange-100 text-orange-700',
    'approuvé': 'bg-green-100 text-green-700',
    'refusé': 'bg-red-100 text-red-700'
  };
  return classes[statut] || 'bg-gray-100 text-gray-700';
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return 'N/A';
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
};

const formatPeriod = (debut: string, fin: string) => {
  return `${formatDate(debut)} - ${formatDate(fin)}`;
};

const calculateDays = (debut: string, fin: string) => {
  if (!debut || !fin) return 0;
  const start = new Date(debut);
  const end = new Date(fin);
  const diffTime = Math.abs(end.getTime() - start.getTime());
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
};
</script>