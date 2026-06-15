<template>
  <div class="min-h-screen bg-slate-50 flex flex-col font-sans pb-32 overflow-x-hidden relative">
    
    <!-- Header -->
    <header class="p-8 pb-4 animate-in">
      <div class="flex justify-between items-center mb-7">
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Kalender</h1>
        <Link href="/tasks/create" class="premium-button w-10 h-10 rounded-full flex items-center justify-center shadow-md hover:shadow-lg transition-all">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
        </Link>
      </div>
      <p class="text-[15px] text-slate-500 font-medium">Jadwal Tugas Anda</p>
    </header>

    <main class="flex-1 px-8 pt-4 animate-in delay-100">
      <div class="space-y-6">
        
        <div class="text-center py-16 text-slate-400 font-medium bg-white rounded-2xl border border-gray-100 border-dashed flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-4 text-slate-300"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
            <p>Fitur Kalender sedang dalam pengembangan.</p>
        </div>

      </div>
    </main>

    <!-- Bottom Navigation -->
    <div class="glass-nav fixed bottom-0 left-0 right-0 px-6 pt-3 pb-6 flex justify-between items-center z-50">
      <Link href="/dashboard" class="flex flex-col items-center p-2 text-slate-400 hover:text-slate-700 transition-colors group">
        <div class="p-1.5 rounded-xl group-hover:bg-slate-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-home"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
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

      <Link href="/calendar" class="flex flex-col items-center p-2 text-primary group">
        <div class="bg-blue-50 p-1.5 rounded-xl group-hover:bg-blue-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="lucide lucide-calendar-days"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
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

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
