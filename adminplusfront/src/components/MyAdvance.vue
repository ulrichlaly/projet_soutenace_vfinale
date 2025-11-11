<template>
  <div class="p-6 space-y-8">
    <!-- 🔹 En-tête -->
    <div>
      <h1 class="text-3xl font-semibold text-gray-800 mb-2">Mes avances sur salaire</h1>
      <p class="text-gray-500">Demandez, suivez et consultez l’état de vos avances.</p>
    </div>

    <!-- 🔹 Cartes de résumé -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
      <div
        v-for="card in cards"
        :key="card.label"
        class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-5 flex flex-col items-center justify-center"
      >
        <span class="text-gray-500 text-sm">{{ card.label }}</span>
        <span class="text-2xl font-semibold text-gray-800 mt-1">{{ card.value }}</span>
      </div>
    </div>

    <!-- 🔹 Formulaire -->
    <div class="bg-white rounded-2xl shadow-md p-6">
      <h2 class="text-xl font-medium text-gray-800 mb-4 flex items-center gap-2">
        <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
        Nouvelle demande
      </h2>

      <form @submit.prevent="submitRequest" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Montant demandé (FCFA)</label>
            <input
              v-model="amount"
              type="number"
              placeholder="Ex: 50000"
              class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
              required
            />
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">Motif</label>
            <input
              v-model="reason"
              type="text"
              placeholder="Ex: Urgences médicales"
              class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
              required
            />
          </div>
        </div>

        <div class="flex justify-end">
          <button
            type="submit"
            class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm"
          >
            Envoyer la demande
          </button>
        </div>
      </form>
    </div>

    <!-- 🔹 Historique -->
    <div class="bg-white rounded-2xl shadow-md p-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-medium text-gray-800">Historique de mes demandes</h2>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
          <thead>
            <tr class="bg-gray-100 text-left text-gray-700">
              <th class="p-3 rounded-tl-xl">Date</th>
              <th class="p-3">Montant</th>
              <th class="p-3">Motif</th>
              <th class="p-3 rounded-tr-xl">Statut</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in advances"
              :key="index"
              class="border-b hover:bg-gray-50 transition"
            >
              <td class="p-3">{{ item.date }}</td>
              <td class="p-3 font-medium text-gray-800">{{ item.amount.toLocaleString() }} FCFA</td>
              <td class="p-3">{{ item.reason }}</td>
              <td class="p-3">
                <span
                  class="px-2 py-1 rounded-full text-xs font-semibold"
                  :class="{
                    'bg-yellow-100 text-yellow-700': item.status === 'En attente',
                    'bg-green-100 text-green-700': item.status === 'Approuvée',
                    'bg-red-100 text-red-700': item.status === 'Rejetée'
                  }"
                >
                  {{ item.status }}
                </span>
              </td>
            </tr>

            <tr v-if="!advances.length">
              <td colspan="4" class="text-center p-4 text-gray-500">Aucune demande pour le moment.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const amount = ref("");
const reason = ref("");
const advances = ref([
  { date: "2025-10-15", amount: 25000, reason: "Transport", status: "En attente" },
  { date: "2025-09-05", amount: 40000, reason: "Urgence médicale", status: "Approuvée" }
]);

function submitRequest() {
  if (amount.value && reason.value) {
    advances.value.unshift({
      date: new Date().toISOString().split("T")[0],
      amount: parseInt(amount.value),
      reason: reason.value,
      status: "En attente"
    });
    amount.value = "";
    reason.value = "";
    alert("✅ Votre demande a été envoyée avec succès !");
  }
}

const cards = computed(() => [
  {
    label: "Demandes totales",
    value: advances.value.length
  },
  {
    label: "Approuvées",
    value: advances.value.filter(a => a.status === "Approuvée").length
  },
  {
    label: "En attente",
    value: advances.value.filter(a => a.status === "En attente").length
  }
]);
</script>

