<template>
  <DashboardLayout>
    <div class="min-h-screen bg-slate-50 font-sans pb-28 overflow-x-hidden">
      <header class="p-8 pb-4 animate-in">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Tables</h1>
            <p class="text-[15px] text-slate-500 font-medium">Contoh tabel data (UI)</p>
          </div>
        </div>
      </header>

      <main class="px-8 pt-2 animate-in delay-100">
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
              <h2 class="text-[16px] font-extrabold text-slate-800">Daftar Tugas</h2>
              <p class="text-sm font-medium text-slate-500">Data demo untuk tampilan tabel</p>
            </div>

            <div class="flex items-center gap-3">
              <input
                v-model="search"
                type="text"
                placeholder="Cari judul..."
                class="w-full md:w-64 px-4 py-3 rounded-xl border border-gray-200/60 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-[15px]"
              />
            </div>
          </div>

          <div class="overflow-x-auto -mx-6 px-6">
            <table class="min-w-full text-left border-separate border-spacing-0">
              <thead>
                <tr class="text-xs font-extrabold text-slate-600">
                  <th class="pb-3 pr-4">#</th>
                  <th class="pb-3 pr-4">Judul</th>
                  <th class="pb-3 pr-4">Deadline</th>
                  <th class="pb-3 pr-4">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="row in filtered"
                  :key="row.id"
                  class="border-t border-gray-100 hover:bg-slate-50/50 transition-colors"
                >
                  <td class="py-4 pr-4 font-bold text-slate-700">{{ row.id }}</td>
                  <td class="py-4 pr-4 font-bold text-slate-800">{{ row.judul }}</td>
                  <td class="py-4 pr-4 font-medium text-slate-600">{{ row.deadline }}</td>
                  <td class="py-4 pr-4">
                    <span
                      class="inline-flex items-center px-3 py-1.5 text-[12px] font-bold rounded-md shadow-sm border"
                      :class="statusClass(row.status)"
                    >
                      {{ row.status }}
                    </span>
                  </td>
                </tr>

                <tr v-if="filtered.length === 0">
                  <td colspan="4" class="py-10 text-center text-slate-400 font-medium">
                    Tidak ada data.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const search = ref('')

const rows = ref([
  { id: 1, judul: 'Kumpul Laporan Praktikum', deadline: '2026-06-10', status: 'Proses' },
  { id: 2, judul: 'Submit Tugas Mandiri', deadline: '2026-06-13', status: 'Selesai' },
  { id: 3, judul: 'Buat Outline Presentasi Minggu Ini', deadline: '2026-06-16', status: 'Tertunda' },
  { id: 4, judul: 'Revisi Makalah', deadline: '2026-06-20', status: 'Proses' },
])

const filtered = computed(() => {
  const q = search.value.trim().toLowerCase()
  if (!q) return rows.value
  return rows.value.filter((r) => r.judul.toLowerCase().includes(q))
})

function statusClass(status) {
  if (status === 'Proses') return 'bg-yellow-100 text-yellow-700 border-yellow-200/50'
  if (status === 'Selesai') return 'bg-green-100 text-green-700 border-green-200/50'
  if (status === 'Tertunda') return 'bg-red-100 text-red-700 border-red-200/50'
  return 'bg-slate-100 text-slate-700 border-slate-200'
}
</script>

