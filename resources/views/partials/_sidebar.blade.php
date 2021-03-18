<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SMS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('/') ? 'active' : '' }} ">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <li class="nav-item {{ request()->is('teacher') ? 'active' : '' }} ">
        <a class="nav-link" href="{{ route('teacher.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Teacher</span></a>
    </li>
    <li class="nav-item {{ request()->is('term') ? 'active' : '' }} ">
        <a class="nav-link" href="{{ route('term.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Term</span></a>
    </li>
    <li class="nav-item {{ request()->is('student') ? 'active' : '' }} ">
        <a class="nav-link" href="{{ route('student.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Student</span></a>
    </li>
    <li class="nav-item {{ request()->is('studentMark') ? 'active' : '' }} ">
        <a class="nav-link" href="{{ route('studentMark.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Student Marks</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>