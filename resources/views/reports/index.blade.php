@extends('layouts.app')

@section('title', 'Laporan Sistem')

@section('header', 'Laporan & Statistik')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Distribusi Pengguna</h5>
                        <small class="text-muted">Pie chart</small>
                    </div>

                    <div class="ratio ratio-4x3">
                        <canvas id="usersByRoleChart"></canvas>
                    </div>

                    <div id="usersByRoleEmpty" class="text-center text-muted mt-3 d-none">Tidak ada data pengguna untuk ditampilkan.</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Peminjaman Per Bulan</h5>
                        <small class="text-muted">Bar chart (vertikal)</small>
                    </div>

                    <div class="ratio ratio-4x3">
                        <canvas id="loansPerMonthChart"></canvas>
                    </div>

                    <div id="loansPerMonthEmpty" class="text-center text-muted mt-3 d-none">Tidak ada data peminjaman untuk ditampilkan.</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mt-2">
        <div class="col-12 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Kondisi Barang (total)</h5>
                        <small class="text-muted">Pie chart (Baik / Rusak Ringan / Rusak Berat)</small>
                    </div>

                    <div class="ratio ratio-4x3">
                        <canvas id="conditionByStatusChart"></canvas>
                    </div>

                    <div id="conditionByStatusEmpty" class="text-center text-muted mt-3 d-none">Tidak ada data kondisi barang untuk ditampilkan.</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Peminjaman Terlambat per Bulan</h5>
                        <small class="text-muted">Bar chart (overdue loans)</small>
                    </div>

                    <div class="ratio ratio-4x3">
                        <canvas id="overduePerMonthChart"></canvas>
                    </div>

                    <div id="overduePerMonthEmpty" class="text-center text-muted mt-3 d-none">Tidak ada data peminjaman terlambat untuk ditampilkan.</div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        // Server-provided data (controller should pass these). The view will fall back to empty objects/arrays.
        const usersByRole = @json($usersByRole ?? []); // { 'mahasiswa': 12, 'dosen': 4 }
        const loansPerMonth = @json($loansPerMonth ?? []); // { '1': 5, '2': 3, ... } or array [0..11]

        // ----- Users by role (pie) -----
        (function(){
            const containerEmpty = document.getElementById('usersByRoleEmpty');
            const canvas = document.getElementById('usersByRoleChart');
            if(!canvas || !containerEmpty) return;

            // Include admin as well — server may provide an 'admin' key.
            const roles = Object.keys(usersByRole);
            const counts = roles.map(r => usersByRole[r] ?? 0);

            const total = counts.reduce((s,v) => s + v, 0);
            if(total === 0){
                containerEmpty.classList.remove('d-none');
                canvas.style.display = 'none';
                return;
            }

            const colors = ['#0d6efd','#198754','#ffc107','#dc3545','#6c757d','#6610f2','#20c997'];

            new Chart(canvas, {
                type: 'pie',
                data: {
                    labels: roles.map(r => r.charAt(0).toUpperCase() + r.slice(1)),
                    datasets: [{
                        data: counts,
                        backgroundColor: colors.slice(0, roles.length),
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.raw}` } }
                    },
                    maintainAspectRatio: false,
                }
            });
        })();

        // ----- Condition (pie) -----
        (function(){
            const canvas = document.getElementById('conditionByStatusChart');
            const containerEmpty = document.getElementById('conditionByStatusEmpty');
            if(!canvas || !containerEmpty) return;

            const conditionCounts = @json($conditionCounts ?? []);
            const statusKeys = ['baik','rusak_ringan','rusak_berat'];
            const friendly = { 'baik': 'Baik', 'rusak_ringan': 'Rusak Ringan', 'rusak_berat': 'Rusak Berat' };
            const colors = { 'baik':'#198754','rusak_ringan':'#ffc107','rusak_berat':'#dc3545' };

            const counts = statusKeys.map(k => Number(conditionCounts[k] ?? conditionCounts[k.replace('_',' ')] ?? conditionCounts[k.replace('_','-')] ?? 0));
            const total = counts.reduce((s,v)=>s+v,0);
            if(total === 0){
                containerEmpty.classList.remove('d-none');
                canvas.style.display = 'none';
                return;
            }

            new Chart(canvas, {
                type: 'pie',
                data: {
                    labels: statusKeys.map(k => friendly[k]),
                    datasets: [{ data: counts, backgroundColor: statusKeys.map(k=>colors[k]), borderColor:'#fff', borderWidth:1 }]
                },
                options: { maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } } }
            });
        })();

        // ----- Loans per month (vertical bar) -----
        (function(){
            const canvas = document.getElementById('loansPerMonthChart');
            const containerEmpty = document.getElementById('loansPerMonthEmpty');
            if(!canvas || !containerEmpty) return;

            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

            const monthly = new Array(12).fill(0);
            if(Array.isArray(loansPerMonth)){
                for(let i=0;i<loansPerMonth.length && i<12;i++) monthly[i]=Number(loansPerMonth[i])||0;
            } else {
                Object.keys(loansPerMonth).forEach(k => {
                    const num = Number(k);
                    if(!Number.isNaN(num) && num>=1 && num<=12){
                        monthly[num-1] = Number(loansPerMonth[k])||0;
                    } else {
                        const m = (''+k).match(/-(\d{1,2})$/);
                        if(m){ const mi = Number(m[1]); if(mi>=1 && mi<=12) monthly[mi-1] = Number(loansPerMonth[k])||0; }
                    }
                });
            }

            const total = monthly.reduce((s,v)=>s+v,0);
            if(total === 0){
                containerEmpty.classList.remove('d-none');
                canvas.style.display = 'none';
                return;
            }

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: monthly,
                        backgroundColor: '#0d6efd',
                        borderColor: '#0b5ed7',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, ticks: { precision:0 } }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        })();

        // ----- Overdue loans per month (vertical bar) -----
        (function(){
            const canvas = document.getElementById('overduePerMonthChart');
            const containerEmpty = document.getElementById('overduePerMonthEmpty');
            if(!canvas || !containerEmpty) return;

            const overduePerMonth = @json($overduePerMonth ?? []);
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            const monthlyOverdue = new Array(12).fill(0);

            if(Array.isArray(overduePerMonth)){
                for(let i=0;i<overduePerMonth.length && i<12;i++) monthlyOverdue[i]=Number(overduePerMonth[i])||0;
            } else {
                Object.keys(overduePerMonth||{}).forEach(k => {
                    const num = Number(k);
                    if(!Number.isNaN(num) && num>=1 && num<=12){
                        monthlyOverdue[num-1] = Number(overduePerMonth[k])||0;
                    } else {
                        const m = (''+k).match(/-(\d{1,2})$/);
                        if(m){ const mi = Number(m[1]); if(mi>=1 && mi<=12) monthlyOverdue[mi-1] = Number(overduePerMonth[k])||0; }
                    }
                });
            }

            const total = monthlyOverdue.reduce((s,v)=>s+v,0);
            if(total === 0){
                containerEmpty.classList.remove('d-none');
                canvas.style.display = 'none';
                return;
            }

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{ label:'Overdue Loans', data: monthlyOverdue, backgroundColor:'#dc3545', borderColor:'#c82333', borderWidth:1, borderRadius:4 }]
                },
                options: { maintainAspectRatio:false, scales:{ x:{ grid:{ display:false } }, y:{ beginAtZero:true, ticks:{ precision:0 } } }, plugins:{ legend:{ display:false } } }
            });
        })();

    });
</script>
@endpush
