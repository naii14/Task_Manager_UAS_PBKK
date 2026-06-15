<template>
  <div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-6 text-center overflow-hidden relative">
    
    <!-- Background Decorators for Premium Feel -->
    <div class="absolute -top-[20%] -left-[10%] w-[70%] h-[50%] bg-blue-100 rounded-full blur-[100px] opacity-60"></div>
    <div class="absolute top-[60%] -right-[20%] w-[60%] h-[50%] bg-indigo-50 rounded-full blur-[80px] opacity-60"></div>

    <!-- Top left logo -->
    <div class="absolute top-8 left-8 font-black text-2xl tracking-tighter text-primary flex items-center animate-in">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 mr-2 shadow-lg shadow-blue-500/20 flex items-center justify-center text-white">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
      </div>
      MyTask
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col items-center justify-center w-full max-w-sm mt-16 relative z-10">
      
      <!-- Premium Illustration Area -->
      <div class="w-72 h-72 mb-12 relative flex items-center justify-center animate-in delay-100">
        <!-- Glowing backdrop -->
        <div class="absolute inset-0 bg-blue-50 rounded-full blur-3xl opacity-50 scale-110"></div>
        
        <!-- Abstract Calendar/Task Illustration -->
        <div class="relative w-56 h-40 bg-white border border-gray-100 rounded-2xl shadow-[0_20px_50px_-12px_rgba(0,0,0,0.1)] flex flex-col overflow-hidden transform -rotate-3 transition-transform hover:rotate-0 duration-500">
          <div class="bg-blue-50 border-b border-blue-100/50 p-3 flex items-center space-x-1.5">
            <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
            <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
            <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
          </div>
          <div class="flex-1 p-4 grid grid-cols-4 gap-2 opacity-60">
            <div class="h-4 bg-gray-100 rounded-sm" v-for="i in 16" :key="i" :class="{'bg-blue-100': i === 6 || i === 11, 'bg-green-100': i === 8}"></div>
          </div>
        </div>

        <!-- Floating UI Elements -->
        <div class="absolute -right-2 top-8 w-14 h-14 bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-50 flex items-center justify-center animate-bounce" style="animation-duration: 3s;">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
        </div>
        <div class="absolute -left-4 bottom-12 w-12 h-12 bg-indigo-600 rounded-xl shadow-[0_8px_30px_rgba(79,70,229,0.3)] flex items-center justify-center transform -rotate-12 animate-pulse" style="animation-duration: 4s;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
      </div>

      <!-- Heading -->
      <h1 class="text-[28px] font-black text-slate-800 mb-5 leading-[1.15] uppercase tracking-wide animate-in delay-200">
        Kelola Tugasmu<br />
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Dengan Mudah</span>
      </h1>

      <!-- Subtitle -->
      <p class="text-[13px] text-slate-500 mb-12 uppercase tracking-widest px-4 leading-relaxed font-semibold animate-in delay-300">
        Aplikasi untuk membantu kamu mengatur dan menyelesaikan tugas sehari-hari
      </p>

      <!-- Button -->
      <Link href="/login" class="w-full animate-in delay-300">
        <button class="premium-button w-full font-bold py-4 px-4 rounded-xl text-[15px] flex items-center justify-center group relative overflow-hidden">
          <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
          <span class="relative z-10 tracking-wide uppercase">Mulai Sekarang</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="relative z-10 ml-2 group-hover:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
      </Link>

      <!-- Pager Dots -->
      <div class="flex space-x-2 mt-10 animate-in delay-300">
        <div class="w-6 h-2 rounded-full bg-primary shadow-sm shadow-blue-500/20"></div>
        <div class="w-2 h-2 rounded-full bg-gray-200 transition-colors hover:bg-gray-300 cursor-pointer"></div>
        <div class="w-2 h-2 rounded-full bg-gray-200 transition-colors hover:bg-gray-300 cursor-pointer"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
