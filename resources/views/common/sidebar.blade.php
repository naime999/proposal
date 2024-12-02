<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="{{ asset(getSetting('app-logo')->value) }}" alt="Logo" height="30">
        </div>
        @if (getSetting('app-name')->value != "")
        <div class="sidebar-brand-text mx-3">{{ getSetting('app-name')->value }}</div>
        @endif
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading"> Management </div>

    <!-- Nav Item  -->
    @can('client-list')
        <li class="nav-item {{ request()->routeIs('users.clients') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.clients') }}">
                <i class="fas fa-solid fa-users"></i>
                <span>Clients</span>
            </a>
        </li>
    @endcan
    @can('category-list')
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#templteDropDown"
                aria-expanded="true" aria-controls="templteDropDown">
                <i class="fas fa-user-alt"></i>
                <span>Template</span>
            </a>
            <div id="templteDropDown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Template Settings</h6>
                    <a class="collapse-item" href="{{ route('users.template') }}">View Template</a>
                    <a class="collapse-item" href="{{ route('users.categories') }}">Categories</a>
                    {{-- <a class="collapse-item" href="{{ route('users.import') }}">Import Data</a> --}}
                </div>
            </div>
        </li>
    @endcan
    @can('proposal-list')
        <li class="nav-item {{ request()->routeIs('users.proposals') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.proposals') }}">
                <i class="fas fa-solid fa-file"></i>
                <span>Proposals</span>
                @if (getProposalCount() > 0)
                <span class="position-absolute top-50 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ getProposalCount() }}
                </span>
                @endif
            </a>
        </li>
    @endcan

    <!-- Divider -->
    <hr class="sidebar-divider">

    @hasrole('Admin')
        <!-- Heading -->
        <div class="sidebar-heading"> Admin Section </div>
        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#taTpDropDown"
                aria-expanded="true" aria-controls="taTpDropDown">
                <i class="fas fa-user-alt"></i>
                <span>User Management</span>
            </a>
            <div id="taTpDropDown" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">User Management:</h6>
                    <a class="collapse-item" href="{{ route('users.index') }}">List</a>
                    <a class="collapse-item" href="{{ route('users.create') }}">Add New</a>
                    <a class="collapse-item" href="{{ route('users.import') }}">Import Data</a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Masters</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Role & Permissions</h6>
                    <a class="collapse-item" href="{{ route('roles.index') }}">Roles</a>
                    <a class="collapse-item" href="{{ route('permissions.index') }}">Permissions</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
    @endhasrole

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>
    <!-- Sidebar Toggler (Sidebar) -->
    {{-- <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div> --}}


</ul>
