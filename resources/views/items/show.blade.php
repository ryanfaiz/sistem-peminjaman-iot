@extends('layouts.app')

@section('title', 'Detail Barang - ' . ($item->name ?? 'Item'))

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
        <div>
            <h4 class="mb-1">Detail Barang <small class="text-muted">#{{ $item->code }}</small></h4>
            <div class="small text-muted">Nama: {{ $item->name }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
            @auth
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus {{ $item->name }}?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                    </form>
                @endif
            @endauth
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-5">
            <div class="card shadow-sm">
                <div class="ratio ratio-4x3">
                    @if($item->photo_path)
                        <img src="{{ asset('storage/' . $item->photo_path) }}" class="card-img-top object-fit-cover" alt="{{ $item->name }}">
                    @else
                        <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='450'><rect width='100%25' height='100%25' fill='%23f8fafb'/><text x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%236c757d' font-family='Noto Sans JP, Arial, sans-serif' font-size='22'>No%20image</text></svg>" class="card-img-top object-fit-cover" alt="{{ $item->name }}">
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="card-title mb-1">{{ $item->name }}</h5>
                    <div class="small text-muted mb-2">Kode: {{ $item->code }}</div>
                    <div class="mb-2">
                        <span class="small text-uppercase text-muted">Kondisi</span>
                        <div class="fw-bold">{{ $item->condition }}</div>
                    </div>
                    <div class="mb-2">
                        <span class="small text-uppercase text-muted">Total Unit</span>
                        <div class="fw-bold">{{ $item->total_quantity }}</div>
                    </div>
                    <div>
                        <span class="small text-uppercase text-muted">Tersedia</span>
                        <div class="fw-bold text-{{ $item->available_quantity > 0 ? 'success' : 'danger' }}">{{ $item->available_quantity }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="fw-bold">Deskripsi</div>
                </div>
                <div class="card-body">
                    @if($item->description)
                        <p class="mb-0">{{ $item->description }}</p>
                    @else
                        <p class="text-muted mb-0">Tidak ada deskripsi untuk barang ini.</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <div class="fw-bold">Riwayat / Catatan</div>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-0">Belum ada riwayat yang tersedia.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection