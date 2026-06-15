<template>
  <div class="min-h-screen bg-slate-50 flex flex-col p-6 font-sans relative overflow-hidden">
    <!-- Header with Back Button -->
    <div class="pt-8 pb-6 relative z-10 animate-in flex items-center justify-between">
      <Link href="/tasks" class="inline-flex items-center justify-center w-11 h-11 -ml-2 text-slate-600 bg-white shadow-sm border border-gray-100 hover:bg-gray-50 hover:shadow-md rounded-full transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </Link>
    </div>

    <!-- Title Section -->
    <div class="mb-8 relative z-10 animate-in delay-100">
      <h1 class="text-[32px] font-black text-slate-800 tracking-tight mb-2">Tugas Baru</h1>
      <p class="text-[15px] text-slate-500 font-medium">Tambahkan tugas baru ke daftar Anda</p>
    </div>

    <!-- Form Section -->
    <form @submit.prevent="submit" class="space-y-6 flex-1 relative z-10 animate-in delay-200">
      
      <!-- Title Field -->
      <div class="space-y-2 group">
        <label for="judul" class="block text-sm font-bold text-slate-700 group-focus-within:text-primary transition-colors">Judul Tugas</label>
        <div class="relative">
          <input 
            type="text" 
            id="judul" 
            v-model="form.judul"
            placeholder="Contoh: Membuat Laporan Keuangan" 
            class="w-full px-4 py-4 bg-white border border-gray-200/80 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-[15px] transition-all text-slate-800 placeholder:text-gray-400 font-medium"
            required
          />
        </div>
        <div v-if="form.errors.judul" class="text-sm text-red-600 mt-1">{{ form.errors.judul }}</div>
      </div>

      <!-- Deskripsi Field -->
      <div class="space-y-2 group">
        <label for="deskripsi" class="block text-sm font-bold text-slate-700 group-focus-within:text-primary transition-colors">Deskripsi</label>
        <div class="relative">
          <textarea 
            id="deskripsi" 
            v-model="form.deskripsi"
            placeholder="Detail tugas..." 
            class="w-full px-4 py-4 bg-white border border-gray-200/80 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-[15px] transition-all text-slate-800 placeholder:text-gray-400 font-medium h-24"
          ></textarea>
        </div>
        <div v-if="form.errors.deskripsi" class="text-sm text-red-600 mt-1">{{ form.errors.deskripsi }}</div>
      </div>

      <!-- Date Field -->
      <div class="space-y-2 group">
        <label for="deadline" class="block text-sm font-bold text-slate-700 group-focus-within:text-primary transition-colors">Tenggat Waktu (Deadline)</label>
        <div class="relative">
          <input 
            type="date" 
            id="deadline" 
            v-model="form.deadline"
            class="w-full px-4 py-4 bg-white border border-gray-200/80 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-[15px] transition-all text-slate-800 placeholder:text-gray-400 font-medium"
          />
        </div>
        <div v-if="form.errors.deadline" class="text-sm text-red-600 mt-1">{{ form.errors.deadline }}</div>
      </div>

      <!-- Status Selection -->
      <div class="space-y-3">
        <label class="block text-sm font-bold text-slate-700">Status</label>
        <div class="grid grid-cols-3 gap-3">
          <!-- Proses -->
          <label class="relative cursor-pointer">
            <input type="radio" name="status" value="Proses" v-model="form.status" class="peer sr-only" />
            <div class="w-full p-3 bg-white border border-gray-200 rounded-xl text-center peer-checked:bg-yellow-50 peer-checked:border-yellow-400 peer-checked:text-yellow-700 text-slate-500 font-bold text-[13px] transition-all shadow-sm">
              Proses
            </div>
            <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-yellow-500 transition-opacity">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><circle cx="12" cy="12" r="10"/></svg>
            </div>
          </label>
          <!-- Tertunda -->
          <label class="relative cursor-pointer">
            <input type="radio" name="status" value="Tertunda" v-model="form.status" class="peer sr-only" />
            <div class="w-full p-3 bg-white border border-gray-200 rounded-xl text-center peer-checked:bg-red-50 peer-checked:border-red-400 peer-checked:text-red-700 text-slate-500 font-bold text-[13px] transition-all shadow-sm">
              Tertunda
            </div>
            <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-red-500 transition-opacity">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><circle cx="12" cy="12" r="10"/></svg>
            </div>
          </label>
          <!-- Selesai -->
          <label class="relative cursor-pointer">
            <input type="radio" name="status" value="Selesai" v-model="form.status" class="peer sr-only" />
            <div class="w-full p-3 bg-white border border-gray-200 rounded-xl text-center peer-checked:bg-green-50 peer-checked:border-green-400 peer-checked:text-green-700 text-slate-500 font-bold text-[13px] transition-all shadow-sm">
              Selesai
            </div>
            <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 text-green-500 transition-opacity">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><circle cx="12" cy="12" r="10"/></svg>
            </div>
          </label>
        </div>
        <div v-if="form.errors.status" class="text-sm text-red-600 mt-1">{{ form.errors.status }}</div>
      </div>

      <!-- Submit Button -->
      <div class="pt-8 pb-10">
        <button 
          type="submit" 
          class="premium-button w-full py-4 px-4 rounded-xl text-[15px] font-bold flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed group relative overflow-hidden"
          :disabled="form.processing"
        >
          <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
          <span class="relative z-10" v-if="form.processing">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
          </span>
          <span class="relative z-10 uppercase tracking-wider flex items-center" v-else>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            Simpan Tugas
          </span>
        </button>
      </div>

    </form>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  judul: '',
  deskripsi: '',
  deadline: '',
  status: 'Proses'
})

const submit = () => {
  form.post('/tasks', {
    onSuccess: () => form.reset()
  })
}
</script>
