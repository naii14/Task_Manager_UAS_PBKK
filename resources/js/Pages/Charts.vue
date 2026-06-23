<template>
  <DashboardLayout>
    <div class="min-h-screen bg-slate-50 font-sans pb-28 overflow-x-hidden">
      <header class="p-8 pb-4 animate-in">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Charts</h1>
            <p class="text-[15px] text-slate-500 font-medium">Visualisasi sederhana untuk laporan</p>
          </div>
        </div>
      </header>

      <main class="px-8 pt-2 animate-in delay-100">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <section class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-[16px] font-extrabold text-slate-800">Sales Overview</h2>
              <span class="text-[12px] font-bold text-blue-600 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-full">Area chart</span>
            </div>
            <div class="h-64 relative">
              <canvas ref="salesAreaCanvas" class="bb-chart-area w-full h-full" />
            </div>
          </section>

          <section class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-[16px] font-extrabold text-slate-800">Orders</h2>
              <span class="text-[12px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-full">Bar chart</span>
            </div>
            <div class="h-64 relative">
              <canvas ref="orderBarCanvas" class="bb-chart-area w-full h-full" />
            </div>
          </section>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tasks</div>
            <div class="text-3xl font-black text-slate-800">—</div>
          </div>
          <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Completed</div>
            <div class="text-3xl font-black text-green-700">—</div>
          </div>
          <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pending</div>
            <div class="text-3xl font-black text-yellow-700">—</div>
          </div>
        </div>
      </main>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { Chart } from 'chart.js/auto'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

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
  salesAreaChart?.destroy()
  orderBarChart?.destroy()

  if (!salesAreaCanvas.value || !orderBarCanvas.value) return

  const salesCtx = salesAreaCanvas.value.getContext('2d')
  const orderCtx = orderBarCanvas.value.getContext('2d')

  const salesHeight = salesAreaCanvas.value.height || 200
  const salesGradient = buildGradient(salesCtx, salesHeight, [
    { pos: 0, color: 'rgba(59, 130, 246, 0.45)' },
    { pos: 0.6, color: 'rgba(59, 130, 246, 0.18)' },
    { pos: 1, color: 'rgba(59, 130, 246, 0.00)' },
  ])

  salesAreaChart = new Chart(salesCtx, {
    type: 'line',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [
        {
          label: 'Sales',
          data: [12, 19, 15, 22, 18, 24, 20],
          borderColor: 'rgba(59, 130, 246, 1)',
          backgroundColor: salesGradient,
          borderWidth: 2,
          tension: 0.35,
          fill: true,
          pointRadius: 0,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { enabled: true },
      },
      scales: {
        x: { grid: { display: false }, ticks: { display: false } },
        y: { grid: { color: 'rgba(15,23,42,0.06)' }, ticks: { display: false } },
      },
    },
  })

  const orderHeight = orderBarCanvas.value.height || 200
  const barGradient = buildGradient(orderCtx, orderHeight, [
    { pos: 0, color: 'rgba(99, 102, 241, 0.65)' },
    { pos: 1, color: 'rgba(99, 102, 241, 0.10)' },
  ])

  orderBarChart = new Chart(orderCtx, {
    type: 'bar',
    data: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
      datasets: [
        {
          label: 'Orders',
          data: [10, 18, 14, 22],
          backgroundColor: barGradient,
          borderColor: 'rgba(99, 102, 241, 1)',
          borderWidth: 1,
          borderRadius: 10,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { color: 'rgba(15,23,42,0.55)' },
        },
        y: {
          grid: { color: 'rgba(15,23,42,0.06)' },
          ticks: { display: false },
        },
      },
    },
  })
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
:deep(.bb-chart-area) {
  min-height: 160px;
}
</style>

