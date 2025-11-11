<template>
  <aside
    class="w-64 bg-white border-r border-gray-200 flex flex-col h-screen flex-shrink-0"
  >
    <!-- Logo -->
    <div class="px-4 py-2 border-b border-gray-200 flex-shrink-0">
      <div class="flex items-center">
        <div class="w-22 h-22 flex items-center justify-center">
          <img
            src="/LogoPAYZEN.png"
            alt="Logo"
            class="w-full h-full object-contain"
          />
        </div>
      </div>
    </div>

    <!-- Profil utilisateur -->
    <div class="p-4 border-b border-gray-200 flex-shrink-0">
      <div class="flex items-center gap-3">
        <div
          class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold"
        >
          {{ userInitials }}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-gray-900 truncate">
            {{ user?.fullname }}
          </p>
          <span
            :class="roleClass"
            class="text-xs px-2 py-0.5 rounded-full font-semibold"
          >
            {{ user?.role }}
          </span>
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto p-4 scrollbar-hide">
      <div class="space-y-1">
<router-link
  v-for="item in navigationItems"
  :key="item.path"
  :to="item.path"
  class="nav-item"
  :active-class="item.path === '/dashboard' ? '' : 'nav-item-active'"
  :exact-active-class="item.path === '/dashboard' ? 'nav-item-active' : ''"
>
  <component :is="item.icon" class="w-5 h-5" />
  <span>{{ item.label }}</span>
  <span
    v-if="item.badge"
    class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold"
  >
    {{ item.badge }}
  </span>
</router-link>

      </div>
    </nav>

    <!-- Déconnexion -->
    <div class="p-4 border-t border-gray-200 space-y-3 flex-shrink-0">
      <button
        @click="logout"
        class="w-full flex items-center justify-center gap-2 px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors font-semibold"
      >
        <svg
          class="w-5 h-5"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
          />
        </svg>
        Déconnexion
      </button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { computed, h } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();
const user = authStore.user;

const userInitials = computed(() => {
  if (!user?.fullname) return "NA";
  const parts = user.fullname.split(" ");
  return parts
    .map((p: string) => p[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
});

const roleClass = computed(() => {
  const role = user?.role;
  if (role === "Admin" || role === "Administrateur" || role === "Admin Général")
    return "bg-purple-100 text-purple-700";
  if (role === "Comptable") return "bg-green-100 text-green-700";
  if (role === "Collaborateur") return "bg-blue-100 text-blue-700";
  return "bg-gray-100 text-gray-700";
});

// 🎨 ICÔNES
const DashboardIcon = () =>
  h("svg", { fill: "none", stroke: "currentColor", viewBox: "0 0 24 24" }, [
    h("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6",
    }),
  ]);

const UsersIcon = () =>
  h("svg", { fill: "none", stroke: "currentColor", viewBox: "0 0 24 24" }, [
    h("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z",
    }),
  ]);

const PayrollIcon = () =>
  h("svg", { fill: "none", stroke: "currentColor", viewBox: "0 0 24 24" }, [
    h("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z",
    }),
  ]);

const DeclarationIcon = () =>
  h("svg", { fill: "none", stroke: "currentColor", viewBox: "0 0 24 24" }, [
    h("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
    }),
  ]);

const LeaveIcon = () =>
  h("svg", { fill: "none", stroke: "currentColor", viewBox: "0 0 24 24" }, [
    h("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z",
    }),
  ]);

const ProfileIcon = () =>
  h("svg", { fill: "none", stroke: "currentColor", viewBox: "0 0 24 24" }, [
    h("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z",
    }),
  ]);

const BulletinIcon = () =>
  h("svg", { fill: "none", stroke: "currentColor", viewBox: "0 0 24 24" }, [
    h("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
    }),
  ]);

// 🆕 NOUVELLES ICÔNES
const ExpenseIcon = () =>
  h("svg", { fill: "none", stroke: "currentColor", viewBox: "0 0 24 24" }, [
    h("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z",
    }),
  ]);

const AdvanceIcon = () =>
  h("svg", { fill: "none", stroke: "currentColor", viewBox: "0 0 24 24" }, [
    h("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
    }),
  ]);

const TrainingIcon = () =>
  h("svg", { fill: "none", stroke: "currentColor", viewBox: "0 0 24 24" }, [
    h("path", {
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253",
    }),
  ]);

// ✅ NAVIGATION PAR RÔLE
const navigationItems = computed(() => {
  const role = user?.role;
  const roles = user?.roles || [];

  const isAdmin = role === "Admin" || 
                  role === "Administrateur" || 
                  role === "Admin Général" ||
                  roles.includes("Admin") ||
                  roles.includes("Administrateur") ||
                  roles.includes("Admin Général");

  const isComptable = role === "Comptable" || roles.includes("Comptable");
  const isCollaborateur = role === "Collaborateur" || roles.includes("Collaborateur");

  // 👨‍💼 NAVIGATION ADMIN
  if (isAdmin) {
    return [
      { path: "/dashboard", label: "Tableau de bord", icon: DashboardIcon },
      { path: "/dashboard/employees", label: "Gestion du personnel", icon: UsersIcon },
      { path: "/dashboard/payroll", label: "Paie et bulletins", icon: PayrollIcon },
      { path: "/dashboard/declarations", label: "Déclarations", icon: DeclarationIcon },
      { path: "/dashboard/leaves", label: "Congés et absences", icon: LeaveIcon },
    ];
  }

  // 💰 NAVIGATION COMPTABLE
  if (isComptable) {
    return [
      { path: "/dashboard", label: "Tableau de bord", icon: DashboardIcon },
      { path: "/dashboard/payroll", label: "Paie et bulletins", icon: PayrollIcon },
      { path: "/dashboard/declarations", label: "Déclarations", icon: DeclarationIcon },
      { path: "/dashboard/leaves", label: "Congés", icon: LeaveIcon },
    ];
  }

  // 👤 NAVIGATION COLLABORATEUR (avec nouveaux modules)
  return [
    { path: "/dashboard", label: "Mon espace", icon: DashboardIcon },
    { path: "/dashboard/my-bulletins", label: "Mes bulletins", icon: BulletinIcon },
    { path: "/dashboard/my-leaves", label: "Mes congés", icon: LeaveIcon },
    { path: "/dashboard/my-expenses", label: "Notes de frais", icon: ExpenseIcon, badge: 0 },
    { path: "/dashboard/my-advances", label: "Avances salaire", icon: AdvanceIcon, badge: 0 },
    { path: "/dashboard/my-trainings", label: "Formations & Entretiens", icon: TrainingIcon, badge: 0 },
    { path: "/dashboard/profile", label: "Mon profil", icon: ProfileIcon },
  ];
});

const logout = async () => {
  if (confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
    try {
      await authStore.logout();
      await router.push("/login");
      window.location.href = "/login";
    } catch (error) {
      console.error("Erreur déconnexion:", error);
      localStorage.clear();
      window.location.href = "/login";
    }
  }
};
</script>

<style scoped>
.nav-item {
  @apply flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg transition-colors font-medium hover:bg-gray-100 hover:text-gray-900;
}

.nav-item-active {
  @apply bg-blue-600 text-white font-semibold;
}

.nav-item-active:hover {
  @apply bg-blue-600 text-white;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>
