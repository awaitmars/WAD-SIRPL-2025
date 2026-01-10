<nav id="sidebar" class="sidebar">
    <div class="brand-section">
        <div class="brand-logo"><i class="fa fa-graduation-cap text-white"></i></div>
        <h6 class="mb-0 fw-bold" style="letter-spacing: 1px; font-size: 24px;">SI-RPL</h6>
    </div>
    <div class="sidebar-heading">Menu Utama</div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                <i class="fa fa-th-large"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('master-mk.index') }}" class="nav-link {{ request()->is('master-mk*') ? 'active' : '' }}">
                <i class="fa fa-database"></i> Master Mata Kuliah
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->is('jadwal*') ? 'active' : '' }}">
                <i class="fa fa-chalkboard-teacher"></i> Jadwal Mata Kuliah
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('academic.index') }}" class="nav-link {{ request()->is('academic-calendar*') ? 'active' : '' }}">
                <i class="fa fa-calendar-alt"></i> Kalender Akademik</a></li>
        <li class="nav-item">
            <a href="{{ route('budget.index') }}" class="nav-link {{ request()->is('budget*') ? 'active' : '' }}">
            <i class="fa fa-wallet"></i> Anggaran Akademik</a></li>
        <li class="nav-item">
            <a href="{{ route('kliping.index') }}" class="nav-link {{ request()->is('kliping-isu*') ? 'active' : '' }}">
            <i class="fa fa-newspaper"></i> Kliping Isu</a></li>
    </ul>
</nav>

