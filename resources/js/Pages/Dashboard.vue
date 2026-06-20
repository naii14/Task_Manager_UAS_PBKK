<template>
  <div class="min-h-screen bg-slate-50 flex flex-col font-sans pb-28 overflow-x-hidden relative">

    <!-- Header -->
    <header class="p-6 pb-4 relative z-10 animate-in">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h2 class="text-sm font-bold text-slate-500 mb-1 tracking-wide uppercase">Selamat Datang 👋</h2>
          <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $page.props.auth.user.name }}</h1>
        </div>
        <button @click="showLogoutModal = true" class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm relative z-10 block focus:outline-none hover:opacity-80 transition-opacity">
          <img :src="`https://ui-avatars.com/api/?name=${encodeURIComponent($page.props.auth.user.name)}&background=0D8ABC&color=fff`" alt="Profile" class="w-full h-full object-cover" />
        </button>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-2 gap-4">
        <!-- Tasks In Progress -->
        <div class="premium-card p-6 flex flex-col group cursor-pointer hover:border-blue-200 transition-colors">
          <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-primary flex items-center justify-center group-hover:scale-110 transition-transform">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
          </div>
          <div class="text-3xl font-black text-slate-800 mb-1">{{ tasks_proses }}</div>
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tugas Proses</div>
        </div>

        <!-- Tasks Completed -->
        <div class="premium-card p-6 flex flex-col group cursor-pointer hover:border-green-200 transition-colors">
          <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            </div>
          </div>
          <div class="text-3xl font-black text-slate-800 mb-1">{{ tasks_selesai }}</div>
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tugas Selesai</div>
        </div>
      </div>
    </header>

    <main class="flex-1 px-6 pt-4 relative z-10 animate-in delay-100">
      <div class="flex justify-between items-center mb-5">
        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Tugas Hari Ini</h2>
        <Link href="/tasks" class="text-sm font-bold text-primary hover:text-blue-700 transition-colors">Lihat Semua</Link>
      </div>

      <div class="space-y-4">

        <div v-if="recent_tasks.length === 0" class="text-center py-8 text-slate-400 font-medium bg-white rounded-2xl border border-gray-100 border-dashed">
          Belum ada tugas terbaru.
        </div>

        <!-- Task Items Loop -->
        <Link
          v-for="task in recent_tasks"
          :key="task.id"
          :href="`/tasks/${task.id}/edit`"
          class="premium-card p-6 flex flex-col group hover:border-blue-200 transition-all cursor-pointer block"
        >
          <div class="flex justify-between items-start mb-3">
            <h3 class="text-[16px] font-bold text-slate-800 group-hover:text-primary transition-colors pr-4">{{ task.judul }}</h3>
            <button class="text-gray-400 hover:text-slate-800 p-2 -mr-2 -mt-2 rounded-full hover:bg-slate-100 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
            </button>
          </div>
          <div class="flex justify-between items-center mt-2">
            <span class="text-[13px] text-slate-500 font-medium flex items-center bg-slate-50 px-3 py-1.5 rounded-md border border-slate-100">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-slate-400"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
              {{ task.deadline || '-' }}
            </span>
            <span :class="[
              'px-3.5 py-1.5 text-[12px] font-bold rounded-md shadow-sm border',
              task.status === 'Proses' ? 'bg-yellow-100 text-yellow-700 border-yellow-200/50' : '',
              task.status === 'Selesai' ? 'bg-green-100 text-green-700 border-green-200/50' : '',
              task.status === 'Tertunda' ? 'bg-red-100 text-red-700 border-red-200/50' : ''
            ]">
              {{ task.status }}
            </span>
          </div>
        </Link>

      </div>
    </main>

    <!-- Bottom Navigation -->
    <div class="glass-nav fixed bottom-0 left-0 right-0 px-6 pt-3 pb-6 flex justify-between items-center z-50">
      <Link href="/dashboard" class="flex flex-col items-center p-2 text-primary group">
        <div class="bg-blue-50 p-1.5 rounded-xl group-hover:bg-blue-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="lucide lucide-home"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
      </Link>

      <Link href="/tasks" class="flex flex-col items-center p-2 text-slate-400 hover:text-slate-700 transition-colors group">
        <div class="p-1.5 rounded-xl group-hover:bg-slate-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
        </div>
      </Link>

      <!-- FAB -->
      <div class="relative -top-8 px-2">
        <Link href="/tasks/create" class="premium-button w-14 h-14 rounded-full flex items-center justify-center relative">
          <div class="absolute inset-0 rounded-full bg-white opacity-20 hover:opacity-0 transition-opacity"></div>
          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus relative z-10"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </Link>
      </div>

      <Link href="/calendar" class="flex flex-col items-center p-2 text-slate-400 hover:text-slate-700 transition-colors group">
        <div class="p-1.5 rounded-xl group-hover:bg-slate-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
        </div>
      </Link>

      <Link href="/profile" class="flex flex-col items-center p-2 text-slate-400 hover:text-slate-700 transition-colors group">
        <div class="p-1.5 rounded-xl group-hover:bg-slate-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
      </Link>
    </div>
    <!-- Logout Confirmation Modal -->
    <div v-if="showLogoutModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white rounded-3xl w-full max-w-[320px] overflow-hidden shadow-2xl">
        <div class="p-6 pb-2 flex justify-center mt-2">
           <div class="w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center">
             <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
           </div>
        </div>
        <div class="text-center px-6">
          <h3 class="text-[19px] font-black text-slate-800 mb-2">Keluar Akun?</h3>
          <p class="text-[14px] text-slate-500 font-medium">Apakah Anda yakin ingin keluar dari sesi ini? Anda harus login kembali nanti.</p>
        </div>
        <div class="p-6 flex gap-3 mt-2">
          <button @click="showLogoutModal = false" class="flex-1 py-3.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition-colors text-[14px]">Batal</button>
          <button @click="confirmLogout" class="flex-1 py-3.5 px-4 bg-red-500 hover:bg-red-600 text-white font-bold rounded-2xl transition-colors shadow-md shadow-red-200 text-[14px]">Ya, Keluar</button>
        </div>
      </div>
    </div>

    <!-- Goodbye Modal -->
    <div v-if="showGoodbyeModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white rounded-3xl w-full max-w-[320px] overflow-hidden shadow-2xl p-8 text-center">
        <div class="flex justify-center mb-6">
           <div class="w-20 h-20 rounded-full bg-blue-50 text-primary flex items-center justify-center animate-bounce">
             <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
           </div>
        </div>
        <h3 class="text-[22px] font-black text-slate-800 mb-2">Sampai Jumpa!</h3>
        <p class="text-[14px] text-slate-500 font-medium">Sesi Anda telah berakhir dengan aman. Semoga harimu menyenangkan!</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  tasks_proses: Number,
  tasks_selesai: Number,
  recent_tasks: Array
})

const showLogoutModal = ref(false)
const showGoodbyeModal = ref(false)

const confirmLogout = () => {
    showLogoutModal.value = false
    showGoodbyeModal.value = true

    setTimeout(() => {
        router.post(route('logout'))
    }, 1500)
}
</script>

<script>
import QuixlabLayout from '@/Layouts/QuixlabLayout.vue'

export default {
    layout: QuixlabLayout
}
</script>
