<ul class="nav nav-pills flex-column mb-auto px-3">

    <li class="nav-item mb-2">
        <a href="{{ route('user.dashboard') }}"
           class="nav-link {{ request()->routeIs('user.dashboard') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fas fa-home me-2"></i> Dashboard
        </a>
    </li>

    <li class="nav-item mb-2">
        <a href="{{ route('user.items.index') }}"
           class="nav-link {{ request()->routeIs('user.items.*') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fas fa-box-open me-2"></i> Daftar Barang
        </a>
    </li>

    <li class="nav-item mb-2">
        <a href="{{ route('user.borrow.index') }}"
           class="nav-link {{ request()->routeIs('user.borrow.*') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fas fa-hand-holding me-2"></i> Peminjaman Saya
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('user.profile') }}"
           class="nav-link {{ request()->routeIs('user.profile') ? 'active bg-primary text-white' : 'text-dark' }}">
            <i class="fas fa-user me-2"></i> Profil
        </a>
    </li>

</ul>