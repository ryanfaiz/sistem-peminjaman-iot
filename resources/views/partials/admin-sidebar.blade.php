<ul class="nav nav-pills flex-column mb-auto px-3">

    <li class="nav-item mb-2">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fas fa-home me-2"></i> Dashboard
        </a>
    </li>

    <li class="nav-item mb-2">
        <a href="{{ route('admin.items.index') }}"
           class="nav-link {{ request()->routeIs('admin.items.*') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fas fa-box me-2"></i> Data Barang
        </a>
    </li>

    <li class="nav-item mb-2">
        <a href="{{ route('admin.loans.index') }}"
           class="nav-link {{ request()->routeIs('admin.loans.*') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fas fa-exchange-alt me-2"></i> Peminjaman
        </a>
    </li>

    <li class="nav-item mb-2">
        <a href="{{ route('admin.users.index') }}"
           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fas fa-users me-2"></i> Pengguna
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.reports.index') }}"
           class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fas fa-file-alt me-2"></i> Laporan
        </a>
    </li>

</ul>