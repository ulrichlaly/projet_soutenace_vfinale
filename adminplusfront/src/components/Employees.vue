<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Liste des employés</h2>
        <p class="text-sm text-gray-500 mt-1">Gérez tous vos employés en un seul endroit</p>
      </div>
      <button @click="openCreateModal" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-semibold shadow-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Ajouter un employé
      </button>
    </div>

    <!-- Message de succès -->
    <div v-if="successMessage" class="bg-green-50 border-2 border-green-200 rounded-lg p-4 flex items-center gap-3">
      <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <p class="text-green-700 font-semibold">{{ successMessage }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
      <div class="p-6 border-b border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher..."
              class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            />
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

          <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Tous les départements</option>
            <option>Administration</option>
            <option>Finance</option>
            <option>RH</option>
            <option>Technique</option>
          </select>

          <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            <option value="">Tous les statuts</option>
            <option>Actif</option>
            <option>En congé</option>
            <option>Inactif</option>
          </select>

          <button @click="loadEmployees" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Actualiser
          </button>
        </div>
      </div>

      <!-- Loader -->
      <div v-if="loading" class="p-12 flex justify-center">
        <div class="text-center">
          <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="text-gray-600">Chargement des employés...</p>
        </div>
      </div>

      <!-- Tableau -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Employé</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Poste</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Téléphone</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Salaire</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Statut</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="employees.length === 0">
              <td colspan="6" class="py-12 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                  <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  <p class="text-lg font-semibold text-gray-700 mb-2">Aucun employé trouvé</p>
                  <p class="text-sm text-gray-500 mb-4">Commencez par ajouter votre premier employé</p>
                  <button @click="openCreateModal" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Ajouter un employé
                  </button>
                </div>
              </td>
            </tr>
            <tr
              v-for="employee in employees"
              :key="employee.id"
              class="border-b border-gray-100 hover:bg-gray-50 transition-colors"
            >
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div
                    class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold"
                    :class="employee.avatarColor"
                  >
                    {{ employee.initials }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-900">{{ employee.name }}</p>
                    <p class="text-xs text-gray-500">{{ employee.email }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6 text-sm text-gray-700">{{ employee.position }}</td>
              <td class="py-4 px-6 text-sm text-gray-600">{{ employee.phone }}</td>
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ employee.salary }}</td>
              <td class="py-4 px-6">
                <span
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                  :class="employee.statusClass"
                >
                  {{ employee.status }}
                </span>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  <button @click="openEditModal(employee)" class="p-2 hover:bg-blue-50 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button @click="confirmDelete(employee)" class="p-2 hover:bg-red-50 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="p-6 border-t border-gray-100">
        <p class="text-sm text-gray-600">Affichage de {{ employees.length }} employé(s)</p>
      </div>
    </div>

    <!-- Modals -->
    <CollaboratorModal
      v-if="showModal"
      :show="showModal"
      :collaborator="selectedEmployee"
      @close="closeModal"
      @submit="handleSubmit"
    />

    <ConfirmModal
      v-if="showDeleteModal"
      :show="showDeleteModal"
      title="Supprimer l'employé"
      :message="`Êtes-vous sûr de vouloir supprimer cet employé ? Cette action est irréversible.`"
      confirm-text="Oui, supprimer"
      cancel-text="Annuler"
      @confirm="handleDelete"
      @cancel="showDeleteModal = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import CollaboratorModal from '../components/CollaboratorModal.vue';
import ConfirmModal from '../components/ConfirmModal.vue';
import api from '../services/api/axios';

interface DisplayEmployee {
  id: number;
  name: string;
  email: string;
  position: string;
  phone: string;
  salary: string;
  status: string;
  statusClass: string;
  initials: string;
  avatarColor: string;
}

const employees = ref<DisplayEmployee[]>([]);
const loading = ref(false);
const showModal = ref(false);
const showDeleteModal = ref(false);
const selectedEmployee = ref<any>(null);
const employeeToDelete = ref<DisplayEmployee | null>(null);
const successMessage = ref('');
const searchQuery = ref('');

onMounted(() => {
  loadEmployees();
});

const loadEmployees = async () => {
  loading.value = true;
  try {
    const response = await api.get('/collaborators');
    const data = Array.isArray(response.data) ? response.data : response.data.data || [];
    
    employees.value = data.map((collab: any) => ({
      id: collab.id,
      name: collab.nom_complet || 'Non défini',
      email: collab.email || 'Non défini',
      position: collab.poste || 'Non défini',
      phone: collab.telephone || 'N/A',
      salary: collab.salaire_base || '0 FCFA',
      status: collab.statut || 'actif',
      statusClass: collab.statut === 'actif' ? 'bg-green-100 text-green-700' : 
                   collab.statut === 'en_conge' ? 'bg-orange-100 text-orange-700' : 
                   'bg-red-100 text-red-700',
      initials: getInitials(collab.nom_complet || 'NA'),
      avatarColor: getRandomColor()
    }));
  } catch (error) {
    console.error('Erreur:', error);
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  selectedEmployee.value = null;
  showModal.value = true;
};

const openEditModal = (employee: DisplayEmployee) => {
  selectedEmployee.value = {
    id: employee.id,
    fullname: employee.name,
    email: employee.email,
    poste: employee.position,
    telephone: employee.phone,
    salaire_base: parseFloat(employee.salary.replace(/[^\d]/g, '')),
    date_embauche: new Date().toISOString().split('T')[0]
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedEmployee.value = null;
};

const handleSubmit = async (data: any) => {
  try {
    if (data.isEdit && selectedEmployee.value?.id) {
      await api.put(`/collaborators/${selectedEmployee.value.id}`, data);
      successMessage.value = 'Employé modifié avec succès !';
    } else {
      await api.post('/collaborators', data);
      successMessage.value = 'Employé créé avec succès ! Un email a été envoyé avec ses identifiants.';
    }
    
    await loadEmployees();
    closeModal();
    
    setTimeout(() => {
      successMessage.value = '';
    }, 5000);
  } catch (error: any) {
    console.error('Erreur:', error);
    alert(error.response?.data?.message || 'Erreur lors de l\'opération');
  }
};

const confirmDelete = (employee: DisplayEmployee) => {
  employeeToDelete.value = employee;
  showDeleteModal.value = true;
};

const handleDelete = async () => {
  if (employeeToDelete.value?.id) {
    try {
      await api.delete(`/collaborators/${employeeToDelete.value.id}`);
      successMessage.value = 'Employé supprimé avec succès !';
      await loadEmployees();
      
      setTimeout(() => {
        successMessage.value = '';
      }, 3000);
    } catch (error) {
      console.error('Erreur:', error);
      alert('Erreur lors de la suppression');
    }
  }
  showDeleteModal.value = false;
  employeeToDelete.value = null;
};

const getInitials = (name: string) => {
  if (!name || name === 'Non défini') return 'NA';
  const parts = name.split(' ');
  return parts.map(p => p[0]).join('').toUpperCase().slice(0, 2);
};

const getRandomColor = () => {
  const colors = [
    'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-orange-500',
    'bg-red-500', 'bg-teal-500', 'bg-indigo-500', 'bg-pink-500',
    'bg-cyan-500', 'bg-lime-500'
  ];
  return colors[Math.floor(Math.random() * colors.length)];
};
</script>