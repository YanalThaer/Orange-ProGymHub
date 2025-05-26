<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-secondary navbar-dark">
        <!-- Logo and Project Name Horizontally -->
        <a href="index.html" class="navbar-brand mx-4 mb-3 d-flex align-items-center justify-content-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="width: 40px; height: 40px;" class="me-2">
            <h4 class="text-primary mb-0">ProGymHub</h4>
        </a>

        <!-- User Info Section in the center -->
        <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center mb-3">
            <div class="d-flex flex-column align-items-center">
                <div class="position-relative mb-2">
                    @php
                        $guard = Auth::guard('admin')->check() ? 'admin' : (Auth::guard('club')->check() ? 'club' : (Auth::guard('coach')->check() ? 'coach' : null));
                        $user = $guard ? Auth::guard($guard)->user() : null;
                        $imageField = $guard === 'admin' ? 'profile_picture' : ($guard === 'club' ? 'logo' : 'profile_image');
                        $imagePath = $user && $user->$imageField 
                            ? (str_starts_with($user->$imageField, 'http') 
                                ? $user->$imageField 
                                : asset('storage/' . $user->$imageField)) 
                            : asset('img/default-avatar.png');
                    @endphp

                    <img class="rounded-circle border border-white" src="{{ $imagePath }}" alt="Profile"
                        style="width: 80px; height: 80px;">
                    <div class="bg-success rounded-circle border border-white position-absolute end-0 bottom-0 p-1"></div>
                </div>

                <div class="text-center text-white">
                    <div class="bg-primary text-white px-3 py-1 rounded mb-1">
                        <strong>{{ ucfirst($guard) }}</strong>
                    </div>
                    <h5 class="mb-0">{{ $user ? $user->name : '' }}</h5>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="navbar-nav w-100">
            <a href="@if($guard === 'admin')
                        {{ route('admin.dashboard') }}
                    @elseif($guard === 'club')
                        {{ route('club.dashboard') }}
                    @elseif($guard === 'coach')
                        {{ route('coach.dashboard') }}
                    @endif"
                class="nav-item nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <i class="fa fa-tachometer-alt me-2"></i>Dashboard
            </a>

            @if($guard === 'admin')
                <a href="{{ route('admin.clubs') }}" class="nav-item nav-link {{ request()->routeIs('admin.clubs') ? 'active' : '' }}">
                    <i class="fa fa-th me-2"></i>Clubs
                </a>
                <a href="{{ route('admin.users') }}" class="nav-item nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fa fa-users me-2"></i>Users
                </a>
                <a href="{{ route('admin.coaches') }}" class="nav-item nav-link {{ request()->routeIs('admin.coaches*') ? 'active' : '' }}">
                    <i class="fa fa-dumbbell me-2"></i>Coaches
                </a>
                <a href="{{ route('admin.search') }}" class="nav-item nav-link {{ request()->routeIs('admin.search*') ? 'active' : '' }}">
                    <i class="fa fa-search me-2"></i>Search
                </a>
            @elseif($guard === 'club')
                <a href="{{ route('club.profile') }}" class="nav-item nav-link {{ request()->routeIs('club.profile') ? 'active' : '' }}">
                    <i class="fa fa-building me-2"></i>My Club
                </a>
                <a href="{{ route('club.subscription-plans') }}" class="nav-item nav-link {{ request()->routeIs('club.subscription-plans*') ? 'active' : '' }}">
                    <i class="fa fa-tags me-2"></i>Subscription Plans
                </a>
                <a href="{{ route('club.users') }}" class="nav-item nav-link {{ request()->routeIs('club.users*') ? 'active' : '' }}">
                    <i class="fa fa-user-cog me-2"></i>User Management
                </a>
                <a href="{{ route('club.coaches') }}" class="nav-item nav-link {{ request()->routeIs('club.coaches*') ? 'active' : '' }}">
                    <i class="fa fa-dumbbell me-2"></i>Coach Management
                </a>
                <a href="{{ route('club.search') }}" class="nav-item nav-link {{ request()->routeIs('club.search*') ? 'active' : '' }}">
                    <i class="fa fa-search me-2"></i>Search
                </a>
            @elseif($guard === 'coach')
                <a href="{{ route('coach.profile') }}" class="nav-item nav-link {{ request()->routeIs('coach.profile*') ? 'active' : '' }}">
                    <i class="fa fa-user me-2"></i>My Profile
                </a>
                <a href="{{ route('coach.club') }}" class="nav-item nav-link {{ request()->routeIs('coach.club') ? 'active' : '' }}">
                    <i class="fa fa-building me-2"></i>My Club
                </a>
                <a href="{{ route('coach.clients') }}" class="nav-item nav-link {{ request()->routeIs('coach.clients*') ? 'active' : '' }}">
                    <i class="fa fa-users me-2"></i>My Clients
                </a>
                <a href="{{ route('coach.search') }}" class="nav-item nav-link {{ request()->routeIs('coach.search*') ? 'active' : '' }}">
                    <i class="fa fa-search me-2"></i>Search
                </a>
            @endif
        </div>
    </nav>
</div>
