<template>
  <div class="min-h-screen bg-slate-50 flex flex-col p-6 font-sans relative overflow-hidden">
    <!-- Background Decorators -->
    <div class="absolute -top-[10%] -left-[20%] w-[60%] h-[40%] bg-blue-100 rounded-full blur-[80px] opacity-50"></div>
    <div class="absolute top-[80%] -right-[10%] w-[50%] h-[40%] bg-indigo-50 rounded-full blur-[80px] opacity-60"></div>

    <!-- Header with Back Button -->
    <div class="pt-8 pb-6 relative z-10 animate-in">
      <Link href="/login" class="inline-flex items-center justify-center w-11 h-11 -ml-2 text-slate-600 bg-white shadow-sm border border-gray-100 hover:bg-gray-50 hover:shadow-md rounded-full transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </Link>
    </div>

    <!-- Title Section -->
    <div class="mb-10 relative z-10 animate-in delay-100">
      <h1 class="text-[32px] font-black text-slate-800 tracking-tight mb-2">Register</h1>
      <p class="text-[15px] text-slate-500 font-medium">Buat Akun Baru</p>
    </div>

    <!-- Form Section -->
    <form @submit.prevent="submit" class="space-y-5 flex-1 relative z-10 animate-in delay-200">
      
      <!-- Name Field -->
      <div class="space-y-2 group">
        <label for="name" class="block text-sm font-bold text-slate-700 group-focus-within:text-primary transition-colors">Nama Lengkap</label>
        <div class="relative">
          <input 
            type="text" 
            id="name" 
            v-model="form.name"
            placeholder="Masukkan Nama Lengkap" 
            class="w-full px-4 py-4 bg-white border border-gray-200/80 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-[15px] transition-all text-slate-800 placeholder:text-gray-400 font-medium"
            required
            autofocus
          />
        </div>
        <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</div>
      </div>

      <!-- Email Field -->
      <div class="space-y-2 group">
        <label for="email" class="block text-sm font-bold text-slate-700 group-focus-within:text-primary transition-colors">Email</label>
        <div class="relative">
          <input 
            type="email" 
            id="email" 
            v-model="form.email"
            placeholder="Masukkan Email" 
            class="w-full px-4 py-4 bg-white border border-gray-200/80 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-[15px] transition-all text-slate-800 placeholder:text-gray-400 font-medium"
            required
          />
          <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
          </div>
        </div>
        <div v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</div>
      </div>

      <!-- Password Field -->
      <div class="space-y-2 group">
        <label for="password" class="block text-sm font-bold text-slate-700 group-focus-within:text-primary transition-colors">Password</label>
        <div class="relative">
          <input 
            type="password" 
            id="password" 
            v-model="form.password"
            placeholder="Masukkan Password" 
            class="w-full px-4 py-4 bg-white border border-gray-200/80 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-[15px] transition-all text-slate-800 placeholder:text-gray-400 font-medium font-sans tracking-widest"
            required
          />
        </div>
        <div v-if="form.errors.password" class="text-sm text-red-600 mt-1">{{ form.errors.password }}</div>
      </div>

      <!-- Password Confirmation -->
      <div class="space-y-2 group">
        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 group-focus-within:text-primary transition-colors">Konfirmasi Password</label>
        <div class="relative">
          <input 
            type="password" 
            id="password_confirmation" 
            v-model="form.password_confirmation"
            placeholder="Konfirmasi Password" 
            class="w-full px-4 py-4 bg-white border border-gray-200/80 rounded-xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 text-[15px] transition-all text-slate-800 placeholder:text-gray-400 font-medium font-sans tracking-widest"
            required
          />
        </div>
        <div v-if="form.errors.password_confirmation" class="text-sm text-red-600 mt-1">{{ form.errors.password_confirmation }}</div>
      </div>

      <!-- Submit Button -->
      <div class="pt-6">
        <button 
          type="submit" 
          class="premium-button w-full py-4 px-4 rounded-xl text-[15px] font-bold flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed group relative overflow-hidden"
          :disabled="form.processing"
        >
          <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
          <span class="relative z-10" v-if="form.processing">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
          </span>
          <span class="relative z-10 uppercase tracking-wider" v-else>Daftar</span>
        </button>
      </div>

    </form>

    <!-- Bottom Login Link -->
    <div class="py-6 text-center mt-auto relative z-10 animate-in delay-300">
      <p class="text-[15px] text-slate-500 font-medium">
        Sudah Punya Akun? 
        <Link href="/login" class="text-primary font-bold hover:text-blue-700 transition-colors">Login</Link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
