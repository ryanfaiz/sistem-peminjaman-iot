@extends('layouts.app')

@section('header', 'Daftar Peminjaman')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Peminjaman</h1>

    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Peminjam</th>
                            <th>Tanggal Diajukan</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $loan)
                        <tr>
                            <td>{{ $loan->id }}</td>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ optional($loan->created_at)->format('d M Y H:i') }}</td>
                            <td>{{ optional($loan->loan_date)->format('d M Y') }}</td>
                            <td>{{ $loan->due_date->format('d M Y') }}</td>
                            <td>
                                @php
                                    $badgeColor = [
                                        'diajukan' => 'warning',
                                        'disetujui' => 'primary',
                                        'dipinjam' => 'info',
                                        'ditolak' => 'danger',
                                        'dikembalikan' => 'success'
                                    ][$loan->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badgeColor }}">
                                    {{ strtoupper($loan->status) }}
                                </span>
                            </td>
                            <td>
                                @auth
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('admin.loans.edit', $loan) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    @endif
                                    @else
                                        <a href="{{ route('user.borrow.show', $loan) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                @endauth
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Tidak ada data peminjaman.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection