<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('dashboard.index') }}" class="app-brand-link">
      <img src="{{ asset('storage/assets/logo.jpg') }}" alt="SBLMS Logo" class="app-brand-logo demo" width="32" height="22">
      <span class="app-brand-text demo menu-text fw-bold">SBLMS</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
      <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    {{-- Dashboard (Visible for all roles) --}}
    <li class="menu-item {{ request()->routeIs('dashboard.index') ? 'active open' : '' }}">
      <a href="{{ route('dashboard.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-layout-dashboard"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>

    {{-- User Management (Visible ONLY for Super Admin) --}}
    @role('super-admin')
    <li class="menu-item {{ request()->is('user-management*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-user"></i>
        <div data-i18n="User Management">User Management</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('user-management.index') ? 'active' : '' }}">
          <a href="{{ route('user-management.index') }}" class="menu-link">
            <div data-i18n="List">List</div>
          </a>
        </li>

        <li class="menu-item {{ request()->routeIs('user-management.pending-approval') ? 'active' : '' }}">
          <a href="{{ route('user-management.pending-approval') }}" class="menu-link">
            <div data-i18n="List of Approval">List of Approval</div>
          </a>
        </li>

        <li class="menu-item {{ request()->routeIs('user-management.faculty-creation.index') ? 'active' : '' }}">
          <a href="{{ route('user-management.faculty-creation.index') }}" class="menu-link">
            <div data-i18n="Faculty">Faculty</div>
          </a>
        </li>
      </ul>
    </li>



     {{-- Books Management --}}
    <li class="menu-item {{ request()->is('books-management*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
       <i class="menu-icon tf-icons ti ti-book"></i>
        <div data-i18n="Books Management">Books Management</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('books-management.index') ? 'active' : '' }}">
          <a href="{{ route('books-management.index') }}" class="menu-link">
            <div data-i18n="Books List">Books List</div>
          </a>
        </li>
        
    @endrole

  </ul>
</aside>
