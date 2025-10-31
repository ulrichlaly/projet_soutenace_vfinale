<template>
  <div class="relative">
    <button
      @click="showNotifications = !showNotifications"
      class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors"
    >
      <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      
      <span
        v-if="unreadCount > 0"
        class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center"
      >
        {{ unreadCount > 9 ? '9+' : unreadCount }}
      </span>
    </button>

    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="showNotifications"
        v-click-outside="closeNotifications"
        class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 z-50"
      >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
          <h3 class="text-lg font-bold text-gray-900">Notifications</h3>
          <button
            v-if="unreadCount > 0"
            @click="markAllAsRead"
            class="text-xs text-blue-600 font-semibold hover:text-blue-700"
          >
            Tout marquer comme lu
          </button>
        </div>

        <!-- Liste des notifications -->
        <div class="max-h-96 overflow-y-auto">
          <div v-if="notifications.length === 0" class="p-8 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-gray-500 font-medium">Aucune notification</p>
          </div>

          <div
            v-for="notif in notifications"
            :key="notif.id"
            @click="handleNotificationClick(notif)"
            :class="[
              'px-4 py-3 border-b border-gray-100 cursor-pointer transition-colors',
              notif.read ? 'bg-white hover:bg-gray-50' : 'bg-blue-50 hover:bg-blue-100'
            ]"
          >
            <div class="flex gap-3">
              <div :class="getIconClass(notif.type)">
                <component :is="getIcon(notif.type)" class="w-5 h-5" />
              </div>
              <div class="flex-1">
                <p class="text-sm font-semibold text-gray-900">{{ notif.title }}</p>
                <p class="text-xs text-gray-600 mt-1">{{ notif.message }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ formatDate(notif.created_at) }}</p>
              </div>
              <div v-if="!notif.read" class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200 text-center">
          <button class="text-sm text-blue-600 font-semibold hover:text-blue-700">
            Voir toutes les notifications
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import api from '../services/api/axios';

interface Notification {
  id: number;
  type: 'info' | 'success' | 'warning' | 'error';
  title: string;
  message: string;
  read: boolean;
  created_at: string;
  link?: string;
}

const showNotifications = ref(false);
const notifications = ref<Notification[]>([
  {
    id: 1,
    type: 'success',
    title: 'Congé approuvé',
    message: 'Votre demande de congé du 15 au 20 novembre a été approuvée',
    read: false,
    created_at: new Date().toISOString(),
  },
  {
    id: 2,
    type: 'info',
    title: 'Nouveau bulletin de paie',
    message: 'Votre bulletin de paie d\'octobre 2025 est disponible',
    read: false,
    created_at: new Date(Date.now() - 86400000).toISOString(),
  },
]);

const unreadCount = computed(() => 
  notifications.value.filter(n => !n.read).length
);

const closeNotifications = () => {
  showNotifications.value = false;
};

const markAllAsRead = () => {
  notifications.value.forEach(n => n.read = true);
};

const handleNotificationClick = (notif: Notification) => {
  notif.read = true;
  if (notif.link) {
  }
};

const getIconClass = (type: string) => {
  const classes: Record<string, string> = {
    info: 'w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center',
    success: 'w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center',
    warning: 'w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center',
    error: 'w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center',
  };
  return classes[type] || classes.info;
};

const getIcon = (type: string) => {
  return 'svg';
};

const formatDate = (dateStr: string) => {
  const date = new Date(dateStr);
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  const minutes = Math.floor(diff / 60000);
  const hours = Math.floor(diff / 3600000);
  const days = Math.floor(diff / 86400000);

  if (minutes < 1) return 'À l\'instant';
  if (minutes < 60) return `Il y a ${minutes} min`;
  if (hours < 24) return `Il y a ${hours}h`;
  if (days < 7) return `Il y a ${days}j`;
  
  return date.toLocaleDateString('fr-FR', { 
    day: '2-digit', 
    month: 'short' 
  });
};

const vClickOutside = {
  mounted(el: any, binding: any) {
    el.clickOutsideEvent = (event: Event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value();
      }
    };
    document.body.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el: any) {
    document.body.removeEventListener('click', el.clickOutsideEvent);
  },
};
</script>