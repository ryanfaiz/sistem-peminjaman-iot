@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="container">
    <h3>Selamat datang, {{ auth()->user()->name }}!</h3>
    <p>Role Anda: {{ auth()->user()->role }}</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Daftar Barang</h5>
                <p><a href="{{ route('user.items.index') }}">Lihat semua barang</a></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Peminjaman Saya</h5>
                <p><a href="{{ route('user.borrow.index') }}">Lihat riwayat peminjaman</a></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Profil</h5>
                <p><a href="{{ route('user.profile') }}">Lihat & ubah profil</a></p>
            </div>
        </div>
    </div>
    
    {{-- User Stats: similar to admin but scoped to current user --}}
    <div class="row g-4 mt-4">
        <div class="col-12 col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-primary rounded-3 p-3"><i class="fas fa-boxes fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Total Barang (Jenis)</div>
                        <div class="h4 fw-bold">{{ \App\Models\Item::count() }}</div>
                        <div class="mt-2"><a href="{{ route('user.items.index') }}" class="small">Lihat Semua Barang</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-success rounded-3 p-3"><i class="fas fa-check-circle fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Total Unit Tersedia</div>
                        <div class="h4 fw-bold">{{ \App\Models\Item::sum('available_quantity') }}</div>
                        <div class="mt-2"><a href="{{ route('user.items.index') }}" class="small">Lihat Barang</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-info rounded-3 p-3"><i class="fas fa-clipboard-list fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Total Peminjaman Saya</div>
                        <div class="h4 fw-bold">{{ \App\Models\Loan::where('user_id', auth()->id())->count() }}</div>
                        <div class="mt-2"><a href="{{ route('user.borrow.index') }}" class="small">Lihat Riwayat</a></div>
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
                        <div class="h4 fw-bold">{{ \App\Models\Loan::where('user_id', auth()->id())->whereIn('status', ['dipinjam','disetujui'])->count() }}</div>
                        <div class="mt-2"><a href="{{ route('user.borrow.index') }}?filter=active" class="small">Lihat Aktif</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-3">
        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-success rounded-3 p-3"><i class="fas fa-rotate-left fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Telah Dikembalikan</div>
                        <div class="h4 fw-bold">{{ \App\Models\Loan::where('user_id', auth()->id())->whereNotNull('returned_at')->count() }}</div>
                        <div class="mt-2"><a href="{{ route('user.borrow.index') }}?filter=returned" class="small">Lihat Pengembalian</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-danger rounded-3 p-3"><i class="fas fa-exclamation-triangle fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Terlambat / Overdue</div>
                        <div class="h4 fw-bold">{{ \App\Models\Loan::where('user_id', auth()->id())->whereIn('status', ['dipinjam','disetujui'])->whereDate('due_date', '<', now())->whereNull('returned_at')->count() }}</div>
                        <div class="mt-2"><a href="{{ route('user.borrow.index') }}?filter=overdue" class="small">Lihat Overdue</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex">
                    <div class="me-3 align-self-center">
                        <span class="badge bg-secondary rounded-3 p-3"><i class="fas fa-boxes fa-lg"></i></span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small text-uppercase text-muted">Unit Dipinjam (Total)</div>
                        <div class="h4 fw-bold">
                            {{ \Illuminate\Support\Facades\DB::table('loan_items')
                                ->join('loans', 'loan_items.loan_id', '=', 'loans.id')
                                ->where('loans.user_id', auth()->id())
                                ->sum('loan_items.quantity') }}
                        </div>
                        <div class="mt-2"><a href="{{ route('user.borrow.index') }}" class="small">Lihat Detail</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection