<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Message d'erreur si profil manquant -->
    <div v-if="error" class="max-w-6xl mx-auto p-6">
      <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl p-8 text-white shadow-2xl">
        <div class="flex items-start gap-4">
          <svg class="w-12 h-12 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <div>
            <h3 class="text-2xl font-bold mb-2">⚠️ Profil incomplet</h3>
            <p class="text-white/90">{{ error }}</p>
            <p class="text-sm text-white/75 mt-2">Veuillez contacter votre administrateur RH</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Contenu principal -->
    <div v-else class="max-w-7xl mx-auto p-6 space-y-6">
      
      <!-- 🎯 Header Hero Section -->
      <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
        
        <div class="relative z-10 flex items-center justify-between flex-wrap gap-6">
          <div class="flex items-center gap-6">
            <div class="w-24 h-24 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center text-3xl font-black border-4 border-white/40 shadow-lg">
              {{ userInitials }}
            </div>
            <div>
              <p class="text-sm text-white/80 mb-1">Bienvenue 👋</p>
              <h1 class="text-4xl font-black mb-2">{{ user?.fullname }}</h1>
              <p class="text-blue-100 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{ user?.email }}
              </p>
            </div>
          </div>
          
          <div class="flex gap-3">
            <button @click="showCongeModal = true" class="bg-white text-purple-600 px-6 py-3 rounded-xl font-bold hover:scale-105 transition-transform shadow-lg flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Demander un congé
            </button>
            <button @click="showNotifications = !showNotifications" class="bg-white/20 backdrop-blur-xl text-white p-3 rounded-xl hover:bg-white/30 transition-all relative">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span v-if="notifications.length > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">{{ notifications.length }}</span>
            </button>
          </div>
        </div>
      </div>

      <!-- 📊 Stats Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-green-500">
          <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <span class="text-4xl font-black text-green-600">{{ stats.solde_conges }}</span>
          </div>
          <h3 class="text-gray-700 font-bold text-lg">Solde congés</h3>
          <p class="text-sm text-gray-500">Jours disponibles</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-orange-500">
          <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span class="text-4xl font-black text-orange-600">{{ stats.conges_en_attente }}</span>
          </div>
          <h3 class="text-gray-700 font-bold text-lg">En attente</h3>
          <p class="text-sm text-gray-500">Demandes congés</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-blue-500">
          <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <span class="text-4xl font-black text-blue-600">{{ stats.paies_count }}</span>
          </div>
          <h3 class="text-gray-700 font-bold text-lg">Bulletins</h3>
          <p class="text-sm text-gray-500">Documents disponibles</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-purple-500">
          <div class="flex items-center justify-between mb-4">
            <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
              <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <span class="text-4xl font-black text-purple-600">{{ stats.formations_count }}</span>
          </div>
          <h3 class="text-gray-700 font-bold text-lg">Formations</h3>
          <p class="text-sm text-gray-500">Cette année</p>
        </div>
      </div>

      <!-- 📋 Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- 📅 CALENDRIER RH -->
          <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                  <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                Calendrier RH - {{ currentMonthName }} {{ currentYear }}
              </h2>
              <div class="flex gap-2">
                <button @click="previousMonth" class="p-2 hover:bg-gray-100 rounded-lg">
                  <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                </button>
                <button @click="nextMonth" class="p-2 hover:bg-gray-100 rounded-lg">
                  <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Mini Calendar -->
            <div class="grid grid-cols-7 gap-2">
              <div v-for="day in ['L', 'M', 'M', 'J', 'V', 'S', 'D']" :key="day" 
                   class="text-center text-xs font-bold text-gray-500 py-2">
                {{ day }}
              </div>
              <div v-for="day in calendarDays" :key="day.date" 
                   :class="[
                     'aspect-square rounded-lg p-1 text-center text-sm cursor-pointer transition-all',
                     day.isToday ? 'bg-blue-600 text-white font-bold' : '',
                     day.hasEvent ? 'bg-green-100 hover:bg-green-200' : 'hover:bg-gray-100',
                     !day.isCurrentMonth ? 'text-gray-300' : 'text-gray-900'
                   ]">
                <div class="text-xs">{{ day.day }}</div>
                <div v-if="day.hasEvent" class="w-1 h-1 bg-green-600 rounded-full mx-auto mt-1"></div>
              </div>
            </div>

            <!-- Légende -->
            <div class="mt-4 flex items-center gap-4 text-xs text-gray-600">
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-blue-600 rounded"></div>
                <span>Aujourd'hui</span>
              </div>
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-100 rounded"></div>
                <span>Événement</span>
              </div>
            </div>
          </div>

          <!-- Bulletins récents -->
          <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                Bulletins récents
              </h2>
              <button @click="goToBulletins" class="text-blue-600 hover:text-blue-700 font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all">
                Voir tout
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>

            <div v-if="loading" class="text-center py-12">
              <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
            </div>

            <div v-else-if="recentBulletins.length === 0" class="text-center py-12 text-gray-400">
              <svg class="w-20 h-20 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <p class="font-semibold">Aucun bulletin disponible</p>
            </div>

            <div v-else class="space-y-3">
              <div v-for="bulletin in recentBulletins" :key="bulletin.id" 
                   class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl hover:shadow-md transition-all group">
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <div>
                    <p class="font-bold text-gray-900">Période {{ bulletin.periode }}</p>
                    <p class="text-sm text-gray-600 font-semibold">{{ formatMontant(bulletin.net) }}</p>
                  </div>
                </div>
                <button @click="downloadBulletin(bulletin)" class="text-blue-600 hover:text-blue-700 p-3 hover:bg-white rounded-xl transition-all">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Congés récents -->
          <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                Mes congés
              </h2>
              <button @click="goToHistorique" class="text-green-600 hover:text-green-700 font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all">
                Voir tout
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>

            <div v-if="loadingConges" class="text-center py-12">
              <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-green-600 border-t-transparent"></div>
            </div>

            <div v-else-if="recentConges.length === 0" class="text-center py-12 text-gray-400">
              <svg class="w-20 h-20 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p class="font-semibold">Aucune demande de congé</p>
            </div>

            <div v-else class="space-y-3">
              <div v-for="conge in recentConges" :key="conge.id" 
                   class="flex items-center justify-between p-5 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl hover:shadow-md transition-all">
                <div class="flex items-center gap-4 flex-1">
                  <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <div class="flex-1">
                    <p class="font-bold text-gray-900">{{ conge.type }}</p>
                    <p class="text-sm text-gray-600">{{ conge.periode }} • {{ conge.nb_jours }} jours</p>
                  </div>
                </div>
                <span :class="['px-4 py-2 rounded-xl text-sm font-bold', conge.statutClass]">
                  {{ conge.statutLabel }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column (1/3) - Quick Links -->
        <div class="space-y-6">
          
          <!-- Quick Access -->
          <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-black text-gray-900 mb-4">Accès rapide</h3>
            <div class="space-y-2">
              <router-link to="/dashboard/my-bulletins" 
                           class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl hover:shadow-md transition-all group">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div>
                  <p class="font-bold text-gray-900">Mes bulletins</p>
                  <p class="text-xs text-gray-500">Télécharger mes fiches</p>
                </div>
              </router-link>

              <router-link to="/dashboard/my-leaves" 
                           class="flex items-center gap-3 p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl hover:shadow-md transition-all group">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <p class="font-bold text-gray-900">Mes congés</p>
                  <p class="text-xs text-gray-500">Gérer mes demandes</p>
                </div>
              </router-link>

              <router-link to="/dashboard/expenses" 
                           class="flex items-center gap-3 p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl hover:shadow-md transition-all group">
                <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="font-bold text-gray-900">Notes de frais</p>
                  <p class="text-xs text-gray-500">Soumettre une note</p>
                </div>
              </router-link>

              <router-link to="/dashboard/advances" 
                           class="flex items-center gap-3 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl hover:shadow-md transition-all group">
                <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="font-bold text-gray-900">Avances salaire</p>
                  <p class="text-xs text-gray-500">Faire une demande</p>
                </div>
              </router-link>

              <router-link to="/dashboard/trainings" 
                           class="flex items-center gap-3 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:shadow-md transition-all group">
                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
                <div>
                  <p class="font-bold text-gray-900">Formations & Entretiens</p>
                  <p class="text-xs text-gray-500">Mon parcours RH</p>
                </div>
              </router-link>

              <router-link to="/dashboard/profile" 
                           class="flex items-center gap-3 p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl hover:shadow-md transition-all group">
                <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
                <div>
                  <p class="font-bold text-gray-900">Mon profil</p>
                  <p class="text-xs text-gray-500">Infos personnelles</p>
                </div>
              </router-link>
            </div>
          </div>

          <!-- Prochains événements -->
          <div class="bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-black text-gray-900 mb-4">📅 Prochains événements</h3>
            <div class="space-y-3 text-sm">
              <div class="flex items-start gap-3 p-3 bg-white rounded-lg">
                <div class="w-2 h-2 bg-green-600 rounded-full mt-1.5"></div>
                <div>
                  <p class="font-bold text-gray-900">Congé approuvé</p>
                  <p class="text-xs text-gray-600">15-20 novembre 2025</p>
                </div>
              </div>
              <div class="flex items-start gap-3 p-3 bg-white rounded-lg">
                <div class="w-2 h-2 bg-purple-600 rounded-full mt-1.5"></div>
                <div>
                  <p class="font-bold text-gray-900">Formation RH</p>
                  <p class="text-xs text-gray-600">25 novembre 2025</p>
                </div>
              </div>
              <div class="flex items-start gap-3 p-3 bg-white rounded-lg">
                <div class="w-2 h-2 bg-blue-600 rounded-full mt-1.5"></div>
                <div>
                  <p class="font-bold text-gray-900">Entretien annuel</p>
                  <p class="text-xs text-gray-600">30 novembre 2025</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Modal Congé -->
    <CongeModal
      :show="showCongeModal"
      :on-close="() => (showCongeModal = false)"
      :on-submit="handleCongeSubmit"
    />

    <!-- Notifications Panel (Slide-in) -->
    <transition name="slide">
      <div v-if="showNotifications" class="fixed inset-y-0 right-0 w-96 bg-white shadow-2xl z-50 overflow-y-auto">
        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-black text-gray-900">Notifications</h2>
            <button @click="showNotifications = false" class="text-gray-400 hover:text-gray-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <div v-if="notifications.length === 0" class="text-center py-12 text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <p class="font-semibold">Aucune notification</p>
          </div>
          
          <div v-else class="space-y-3">
            <div v-for="notif in notifications" :key="notif.id" 
                 class="p-4 bg-blue-50 rounded-xl border-l-4 border-blue-500">
              <p class="font-bold text-gray-900 mb-1">{{ notif.title }}</p>
              <p class="text-sm text-gray-600">{{ notif.message }}</p>
              <p class="text-xs text-gray-400 mt-2">{{ notif.date }}</p>
            </div>
          </div>
        </div>
      </div>
    </transition>
    
    <!-- Overlay for notifications -->
    <div v-if="showNotifications" @click="showNotifications = false" 
         class="fixed inset-0 bg-black/50 z-40"></div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";
import CongeModal from "./CongeModal.vue";
import api from "../services/api/axios";

const authStore = useAuthStore();
const router = useRouter();
const user = authStore.user;

const stats = ref({
  solde_conges: 30,
  conges_en_attente: 0,
  paies_count: 0,
  formations_count: 2,
});

const collaboratorInfo = ref<any>(null);
const recentBulletins = ref<any[]>([]);
const recentConges = ref<any[]>([]);
const loading = ref(false);
const loadingConges = ref(false);
const showCongeModal = ref(false);
const showNotifications = ref(false);
const error = ref("");

const notifications = ref([
  { id: 1, title: "Congé approuvé", message: "Votre congé du 15-20 nov a été approuvé", date: "Il y a 2h" },
  { id: 2, title: "Nouveau bulletin", message: "Bulletin de octobre 2025 disponible", date: "Hier" },
]);

// Calendrier
const currentDate = ref(new Date());

const currentMonthName = computed(() => {
  return currentDate.value.toLocaleDateString('fr-FR', { month: 'long' });
});

const currentYear = computed(() => {
  return currentDate.value.getFullYear();
});

const calendarDays = computed(() => {
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth();
  
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const prevLastDay = new Date(year, month, 0);
  
  const firstDayOfWeek = firstDay.getDay() === 0 ? 7 : firstDay.getDay();
  const days: any[] = [];
  
  // Jours du mois précédent
  for (let i = firstDayOfWeek - 2; i >= 0; i--) {
    days.push({
      day: prevLastDay.getDate() - i,
      date: new Date(year, month - 1, prevLastDay.getDate() - i),
      isCurrentMonth: false,
      isToday: false,
      hasEvent: false,
    });
  }
  
  // Jours du mois actuel
  const today = new Date();
  for (let i = 1; i <= lastDay.getDate(); i++) {
    const currentDateCheck = new Date(year, month, i);
    days.push({
      day: i,
      date: currentDateCheck,
      isCurrentMonth: true,
      isToday: 
        i === today.getDate() &&
        month === today.getMonth() &&
        year === today.getFullYear(),
      hasEvent: i === 15 || i === 20 || i === 25 || i === 30, // Exemple d'événements
    });
  }
  
  // Jours du mois suivant
  const remainingDays = 42 - days.length;
  for (let i = 1; i <= remainingDays; i++) {
    days.push({
      day: i,
      date: new Date(year, month + 1, i),
      isCurrentMonth: false,
      isToday: false,
      hasEvent: false,
    });
  }
  
  return days;
});

const previousMonth = () => {
  currentDate.value = new Date(
    currentDate.value.getFullYear(),
    currentDate.value.getMonth() - 1,
    1
  );
};

const nextMonth = () => {
  currentDate.value = new Date(
    currentDate.value.getFullYear(),
    currentDate.value.getMonth() + 1,
    1
  );
};

const userInitials = computed(() => {
  if (!user?.fullname) return "NA";
  const parts = user.fullname.split(" ");
  return parts
    .map((p: string) => p[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
});

onMounted(async () => {
  await loadDashboardData();
});

const loadDashboardData = async () => {
  try {
    console.log("=== CHARGEMENT DASHBOARD COLLABORATEUR ===");

    try {
      const dashResponse = await api.get("/dashboard");
      console.log("Stats dashboard:", dashResponse.data);
      stats.value = { ...stats.value, ...dashResponse.data.stats };
    } catch (err) {
      console.warn("Erreur chargement stats dashboard:", err);
    }

    const collabResponse = await api.get("/me");
    console.log("Infos utilisateur:", collabResponse.data);
    const userId = collabResponse.data.id;

    try {
      const collaborators = await api.get("/collaborators");
      console.log("Réponse collaborators:", collaborators.data);

      const collabData = Array.isArray(collaborators.data)
        ? collaborators.data
        : collaborators.data.data || [];

      console.log("Liste collaborateurs:", collabData);

      const myCollab = collabData.find((c: any) => c.user_id === userId);
      console.log("Mon profil collaborateur:", myCollab);

      if (myCollab) {
        collaboratorInfo.value = myCollab;
        await loadBulletins(myCollab.id);
        await loadConges(myCollab.id);
      } else {
        console.error("Profil collaborateur non trouvé pour user_id:", userId);
        error.value =
          "Votre profil collaborateur n'est pas encore créé. Veuillez contacter l'administrateur.";
      }
    } catch (err: any) {
      console.error("Erreur chargement profil collaborateur:", err);
      if (err.response?.status === 404) {
        error.value =
          "Votre profil collaborateur n'existe pas. Veuillez contacter l'administrateur.";
      } else {
        error.value = "Erreur lors du chargement de votre profil.";
      }
    }
  } catch (error) {
    console.error("Erreur chargement dashboard:", error);
  }
};

const loadBulletins = async (collaboratorId: number) => {
  loading.value = true;
  try {
    console.log("Chargement bulletins pour collaborator_id:", collaboratorId);
    const response = await api.get(`/collaborators/${collaboratorId}/paies`);
    console.log("Bulletins reçus:", response.data);

    const data = Array.isArray(response.data)
      ? response.data
      : response.data.historique || [];
    recentBulletins.value = data.slice(0, 3).map((p: any) => ({
      id: p.id,
      periode: `${p.mois}/${p.annee}`,
      net: parseFloat(p.net_a_payer || 0),
    }));

    stats.value.paies_count = data.length;
  } catch (error) {
    console.error("Erreur chargement bulletins:", error);
  } finally {
    loading.value = false;
  }
};

const loadConges = async (collaboratorId: number) => {
  loadingConges.value = true;
  try {
    console.log("Chargement congés pour collaborator_id:", collaboratorId);
    const response = await api.get(`/collaborators/${collaboratorId}/conges`);
    console.log("Congés reçus:", response.data);

    const data = Array.isArray(response.data)
      ? response.data
      : response.data.historique || [];
    recentConges.value = data.slice(0, 3).map((c: any) => ({
      id: c.id,
      periode: `${formatDate(c.date_debut)} - ${formatDate(c.date_fin)}`,
      nb_jours: c.nb_jours,
      type: getTypeLabel(c.type),
      statut: c.statut,
      statutLabel: getStatutLabel(c.statut),
      statutClass: getStatutClass(c.statut),
    }));

    stats.value.conges_en_attente = data.filter(
      (c: any) => c.statut === "en_attente"
    ).length;
  } catch (error) {
    console.error("Erreur chargement congés:", error);
  } finally {
    loadingConges.value = false;
  }
};

const handleCongeSubmit = async (data: any) => {
  if (!collaboratorInfo.value?.id) {
    alert(
      "Votre profil collaborateur n'est pas disponible. Veuillez contacter l'administrateur."
    );
    return;
  }

  try {
    await api.post("/conges", {
      ...data,
      collaborator_id: collaboratorInfo.value.id,
    });
    showCongeModal.value = false;
    alert("Demande de congé envoyée avec succès !");
    await loadConges(collaboratorInfo.value.id);
  } catch (error) {
    console.error("Erreur:", error);
    alert("Erreur lors de l'envoi de la demande");
  }
};

const goToBulletins = () => {
  router.push("/dashboard/my-bulletins");
};

const goToHistorique = () => {
  router.push("/dashboard/my-leaves");
};

const downloadBulletin = (bulletin: any) => {
  alert(`Téléchargement du bulletin ${bulletin.periode}...`);
};

const formatMontant = (montant: number) => {
  return (
    new Intl.NumberFormat("fr-FR", { minimumFractionDigits: 0 }).format(
      montant
    ) + " FCFA"
  );
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return "N/A";
  const date = new Date(dateStr);
  return date.toLocaleDateString("fr-FR", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
};

const getTypeLabel = (type: string) => {
  const labels: Record<string, string> = {
    annuel: "Congé annuel",
    maladie: "Congé maladie",
    maternite: "Congé maternité",
    autre: "Autre",
  };
  return labels[type] || type;
};

const getStatutLabel = (statut: string) => {
  const labels: Record<string, string> = {
    en_attente: "En attente",
    approuvé: "Approuvé",
    refusé: "Refusé",
  };
  return labels[statut] || statut;
};

const getStatutClass = (statut: string) => {
  const classes: Record<string, string> = {
    en_attente: "bg-orange-100 text-orange-700",
    approuvé: "bg-green-100 text-green-700",
    refusé: "bg-red-100 text-red-700",
  };
  return classes[statut] || "bg-gray-100 text-gray-700";
};
</script>

<style scoped>
.slide-enter-active, .slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from {
  transform: translateX(100%);
}
.slide-leave-to {
  transform: translateX(100%);
}
</style>
