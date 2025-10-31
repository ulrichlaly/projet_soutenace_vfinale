<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Mon profil</h2>
        <p class="text-sm text-gray-500 mt-1">Gérez vos informations personnelles</p>
      </div>
    </div>

    <div v-if="successMessage" class="bg-green-50 border-2 border-green-200 rounded-lg p-4 flex items-center gap-3">
      <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <p class="text-green-700 font-semibold">{{ successMessage }}</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <div class="xl:col-span-1 space-y-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center">
          <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-5xl font-bold mb-4">
            {{ userInitials }}
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-1">{{ profile.fullname }}</h3>
          <p class="text-sm text-gray-600 mb-3">{{ profile.poste }}</p>
          <span class="px-4 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
            {{ profile.statut || 'Actif' }}
          </span>

          <div class="mt-6 pt-6 border-t border-gray-200 space-y-3 text-left">
            <div class="flex items-center gap-3 text-sm">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
              </svg>
              <span class="text-gray-600">Matricule :</span>
              <span class="font-semibold text-gray-900">{{ profile.matricule }}</span>
            </div>

            <div class="flex items-center gap-3 text-sm">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span class="text-gray-600">Embauche :</span>
              <span class="font-semibold text-gray-900">{{ formatDate(profile.date_embauche) }}</span>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-xl p-6 shadow-lg">
          <h4 class="text-sm font-semibold opacity-90 mb-4"> Mes statistiques</h4>
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm">Ancienneté</span>
              <span class="font-bold text-lg">{{ anciennete }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm">Congés restants</span>
              <span class="font-bold text-lg">{{ soldeConges }} jours</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm">Bulletins reçus</span>
              <span class="font-bold text-lg">{{ bulletinsCount }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Informations personnelles</h3>
            <button v-if="!editMode" @click="editMode = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Modifier
            </button>
          </div>

          <form @submit.prevent="saveProfile" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nom complet</label>
                <input
                  v-model="formData.fullname"
                  type="text"
                  :disabled="!editMode"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:text-gray-500"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input
                  v-model="formData.email"
                  type="email"
                  disabled
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                />
                <p class="text-xs text-gray-500 mt-1">L'email ne peut pas être modifié</p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                <input
                  v-model="formData.tel"
                  type="tel"
                  :disabled="!editMode"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:text-gray-500"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date de naissance</label>
                <input
                  v-model="formData.date_naissance"
                  type="date"
                  :disabled="!editMode"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:text-gray-500"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Genre</label>
                <select
                  v-model="formData.genre"
                  :disabled="!editMode"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:text-gray-500"
                >
                  <option value="">Sélectionner</option>
                  <option value="M">Masculin</option>
                  <option value="F">Féminin</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse</label>
                <input
                  v-model="formData.adresse"
                  type="text"
                  :disabled="!editMode"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:text-gray-500"
                />
              </div>
            </div>

            <div class="pt-6 border-t border-gray-200">
              <h4 class="text-md font-bold text-gray-900 mb-4">Informations professionnelles</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Poste</label>
                  <input
                    v-model="profile.poste"
                    type="text"
                    disabled
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Date d'embauche</label>
                  <input
                    :value="formatDate(profile.date_embauche)"
                    type="text"
                    disabled
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Matricule</label>
                  <input
                    v-model="profile.matricule"
                    type="text"
                    disabled
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                  <input
                    v-model="profile.statut"
                    type="text"
                    disabled
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                  />
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div v-if="editMode" class="flex gap-3 pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="cancelEdit"
                class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
              >
                Annuler
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
              >
                {{ saving ? 'Enregistrement...' : 'Enregistrer les modifications' }}
              </button>
            </div>
          </form>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Changer mon mot de passe</h3>
          <form @submit.prevent="changePassword" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe actuel</label>
              <input
                v-model="passwordData.current"
                type="password"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="••••••••"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Nouveau mot de passe</label>
              <input
                v-model="passwordData.new"
                type="password"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="••••••••"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe</label>
              <input
                v-model="passwordData.confirm"
                type="password"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="••••••••"
              />
            </div>

            <button
              type="submit"
              class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all"
            >
              Mettre à jour le mot de passe
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import api from '../services/api/axios';

const authStore = useAuthStore();
const editMode = ref(false);
const saving = ref(false);
const successMessage = ref('');

const profile = ref<any>({
  fullname: '',
  email: '',
  tel: '',
  matricule: '',
  poste: '',
  date_embauche: '',
  date_naissance: '',
  genre: '',
  adresse: '',
  statut: 'actif'
});

const formData = ref<any>({});

const passwordData = ref({
  current: '',
  new: '',
  confirm: ''
});

const soldeConges = ref(30);
const bulletinsCount = ref(0);

const userInitials = computed(() => {
  if (!profile.value.fullname) return 'NA';
  const parts = profile.value.fullname.split(' ');
  return parts.map((p: string) => p[0]).join('').toUpperCase().slice(0, 2);
});

const anciennete = computed(() => {
  if (!profile.value.date_embauche) return 'N/A';
  const embauche = new Date(profile.value.date_embauche);
  const now = new Date();
  const diff = now.getTime() - embauche.getTime();
  const years = Math.floor(diff / (1000 * 60 * 60 * 24 * 365));
  const months = Math.floor((diff % (1000 * 60 * 60 * 24 * 365)) / (1000 * 60 * 60 * 24 * 30));
  
  if (years > 0) {
    return `${years} an${years > 1 ? 's' : ''}`;
  }
  return `${months} mois`;
});

onMounted(async () => {
  await loadProfile();
});

const loadProfile = async () => {
  try {
    const userResponse = await api.get('/me');
    const userId = userResponse.data.id;

    const collabResponse = await api.get('/collaborators');
    const collabData = Array.isArray(collabResponse.data) ? collabResponse.data : collabResponse.data.data || [];
    const myCollab = collabData.find((c: any) => c.user_id === userId);

    if (myCollab) {
      profile.value = {
        id: myCollab.id,
        fullname: userResponse.data.fullname,
        email: userResponse.data.email,
        tel: userResponse.data.tel,
        matricule: myCollab.matricule,
        poste: myCollab.poste,
        date_embauche: myCollab.date_embauche,
        date_naissance: myCollab.date_naissance,
        genre: myCollab.genre,
        adresse: myCollab.adresse,
        statut: myCollab.statut
      };

      formData.value = { ...profile.value };

      const congesResponse = await api.get(`/collaborators/${myCollab.id}/conges`);
      const congesData = Array.isArray(congesResponse.data) ? congesResponse.data : congesResponse.data.historique || [];
      const joursPris = congesData
        .filter((c: any) => c.statut === 'approuvé')
        .reduce((sum: number, c: any) => sum + (c.nb_jours || 0), 0);
      soldeConges.value = 30 - joursPris;

      const paiesResponse = await api.get(`/collaborators/${myCollab.id}/paies`);
      const paiesData = Array.isArray(paiesResponse.data) ? paiesResponse.data : paiesResponse.data.historique || [];
      bulletinsCount.value = paiesData.length;
    }
  } catch (error) {
    console.error('Erreur chargement profil:', error);
  }
};

const cancelEdit = () => {
  editMode.value = false;
  formData.value = { ...profile.value };
};

const saveProfile = async () => {
  saving.value = true;
  successMessage.value = '';
  
  try {
    await api.put('/profile', {
      fullname: formData.value.fullname,
      telephone: formData.value.tel,
      date_naissance: formData.value.date_naissance,
      genre: formData.value.genre,
      adresse: formData.value.adresse
    });


    await loadProfile();
    
    await authStore.fetchUser();
    
    successMessage.value = 'Profil mis à jour avec succès !';
    editMode.value = false;
    
    setTimeout(() => {
      successMessage.value = '';
    }, 5000);
    
  } catch (error: any) {
    console.error('Erreur sauvegarde profil:', error);
    const errorMsg = error.response?.data?.message || error.message;
    alert('Erreur lors de la sauvegarde du profil: ' + errorMsg);
  } finally {
    saving.value = false;
  }
};



const changePassword = async () => {
  if (passwordData.value.new !== passwordData.value.confirm) {
    alert('Les mots de passe ne correspondent pas');
    return;
  }

  if (passwordData.value.new.length < 8) {
    alert('Le mot de passe doit contenir au moins 8 caractères');
    return;
  }

  try {
    await api.post('/change-password', {
      current_password: passwordData.value.current,
      new_password: passwordData.value.new
    });

    successMessage.value = 'Mot de passe modifié avec succès !';
    passwordData.value = { current: '', new: '', confirm: '' };
    
    setTimeout(() => {
      successMessage.value = '';
    }, 5000);
  } catch (error) {
    console.error('Erreur changement mot de passe:', error);
    alert('Erreur lors du changement de mot de passe');
  }
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return 'N/A';
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' });
};
</script>