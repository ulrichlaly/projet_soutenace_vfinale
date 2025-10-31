
export const getRoleDashboard = (role: string): string => {
  const roleRoutes: Record<string, string> = {
    'Admin': '/admin/dashboard',
    'Administrateur': '/admin/dashboard',
    'Admin Général': '/admin/dashboard',
    'RH': '/rh/dashboard',
    'Comptable': '/comptable/dashboard',
    'Collaborateur': '/collaborateur/dashboard',
  };

  return roleRoutes[role] || '/collaborateur/dashboard';
};