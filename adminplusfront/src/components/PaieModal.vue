<template>
  <Transition name="modal">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="$emit('close')"></div>

      <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-2xl z-10">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 text-white">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <div>
                <h3 class="text-xl font-bold">Générer une paie</h3>
                <p class="text-sm text-green-100">Créer une fiche de paie pour un collaborateur</p>
              </div>
            </div>
            <button @click="$emit('close')" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Body -->
        <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Collaborateur <span class="text-red-500">*</span>
              </label>
              <select
                v-model="formData.collaborator_id"
                @change="onEmployeeChange"
                required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
              >
                <option value="">Sélectionner...</option>
                <option v-for="emp in employees" :key="emp.id" :value="emp.id">
                  {{ emp.fullname }} ({{ emp.matricule }})
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Mois <span class="text-red-500">*</span>
              </label>
              <select
                v-model="formData.mois"
                required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
              >
                <option value="">Sélectionner...</option>
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
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Année <span class="text-red-500">*</span>
              </label>
              <select
                v-model="formData.annee"
                required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
              >
                <option value="">Sélectionner...</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
              </select>
            </div>
          </div>

          <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-5">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Éléments de rémunération
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Salaire de base (FCFA) <span class="text-red-500">*</span>
                </label>
                <input
                  v-model.number="formData.salaire_base"
                  type="number"
                  required
                  min="0"
                  step="1000"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none bg-white"
                  placeholder="450000"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Prime (FCFA)
                </label>
                <input
                  v-model.number="formData.prime"
                  type="number"
                  min="0"
                  step="1000"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none bg-white"
                  placeholder="0"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Indemnité (FCFA)
                </label>
                <input
                  v-model.number="formData.indemnite"
                  type="number"
                  min="0"
                  step="1000"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none bg-white"
                  placeholder="0"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Retenue (FCFA)
                </label>
                <input
                  v-model.number="formData.retenue"
                  type="number"
                  min="0"
                  step="1000"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none bg-white"
                  placeholder="0"
                />
                <p class="text-xs text-gray-500 mt-1">CNSS, avances, sanctions, etc.</p>
              </div>
            </div>
          </div>

          <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-xl p-5">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
              <div>
                <p class="text-xs font-medium text-gray-600 mb-1">Salaire brut</p>
                <p class="text-xl font-bold text-gray-900">{{ formatMontant(salaireBrut) }}</p>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-600 mb-1">Total primes</p>
                <p class="text-xl font-bold text-blue-600">{{ formatMontant(totalPrimes) }}</p>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-600 mb-1">Total retenues</p>
                <p class="text-xl font-bold text-red-600">{{ formatMontant(formData.retenue || 0) }}</p>
              </div>
              <div class="md:col-span-1">
                <p class="text-xs font-medium text-gray-600 mb-1">Net à payer</p>
                <p class="text-2xl font-bold text-green-700">{{ formatMontant(netAPayer) }}</p>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Commentaire
            </label>
            <textarea
              v-model="formData.commentaire"
              rows="3"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none resize-none"
              placeholder="Ajoutez un commentaire optionnel..."
            ></textarea>
          </div>

          <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <div class="text-sm text-yellow-800">
                <p class="font-semibold mb-1">Vérifiez les informations</p>
                <p>Assurez-vous que toutes les données sont correctes avant de générer la fiche de paie.</p>
              </div>
            </div>
          </div>

          <div v-if="error" class="bg-red-50 border-2 border-red-200 rounded-lg p-3 flex items-start gap-2">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-red-700 text-sm font-semibold">{{ error }}</p>
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-4 border-t-2 border-gray-100">
            <button
              type="button"
              @click="$emit('close')"
              class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button
              type="submit"
              :disabled="loading || !isFormValid"
              class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold hover:from-green-700 hover:to-green-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
            >
              <svg v-if="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ loading ? 'Génération...' : 'Générer la paie' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import api from '../services/api/axios';

interface Employee {
  id: number;
  fullname: string;
  matricule: string;
  salaire_base: number;
}

const props = defineProps<{
  show: boolean;
}>();

const emit = defineEmits(['close', 'submit']);

const loading = ref(false);
const error = ref('');
const employees = ref<Employee[]>([]);

const formData = ref({
  collaborator_id: '',
  mois: '',
  annee: new Date().getFullYear().toString(),
  salaire_base: 0,
  prime: 0,
  indemnite: 0,
  retenue: 0,
  commentaire: ''
});

onMounted(async () => {
  await loadEmployees();
});

const loadEmployees = async () => {
  try {
    const response = await api.get('/collaborators');
    const data = Array.isArray(response.data) ? response.data : response.data.data || [];
    employees.value = data.map((c: any) => ({
      id: c.id,
      fullname: c.nom_complet || c.fullname || 'N/A',
      matricule: c.matricule || 'N/A',
      salaire_base: parseFloat(String(c.salaire_base || '0').replace(/[^\d]/g, '')) || 0
    }));
  } catch (error) {
    console.error('Erreur chargement employés:', error);
  }
};

const onEmployeeChange = () => {
  const employee = employees.value.find(e => e.id === Number(formData.value.collaborator_id));
  if (employee) {
    formData.value.salaire_base = employee.salaire_base;
  }
};

const salaireBrut = computed(() => {
  return formData.value.salaire_base || 0;
});

const totalPrimes = computed(() => {
  return (formData.value.prime || 0) + (formData.value.indemnite || 0);
});

const netAPayer = computed(() => {
  return salaireBrut.value + totalPrimes.value - (formData.value.retenue || 0);
});

const isFormValid = computed(() => {
  return formData.value.collaborator_id && 
         formData.value.mois && 
         formData.value.annee && 
         formData.value.salaire_base > 0;
});

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat('fr-FR', { 
    style: 'currency', 
    currency: 'XOF',
    minimumFractionDigits: 0 
  }).format(montant);
};

const handleSubmit = () => {
  error.value = '';
  
  if (!isFormValid.value) {
    error.value = 'Veuillez remplir tous les champs obligatoires';
    return;
  }

  loading.value = true;

  const submitData = {
    collaborator_id: Number(formData.value.collaborator_id),
    mois: formData.value.mois,
    annee: Number(formData.value.annee),
    salaire_base: formData.value.salaire_base,
    prime: formData.value.prime || 0,
    indemnite: formData.value.indemnite || 0,
    retenue: formData.value.retenue || 0,
    commentaire: formData.value.commentaire
  };

  emit('submit', submitData);
  
  setTimeout(() => {
    loading.value = false;
  }, 2000);
};
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.2s;
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
}
</style>