<template>
  <Transition name="modal">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="$emit('close')"></div>

      <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl z-10">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold text-gray-900">
                {{ isEdit ? 'Modifier l\'employé' : 'Nouvel employé' }}
              </h3>
              <p class="text-sm text-gray-500 mt-1">
                {{ isEdit ? 'Modifiez les informations' : 'Les champs avec * sont obligatoires' }}
              </p>
            </div>
            <button @click="$emit('close')" class="p-2 hover:bg-gray-100 rounded-lg">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Body -->
        <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
          <!-- Nom complet -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Nom complet <span class="text-red-500">*</span>
            </label>
            <input
              v-model="formData.fullname"
              type="text"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
              placeholder="Ex: Kokou Mensah"
            />
          </div>

          <!-- Email et Téléphone -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.email"
                type="email"
                required
                :disabled="isEdit"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-100 disabled:cursor-not-allowed"
                placeholder="exemple@email.com"
              />
              <p v-if="isEdit" class="text-xs text-gray-500 mt-1">L'email ne peut pas être modifié</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Téléphone
              </label>
              <input
                v-model="formData.telephone"
                type="tel"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="+229 XX XX XX XX"
              />
            </div>
          </div>

          <!-- Poste et Salaire -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Poste <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.poste"
                type="text"
                required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="Ex: Analyste RH"
              />
            </div>

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
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="450000"
              />
            </div>
          </div>

          <!-- Date embauche et Date naissance -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Date d'embauche <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.date_embauche"
                type="date"
                required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Date de naissance
              </label>
              <input
                v-model="formData.date_naissance"
                type="date"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
              />
            </div>
          </div>

          <!-- Genre et Adresse -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Genre
              </label>
              <select
                v-model="formData.genre"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
              >
                <option value="">Sélectionnez</option>
                <option value="M">Masculin</option>
                <option value="F">Féminin</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Adresse
              </label>
              <input
                v-model="formData.adresse"
                type="text"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="Ex: Cotonou, Akpakpa"
              />
            </div>
          </div>

          <!-- Statut (uniquement en édition) -->
          <div v-if="isEdit">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Statut
            </label>
            <select
              v-model="formData.statut"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            >
              <option value="actif">Actif</option>
              <option value="en_conge">En congé</option>
              <option value="inactif">Inactif</option>
            </select>
          </div>

          <!-- Info mot de passe (uniquement en création) -->
          <div v-if="!isEdit" class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p class="font-semibold text-blue-900 mb-1">📧 Email automatique</p>
                <p class="text-sm text-blue-700">
                  Un email sera automatiquement envoyé à <strong>{{ formData.email || 'l\'employé' }}</strong> avec :
                </p>
                <ul class="text-sm text-blue-700 list-disc list-inside mt-2 space-y-1">
                  <li>Ses identifiants de connexion</li>
                  <li>Son mot de passe par défaut : <code class="bg-blue-100 px-2 py-0.5 rounded font-mono">Password123!</code></li>
                  <li>Son matricule unique</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Erreur -->
          <div v-if="error" class="bg-red-50 border-2 border-red-200 rounded-lg p-3 flex items-start gap-2">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-red-700 text-sm font-semibold">{{ error }}</p>
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="$emit('close')"
              class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              <span v-if="loading" class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ isEdit ? 'Mise à jour...' : 'Création...' }}
              </span>
              <span v-else>{{ isEdit ? 'Mettre à jour' : 'Créer l\'employé' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref, watch, computed } from 'vue';

interface Props {
  show: boolean;
  collaborator?: any;
}

const props = defineProps<Props>();
const emit = defineEmits(['close', 'submit']);

const loading = ref(false);
const error = ref('');

const isEdit = computed(() => !!props.collaborator);

const formData = ref({
  fullname: '',
  email: '',
  telephone: '',
  poste: '',
  salaire_base: 0,
  date_embauche: new Date().toISOString().split('T')[0],
  date_naissance: '',
  genre: '',
  adresse: '',
  statut: 'actif'
});

// Réinitialiser ou remplir le formulaire quand le modal s'ouvre
watch(() => props.show, (newVal) => {
  if (newVal) {
    error.value = '';
    if (props.collaborator) {
      // Mode édition
      formData.value = {
        fullname: props.collaborator.fullname || props.collaborator.name || '',
        email: props.collaborator.email || '',
        telephone: props.collaborator.telephone || props.collaborator.phone || '',
        poste: props.collaborator.poste || props.collaborator.position || '',
        salaire_base: typeof props.collaborator.salaire_base === 'number' 
          ? props.collaborator.salaire_base 
          : parseFloat(String(props.collaborator.salaire_base || '0').replace(/[^\d]/g, '')) || 0,
        date_embauche: props.collaborator.date_embauche || new Date().toISOString().split('T')[0],
        date_naissance: props.collaborator.date_naissance || '',
        genre: props.collaborator.genre || '',
        adresse: props.collaborator.adresse || '',
        statut: props.collaborator.statut || 'actif'
      };
    } else {
      // Mode création - réinitialiser
      formData.value = {
        fullname: '',
        email: '',
        telephone: '',
        poste: '',
        salaire_base: 0,
        date_embauche: new Date().toISOString().split('T')[0],
        date_naissance: '',
        genre: '',
        adresse: '',
        statut: 'actif'
      };
    }
  }
});

const handleSubmit = () => {
  error.value = '';
  
  // Validation basique
  if (!formData.value.fullname || !formData.value.email || !formData.value.poste) {
    error.value = 'Veuillez remplir tous les champs obligatoires';
    return;
  }

  if (!formData.value.salaire_base || formData.value.salaire_base <= 0) {
    error.value = 'Le salaire doit être supérieur à 0';
    return;
  }

  loading.value = true;
  
  const submitData = {
    ...formData.value,
    isEdit: isEdit.value
  };

  emit('submit', submitData);
  
  // Le loading sera réinitialisé par le parent après succès ou échec
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