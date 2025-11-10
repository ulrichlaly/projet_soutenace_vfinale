<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Paie et bulletins</h2>
        <p class="text-sm text-gray-500 mt-1">Gestion des salaires et bulletins de paie</p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="exportAll" class="btn-secondary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Exporter tout
        </button>
        <button @click="openCreateModal" class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          Calculer les salaires
        </button>
      </div>
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
            <p class="text-sm font-medium text-gray-600 mb-1">Masse salariale</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ formatMontantShort(stats.masseSalariale) }}</h3>
            <p class="text-xs text-green-600 font-semibold">{{ stats.total }} paies</p>
          </div>
          <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Bulletins générés</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ stats.total }}</h3>
            <p class="text-xs text-blue-600 font-semibold">Ce mois</p>
          </div>
          <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Paiements envoyés</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ stats.payees }}</h3>
            <p class="text-xs text-green-600 font-semibold">{{ calculatePercentage(stats.payees, stats.total) }}% effectués</p>
          </div>
          <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">En attente</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ stats.enAttente }}</h3>
            <p class="text-xs text-orange-600 font-semibold">{{ calculatePercentage(stats.enAttente, stats.total) }}% restants</p>
          </div>
          <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">Bulletins de paie</h3>
        <div class="flex items-center gap-3">
          <select v-model="filters.mois" @change="loadPaies" class="input-field w-auto">
            <option value="01">Janvier</option>
            <option value="02">Février</option>
            <option value="03">Mars</option>
            <option value="04">Avril</option>
            <option value="05">Mai</option>
            <option value="06">Juin</option>
            <option value="07">Juillet</option>
            <option value="08">Août</option>
            <option value="09">Septembre</option>
            <option value="10">Octobre</option>
            <option value="11">Novembre</option>
            <option value="12">Décembre</option>
          </select>
          <select v-model="filters.annee" @change="loadPaies" class="input-field w-auto">
            <option value="2025">2025</option>
            <option value="2024">2024</option>
            <option value="2023">2023</option>
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
          <p class="text-gray-600">Chargement des paies...</p>
        </div>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Collaborateur</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Département</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Salaire Brut</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Déductions</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Salaire Net</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Statut</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="paies.length === 0">
              <td colspan="7" class="py-12 text-center text-gray-500">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-lg font-semibold text-gray-700">Aucune paie trouvée</p>
                <p class="text-sm text-gray-500 mt-2">Cliquez sur "Calculer les salaires" pour générer les paies</p>
              </td>
            </tr>
            <tr v-for="paie in paies" :key="paie.id" class="table-row">
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div :class="paie.avatarColor" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ paie.initials }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-900">{{ paie.employeeName }}</p>
                    <p class="text-xs text-gray-500">{{ paie.poste }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6">
                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                  {{ paie.departement || 'N/A' }}
                </span>
              </td>
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ formatMontant(paie.salaireBrut) }}</td>
              <td class="py-4 px-6 text-sm text-red-600">-{{ formatMontant(paie.retenue) }}</td>
              <td class="py-4 px-6 text-sm font-bold text-gray-900">{{ formatMontant(paie.netAPayer) }}</td>
              <td class="py-4 px-6">
                <span :class="paie.statusClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ paie.statusLabel }}
                </span>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  <button @click="viewBulletin(paie)" class="p-2 hover:bg-blue-50 rounded-lg transition-colors group" title="Voir le bulletin">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                  <button @click="downloadBulletin(paie)" class="p-2 hover:bg-green-50 rounded-lg transition-colors group" title="Télécharger">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                  </button>
                  <button @click="sendEmail(paie)" class="p-2 hover:bg-primary-50 rounded-lg transition-colors group" title="Envoyer par email">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modals -->
    <PaieModal
      v-if="showModal"
      :show="showModal"
      @close="closeModal"
      @submit="handleSubmit"
    />

    <BulletinModal
      v-if="showBulletinModal"
      :show="showBulletinModal"
      :paie="selectedPaie"
      @close="showBulletinModal = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import PaieModal from '../components/PaieModal.vue';
import BulletinModal from '../components/BulletinModal.vue';
import api from '../services/api/axios';

interface Paie {
  id: number;
  employeeName: string;
  employeeMatricule: string;
  initials: string;
  avatarColor: string;
  poste: string;
  departement: string;
  salaireBrut: number;
  primes: number;
  retenue: number;
  netAPayer: number;
  statusLabel: string;
  statusClass: string;
  periode?: string;
  salaireBase?: number;
  retenues?: number;
}

const paies = ref<Paie[]>([]);
const loading = ref(false);
const showModal = ref(false);
const showBulletinModal = ref(false);
const selectedPaie = ref<Paie | null>(null);
const successMessage = ref('');

const filters = ref({
  mois: new Date().getMonth() + 1 < 10 ? '0' + (new Date().getMonth() + 1) : String(new Date().getMonth() + 1),
  annee: new Date().getFullYear().toString()
});

const stats = computed(() => {
  const total = paies.value.length;
  const payees = paies.value.filter((p: Paie) => p.statusLabel === 'Payé' || p.statusLabel === 'Payée').length;
  const enAttente = paies.value.filter((p: Paie) => p.statusLabel === 'En attente').length;
  const masseSalariale = paies.value.reduce((sum: number, p: Paie) => sum + p.netAPayer, 0);
  
  return {
    total,
    payees,
    enAttente,
    masseSalariale
  };
});

onMounted(() => {
  loadPaies();
});

const loadPaies = async () => {
  loading.value = true;
  try {
    const response = await api.get('/paies');
    const data = Array.isArray(response.data) ? response.data : response.data.data || [];
    
    paies.value = data.map((p: any) => {
      const collaborator = p.collaborator || {};
      const user = collaborator.user || {};
      const nom = user.fullname || collaborator.nom_complet || 'N/A';
      
      return {
        id: p.id,
        employeeName: nom,
        employeeMatricule: collaborator.matricule || 'N/A',
        initials: getInitials(nom),
        avatarColor: getRandomColor(),
        poste: collaborator.poste || 'N/A',
        departement: 'N/A',
        salaireBrut: parseFloat(p.salaire_base || 0) + parseFloat(p.prime || 0) + parseFloat(p.indemnite || 0),
        primes: parseFloat(p.prime || 0) + parseFloat(p.indemnite || 0),
        retenue: parseFloat(p.retenue || 0),
        netAPayer: parseFloat(p.net_a_payer || 0),
        statusLabel: 'Payé',
        statusClass: 'bg-green-100 text-green-700'
      };
    });
  } catch (error) {
    console.error('Erreur chargement paies:', error);
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
    await api.post('/paies', data);
    successMessage.value = 'Paie générée avec succès !';
    await loadPaies();
    closeModal();
    
    setTimeout(() => {
      successMessage.value = '';
    }, 5000);
  } catch (error: any) {
    console.error('Erreur:', error);
    alert(error.response?.data?.message || 'Erreur lors de la génération');
  }
};

const viewBulletin = (paie: Paie) => {
  selectedPaie.value = {
    ...paie,
    periode: `${getMoisLabel(filters.value.mois)} ${filters.value.annee}`,
    salaireBase: paie.salaireBrut - paie.primes,
    retenues: paie.retenue
  };
  showBulletinModal.value = true;
};

const downloadBulletin = async (paie: Paie) => {
  alert(`Téléchargement du bulletin pour ${paie.employeeName}`);
};

const sendEmail = async (paie: Paie) => {
  alert(`Email envoyé à ${paie.employeeName}`);
};

const exportAll = () => {
  alert('Export de toutes les paies en cours...');
};

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat('fr-FR', { 
    minimumFractionDigits: 0 
  }).format(montant);
};

const formatMontantShort = (montant: number) => {
  if (montant >= 1000000) {
    return (montant / 1000000).toFixed(1) + 'M';
  }
  return formatMontant(montant);
};

const calculatePercentage = (value: number, total: number) => {
  return total > 0 ? Math.round((value / total) * 100) : 0;
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

const getMoisLabel = (mois: string) => {
  const labels: Record<string, string> = {
    '01': 'Janvier', '02': 'Février', '03': 'Mars', '04': 'Avril',
    '05': 'Mai', '06': 'Juin', '07': 'Juillet', '08': 'Août',
    '09': 'Septembre', '10': 'Octobre', '11': 'Novembre', '12': 'Décembre'
  };
  return labels[mois] || mois;
};
</script>