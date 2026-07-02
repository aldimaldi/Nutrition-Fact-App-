<aside class="sidebar">
    <div class="brand">
        <div class="brand-mark">NQ</div>
        <div>
            <div class="brand-name">NutriQuest</div>
            <div class="brand-sub">Scan. Level up. Repeat.</div>
        </div>
    </div>

    <!-- Navigasi Dinamis -->
    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="nav-dot"></span> Home
    </a>
    
    <a href="{{ route('food-logs.index') }}" class="nav-item {{ request()->routeIs('food-logs.index') ? 'active' : '' }}">
        <span class="nav-dot"></span> Food logs
    </a>
    
    <a href="{{ route('character.index') }}" class="nav-item {{ request()->routeIs('character.*') ? 'active' : '' }}">
        <span class="nav-dot"></span> Character
    </a>
    
    <a href="{{ route('stats.index') }}" class="nav-item {{ request()->routeIs('stats.*') ? 'active' : '' }}">
        <span class="nav-dot"></span> Stats
    </a>

    <a href="{{ route('awards.index') }}" class="nav-item {{ request()->routeIs('awards.*') ? 'active' : '' }}">
        <span class="nav-dot"></span> Awards
    </a>
    
    <div class="sidebar-foot">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="nav-item-div" onclick="event.preventDefault(); this.closest('form').submit();" style="margin-bottom: 0;">
                <span class="nav-dot"></span> Logout
            </div>
        </form>
    </div>
</aside>