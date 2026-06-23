<template>
  <DashboardLayout>
    <div class="min-h-screen bg-slate-50 font-sans pb-28 overflow-x-hidden">
      <header class="p-8 pb-4 animate-in">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Forms</h1>
            <p class="text-[15px] text-slate-500 font-medium">Template form untuk koleksi data</p>
          </div>
        </div>
      </header>

      <main class="px-8 pt-2 animate-in delay-100">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <section class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <h2 class="text-[16px] font-extrabold text-slate-800 mb-4">Form Example</h2>

            <form @submit.prevent="onSubmit" class="space-y-4">
              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Judul</label>
                <input
                  v-model="form.title"
                  type="text"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200/60 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-[15px]"
                  placeholder="Masukkan judul"
                />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                  <select
                    v-model="form.status"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200/60 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-[15px]"
                  >
                    <option value="Proses">Proses</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Tertunda">Tertunda</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-bold text-slate-700 mb-2">Priority</label>
                  <select
                    v-model="form.priority"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200/60 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-[15px]"
                  >
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
                <textarea
                  v-model="form.description"
                  rows="5"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200/60 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 text-[15px]"
                  placeholder="Tulis deskripsi"
                ></textarea>
              </div>

              <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" @click="onReset" class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-colors">
                  Reset
                </button>
                <button type="submit" class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold transition-colors shadow-sm shadow-blue-200">
                  Simpan
                </button>
              </div>

              <div v-if="submitted" class="pt-2">
                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 font-bold text-sm">
                  Berhasil: {{ summary }}
                </div>
              </div>
            </form>
          </section>

          <aside class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <h2 class="text-[16px] font-extrabold text-slate-800 mb-3">Catatan</h2>
            <ul class="space-y-3 text-sm text-slate-600 font-medium">
              <li class="flex gap-2">
                <span class="w-2 h-2 rounded-full mt-2 bg-blue-500"></span>
                <span>Form ini hanya demo UI (tanpa menyimpan ke server).</span>
              </li>
              <li class="flex gap-2">
                <span class="w-2 h-2 rounded-full mt-2 bg-purple-500"></span>
                <span>Routing sudah tersedia untuk menghindari 404.</span>
              </li>
            </ul>
          </aside>
        </div>
      </main>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const form = reactive({
  title: '',
  status: 'Proses',
  priority: 'Medium',
  description: '',
})

const submitted = ref(false)

const summary = computed(() => {
  const t = form.title?.trim() || '(tanpa judul)'
  return `${t} • ${form.status} • ${form.priority}`
})

function onReset() {
  form.title = ''
  form.status = 'Proses'
  form.priority = 'Medium'
  form.description = ''
  submitted.value = false
}

function onSubmit() {
  submitted.value = true
}
</script>

