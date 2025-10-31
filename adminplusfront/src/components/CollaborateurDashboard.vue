<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';
import CongeModal from './CongeModal.vue';
import api from '../services/api/axios';

const authStore = useAuthStore();
const router = useRouter();
const user = authStore.user;

const stats = ref({
  solde_conges: 30,
  conges_en_attente: 0,
  paies_count: 0
});

const collaboratorInfo = ref<any>(null);
const recentBulletins = ref<any[]>([]);
const recentConges = ref<any[]>([]);
const loading = ref(false);
const loadingConges = ref(false);
const showCongeModal = ref(false);
const error = ref('');

const userInitials = computed(() => {
  if (!user?.fullname) return 'NA';
  const parts = user.fullname.split(' ');
  return parts.map((p: string) => p[0]).join('').toUpperCase().slice(0, 2);
});
console.log('Initiales utilisateur :', userInitials.value);


onMounted(async () => {
  await loadDashboardData();
});

const loadDashboardData = async () => {
  try {
    console.log('=== CHARGEMENT DASHBOARD COLLABORATEUR ===');
    
    try {
      const dashResponse = await api.get('/dashboard');
      console.log('Stats dashboard:', dashResponse.data);
      stats.value = dashResponse.data.stats || stats.value;
    } catch (err) {
      console.warn('Erreur chargement stats dashboard:', err);
    }


    const collabResponse = await api.get('/me');
    console.log('Infos utilisateur:', collabResponse.data);
    const userId = collabResponse.data.id;
    
    try {
      const collaborators = await api.get('/collaborators');
      console.log('Réponse collaborators:', collaborators.data);
      
      const collabData = Array.isArray(collaborators.data) 
        ? collaborators.data 
        : collaborators.data.data || [];
        
      console.log('Liste collaborateurs:', collabData);
      
      const myCollab = collabData.find((c: any) => c.user_id === userId);
      console.log('Mon profil collaborateur:', myCollab);
      
      if (myCollab) {
        collaboratorInfo.value = myCollab;
        
        await loadBulletins(myCollab.id);
        
        await loadConges(myCollab.id);
      } else {
        console.error('Profil collaborateur non trouvé pour user_id:', userId);
        error.value = 'Votre profil collaborateur n\'est pas encore créé. Veuillez contacter l\'administrateur.';
      }
    } catch (err: any) {
      console.error('Erreur chargement profil collaborateur:', err);
      if (err.response?.status === 404) {
        error.value = 'Votre profil collaborateur n\'existe pas. Veuillez contacter l\'administrateur.';
      } else {
        error.value = 'Erreur lors du chargement de votre profil.';
      }
    }
  } catch (error) {
    console.error('Erreur chargement dashboard:', error);
  }
};

const loadBulletins = async (collaboratorId: number) => {
  loading.value = true;
  try {
    console.log('Chargement bulletins pour collaborator_id:', collaboratorId);
    const response = await api.get(`/collaborators/${collaboratorId}/paies`);
    console.log('Bulletins reçus:', response.data);
    
    const data = Array.isArray(response.data) ? response.data : response.data.historique || [];
    recentBulletins.value = data.slice(0, 3).map((p: any) => ({
      id: p.id,
      periode: `${p.mois}/${p.annee}`,
      net: parseFloat(p.net_a_payer || 0)
    }));
    
    stats.value.paies_count = data.length;
  } catch (error) {
    console.error('Erreur chargement bulletins:', error);
  } finally {
    loading.value = false;
  }
};

const loadConges = async (collaboratorId: number) => {
  loadingConges.value = true;
  try {
    console.log('Chargement congés pour collaborator_id:', collaboratorId);
    const response = await api.get(`/collaborators/${collaboratorId}/conges`);
    console.log('Congés reçus:', response.data);
    
    const data = Array.isArray(response.data) ? response.data : response.data.historique || [];
    recentConges.value = data.slice(0, 3).map((c: any) => ({
      id: c.id,
      periode: `${formatDate(c.date_debut)} - ${formatDate(c.date_fin)}`,
      nb_jours: c.nb_jours,
      type: getTypeLabel(c.type),
      statut: c.statut,
      statutLabel: getStatutLabel(c.statut),
      statutClass: getStatutClass(c.statut)
    }));
    
    stats.value.conges_en_attente = data.filter((c: any) => c.statut === 'en_attente').length;
  } catch (error) {
    console.error('Erreur chargement congés:', error);
  } finally {
    loadingConges.value = false;
  }
};

const handleCongeSubmit = async (data: any) => {
  if (!collaboratorInfo.value?.id) {
    alert('Votre profil collaborateur n\'est pas disponible. Veuillez contacter l\'administrateur.');
    return;
  }

  try {
    await api.post('/conges', {
      ...data,
      collaborator_id: collaboratorInfo.value.id
    });
    showCongeModal.value = false;
    alert('Demande de congé envoyée avec succès !');
    await loadConges(collaboratorInfo.value.id);
  } catch (error) {
    console.error('Erreur:', error);
    alert('Erreur lors de l\'envoi de la demande');
  }
};

const goToBulletins = () => {
  router.push('/dashboard/my-bulletins');
};

const goToHistorique = () => {
  router.push('/dashboard/my-history');
};

const downloadBulletin = (bulletin: any) => {
  alert(`Téléchargement du bulletin ${bulletin.periode}...`);
};

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 0 }).format(montant) + ' FCFA';
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

<template>
  <!-- Message d'erreur si profil manquant -->
  <div v-if="error" class="max-w-2xl mx-auto mt-10 mb-6">
    <div class="bg-orange-50 border-2 border-orange-200 rounded-xl p-6">
      <div class="flex items-start gap-4">
        <svg class="w-10 h-10 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <div>
          <h3 class="text-lg font-bold text-orange-800 mb-2">Profil incomplet</h3>
          <p class="text-orange-700">{{ error }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Reste du template identique à votre version -->
  <div class="space-y-6">
    <!-- Header avec photo de profil -->
    <!-- ... gardez tout votre template existant ... -->
  </div>
</template>