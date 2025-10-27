<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Congés et absences</h2>
        <p class="text-sm text-gray-500 mt-1">Gérez les demandes de congés</p>
      </div>
      <button class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Nouvelle demande
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">En attente</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">24</h3>
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
            <h3 class="text-3xl font-bold text-gray-900 mb-2">156</h3>
            <p class="text-xs text-green-600 font-semibold">Ce mois</p>
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
            <p class="text-sm font-medium text-gray-600 mb-1">En congé</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">58</h3>
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
            <h3 class="text-3xl font-bold text-gray-900 mb-2">12</h3>
            <p class="text-xs text-red-600 font-semibold">Ce mois</p>
          </div>
          <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">Demandes en attente</h3>
        <div class="flex items-center gap-3">
          <select class="input-field w-auto">
            <option value="">Tous les types</option>
            <option>Congé annuel</option>
            <option>Congé maladie</option>
            <option>Congé maternité</option>
            <option>Permission</option>
          </select>
        </div>
      </div>

      <div class="space-y-3">
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
              <p class="text-xs text-gray-500">{{ request.position }} - {{ request.department }}</p>
            </div>
          </div>
          <div class="flex items-center gap-6">
            <div>
              <p class="text-xs text-gray-500">Type</p>
              <span :class="request.typeClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                {{ request.type }}
              </span>
            </div>
            <div class="text-center">
              <p class="text-xs text-gray-500">Période</p>
              <p class="text-sm font-semibold text-gray-900">{{ request.period }}</p>
            </div>
            <div class="text-center">
              <p class="text-xs text-gray-500">Durée</p>
              <p class="text-sm font-bold text-gray-900">{{ request.duration }}</p>
            </div>
            <div class="text-center">
              <p class="text-xs text-gray-500">Date demande</p>
              <p class="text-sm text-gray-700">{{ request.requestDate }}</p>
            </div>
            <div class="flex items-center gap-2">
              <button class="p-2 bg-green-500 hover:bg-green-600 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </button>
              <button class="p-2 bg-red-500 hover:bg-red-600 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">Historique des congés</h3>
        <select class="input-field w-auto">
          <option>Octobre 2025</option>
          <option>Septembre 2025</option>
          <option>Août 2025</option>
        </select>
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
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="leave in leaveHistory"
              :key="leave.id"
              class="table-row"
            >
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div :class="leave.avatarColor" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ leave.initials }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-900">{{ leave.name }}</p>
                    <p class="text-xs text-gray-500">{{ leave.department }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6">
                <span :class="leave.typeClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ leave.type }}
                </span>
              </td>
              <td class="py-4 px-6 text-sm text-gray-700">{{ leave.startDate }}</td>
              <td class="py-4 px-6 text-sm text-gray-700">{{ leave.endDate }}</td>
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ leave.duration }}</td>
              <td class="py-4 px-6">
                <span :class="leave.statusClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ leave.status }}
                </span>
              </td>
              <td class="py-4 px-6">
                <button class="text-primary-600 hover:text-primary-700 text-sm font-medium">Détails</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Types de congés</h3>
        <div class="space-y-4">
          <div
            v-for="type in leaveTypes"
            :key="type.name"
            class="flex items-center justify-between p-4 rounded-lg border border-gray-200"
          >
            <div class="flex items-center gap-3">
              <div :class="type.iconBg" class="w-10 h-10 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900">{{ type.name }}</p>
                <p class="text-xs text-gray-500">{{ type.description }}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-lg font-bold text-gray-900">{{ type.count }}</p>
              <p class="text-xs text-gray-500">demandes</p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Solde de congés</h3>
        <div class="space-y-4">
          <div
            v-for="balance in leaveBalance"
            :key="balance.id"
            class="p-4 rounded-lg border border-gray-200"
          >
            <div class="flex items-center justify-between mb-3">
              <div class="flex items-center gap-3">
                <div :class="balance.avatarColor" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                  {{ balance.initials }}
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-900">{{ balance.name }}</p>
                  <p class="text-xs text-gray-500">{{ balance.department }}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-lg font-bold text-primary-600">{{ balance.remaining }}</p>
                <p class="text-xs text-gray-500">jours restants</p>
              </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
              <div
                :class="balance.progressColor"
                class="h-full rounded-full"
                :style="{ width: balance.percentage + '%' }"
              ></div>
            </div>
            <div class="flex items-center justify-between mt-2">
              <span class="text-xs text-gray-500">Utilisés: {{ balance.used }} jours</span>
              <span class="text-xs text-gray-500">Total: {{ balance.total }} jours</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const pendingRequests = [
  { id: 1, name: 'Thomas Agbodjan', position: 'Chef de Projet', department: 'Technique', type: 'Congé annuel', typeClass: 'bg-blue-100 text-blue-700', period: '25-30 Oct', duration: '5 jours', requestDate: '15 Oct', initials: 'TA', avatarColor: 'bg-orange-500' },
  { id: 2, name: 'Marie Kossou', position: 'Support', department: 'Support', type: 'Congé maladie', typeClass: 'bg-red-100 text-red-700', period: '22-24 Oct', duration: '2 jours', requestDate: '20 Oct', initials: 'MK', avatarColor: 'bg-red-500' },
  { id: 3, name: 'Serge Akplogan', position: 'Directeur', department: 'Finance', type: 'Permission', typeClass: 'bg-orange-100 text-orange-700', period: '23 Oct', duration: '4 heures', requestDate: '18 Oct', initials: 'SA', avatarColor: 'bg-teal-500' },
  { id: 4, name: 'Rachelle Dossou', position: 'Analyste', department: 'Technique', type: 'Congé maternité', typeClass: 'bg-green-100 text-green-700', period: '01 Nov-28 Fév', duration: '90 jours', requestDate: '10 Oct', initials: 'RD', avatarColor: 'bg-pink-500' }
]

const leaveHistory = [
  { id: 1, name: 'Kokou Mensah', department: 'RH', type: 'Congé annuel', typeClass: 'bg-blue-100 text-blue-700', startDate: '05 Oct', endDate: '10 Oct', duration: '5 jours', status: 'Approuvé', statusClass: 'bg-green-100 text-green-700', initials: 'KM', avatarColor: 'bg-blue-500' },
  { id: 2, name: 'Amina Saidou', department: 'Finance', type: 'Congé maladie', typeClass: 'bg-red-100 text-red-700', startDate: '12 Oct', endDate: '14 Oct', duration: '2 jours', status: 'Approuvé', statusClass: 'bg-green-100 text-green-700', initials: 'AS', avatarColor: 'bg-green-500' },
  { id: 3, name: 'Jean Baptiste', department: 'Technique', type: 'Congé annuel', typeClass: 'bg-blue-100 text-blue-700', startDate: '08 Oct', endDate: '15 Oct', duration: '7 jours', status: 'En cours', statusClass: 'bg-blue-100 text-blue-700', initials: 'JB', avatarColor: 'bg-primary-500' },
  { id: 4, name: 'Fatou Diallo', department: 'Admin', type: 'Permission', typeClass: 'bg-orange-100 text-orange-700', startDate: '18 Oct', endDate: '18 Oct', duration: '3 heures', status: 'Refusé', statusClass: 'bg-red-100 text-red-700', initials: 'FD', avatarColor: 'bg-orange-500' }
]

const leaveTypes = [
  { name: 'Congé annuel', description: 'Congés payés annuels', count: 142, iconBg: 'bg-blue-500' },
  { name: 'Congé maladie', description: 'Arrêts maladie', count: 38, iconBg: 'bg-red-500' },
  { name: 'Congé maternité', description: 'Congé maternité/paternité', count: 12, iconBg: 'bg-green-500' },
  { name: 'Permission', description: 'Absences de courte durée', count: 89, iconBg: 'bg-orange-500' }
]

const leaveBalance = [
  { id: 1, name: 'Kokou Mensah', department: 'RH', remaining: 18, used: 12, total: 30, percentage: 60, progressColor: 'bg-primary-500', initials: 'KM', avatarColor: 'bg-blue-500' },
  { id: 2, name: 'Amina Saidou', department: 'Finance', remaining: 22, used: 8, total: 30, percentage: 73, progressColor: 'bg-green-500', initials: 'AS', avatarColor: 'bg-green-500' },
  { id: 3, name: 'Jean Baptiste', department: 'Technique', remaining: 15, used: 15, total: 30, percentage: 50, progressColor: 'bg-orange-500', initials: 'JB', avatarColor: 'bg-primary-500' }
]
</script>
