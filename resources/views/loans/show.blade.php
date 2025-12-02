@extends('layouts.app')

@section('header', 'Detail Peminjaman')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-3">
        <div>
            <h4 class="mb-1">Detail Peminjaman <small class="text-muted">#{{ $loan->id }}</small></h4>
            <div class="small text-muted">Diajukan oleh: {{ $loan->user->name ?? '-' }}</div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('user.borrow.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
            <a href="{{ route('user.borrow.export', $loan) }}" class="btn btn-primary btn-sm" target="_blank">Print</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="fw-bold">Informasi Transaksi</div>
                </div>
                <div class="card-body">
                    @php
                        $statuses = [
                            'diajukan' => 'warning',
                            'disetujui' => 'primary',
                            'dipinjam' => 'info',
                            'ditolak' => 'danger',
                            'dikembalikan' => 'success'
                        ];
                        $badgeColor = $statuses[$loan->status] ?? 'secondary';
                    @endphp
                    <div class="mb-2">
                        <div class="small text-uppercase text-muted">Tanggal Pinjam</div>
                        <div class="fw-bold">{{ $loan->loan_date ? $loan->loan_date->format('d-m-Y') : '-' }}</div>
                    </div>

                    <div class="mb-2">
                        <div class="small text-uppercase text-muted">Jatuh Tempo</div>
                        <div class="fw-bold">{{ $loan->due_date ? $loan->due_date->format('d-m-Y') : '-' }}</div>
                    </div>

                    <div class="mb-2">
                        <div class="small text-uppercase text-muted">Status</div>
                        <div><span class="badge bg-{{ $badgeColor }}">{{ strtoupper($loan->status ?? '-') }}</span></div>
                    </div>

                    @if($loan->purpose)
                        <div class="mb-0">
                            <div class="small text-uppercase text-muted">Tujuan Peminjaman</div>
                            <div class="fw-semibold">{{ $loan->purpose }}</div>
                        </div>
                    @endif
                    <div class="mt-3">
                        <div class="small text-uppercase text-muted">Catatan Admin</div>
                        <div style="white-space:pre-wrap; font-size:0.95rem;">{{ $loan->admin_notes ?: '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <div class="fw-bold">Daftar Barang</div>
                    <div class="small">Total: <span class="fw-bold">{{ $loan->items->sum('pivot.quantity') }}</span></div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Kuantitas</th>
                                    <th class="text-center">Stok Tersedia</th>
                                    <th class="text-center">Stok Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($loan->items as $item)
                                    <tr>
                                        <td class="align-middle">{{ $item->name }}</td>
                                        <td class="align-middle">{{ $item->category->name ?? '-' }}</td>
                                        <td class="text-center align-middle fw-bold">{{ $item->pivot->quantity }}</td>
                                        <td class="text-center align-middle">{{ $item->available_quantity }}</td>
                                        <td class="text-center align-middle">{{ $item->total_quantity }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Tidak ada barang pada transaksi ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="fw-bold">Informasi Pengguna</div>
                </div>
                <div class="card-body">
                    <div class="mb-2"><strong>Nama:</strong> {{ $loan->user->name ?? '-' }}</div>
                    <div class="mb-2"><strong>Email:</strong>
                        @if($loan->user->email)
                            <a href="mailto:{{ $loan->user->email }}">{{ $loan->user->email }}</a>
                        @else
                            -
                        @endif
                    </div>
                    <div class="mb-2"><strong>WhatsApp:</strong>
                        @if(!empty($loan->user->whatsapp_number))
                            @php $wa = preg_replace('/[^0-9+]/', '', $loan->user->whatsapp_number); @endphp
                            <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener">{{ $loan->user->whatsapp_number }}</a>
                        @else
                            -
                        @endif
                    </div>
                    <div class="mb-2"><strong>NIP/NIM:</strong> {{ $loan->user->identifier ?? '-' }}</div>
                    <div class="mb-0"><strong>Role:</strong> {{ ucfirst($loan->user->role ?? '-') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
