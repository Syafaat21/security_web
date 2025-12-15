<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                </p>
                </a>
            </li>
            @if(auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a href="/user" class="nav-link {{ request()->is('user') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Users
                    </p>
                    </a>
                </li>
            @endif
            @if(auth()->user()->role == 'customer')
                <li class="nav-item">
                    <a href="/dashboard_customer" class="nav-link {{ request()->is('dashboard_customer') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Customer Dashboard
                    </p>
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a href="/logout" class="nav-link {{ request()->is('logout') ? 'active' : '' }}">
                <i class="nav-icon fas fa-power-off"></i>
                <p>
                    Logout
                </p>
                </a>
            </li>
            </ul>
        </nav>
    </div>
</aside>
