<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Paie et bulletins</h2>
        <p class="text-sm text-gray-500 mt-1">Gestion des salaires et bulletins de paie</p>
      </div>
      <div class="flex items-center gap-3">
        <button class="btn-secondary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Exporter tout
        </button>
        <button class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          Calculer les salaires
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Masse salariale</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">42.5M</h3>
            <p class="text-xs text-green-600 font-semibold">+8% vs mois dernier</p>
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
            <h3 class="text-3xl font-bold text-gray-900 mb-2">1,247</h3>
            <p class="text-xs text-blue-600 font-semibold">100% traités</p>
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
            <h3 class="text-3xl font-bold text-gray-900 mb-2">1,189</h3>
            <p class="text-xs text-green-600 font-semibold">95.3% effectués</p>
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
            <h3 class="text-3xl font-bold text-gray-900 mb-2">58</h3>
            <p class="text-xs text-orange-600 font-semibold">4.7% restants</p>
          </div>
          <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">Bulletins de paie</h3>
        <div class="flex items-center gap-3">
          <select class="input-field w-auto">
            <option>Octobre 2025</option>
            <option>Septembre 2025</option>
            <option>Août 2025</option>
          </select>
          <select class="input-field w-auto">
            <option value="">Tous les départements</option>
            <option>Administration</option>
            <option>Finance</option>
            <option>RH</option>
            <option>Technique</option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Employé</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Département</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Salaire Brut</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Déductions</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Salaire Net</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Statut</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="payroll in payrollData"
              :key="payroll.id"
              class="table-row"
            >
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div :class="payroll.avatarColor" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ payroll.initials }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-900">{{ payroll.name }}</p>
                    <p class="text-xs text-gray-500">{{ payroll.position }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6">
                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                  {{ payroll.department }}
                </span>
              </td>
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ payroll.grossSalary }}</td>
              <td class="py-4 px-6 text-sm text-red-600">-{{ payroll.deductions }}</td>
              <td class="py-4 px-6 text-sm font-bold text-gray-900">{{ payroll.netSalary }}</td>
              <td class="py-4 px-6">
                <span :class="payroll.statusClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ payroll.status }}
                </span>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  <button class="p-2 hover:bg-blue-50 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                  <button class="p-2 hover:bg-green-50 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                  </button>
                  <button class="p-2 hover:bg-primary-50 rounded-lg transition-colors group">
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

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Répartition par département</h3>
        <div class="space-y-4">
          <div
            v-for="dept in departmentPayroll"
            :key="dept.name"
          >
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">{{ dept.name }}</span>
              <span class="text-sm font-bold text-gray-900">{{ dept.total }}</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
              <div
                :class="dept.color"
                class="h-full rounded-full transition-all duration-500"
                :style="{ width: dept.percentage + '%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Historique des paiements</h3>
        <div class="space-y-3">
          <div
            v-for="payment in paymentHistory"
            :key="payment.id"
            class="flex items-center justify-between p-4 rounded-lg border border-gray-200"
          >
            <div class="flex items-center gap-3">
              <div :class="payment.iconBg" class="w-10 h-10 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900">{{ payment.month }}</p>
                <p class="text-xs text-gray-500">{{ payment.employees }} employés</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-sm font-bold text-gray-900">{{ payment.amount }}</p>
              <p class="text-xs text-gray-500">{{ payment.date }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const payrollData = [
  { id: 1, name: 'Kokou Mensah', position: 'Analyste RH', department: 'RH', grossSalary: '500,000', deductions: '50,000', netSalary: '450,000', status: 'Payé', statusClass: 'bg-green-100 text-green-700', initials: 'KM', avatarColor: 'bg-blue-500' },
  { id: 2, name: 'Amina Saidou', position: 'Comptable', department: 'Finance', grossSalary: '650,000', deductions: '70,000', netSalary: '580,000', status: 'Payé', statusClass: 'bg-green-100 text-green-700', initials: 'AS', avatarColor: 'bg-green-500' },
  { id: 3, name: 'Jean Baptiste', position: 'Développeur', department: 'Technique', grossSalary: '700,000', deductions: '80,000', netSalary: '620,000', status: 'En attente', statusClass: 'bg-orange-100 text-orange-700', initials: 'JB', avatarColor: 'bg-primary-500' },
  { id: 4, name: 'Thomas Agbodjan', position: 'Chef de Projet', department: 'Technique', grossSalary: '850,000', deductions: '100,000', netSalary: '750,000', status: 'Payé', statusClass: 'bg-green-100 text-green-700', initials: 'TA', avatarColor: 'bg-orange-500' },
  { id: 5, name: 'Marie Kossou', position: 'Support', department: 'Support', grossSalary: '470,000', deductions: '50,000', netSalary: '420,000', status: 'Payé', statusClass: 'bg-green-100 text-green-700', initials: 'MK', avatarColor: 'bg-red-500' },
  { id: 6, name: 'Serge Akplogan', position: 'Directeur', department: 'Finance', grossSalary: '1,100,000', deductions: '120,000', netSalary: '980,000', status: 'Payé', statusClass: 'bg-green-100 text-green-700', initials: 'SA', avatarColor: 'bg-teal-500' },
  { id: 7, name: 'Rachelle Dossou', position: 'Analyste', department: 'Technique', grossSalary: '620,000', deductions: '70,000', netSalary: '550,000', status: 'En attente', statusClass: 'bg-orange-100 text-orange-700', initials: 'RD', avatarColor: 'bg-pink-500' },
  { id: 8, name: 'Rodrigue Hounsou', position: 'Responsable RH', department: 'RH', grossSalary: '800,000', deductions: '80,000', netSalary: '720,000', status: 'Payé', statusClass: 'bg-green-100 text-green-700', initials: 'RH', avatarColor: 'bg-cyan-500' }
]

const departmentPayroll = [
  { name: 'Technique', total: '28.9M FCFA', percentage: 95, color: 'bg-orange-500' },
  { name: 'Finance', total: '22.3M FCFA', percentage: 78, color: 'bg-green-500' },
  { name: 'Administration', total: '18.5M FCFA', percentage: 65, color: 'bg-blue-500' },
  { name: 'RH', total: '15.8M FCFA', percentage: 55, color: 'bg-primary-500' },
  { name: 'Support', total: '12.4M FCFA', percentage: 43, color: 'bg-red-500' }
]

const paymentHistory = [
  { id: 1, month: 'Septembre 2025', employees: 1245, amount: '41.2M FCFA', date: '01 Oct 2025', iconBg: 'bg-green-500' },
  { id: 2, month: 'Août 2025', employees: 1238, amount: '39.7M FCFA', date: '01 Sep 2025', iconBg: 'bg-green-500' },
  { id: 3, month: 'Juillet 2025', employees: 1230, amount: '41.3M FCFA', date: '01 Août 2025', iconBg: 'bg-green-500' },
  { id: 4, month: 'Juin 2025', employees: 1215, amount: '35.8M FCFA', date: '01 Juil 2025', iconBg: 'bg-green-500' }
]
</script>
