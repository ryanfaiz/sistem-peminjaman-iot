<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Peminjaman IoT')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.3.5/b-3.2.5/b-html5-3.2.5/b-print-3.2.5/r-3.0.7/datatables.min.css" rel="stylesheet" integrity="sha384-AaxeBOsLWAyJgToLJr/OsGQMUCZ4p1IyK+OIywHnzJVkgGSXmI4yr7mKcFMlOChp" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .dataTables_wrapper {
            width: 100%;
            clear: both;
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: #495057;
        }
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            display: inline-block;
            width: auto;
            margin-left: .5rem;
            vertical-align: middle;
        }
        .dt-buttons .btn,
        .dt-button,
        .dataTables_wrapper .dt-buttons button {
            margin-right: .35rem;
            padding: .35rem .6rem;
            border-radius: .25rem;
            border: 1px solid rgba(0,0,0,0.06);
            background: #fff;
            color: #212529;
        }
        table.dataTable thead th {
            background-color: #f8f9fa;
            color: #212529;
        }
        .table td,.table th { vertical-align: middle; }
        /* Reduce button clutter on small screens: hide export buttons on narrow viewports */
        @media (max-width: 575.98px){
            .dt-buttons { display: none !important; }
        }
        }
    </style>
    <style>
        .dataTables_wrapper .dt-right { display: flex; align-items: center; gap: .5rem; }
        .dataTables_wrapper .dt-info { display: flex; align-items: center; }
        .dataTables_wrapper .dt-pag { display: flex; justify-content: flex-end; }
        /* make sure DataTables buttons group matches Bootstrap spacing */
        .dataTables_wrapper .dt-buttons { display: flex; gap: .35rem; flex-wrap: wrap; }
    </style>
    <style>
        /* Gaya kustom minimal untuk sidebar dan body */
        body {
            background-color: #f8f9fa; /* Light gray background */
        }
        .sidebar {
            width: 280px;
            min-height: 100vh;
            background-color: #ffffff; /* Light sidebar to match welcome */
            color: #212529;
            padding-top: 1rem;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            border-right: 1px solid #e9ecef;
        }
        .sidebar,
        .main-content,
        .navbar-top {
            transition: all .25s ease-in-out;
        }

        /* Collapsed sidebar state */
        body.sidebar-collapsed .sidebar {
            width: 0 !important;
            transform: translateX(-100%);
            visibility: hidden;
            border-right: none;
        }
        body.sidebar-collapsed .main-content {
            margin-left: 0 !important;
        }
        body.sidebar-collapsed .navbar-top {
            width: 100% !important;
            margin-left: 0 !important;
        }
        .main-content {
            margin-left: 280px; /* Offset main content */
            padding-top: 70px; /* Offset for fixed navbar */
            min-height: 100vh;
        }

        /* Sidebar link colors (light sidebar) */
        .sidebar .nav-link {
            color: #495057; /* dark text for links */
        }
        .sidebar .nav-link:hover {
            color: #0d6efd; /* bootstrap primary on hover */
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link.bg-primary {
            color: #fff !important;
        }
        .navbar-top {
            position: fixed;
            width: calc(100% - 280px);
            margin-left: 280px;
            top: 0;
            z-index: 1050;
        }

        /* Responsive adjustments: collapse sidebar on narrow screens (phones) */
        @media (max-width: 767.98px) {
            /* On mobile the sidebar should be hidden by default and act as an overlay when opened */
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 280px;
                height: 100vh;
                transform: translateX(-100%);
                visibility: hidden;
                overflow-y: auto;
                z-index: 1100; /* overlay above navbar */
                border-right: 1px solid #e9ecef;
                box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08);
            }
            .navbar-top {
                width: 100%;
                margin-left: 0;
                z-index: 1050;
            }
            .main-content {
                margin-left: 0;
                padding-top: 70px; /* keep default navbar offset */
            }

            /* When not collapsed (i.e., opened) the sidebar overlays the page */
            body:not(.sidebar-collapsed) .sidebar {
                transform: translateX(0);
                visibility: visible;
            }
            /* overlay backdrop when sidebar open on mobile */
            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.35);
                z-index: 1090;
            }
        }
    </style>
</head>
<body>
    
    <div class="sidebar shadow-lg d-flex flex-column">
        <div class="p-3 mb-3 text-center border-bottom">
            <span class="fs-4 fw-bold text-primary">@yield('title', 'Sistem Peminjaman IoT')</span>
        </div>
        @if (Auth::check() && Auth::user()->role === 'admin')
            @include('partials.admin-sidebar')
        @elseif(Auth::check())
            @include('partials.user-sidebar')
        @endif

    </div>

    <!-- TOP NAVIGATION BAR (Header) -->
    <nav class="navbar navbar-top navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <!-- Sidebar toggle (visible on all sizes) -->
            <button id="sidebarToggle" class="btn btn-outline-secondary btn-sm me-3" type="button" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Header Title -->
            @if(View::hasSection('header'))
                <h5 class="mb-0 text-dark">@yield('header')</h5>
            @elseif(View::hasSection('title'))
                <h5 class="mb-0 text-dark">@yield('title')</h5>
            @else
                <h5 class="mb-0 text-dark">Sistem Peminjaman IoT</h5>
            @endif

            <!-- User Dropdown & Profile Info -->
            <div class="d-flex align-items-center">
                @if (Auth::check())
                    <span class="me-3 text-muted d-none d-sm-inline">
                        {{ Auth::user()->name }} ({{ strtoupper(Auth::user()->role) }})
                    </span>
                @endif
                
                <form method="POST" action="/logout" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>
    
    <!-- MAIN CONTENT AREA -->
    <div class="main-content">
        <main class="container-fluid p-4">
            <!-- Session Status / Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <p class="mb-0 fw-bold">Berhasil</p>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p class="mb-0 fw-bold">Gagal</p>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script>
        (function(){
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const STORAGE_KEY = 'sistem_sidebar_collapsed';
            let backdrop = null;

            function isPhone(){ return window.innerWidth < 768; }
            function storageKeyForCurrent(){ return STORAGE_KEY + (isPhone() ? '_phone' : '_desktop'); }

            function createBackdrop(){
                if(backdrop) return backdrop;
                backdrop = document.createElement('div');
                backdrop.className = 'sidebar-backdrop';
                backdrop.id = 'sidebarOverlay';
                backdrop.addEventListener('click', () => setCollapsed(true));
                document.body.appendChild(backdrop);
                return backdrop;
            }

            function removeBackdrop(){
                if(!backdrop) return;
                try { backdrop.removeEventListener('click', () => setCollapsed(true)); } catch(e){}
                if(backdrop.parentNode) backdrop.parentNode.removeChild(backdrop);
                backdrop = null;
            }

            function setCollapsed(collapsed){
                if(collapsed){
                    document.body.classList.add('sidebar-collapsed');
                    // remove overlay if present
                    removeBackdrop();
                } else {
                    document.body.classList.remove('sidebar-collapsed');
                    // on phone show overlay
                    if(isPhone()){
                        createBackdrop();
                    }
                }
                try { localStorage.setItem(storageKeyForCurrent(), collapsed ? '1' : '0'); } catch(e){}
            }

            // determine initial state: prefer stored user choice; if none, default to hidden on small screens
            try {
                const stored = localStorage.getItem(storageKeyForCurrent());
                if(stored === '1') {
                    setCollapsed(true);
                } else if(stored === '0') {
                    setCollapsed(false);
                } else {
                    // no stored preference: hide sidebar by default only on narrow screens (phones)
                    if(isPhone()) {
                        setCollapsed(true);
                    } else {
                        setCollapsed(false);
                    }
                }
            } catch(e){}

            // handle toggle
            if(toggle){
                toggle.addEventListener('click', function(e){
                    const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
                    // when opening on narrow screens (phones), ensure overlay is present
                    if(!isCollapsed && isPhone()){
                        createBackdrop();
                    } else {
                        removeBackdrop();
                    }
                    try { localStorage.setItem(storageKeyForCurrent(), isCollapsed ? '1' : '0'); } catch(e){}
                });
            }

                // when clicking a nav link on phone, close sidebar
                document.addEventListener('click', function(e){
                    if(!isPhone()) return;
                const open = !document.body.classList.contains('sidebar-collapsed');
                if(!open) return;
                const target = e.target;
                if(target.closest && target.closest('.sidebar')) return; // clicks inside sidebar don't close
                if(target.closest && target.closest('#sidebarToggle')) return; // toggle button
                // otherwise close
                setCollapsed(true);
            }, true);

            // focus handling: if sidebar loses focus on phone, close it
            if(sidebar){
                sidebar.addEventListener('focusout', function(e){
                        if(!isPhone()) return;
                    // defer check so activeElement updates
                    setTimeout(()=>{
                        if(!sidebar.contains(document.activeElement)){
                            setCollapsed(true);
                        }
                    }, 0);
                });
            }

            // cleanup overlay on resize
            window.addEventListener('resize', function(){
                if(!isPhone()){
                    removeBackdrop();
                } else {
                    // if sidebar open on small screens, ensure backdrop exists
                    if(!document.body.classList.contains('sidebar-collapsed')){
                        createBackdrop();
                    }
                }
            });

        })();
    </script>
    <!-- jQuery (required by DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" crossorigin="anonymous"></script>

    <!-- pdfmake + vfs fonts (for PDF export) and DataTables bundle (Bootstrap 5 + Buttons + Responsive) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.3.5/b-3.2.5/b-html5-3.2.5/b-print-3.2.5/r-3.0.7/datatables.min.js" integrity="sha384-QV/DeGqSJeC9KstgX3na3cxDRLzL3tBgNyVQyb+oQ5gE/keNK7YsLC7+dRZY73uA" crossorigin="anonymous"></script>

    <!-- Initialize DataTables for all tables (except those opting out with .no-datatable) -->
    <script>
        (function(){
            // Wait for jQuery + DataTables to be available
            function init() {
                if(typeof jQuery === 'undefined' || typeof jQuery.fn.DataTable === 'undefined'){
                    // try again shortly
                    setTimeout(init, 100);
                    return;
                }

                jQuery(function($){
                    $('table').each(function(){
                        const $t = $(this);
                        if($t.hasClass('no-datatable')) return;
                        // avoid double-initialization
                        if($.fn.dataTable.isDataTable(this)) return;

                        try {
                            console.info('Initializing DataTable for', this);
                            $t.DataTable({
                                    responsive: true,
                                    // Top: length (l) on left, filter (f) + buttons (B) on right; Bottom: info (i) + pagination (p) aligned right
                                    dom: '<"d-flex justify-content-between align-items-center mb-2"<"dt-left"l><"dt-right d-flex align-items-center"fB>>t<"d-flex justify-content-end align-items-center mt-2"<"dt-info me-3"i><"dt-pag"p>>',
                                    buttons: [
                                        { extend: 'copy', className: 'btn btn-sm btn-outline-secondary' },
                                        { extend: 'csv', className: 'btn btn-sm btn-outline-secondary' },
                                        { extend: 'excel', className: 'btn btn-sm btn-outline-secondary' },
                                        { extend: 'pdf', className: 'btn btn-sm btn-outline-secondary' },
                                        { extend: 'print', className: 'btn btn-sm btn-outline-secondary' }
                                    ],
                                    pagingType: 'simple_numbers',
                                    // preserve existing table classes/appearance
                                    language: {
                                        url: ''
                                    }
                                });
                        } catch (err) {
                            // log and continue initializing other tables
                            console.error('DataTable init error for table:', this, err && err.message ? err.message : err);
                        }
                    });
                });
            }
            init();
        })();
    </script>
    @stack('scripts')
</body>
</html>