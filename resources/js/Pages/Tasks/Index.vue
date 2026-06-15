<template>
  <div class="min-h-screen bg-slate-50 flex flex-col font-sans pb-32 overflow-x-hidden">
    <!-- Header -->
    <header class="p-8 pb-4 animate-in">
      <div class="flex justify-between items-center mb-7">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Tugas Saya</h1>
        <Link href="/tasks/create" class="premium-button w-10 h-10 rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </Link>
      </div>

      <!-- Search Bar -->
      <div class="relative mb-6 group">
        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
        <input 
          type="text" 
          v-model="searchQuery"
          placeholder="Cari Tugas..." 
          class="w-full pl-11 pr-4 py-4 bg-white border border-gray-200/60 rounded-xl shadow-sm focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-[15px] transition-all text-slate-700 placeholder:text-gray-400 hover:border-gray-300"
        />
      </div>

      <!-- Tabs -->
      <div class="flex space-x-2 overflow-x-auto pb-2 scrollbar-hide">
        <button 
          v-for="tab in tabs" 
          :key="tab"
          @click="activeTab = tab"
          :class="[
            'px-5 py-2.5 text-[13px] font-bold rounded-full whitespace-nowrap transition-all',
            activeTab === tab 
              ? 'text-white bg-slate-800 shadow-sm' 
              : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'
          ]"
        >
          {{ tab }}
        </button>
      </div>
    </header>

    <main class="flex-1 px-8 pt-4 animate-in delay-100">
      <div class="space-y-6">
        
        <div v-if="filteredTasks.length === 0" class="text-center py-10 text-slate-400 font-medium bg-white rounded-2xl border border-gray-100 border-dashed">
          Tidak ada tugas dalam kategori ini.
        </div>

        <!-- Task Items Loop -->
        <Link 
          v-for="task in filteredTasks" 
          :key="task.id" 
          :href="`/tasks/${task.id}/edit`"
          class="premium-card p-6 flex flex-col group hover:border-blue-200 transition-all cursor-pointer animate-in block"
        >
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-[16px] font-bold text-slate-800 group-hover:text-primary transition-colors pr-4">{{ task.judul }}</h3>
            <button class="text-gray-400 hover:text-slate-800 p-2 -mr-2 -mt-2 rounded-full hover:bg-slate-100 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
            </button>
          </div>
          <div class="flex justify-between items-center mt-2">
            <span class="text-[13px] text-slate-500 font-medium flex items-center bg-slate-50 px-3 py-1.5 rounded-md border border-slate-100">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mr-2 text-slate-400"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
              {{ task.deadline }}
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
      <Link href="/dashboard" class="flex flex-col items-center p-2 text-slate-400 hover:text-slate-700 transition-colors group">
        <div class="p-1.5 rounded-xl group-hover:bg-slate-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-home"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
      </Link>
      
      <Link href="/tasks" class="flex flex-col items-center p-2 text-primary group">
        <div class="bg-blue-50 p-1.5 rounded-xl group-hover:bg-blue-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="lucide lucide-clipboard-list"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
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
  </div>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  tasks: Array
})

const tabs = ['Semua', 'Proses', 'Selesai', 'Tertunda']
const activeTab = ref('Semua')
const searchQuery = ref('')

// Computed property untuk memfilter tasks berdasarkan tab dan search query
const filteredTasks = computed(() => {
  let result = props.tasks || []
  
  // Filter by Tab
  if (activeTab.value !== 'Semua') {
    result = result.filter(task => task.status === activeTab.value)
  }
  
  // Filter by Search Query
  if (searchQuery.value.trim() !== '') {
    const query = searchQuery.value.toLowerCase().trim()
    result = result.filter(task => task.judul.toLowerCase().includes(query))
  }
  
  return result
})
</script>
