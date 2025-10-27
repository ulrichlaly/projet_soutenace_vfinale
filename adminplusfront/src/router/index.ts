import { createRouter, createWebHistory } from "vue-router";

const routes = [
  {
    path: "/",
    redirect: "/dashboard",
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("../components/Login.vue"),
  },
  {
    path: "/dashboard",
    component: () => import("../components/DashboardLayout.vue"),
    meta: { requiresAuth: true },
    children: [
      {
        path: "",
        name: "Dashboard",
        component: () => import("../components/Dashboard.vue"),
      },
      {
        path: "employees",
        name: "Employees",
        component: () => import("../components/Employees.vue"),
      },
      {
        path: "departments",
        name: "Departments",
        component: () => import("../components/Departments.vue"),
      },
      {
        path: "attendance",
        name: "Attendance",
        component: () => import("../components/Attendance.vue"),
      },
      {
        path: "payroll",
        name: "Payroll",
        component: () => import("../components/Payroll.vue"),
      },
      {
        path: "leaves",
        name: "Leaves",
        component: () => import("../components/Leaves.vue"),
      },
      {
        path: "reports",
        name: "Reports",
        component: () => import("../components/Reports.vue"),
      },
      {
        path: "settings",
        name: "Settings",
        component: () => import("../components/Settings.vue"),
      },
      {
  path: '/paies',
  name: 'paies',
  component: () => import('../components/Payroll.vue')
},
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Guard de navigation
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");

  if (to.meta.requiresAuth && !token) {
    next("/login");
  } else if (to.path === "/login" && token) {
    next("/dashboard");
  } else {
    next();
  }
});

export default router;