<template>
  <DashboardLayout>
    <div class="bb-page">
      <!-- Page header -->
      <div class="bb-page-head">
        <div>
          <div class="bb-page-title">Dashboard</div>
          <div class="bb-page-sub">Sistem Manajemen Tugas Kuliah & Portofolio Akademik</div>
        </div>
      </div>

      <!-- Metric cards -->
      <div class="bb-grid-4">
        <div class="bb-stat-card bb-stat--purple premium-card">
          <div class="bb-stat-top">
            <div class="bb-stat-icon">🛒</div>
            <div class="bb-stat-meta">
              <div class="bb-stat-label">Products Sold</div>
              <div class="bb-stat-value">{{ products_sold }}</div>
            </div>
          </div>
          <div class="bb-stat-foot">This month</div>
        </div>

        <div class="bb-stat-card bb-stat--blue premium-card">
          <div class="bb-stat-top">
            <div class="bb-stat-icon">💎</div>
            <div class="bb-stat-meta">
              <div class="bb-stat-label">Net Profit</div>
              <div class="bb-stat-value">{{ net_profit }}</div>
            </div>
          </div>
          <div class="bb-stat-foot">+{{ profit_growth }}% vs last</div>
        </div>

        <div class="bb-stat-card bb-stat--pink premium-card">
          <div class="bb-stat-top">
            <div class="bb-stat-icon">👥</div>
            <div class="bb-stat-meta">
              <div class="bb-stat-label">New Customers</div>
              <div class="bb-stat-value">{{ new_customers }}</div>
            </div>
          </div>
          <div class="bb-stat-foot">Last 30 days</div>
        </div>

        <div class="bb-stat-card bb-stat--orange premium-card">
          <div class="bb-stat-top">
            <div class="bb-stat-icon">✨</div>
            <div class="bb-stat-meta">
              <div class="bb-stat-label">Customer Satisfaction</div>
              <div class="bb-stat-value">{{ customer_satisfaction }}%</div>
            </div>
          </div>
          <div class="bb-stat-foot">Average rating</div>
        </div>
      </div>

      <!-- Main area chart -->
      <div class="bb-chart-wrap premium-card">
        <div class="bb-section-head">
          <div>
            <div class="bb-section-title">Sales Overview</div>
            <div class="bb-section-sub">Area chart halus, responsif, dan modern</div>
          </div>
          <div class="bb-chart-pill">
            <span class="bb-pill active">Month</span>
            <span class="bb-pill">Week</span>
            <span class="bb-pill">Day</span>
          </div>
        </div>

        <div class="bb-chart-area">
          <canvas ref="salesAreaCanvas" height="120"></canvas>
        </div>
      </div>

      <!-- Bottom panels (3 columns) -->
      <div class="bb-grid-3">
        <!-- Order Summary -->
        <section class="premium-card bb-panel">
          <div class="bb-section-head bb-section-head--tight">
            <div>
              <div class="bb-section-title">Order Summary</div>
              <div class="bb-section-sub">Ringkasan penjualan</div>
            </div>
          </div>
          <div class="bb-panel-body">
            <canvas ref="orderBarCanvas" height="140"></canvas>
          </div>
        </section>

        <!-- Order Overview -->
        <section class="premium-card bb-panel">
          <div class="bb-section-head bb-section-head--tight">
            <div>
              <div class="bb-section-title">Order Overview</div>
              <div class="bb-section-sub">Statistik & progress</div>
            </div>
          </div>

          <div class="bb-panel-body">
            <div class="bb-overview-stats">
              <div class="bb-mini">
                <div class="bb-mini-label">Completed</div>
                <div class="bb-mini-value">{{ completed_orders }}</div>
              </div>
              <div class="bb-mini">
                <div class="bb-mini-label">In Progress</div>
                <div class="bb-mini-value">{{ in_progress_orders }}</div>
              </div>
            </div>

            <div class="bb-progress">
              <div class="bb-progress-row">
                <div class="bb-progress-label">Completion</div>
                <div class="bb-progress-value">{{ completion_percent }}%</div>
              </div>
              <div class="bb-progress-track">
                <div class="bb-progress-fill" :style="{ width: completion_percent + '%' }"></div>
              </div>
            </div>

            <div class="bb-progress-legend">
              <div class="bb-legend-item">
                <span class="bb-dot bb-dot--purple"></span>
                <span>Study Tasks</span>
              </div>
              <div class="bb-legend-item">
                <span class="bb-dot bb-dot--blue"></span>
                <span>Assignments</span>
              </div>
              <div class="bb-legend-item">
                <span class="bb-dot bb-dot--orange"></span>
                <span>Deadlines</span>
              </div>
            </div>
          </div>
        </section>

        <!-- Todo List -->
        <section class="premium-card bb-panel">
          <div class="bb-section-head bb-section-head--tight">
            <div>
              <div class="bb-section-title">Todo List</div>
              <div class="bb-section-sub">Daftar tugas terbaru</div>
            </div>
          </div>

          <div class="bb-panel-body">
            <ul class="bb-todo">
              <li v-if="todo_items.length === 0" class="bb-todo-empty">Tidak ada tugas.</li>
              <li v-for="item in todo_items" :key="item.id" class="bb-todo-item">
                <label class="bb-todo-left">
                  <input type="checkbox" v-model="item.done" class="bb-todo-checkbox" />
                  <span :class="{ 'bb-todo-done': item.done }" class="bb-todo-text">{{ item.title }}</span>
                </label>

                <button class="bb-todo-action" type="button" @click="removeTodo(item.id)" aria-label="Delete todo" title="Delete">
                  🗑️
                </button>
              </li>
            </ul>

            <div class="bb-todo-input">
              <input type="text" class="bb-input" placeholder="Catat deadline baru dari dosen" disabled />
            </div>

            <div v-if="recent_tasks && recent_tasks.length" class="bb-recent">
              <div class="bb-recent-title">Recent Tasks</div>
              <ul class="bb-recent-list">
                <li v-for="t in recent_tasks" :key="t.id" class="bb-recent-item">
                  <span class="bb-recent-text">{{ t.judul }}</span>
                  <span class="bb-badge" :style="statusStyle(t.status)">{{ t.status }}</span>
                </li>
              </ul>
            </div>
          </div>
        </section>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue'
import { Chart } from 'chart.js/auto'
import DashboardLayout from '../Layouts/DashboardLayout.vue'

const props = defineProps({
  tasks_proses: { type: Number, default: 0 },
  tasks_selesai: { type: Number, default: 0 },
  recent_tasks: { type: Array, default: () => [] },
})

// ===== Statistic cards =====
// Keep existing props for “preserve existing dashboard functionality”
const tasksDone = computed(() => props.tasks_selesai ?? 0)
const tasksInProgress = computed(() => props.tasks_proses ?? 0)

const products_sold = computed(() => Math.max(1, tasksDone.value * 2 + 3))
const net_profit = computed(() => (tasksDone.value * 125000 + 350000).toLocaleString('id-ID'))
const profit_growth = computed(() => Math.min(42, 8 + tasksInProgress.value * 2))
const new_customers = computed(() => Math.max(1, tasksInProgress.value + 5))
const customer_satisfaction = computed(() => Math.min(99, 75 + tasksDone.value))

// ===== Order Overview =====
const completed_orders = computed(() => Math.max(0, tasksDone.value))
const in_progress_orders = computed(() => Math.max(0, tasksInProgress.value))
const completion_percent = computed(() => {
  const total = completed_orders.value + in_progress_orders.value
  if (total === 0) return 0
  return Math.round((completed_orders.value / total) * 100)
})

// ===== Todo =====
const todo_items = reactive([
  { id: 1, title: 'Kumpul Laporan Praktikum', done: true },
  { id: 2, title: 'Submit Tugas Mandiri', done: true },
  { id: 3, title: 'Buat Outline Presentasi Minggu Ini', done: false },
])

function removeTodo(id) {
  const idx = todo_items.findIndex((x) => x.id === id)
  if (idx >= 0) todo_items.splice(idx, 1)
}

function statusStyle(status) {
  if (status === 'Proses') return 'background: rgba(234,179,8,0.15); color:#B45309; border: 1px solid rgba(234,179,8,0.25);'
  if (status === 'Selesai') return 'background: rgba(34,197,94,0.15); color:#15803D; border: 1px solid rgba(34,197,94,0.25);'
  if (status === 'Tertunda') return 'background: rgba(239,68,68,0.15); color:#B91C1C; border: 1px solid rgba(239,68,68,0.25);'
  return 'background: rgba(148,163,184,0.15); color:#475569; border: 1px solid rgba(148,163,184,0.25);'
}

// ===== Charts (Chart.js) =====
const salesAreaCanvas = ref(null)
const orderBarCanvas = ref(null)

let salesAreaChart = null
let orderBarChart = null

function buildGradient(ctx, areaHeight, stops) {
  const g = ctx.createLinearGradient(0, 0, 0, areaHeight)
  stops.forEach((s) => g.addColorStop(s.pos, s.color))
  return g
}

function renderCharts() {
  // NOTE: This is the pre-fix corrupted file; backup preserves it.
}

onMounted(() => {
  renderCharts()
})

onBeforeUnmount(() => {
  salesAreaChart?.destroy()
  orderBarChart?.destroy()
})
</script>

<style scoped>
/* Namespace local styles to avoid clobbering theme globals */
.dashboard-page {
  padding-bottom: 10px;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0;
}

.dashboard-page .card {
  background: rgba(255,255,255,0.92);
  border: 1px solid rgba(15, 23, 42, 0.06);
  border-radius: 14px;
  box-shadow: 0 10px 30px rgba(2, 6, 23, 0.04);
}

.dashboard-page .card-body {
  padding: 16px;
}

.dashboard-page .card-title {
  font-weight: 700;
  color: #0F172A;
}

.dashboard-page .badge {
  font-weight: 700;
  padding: 7px 10px;
  border-radius: 999px;
  border: 1px solid rgba(0,0,0,0.03);
}

.dashboard-subtitle {
  letter-spacing: 0.2px;
}

.dashboard-page .progress {
  background: rgba(148, 163, 184, 0.18);
  border-radius: 999px;
  overflow: hidden;
}

.dashboard-page .progress-bar {
  height: 8px;
}

.dashboard-page .text-muted {
  color: rgba(100, 116, 139, 0.95) !important;
}

.dashboard-page .h2 {
  line-height: 1.2;
}
</style>

