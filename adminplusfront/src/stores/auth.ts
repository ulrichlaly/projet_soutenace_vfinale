import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../services/api/axios';

interface User {
  id: number;
  fullname: string;
  email: string;
  tel?: string;
  role: string;
  roles: string[];
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const token = ref<string | null>(localStorage.getItem('token'));
  
  const isAuthenticated = computed(() => !!token.value);
  
  // CORRECTION: S'assurer que userRole renvoie toujours une valeur
  const userRole = computed(() => {
    if (!user.value) return 'Collaborateur';
    return user.value.role || 'Collaborateur';
  });
  
  // CORRECTION: S'assurer que userRoles renvoie toujours un tableau
  const userRoles = computed(() => {
    if (!user.value) return [];
    // Si roles est vide mais role existe, utiliser role comme fallback
    if (user.value.roles && user.value.roles.length > 0) {
      return user.value.roles;
    }
    // Fallback: si pas de roles array, utiliser le role string
    if (user.value.role) {
      return [user.value.role];
    }
    return [];
  });
  
  const isAdmin = computed(() => {
    const roles = userRoles.value;
    return roles.some(role => 
      role === 'Admin' || 
      role === 'Administrateur' || 
      role === 'Admin Général'
    );
  });
  
  const isComptable = computed(() => {
    const roles = userRoles.value;
    return roles.includes('Comptable');
  });
  
  const isCollaborateur = computed(() => {
    const roles = userRoles.value;
    return roles.includes('Collaborateur');
  });

  const login = async (credentials: { email: string; password: string }) => {
    try {
      const response = await api.post('/login', credentials);
      
      console.log('=== RÉPONSE LOGIN ===');
      console.log('Response:', response.data);
      
      // Le backend retourne 'access_token'
      token.value = response.data.access_token;
      localStorage.setItem('token', response.data.access_token);
      
      // Normaliser l'utilisateur pour s'assurer que roles est toujours un array
      const userData = response.data.user;
      user.value = {
        ...userData,
        roles: userData.roles || (userData.role ? [userData.role] : [])
      };
      
      localStorage.setItem('user', JSON.stringify(user.value));
      
      console.log('User après login:', user.value);
      console.log('Rôle principal:', userRole.value);
      console.log('Tous les rôles:', userRoles.value);
      console.log('isAdmin:', isAdmin.value);
      console.log('isComptable:', isComptable.value);
      console.log('isCollaborateur:', isCollaborateur.value);
      console.log('====================');
      
      return response.data;
    } catch (error) {
      console.error('Erreur de connexion:', error);
      throw error;
    }
  };

  const fetchUser = async () => {
    try {
      const response = await api.get('/me');
      
      // Normaliser l'utilisateur
      const userData = response.data;
      user.value = {
        ...userData,
        roles: userData.roles || (userData.role ? [userData.role] : [])
      };
      
      localStorage.setItem('user', JSON.stringify(user.value));
      
      console.log('User après fetchUser:', user.value);
      console.log('Rôles après fetchUser:', userRoles.value);
      
      return user.value;
    } catch (error) {
      console.error('Erreur récupération utilisateur:', error);
      logout();
      throw error;
    }
  };

  const logout = async () => {
    try {
      await api.post('/logout');
    } catch (error) {
      console.error('Erreur logout:', error);
    } finally {
      user.value = null;
      token.value = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
  };

  const restoreUser = () => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      try {
        const parsedUser = JSON.parse(storedUser);
        // Normaliser l'utilisateur restauré
        user.value = {
          ...parsedUser,
          roles: parsedUser.roles || (parsedUser.role ? [parsedUser.role] : [])
        };
        
        console.log('✅ User restauré depuis localStorage:', user.value);
        console.log('   - Rôle principal:', userRole.value);
        console.log('   - Tous les rôles:', userRoles.value);
      } catch (error) {
        console.error('❌ Erreur restauration utilisateur:', error);
        localStorage.removeItem('user');
      }
    }
  };

  return {
    user,
    token,
    isAuthenticated,
    userRole,
    userRoles,
    isAdmin,
    isComptable,
    isCollaborateur,
    login,
    fetchUser,
    logout,
    restoreUser
  };
});
