<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const showLogoutModal = ref(false);
const showGoodbyeModal = ref(false);

const confirmLogout = () => {
    showLogoutModal.value = false;
    showGoodbyeModal.value = true;
    
    setTimeout(() => {
        router.post(route('logout'));
    }, 1500);
};

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />
    <div class="min-h-screen bg-slate-50 flex flex-col font-sans pb-32 overflow-x-hidden relative">
      
        <!-- Header -->
        <header class="p-8 pb-4 animate-in">
            <div class="flex justify-between items-center mb-7">
                <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Profil</h1>
                <button @click="showLogoutModal = true" class="text-sm font-bold text-red-600 bg-red-50 px-4 py-2 rounded-xl hover:bg-red-100 transition-colors">
                    Logout
                </button>
            </div>
        </header>

        <main class="flex-1 px-8 pt-4 animate-in delay-100">
            <div class="space-y-6">
                <div class="premium-card p-6">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="premium-card p-6">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="premium-card p-6">
                    <DeleteUserForm class="max-w-xl" />
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

            <Link href="/calendar" class="flex flex-col items-center p-2 text-slate-400 hover:text-slate-700 transition-colors group">
                <div class="p-1.5 rounded-xl group-hover:bg-slate-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                </div>
            </Link>

            <Link href="/profile" class="flex flex-col items-center p-2 text-primary group">
                <div class="bg-blue-50 p-1.5 rounded-xl group-hover:bg-blue-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
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
