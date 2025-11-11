<template>
  <div class="p-6 space-y-8">
    <!-- 🔹 En-tête -->
    <div>
      <h1 class="text-3xl font-semibold text-gray-800 mb-2">Formations & Entretiens</h1>
      <p class="text-gray-500">Suivez votre parcours de développement professionnel.</p>
    </div>

    <!-- 🔹 Section Formations -->
    <div class="bg-white rounded-2xl shadow-md p-6 space-y-6">
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-medium text-gray-800 flex items-center gap-2">
          <span class="w-2 h-2 bg-indigo-600 rounded-full"></span> Mes formations
        </h2>
        <button
          class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium"
          @click="openTrainingModal = true"
        >
          S’inscrire à une formation
        </button>
      </div>

      <!-- Tableau des formations -->
      <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
          <thead>
            <tr class="bg-gray-100 text-left text-gray-700">
              <th class="p-3 rounded-tl-xl">Titre</th>
              <th class="p-3">Date</th>
              <th class="p-3">Formateur</th>
              <th class="p-3">Statut</th>
              <th class="p-3 rounded-tr-xl">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in trainings"
              :key="index"
              class="border-b hover:bg-gray-50 transition"
            >
              <td class="p-3 font-medium text-gray-800">{{ item.title }}</td>
              <td class="p-3">{{ item.date }}</td>
              <td class="p-3">{{ item.trainer }}</td>
              <td class="p-3">
                <span
                  class="px-2 py-1 rounded-full text-xs font-semibold"
                  :class="{
                    'bg-yellow-100 text-yellow-700': item.status === 'En cours',
                    'bg-green-100 text-green-700': item.status === 'Terminée',
                    'bg-blue-100 text-blue-700': item.status === 'À venir'
                  }"
                >
                  {{ item.status }}
                </span>
              </td>
              <td class="p-3">
                <button
                  v-if="item.status === 'À venir'"
                  class="text-indigo-600 hover:underline text-sm"
                >
                  Annuler
                </button>
              </td>
            </tr>

            <tr v-if="!trainings.length">
              <td colspan="5" class="text-center p-4 text-gray-500">
                Aucune formation enregistrée.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 🔹 Section Entretiens -->
    <div class="bg-white rounded-2xl shadow-md p-6 space-y-6">
      <h2 class="text-xl font-medium text-gray-800 flex items-center gap-2">
        <span class="w-2 h-2 bg-indigo-600 rounded-full"></span> Mes entretiens annuels
      </h2>

      <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
          <thead>
            <tr class="bg-gray-100 text-left text-gray-700">
              <th class="p-3 rounded-tl-xl">Date</th>
              <th class="p-3">Évaluateur</th>
              <th class="p-3">Objectif</th>
              <th class="p-3">Résultat</th>
              <th class="p-3 rounded-tr-xl">Statut</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(interview, index) in interviews"
              :key="index"
              class="border-b hover:bg-gray-50 transition"
            >
              <td class="p-3">{{ interview.date }}</td>
              <td class="p-3">{{ interview.evaluator }}</td>
              <td class="p-3">{{ interview.goal }}</td>
              <td class="p-3">{{ interview.result || 'En attente...' }}</td>
              <td class="p-3">
                <span
                  class="px-2 py-1 rounded-full text-xs font-semibold"
                  :class="{
                    'bg-yellow-100 text-yellow-700': interview.status === 'Planifié',
                    'bg-green-100 text-green-700': interview.status === 'Terminé'
                  }"
                >
                  {{ interview.status }}
                </span>
              </td>
            </tr>

            <tr v-if="!interviews.length">
              <td colspan="5" class="text-center p-4 text-gray-500">
                Aucun entretien enregistré.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal d'inscription -->
    <div
      v-if="openTrainingModal"
      class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50"
    >
      <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-lg">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Nouvelle inscription</h3>

        <form @submit.prevent="addTraining" class="space-y-4">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Titre de la formation</label>
            <input
              v-model="newTraining.title"
              type="text"
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400"
              required
            />
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">Formateur</label>
            <input
              v-model="newTraining.trainer"
              type="text"
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400"
              required
            />
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">Date</label>
            <input
              v-model="newTraining.date"
              type="date"
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400"
              required
            />
          </div>

          <div class="flex justify-end gap-3">
            <button
              type="button"
              @click="openTrainingModal = false"
              class="text-gray-600 hover:underline"
            >
              Annuler
            </button>
            <button
              type="submit"
              class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
            >
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const openTrainingModal = ref(false);
const newTraining = ref({ title: "", trainer: "", date: "" });

const trainings = ref([
  { title: "Leadership & Communication", trainer: "M. Koffi", date: "2025-09-12", status: "Terminée" },
  { title: "Excel Avancé", trainer: "Mme Yao", date: "2025-12-01", status: "À venir" }
]);

const interviews = ref([
  { date: "2025-10-10", evaluator: "DRH", goal: "Bilan annuel", result: "Bon progrès", status: "Terminé" },
  { date: "2025-12-20", evaluator: "Responsable équipe", goal: "Objectifs 2026", result: "", status: "Planifié" }
]);

function addTraining() {
  trainings.value.unshift({
    title: newTraining.value.title,
    trainer: newTraining.value.trainer,
    date: newTraining.value.date,
    status: "À venir"
  });
  openTrainingModal.value = false;
  newTraining.value = { title: "", trainer: "", date: "" };
}
</script>

