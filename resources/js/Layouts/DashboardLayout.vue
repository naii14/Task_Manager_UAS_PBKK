<template>
  <div class="bb-dashboard-shell">
    <!-- Top Navbar -->
    <header class="bb-topbar">
      <div class="bb-topbar-inner">
        <button
          class="bb-icon-btn"
          type="button"
          @click="isCollapsed = !isCollapsed"
          aria-label="Toggle sidebar"
          title="Toggle sidebar"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="18" x2="21" y2="18" />
          </svg>
        </button>

        <div class="bb-topbar-brand">
          <div class="bb-brand-pill">
            <span class="bb-brand-mark"></span>
            <span class="bb-brand-text">Quixlab</span>
          </div>
          <div class="bb-brand-sub">Premium Academic Dashboard</div>
        </div>

        <div class="bb-topbar-actions">
          <div class="bb-search">
            <span class="bb-search-icon" aria-hidden="true">🔎</span>
            <input
              type="text"
              v-model="searchQuery"
              class="bb-search-input"
              placeholder="Search materi / dosen / judul tugas..."
              aria-label="Search"
            />
          </div>

          <button class="bb-icon-btn" type="button" aria-label="Notifications" title="Notifications">
            <div class="bb-badge-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 7h18s-3 0-3-7" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
              </svg>
              <span class="bb-badge">3</span>
            </div>
          </button>

          <button class="bb-icon-btn" type="button" aria-label="Messages" title="Messages">
            <div class="bb-badge-wrap">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a4 4 0 0 1-4 4H7l-4 4V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z" />
              </svg>
              <span class="bb-badge bb-badge--purple">3</span>
            </div>
          </button>

          <div class="bb-divider" aria-hidden="true"></div>

          <div class="bb-lang">
            <select v-model="lang" class="bb-select" aria-label="Language">
              <option value="id">ID</option>
              <option value="en">EN</option>
            </select>
          </div>

          <div class="bb-profile">
            <div class="bb-avatar" aria-hidden="true">👤</div>
            <div class="bb-profile-meta">
              <div class="bb-profile-name">User</div>
              <div class="bb-profile-role">Student</div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="bb-body">
      <!-- Sidebar -->
      <aside class="bb-sidebar" :class="{ 'bb-sidebar--collapsed': isCollapsed }">
        <nav class="bb-nav">
          <ul class="bb-nav-list">
            <li class="bb-nav-label" v-if="!isCollapsed">Menu</li>

            <li>
              <a class="bb-nav-link" href="/dashboard">
                <span class="bb-nav-icon">🏠</span>
                <span class="bb-nav-text">Dashboard</span>
              </a>
            </li>
            <li>
              <a class="bb-nav-link" href="/tasks">
                <span class="bb-nav-icon">🧩</span>
                <span class="bb-nav-text">Apps</span>
              </a>
            </li>
            <li>
              <a class="bb-nav-link" href="/charts">
                <span class="bb-nav-icon">📈</span>
                <span class="bb-nav-text">Charts</span>
              </a>
            </li>
            <li>
              <a class="bb-nav-link" href="/forms">
                <span class="bb-nav-icon">📝</span>
                <span class="bb-nav-text">Forms</span>
              </a>
            </li>
            <li>
              <a class="bb-nav-link" href="/tables">
                <span class="bb-nav-icon">🗂️</span>
                <span class="bb-nav-text">Tables</span>
              </a>
            </li>
            <li>
              <a class="bb-nav-link" href="/profile">
                <span class="bb-nav-icon">📄</span>
                <span class="bb-nav-text">Pages</span>
              </a>
            </li>
          </ul>
        </nav>
      </aside>

      <!-- Content -->
      <main class="bb-content">
        <div class="bb-container">
          <slot />
        </div>
      </main>
    </div>

    <!-- Footer -->
    <footer class="bb-footer">
      <div class="bb-footer-inner">Quixlab • Academic Dashboard</div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const isCollapsed = ref(false)
const searchQuery = ref('')
const lang = ref('id')
</script>

<style scoped>
.bb-dashboard-shell {
  min-height: 100vh;
  background: #f8fafc;
  display: flex;
  flex-direction: column;
}

.bb-topbar {
  position: sticky;
  top: 0;
  z-index: 20;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(15, 23, 42, 0.06);
}

.bb-topbar-inner {
  height: 70px;
  display: flex;
  align-items: center;
  padding: 0 18px;
  gap: 14px;
}

.bb-icon-btn {
  width: 40px;
  height: 40px;
  border-radius: 14px;
  border: 1px solid rgba(15, 23, 42, 0.08);
  background: rgba(255, 255, 255, 0.9);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: rgba(17, 24, 39, 0.88);
  box-shadow: 0 8px 30px -4px rgba(0, 0, 0, 0.04);
  transition: transform 0.15s ease;
}
.bb-icon-btn:active { transform: scale(0.98); }

.bb-topbar-brand {
  display: flex;
  flex-direction: column;
  gap: 2px;
  min-width: 240px;
}

.bb-brand-pill {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 10px 14px;
  border-radius: 999px;
  background: linear-gradient(135deg, rgba(109, 40, 217, 0.12), rgba(14, 165, 233, 0.10));
  border: 1px solid rgba(109, 40, 217, 0.12);
  width: fit-content;
}

.bb-brand-mark {
  width: 10px;
  height: 10px;
  border-radius: 999px;
  background: linear-gradient(135deg, #6d28d9, #0ea5e9);
  box-shadow: 0 10px 30px -10px rgba(109,40,217,0.6);
}

.bb-brand-text { font-weight: 900; letter-spacing: 0.2px; }
.bb-brand-sub { font-size: 12px; color: rgba(100, 116, 139, 0.95); font-weight: 600; }

.bb-topbar-actions {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 12px;
}

.bb-search {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 12px;
  border-radius: 16px;
  border: 1px solid rgba(15, 23, 42, 0.08);
  background: rgba(255, 255, 255, 0.9);
  min-width: 360px;
}
.bb-search-icon { opacity: 0.9; }
.bb-search-input {
  border: none;
  outline: none;
  background: transparent;
  width: 100%;
  font-weight: 600;
  color: rgba(15, 23, 42, 0.9);
}
.bb-search-input::placeholder { color: rgba(100, 116, 139, 0.9); font-weight: 600; }

.bb-divider { width: 1px; height: 28px; background: rgba(15, 23, 42, 0.08); }

.bb-badge-wrap { position: relative; }
.bb-badge {
  position: absolute;
  top: -9px;
  right: -10px;
  background: #ef4444;
  color: #fff;
  border-radius: 999px;
  font-size: 11px;
  padding: 2px 6px;
  font-weight: 900;
  border: 2px solid rgba(255,255,255,0.9);
}
.bb-badge--purple { background: #8b5cf6; }

.bb-select {
  border: 1px solid rgba(15, 23, 42, 0.08);
  background: rgba(255,255,255,0.9);
  border-radius: 16px;
  padding: 10px 12px;
  font-weight: 800;
  outline: none;
}

.bb-profile {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 10px;
  border-radius: 18px;
  border: 1px solid rgba(15, 23, 42, 0.08);
  background: rgba(255, 255, 255, 0.9);
}

.bb-avatar {
  width: 34px;
  height: 34px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(109, 40, 217, 0.10);
}

.bb-profile-meta { display: flex; flex-direction: column; line-height: 1.1; }
.bb-profile-name { font-weight: 900; font-size: 13px; color: rgba(15, 23, 42, 0.92); }
.bb-profile-role { font-size: 12px; font-weight: 700; color: rgba(100, 116, 139, 0.95); }

.bb-body {
  flex: 1;
  display: grid;
  grid-template-columns: 270px 1fr;
  gap: 0;
}

.bb-sidebar {
  background: #ffffff;
  border-right: 1px solid rgba(15, 23, 42, 0.06);
  padding: 16px 10px;
  position: sticky;
  top: 70px;
  height: calc(100vh - 70px - 44px);
  overflow: auto;
}

.bb-sidebar--collapsed {
  grid-column: 1;
}

.bb-sidebar.bb-sidebar--collapsed {
  width: 92px;
}

.bb-nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.bb-nav-label {
  font-size: 12px;
  font-weight: 900;
  color: rgba(100, 116, 139, 0.95);
  margin: 8px 10px;
}

.bb-nav-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 12px;
  border-radius: 16px;
  color: rgba(15, 23, 42, 0.88);
  text-decoration: none;
  font-weight: 900;
  border: 1px solid transparent;
  transition: background 0.15s ease, border 0.15s ease, transform 0.15s ease;
}

.bb-nav-link:hover {
  background: linear-gradient(135deg, rgba(109, 40, 217, 0.10), rgba(14, 165, 233, 0.08));
  border-color: rgba(109, 40, 217, 0.14);
}

.bb-nav-icon {
  width: 36px;
  height: 36px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(109, 40, 217, 0.10);
}

.bb-nav-text { white-space: nowrap; }

.bb-content {
  padding: 18px;
}
.bb-container {
  max-width: 1240px;
  margin: 0 auto;
}

.bb-footer {
  border-top: 1px solid rgba(15, 23, 42, 0.06);
  background: rgba(255,255,255,0.7);
}
.bb-footer-inner {
  padding: 12px 18px;
  color: rgba(100, 116, 139, 0.95);
  font-weight: 800;
}

@media (max-width: 1024px) {
  .bb-topbar-brand { min-width: 160px; }
  .bb-search { min-width: 260px; }
}

@media (max-width: 820px) {
  .bb-body { grid-template-columns: 1fr; }
  .bb-sidebar { position: fixed; z-index: 30; top: 70px; height: calc(100vh - 70px - 44px); }
  .bb-content { padding: 14px; }
  .bb-search { min-width: 1px; width: 100%; }
  .bb-topbar-inner { flex-wrap: wrap; height: auto; padding: 14px; }
}
</style>


