@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary fw-bold"><i class="fas fa-tachometer-alt me-2"></i> Selamat Datang, Administrator!</h1>
    
    <p class="lead text-muted">Akses cepat ke status inventaris dan manajemen pengguna.</p>
    {{-- Kumpulan Kartu Ringkasan (Statistics) --}}
    <div class="row g-4 mb-4">

        {{-- Items Summary --}}
        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-primary rounded-3 p-3"><i class="fas fa-boxes fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Total Barang (Jenis)</div>
                        <div class="h4 fw-bold">{{ \App\Models\Item::count() }}</div>
                        <div class="mt-2"><a href="{{ route('admin.items.index') }}" class="small">Lihat Detail</a></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Units Available --}}
        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-success rounded-3 p-3"><i class="fas fa-check-circle fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Total Unit Tersedia</div>
                        <div class="h4 fw-bold">{{ \App\Models\Item::sum('available_quantity') }}</div>
                        <div class="mt-2"><a href="{{ route('admin.items.create') }}" class="small">Tambah Barang</a></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Units Borrowed (sum of loan_items.quantity for active loans) --}}
        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-warning rounded-3 p-3"><i class="fas fa-boxes fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Total Unit Dipinjam</div>
                        <div class="h4 fw-bold">
                            {{ \Illuminate\Support\Facades\DB::table('loan_items')
                                ->join('loans', 'loan_items.loan_id', '=', 'loans.id')
                                ->whereIn('loans.status', ['dipinjam', 'disetujui'])
                                ->sum('loan_items.quantity') }}
                        </div>
                        <div class="mt-2"><a href="{{ route('admin.loans.index') }}" class="small">Lihat Peminjaman Aktif</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Loans Summary (new row) --}}
    <div class="row g-4 mb-5">
        <div class="col-12 col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-info rounded-3 p-3"><i class="fas fa-clipboard-list fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Total Peminjaman</div>
                        <div class="h4 fw-bold">{{ \App\Models\Loan::count() }}</div>
                        <div class="mt-2"><a href="{{ route('admin.loans.index') }}" class="small">Kelola Peminjaman</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-warning rounded-3 p-3"><i class="fas fa-hourglass-half fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Peminjaman Aktif</div>
                        <div class="h4 fw-bold">{{ \App\Models\Loan::whereIn('status', ['dipinjam','disetujui'])->count() }}</div>
                        <div class="mt-2"><a href="{{ route('admin.loans.index') }}?filter=active" class="small">Lihat Aktif</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-success rounded-3 p-3"><i class="fas fa-rotate-left fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Telah Dikembalikan</div>
                        <div class="h4 fw-bold">{{ \App\Models\Loan::whereNotNull('returned_at')->count() }}</div>
                        <div class="mt-2"><a href="{{ route('admin.loans.index') }}?filter=returned" class="small">Lihat Pengembalian</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-danger rounded-3 p-3"><i class="fas fa-exclamation-triangle fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Terlambat / Overdue</div>
                        <div class="h4 fw-bold">{{ \App\Models\Loan::whereIn('status', ['dipinjam','disetujui'])->whereDate('due_date', '<', now())->whereNull('returned_at')->count() }}</div>
                        <div class="mt-2"><a href="{{ route('admin.loans.index') }}?filter=overdue" class="small">Lihat Overdue</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Panel Navigasi Utama (removed) --}}
</div>
@endsection