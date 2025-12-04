@extends('layouts.app')

@section('header', 'Daftar Barang')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Semua Barang</h4>
        <div class="d-flex align-items-center gap-2">
            <div class="btn-group" role="group" aria-label="View toggle">
                <button id="viewTableBtn" type="button" class="btn btn-outline-secondary" title="Table view"><i class="fas fa-table"></i></button>
                <button id="viewGridBtn" type="button" class="btn btn-outline-secondary" title="Grid view"><i class="fas fa-th"></i></button>
            </div>
            @auth
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.items.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i> Tambah Barang Baru
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Table view -->
    <div id="itemsTable" class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0 datatable">
                    <thead class="bg-light">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Kode</th>
                            <th class="text-center">Kondisi</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Tersedia</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td>
                                @auth
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('admin.items.show', $item) }}" class="text-primary fw-bold text-decoration-none">{{ $item->name }}</a>
                                    @else
                                        <a href="{{ route('user.items.show', $item) }}" class="text-primary fw-bold text-decoration-none">{{ $item->name }}</a>
                                    @endif
                                @else
                                    <a href="{{ route('user.items.show', $item) }}" class="text-primary fw-bold text-decoration-none">{{ $item->name }}</a>
                                @endauth
                            </td>
                            <td>{{ $item->code }}</td>
                            <td class="text-center">{{ $item->condition }}</td>
                            <td class="text-center">{{ $item->total_quantity ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $item->available_quantity > 0 ? 'success' : 'danger' }}">{{ $item->available_quantity }}</span>
                            </td>
                            <td class="text-center">
                                @auth
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('admin.items.show', $item) }}" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus {{ $item->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('user.items.show', $item) }}" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="fas fa-eye"></i></a>
                                    @endif
                                @endauth
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-muted py-3">Belum ada barang tersedia.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grid view -->
    <div id="itemsGrid" class="row g-3 d-none">
        @forelse($items as $item)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <div class="ratio ratio-4x3">
                        @if($item->photo_path)
                            <img src="{{ asset('storage/' . $item->photo_path) }}" class="card-img-top object-fit-cover" alt="{{ $item->name }}">
                        @else
                            <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='450'><rect width='100%25' height='100%25' fill='%23f8fafb'/><text x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%236c757d' font-family='Noto Sans JP, Arial, sans-serif' font-size='22'>No%20image</text></svg>" class="card-img-top object-fit-cover" alt="{{ $item->name }}">
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">
                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('admin.items.show', $item) }}" class="text-decoration-none">{{ $item->name }}</a>
                                @else
                                    <a href="{{ route('user.items.show', $item) }}" class="text-decoration-none">{{ $item->name }}</a>
                                @endif
                            @else
                                <a href="{{ route('user.items.show', $item) }}" class="text-decoration-none">{{ $item->name }}</a>
                            @endauth
                        </h6>
                        <div class="small text-muted mb-2">{{ $item->code }}</div>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-{{ $item->available_quantity > 0 ? 'success' : 'danger' }}">{{ $item->available_quantity }}</span>
                                <div class="small text-muted">Total: {{ $item->total_quantity ?? '-' }}</div>
                            </div>
                            <div class="btn-group">
                                @auth
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('admin.items.show', $item) }}" class="btn btn-sm btn-primary" title="Lihat"><i class="fas fa-eye"></i></a>
                                    @else
                                        <a href="{{ route('user.items.show', $item) }}" class="btn btn-sm btn-primary" title="Lihat"><i class="fas fa-eye"></i></a>
                                    @endif
                                @else
                                    <a href="{{ route('user.items.show', $item) }}" class="btn btn-sm btn-primary" title="Lihat"><i class="fas fa-eye"></i></a>
                                @endauth
                                @auth
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-outline-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus {{ $item->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-3">Belum ada barang tersedia.</div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        const table = document.getElementById('itemsTable');
        const grid = document.getElementById('itemsGrid');
        const tableBtn = document.getElementById('viewTableBtn');
        const gridBtn = document.getElementById('viewGridBtn');
        const STORAGE_KEY = 'items_view_mode';

        function setView(mode){
            if(mode === 'grid'){
                grid.classList.remove('d-none');
                table.classList.add('d-none');
                gridBtn.classList.add('active');
                tableBtn.classList.remove('active');
            } else {
                grid.classList.add('d-none');
                table.classList.remove('d-none');
                gridBtn.classList.remove('active');
                tableBtn.classList.add('active');
            }
            try{ localStorage.setItem(STORAGE_KEY, mode); }catch(e){}
        }

        // init
        try{
            const stored = localStorage.getItem(STORAGE_KEY) || 'table';
            setView(stored);
        }catch(e){ setView('table'); }

        if(tableBtn) tableBtn.addEventListener('click', ()=> setView('table'));
        if(gridBtn) gridBtn.addEventListener('click', ()=> setView('grid'));
    })();
</script>
@endpush