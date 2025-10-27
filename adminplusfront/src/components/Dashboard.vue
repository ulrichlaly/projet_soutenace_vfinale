<template>
  <div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      <div
        v-for="stat in stats"
        :key="stat.id"
        class="stat-card group hover:scale-105 transition-transform duration-200"
      >
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 mb-1">{{ stat.label }}</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ stat.value }}</h3>
            <div class="flex items-center gap-2">
              <span
                class="text-xs font-semibold px-2 py-1 rounded-full"
                :class="stat.trend === 'up' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
              >
                {{ stat.change }}
              </span>
              <span class="text-xs text-gray-500">vs mois dernier</span>
            </div>
          </div>
          <div
            class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors duration-200"
            :class="stat.bgColor"
          >
            <component :is="stat.icon" class="w-6 h-6 text-white" />
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <div class="xl:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-gray-900">Évolution de la masse salariale</h3>
          <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 outline-none">
            <option>6 derniers mois</option>
            <option>12 derniers mois</option>
            <option>Cette année</option>
          </select>
        </div>
        <div class="h-64 flex items-end justify-between gap-2">
          <div
            v-for="(bar, index) in chartData"
            :key="index"
            class="flex-1 bg-gradient-to-t from-primary-500 to-primary-400 rounded-t-lg relative group cursor-pointer hover:from-primary-600 hover:to-primary-500 transition-all duration-200"
            :style="{ height: bar.height + '%' }"
          >
            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-2 py-1 rounded text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
              {{ bar.value }} FCFA
            </div>
            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs text-gray-600 font-medium">{{ bar.month }}</span>
          </div>
        </div>
        <div class="mt-8 pt-4 border-t border-gray-100">
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">Total cette année</span>
            <span class="font-bold text-gray-900">245,780,000 FCFA</span>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Répartition par département</h3>
        <div class="space-y-4">
          <div
            v-for="dept in departments"
            :key="dept.id"
            class="group cursor-pointer"
          >
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700 group-hover:text-primary-600 transition-colors">{{ dept.name }}</span>
              <span class="text-sm font-bold text-gray-900">{{ dept.count }}</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
              <div
                class="h-full rounded-full transition-all duration-500 group-hover:opacity-80"
                :class="dept.color"
                :style="{ width: dept.percentage + '%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-gray-900">Employés récents</h3>
          <button class="text-sm text-primary-600 font-medium hover:text-primary-700">Voir tout</button>
        </div>
        <div class="space-y-3">
          <div
            v-for="employee in recentEmployees"
            :key="employee.id"
            class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer"
          >
            <div
              class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold text-sm"
              :class="employee.avatarColor"
            >
              {{ employee.initials }}
            </div>
            <div class="flex-1">
              <p class="text-sm font-semibold text-gray-900">{{ employee.name }}</p>
              <p class="text-xs text-gray-500">{{ employee.position }}</p>
            </div>
            <div class="text-right">
              <p class="text-xs text-gray-500">{{ employee.department }}</p>
              <p class="text-xs font-medium text-gray-700">{{ employee.joinDate }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-gray-900">Demandes en attente</h3>
          <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">{{ pendingRequests.length }} en attente</span>
        </div>
        <div class="space-y-3">
          <div
            v-for="request in pendingRequests"
            :key="request.id"
            class="flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:border-primary-300 transition-colors cursor-pointer"
          >
            <div class="flex items-center gap-3">
              <div
                class="w-2 h-2 rounded-full"
                :class="request.typeColor"
              ></div>
              <div>
                <p class="text-sm font-semibold text-gray-900">{{ request.employee }}</p>
                <p class="text-xs text-gray-500">{{ request.type }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button class="p-1.5 hover:bg-green-50 rounded-lg transition-colors group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </button>
              <button class="p-1.5 hover:bg-red-50 rounded-lg transition-colors group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <h3 class="text-lg font-bold text-gray-900">Présences aujourd'hui</h3>
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
            <span class="text-sm text-gray-600">Présent ({{ attendanceToday.present }})</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
            <span class="text-sm text-gray-600">En retard ({{ attendanceToday.late }})</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
            <span class="text-sm text-gray-600">Absent ({{ attendanceToday.absent }})</span>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Employé</th>
              <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Département</th>
              <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Heure d'arrivée</th>
              <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Statut</th>
              <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="record in attendanceRecords"
              :key="record.id"
              class="table-row"
            >
              <td class="py-4 px-4">
                <div class="flex items-center gap-3">
                  <div
                    class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-xs"
                    :class="record.avatarColor"
                  >
                    {{ record.initials }}
                  </div>
                  <span class="text-sm font-medium text-gray-900">{{ record.name }}</span>
                </div>
              </td>
              <td class="py-4 px-4 text-sm text-gray-600">{{ record.department }}</td>
              <td class="py-4 px-4 text-sm text-gray-900 font-medium">{{ record.arrivalTime }}</td>
              <td class="py-4 px-4">
                <span
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                  :class="record.statusClass"
                >
                  {{ record.status }}
                </span>
              </td>
              <td class="py-4 px-4">
                <button class="text-primary-600 hover:text-primary-700 text-sm font-medium">Détails</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineComponent } from 'vue'

const stats = [
  {
    id: 1,
    label: 'Total Employés',
    value: '1,247',
    change: '+12%',
    trend: 'up',
    bgColor: 'bg-blue-500',
    icon: defineComponent({
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>`
    })
  },
  {
    id: 2,
    label: 'Masse Salariale',
    value: '42.5M',
    change: '+8%',
    trend: 'up',
    bgColor: 'bg-green-500',
    icon: defineComponent({
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>`
    })
  },
  {
    id: 3,
    label: 'Présents Aujourd\'hui',
    value: '1,189',
    change: '+95.3%',
    trend: 'up',
    bgColor: 'bg-primary-500',
    icon: defineComponent({
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`
    })
  },
  {
    id: 4,
    label: 'Congés en Cours',
    value: '58',
    change: '-3%',
    trend: 'down',
    bgColor: 'bg-orange-500',
    icon: defineComponent({
      template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>`
    })
  }
]

const chartData = [
  { month: 'Mai', value: '38.2M', height: 65 },
  { month: 'Juin', value: '35.8M', height: 55 },
  { month: 'Juil', value: '41.3M', height: 75 },
  { month: 'Août', value: '39.7M', height: 68 },
  { month: 'Sept', value: '43.1M', height: 82 },
  { month: 'Oct', value: '42.5M', height: 78 }
]

const departments = [
  { id: 1, name: 'Administration', count: 245, percentage: 85, color: 'bg-blue-500' },
  { id: 2, name: 'Finance', count: 189, percentage: 70, color: 'bg-green-500' },
  { id: 3, name: 'Ressources Humaines', count: 156, percentage: 60, color: 'bg-primary-500' },
  { id: 4, name: 'Technique', count: 298, percentage: 95, color: 'bg-orange-500' },
  { id: 5, name: 'Support', count: 142, percentage: 50, color: 'bg-red-500' }
]

const recentEmployees = [
  { id: 1, name: 'Kokou Mensah', position: 'Analyste RH', department: 'RH', joinDate: '15 Oct 2025', initials: 'KM', avatarColor: 'bg-blue-500' },
  { id: 2, name: 'Amina Saidou', position: 'Comptable', department: 'Finance', joinDate: '12 Oct 2025', initials: 'AS', avatarColor: 'bg-green-500' },
  { id: 3, name: 'Jean Baptiste', position: 'Développeur', department: 'Technique', joinDate: '10 Oct 2025', initials: 'JB', avatarColor: 'bg-primary-500' },
  { id: 4, name: 'Fatou Diallo', position: 'Assistante Admin', department: 'Admin', joinDate: '08 Oct 2025', initials: 'FD', avatarColor: 'bg-orange-500' }
]

const pendingRequests = [
  { id: 1, employee: 'Thomas Agbodjan', type: 'Congé annuel - 5 jours', typeColor: 'bg-blue-500' },
  { id: 2, employee: 'Marie Kossou', type: 'Congé maladie - 2 jours', typeColor: 'bg-red-500' },
  { id: 3, employee: 'Serge Akplogan', type: 'Permission - 4 heures', typeColor: 'bg-orange-500' },
  { id: 4, employee: 'Rachelle Dossou', type: 'Congé maternité', typeColor: 'bg-green-500' }
]

const attendanceToday = {
  present: 1189,
  late: 34,
  absent: 24
}

const attendanceRecords = [
  { id: 1, name: 'Kokou Mensah', department: 'RH', arrivalTime: '07:45', status: 'Présent', statusClass: 'bg-green-100 text-green-700', initials: 'KM', avatarColor: 'bg-blue-500' },
  { id: 2, name: 'Amina Saidou', department: 'Finance', arrivalTime: '08:15', status: 'En retard', statusClass: 'bg-orange-100 text-orange-700', initials: 'AS', avatarColor: 'bg-green-500' },
  { id: 3, name: 'Jean Baptiste', department: 'Technique', arrivalTime: '07:30', status: 'Présent', statusClass: 'bg-green-100 text-green-700', initials: 'JB', avatarColor: 'bg-primary-500' },
  { id: 4, name: 'Thomas Agbodjan', department: 'Admin', arrivalTime: '-', status: 'Absent', statusClass: 'bg-red-100 text-red-700', initials: 'TA', avatarColor: 'bg-orange-500' },
  { id: 5, name: 'Marie Kossou', department: 'Support', arrivalTime: '08:00', status: 'Présent', statusClass: 'bg-green-100 text-green-700', initials: 'MK', avatarColor: 'bg-red-500' }
]
</script>
