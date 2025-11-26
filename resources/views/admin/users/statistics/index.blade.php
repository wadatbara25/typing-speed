@extends('layouts.app')
@section('title', 'الإحصاءات العامة')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-indigo-700 mb-6">📈 الإحصاءات العامة</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-dashboard-card title="متوسط السرعة العام" :value="$stats['avg_wpm'] . ' WPM'" icon="zap" color="orange" />
        <x-dashboard-card title="متوسط الدقة" :value="$stats['avg_accuracy'] . '%'" icon="check-circle" color="emerald" />
        <x-dashboard-card title="عدد المحاولات" :value="$stats['total_attempts']" icon="keyboard" color="indigo" />
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
        <h3 class="text-lg font-bold mb-4">📅 الأداء اليومي</h3>
        <canvas id="chart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($daily->pluck('date')),
            datasets: [{
                label: 'متوسط WPM',
                data: @json($daily->pluck('avg_wpm')),
                backgroundColor: '#6366F1',
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>
@endsection
