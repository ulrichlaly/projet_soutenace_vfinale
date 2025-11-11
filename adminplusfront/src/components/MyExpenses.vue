<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-orange-50 p-6">
    <div class="max-w-7xl mx-auto">
      
      <!-- Header -->
      <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-3xl p-8 text-white shadow-2xl mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
          <div>
            <h1 class="text-4xl font-black mb-2">💰 Notes de frais</h1>
            <p class="text-orange-100">Gérez vos demandes de remboursement</p>
          </div>
          <button 
            @click="showModal = true"
            class="bg-white text-orange-600 px-6 py-3 rounded-xl font-bold hover:scale-105 transition-transform shadow-lg flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nouvelle note de frais
          </button>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
          <div class="flex items-center justify-between mb-2">
            <span class="text-gray-600 font-semibold">Total en attente</span>
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <p class="text-3xl font-black text-orange-600">{{ stats.en_attente }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
          <div class="flex items-center justify-between mb-2">
            <span class="text-gray-600 font-semibold">Approuvées</span>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
          <p class="text-3xl font-black text-green-600">{{ stats.approuvees }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
          <div class="flex items-center justify-between mb-2">
            <span class="text-gray-600 font-semibold">Remboursées</span>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
          <p class="text-3xl font-black text-blue-600">{{ stats.remboursees }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
          <div class="flex items-center justify-between mb-2">
            <span class="text-gray-600 font-semibold">Montant total</span>
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <p class="text-2xl font-black text-purple-600">{{ formatMontant(stats.montant_total) }}</p>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-wrap gap-4">
          <button 
            v-for="filter in filters" 
            :key="filter.value"
            @click="currentFilter = filter.value"
            :class="[
              'px-6 py-3 rounded-xl font-bold transition-all',
              currentFilter === filter.value 
                ? 'bg-orange-600 text-white shadow-lg' 
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
          >
            {{ filter.label }}
          </button>
        </div>
      </div>

      <!-- Liste des notes de frais -->
      <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-black text-gray-900 mb-6">Mes notes de frais</h2>

        <div v-if="loading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-orange-600 border-t-transparent"></div>
        </div>

        <div v-else-if="filteredExpenses.length === 0" class="text-center py-12 text-gray-400">
          <svg class="w-20 h-20 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="font-semibold">Aucune note de frais</p>
          <button @click="showModal = true" class="mt-4 text-orange-600 hover:text-orange-700 font-bold">
            Créer ma première note de frais
          </button>
        </div>

        <div v-else class="space-y-4">
          <div 
            v-for="expense in filteredExpenses" 
            :key="expense.id"
            class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all"
          >
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                  <h3 class="text-lg font-bold text-gray-900">{{ expense.titre }}</h3>
                  <span :class="['px-3 py-1 rounded-full text-xs font-bold', getStatutClass(expense.statut)]">
                    {{ getStatutLabel(expense.statut) }}
                  </span>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ expense.categorie }}</p>
                <p class="text-gray-700">{{ expense.description }}</p>
              </div>
              <div class="text-right">
                <p class="text-2xl font-black text-orange-600">{{ formatMontant(expense.montant) }}</p>
                <p class="text-sm text-gray-500">{{ formatDate(expense.date) }}</p>
              </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
              <div class="flex items-center gap-4 text-sm text-gray-600">
                <span v-if="expense.justificatif" class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Justificatif joint
                </span>
                <span v-if="expense.commentaire_admin" class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                  </svg>
                  Commentaire RH
                </span>
              </div>
              <div class="flex gap-2">
                <button 
                  @click="viewDetails(expense)"
                  class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-semibold hover:bg-blue-200 transition-colors"
                >
                  Voir détails
                </button>
                <button 
                  v-if="expense.statut === 'en_attente'"
                  @click="deleteExpense(expense.id)"
                  class="px-4 py-2 bg-red-100 text-red-700 rounded-lg font-semibold hover:bg-red-200 transition-colors"
                >
                  Annuler
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Nouvelle note de frais -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-2xl font-black text-gray-900">Nouvelle note de frais</h3>
            <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <form @submit.prevent="submitExpense" class="p-6 space-y-6">
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Titre *</label>
            <input 
              v-model="form.titre"
              type="text"
              required
              placeholder="Ex: Déplacement client"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Catégorie *</label>
            <select 
              v-model="form.categorie"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
              <option value="">Sélectionnez une catégorie</option>
              <option value="Transport">Transport</option>
              <option value="Restauration">Restauration</option>
              <option value="Hébergement">Hébergement</option>
              <option value="Matériel">Matériel</option>
              <option value="Télécom">Télécom</option>
              <option value="Formation">Formation</option>
              <option value="Autre">Autre</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Montant (FCFA) *</label>
            <input 
              v-model.number="form.montant"
              type="number"
              required
              min="0"
              step="100"
              placeholder="0"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Date *</label>
            <input 
              v-model="form.date"
              type="date"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Description *</label>
            <textarea 
              v-model="form.description"
              required
              rows="4"
              placeholder="Décrivez votre dépense..."
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Justificatif</label>
            <input 
              type="file"
              @change="handleFileUpload"
              accept="image/*,application/pdf"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
            <p class="text-xs text-gray-500 mt-1">PDF ou Image (max 5MB)</p>
          </div>

          <div class="flex gap-4 pt-4">
            <button 
              type="button"
              @click="closeModal"
              class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button 
              type="submit"
              class="flex-1 px-6 py-3 bg-orange-600 text-white rounded-xl font-bold hover:bg-orange-700 transition-colors"
            >
              Soumettre
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';

const showModal = ref(false);
const loading = ref(false);
const currentFilter = ref('all');

const stats = ref({
  en_attente: 3,
  approuvees: 5,
  remboursees: 12,
  montant_total: 250000,
});

const filters = [
  { label: 'Toutes', value: 'all' },
  { label: 'En attente', value: 'en_attente' },
  { label: 'Approuvées', value: 'approuvee' },
  { label: 'Remboursées', value: 'remboursee' },
  { label: 'Refusées', value: 'refusee' },
];

const form = ref({
  titre: '',
  categorie: '',
  montant: 0,
  date: '',
  description: '',
  justificatif: null as File | null,
});

const expenses = ref([
  {
    id: 1,
    titre: 'Déplacement client Abomey',
    categorie: 'Transport',
    montant: 25000,
    date: '2025-11-05',
    description: 'Carburant et péage pour visite client',
    statut: 'en_attente',
    justificatif: true,
    commentaire_admin: null,
  },
  {
    id: 2,
    titre: 'Déjeuner d\'affaires',
    categorie: 'Restauration',
    montant: 18000,
    date: '2025-11-03',
    description: 'Repas avec prospects',
    statut: 'approuvee',
    justificatif: true,
    commentaire_admin: 'Approuvé par la direction',
  },
  {
    id: 3,
    titre: 'Fournitures bureau',
    categorie: 'Matériel',
    montant: 32000,
    date: '2025-10-28',
    description: 'Papeterie et accessoires',
    statut: 'remboursee',
    justificatif: true,
    commentaire_admin: null,
  },
]);

const filteredExpenses = computed(() => {
  if (currentFilter.value === 'all') return expenses.value;
  return expenses.value.filter(e => e.statut === currentFilter.value);
});

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
};

const formatDate = (dateStr: string) => {
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
  });
};

const getStatutLabel = (statut: string) => {
  const labels: Record<string, string> = {
    en_attente: 'En attente',
    approuvee: 'Approuvée',
    remboursee: 'Remboursée',
    refusee: 'Refusée',
  };
  return labels[statut] || statut;
};

const getStatutClass = (statut: string) => {
  const classes: Record<string, string> = {
    en_attente: 'bg-orange-100 text-orange-700',
    approuvee: 'bg-green-100 text-green-700',
    remboursee: 'bg-blue-100 text-blue-700',
    refusee: 'bg-red-100 text-red-700',
  };
  return classes[statut] || 'bg-gray-100 text-gray-700';
};

const handleFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    form.value.justificatif = target.files[0];
  }
};

const submitExpense = () => {
  console.log('Soumission note de frais:', form.value);
  // TODO: API call
  alert('Note de frais soumise avec succès !');
  closeModal();
};

const closeModal = () => {
  showModal.value = false;
  form.value = {
    titre: '',
    categorie: '',
    montant: 0,
    date: '',
    description: '',
    justificatif: null,
  };
};

const viewDetails = (expense: any) => {
  alert(`Détails de: ${expense.titre}`);
};

const deleteExpense = (id: number) => {
  if (confirm('Voulez-vous vraiment annuler cette note de frais ?')) {
    expenses.value = expenses.value.filter(e => e.id !== id);
    alert('Note de frais annulée');
  }
};
</script>
