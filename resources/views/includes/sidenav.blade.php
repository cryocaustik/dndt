<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link text-center">
        <img class="brand-image-xl logo-xs" src="{{ asset('img/logo-50.png') }}" alt="DnDT" style="filter: invert(100%)">
        <span class="brand-text font-weight-light">DnD<b>T</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            @auth
                <div class="info">
                    <a href="#" class="d-block">
                        {{ Auth::user()->username }}
                    </a>
                </div>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary btn-block">
                    Login
                </a>
            @endguest
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item menu {{ Route::is('campaign.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-fire-smoke"></i>
                        <p>
                            Campaigns
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('campaign.list') }}" class="nav-link {{ Route::is('campaign.list') ? 'active' : '' }}">
                                <i class="nav-icon fal fa-fire-smoke"></i>
                                <p>Campaigns</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('campaign.permission.list') }}" class="nav-link {{ Route::is('campaign.permission.list') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>Campaign Permissions</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item menu {{ Route::is('inventory.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-backpack"></i>
                        <p>
                            Inventory
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('inventory.summary') }}" class="nav-link {{ Route::is('inventory.summary') ? 'active' : '' }}">
                                <i class="nav-icon fal fa-backpack"></i>
                                <p>Summary</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Log</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item menu">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users-crown"></i>
                        <p>
                            Admin
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Page</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Page</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
