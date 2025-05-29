<nav class="navbar navbar-expand-lg bg-secondary navbar-dark sticky-top px-4 py-0" style="color: white;">
    <style>
        nav.navbar .nav-link,
        nav.navbar .dropdown-menu,
        nav.navbar .dropdown-item,
        nav.navbar .btn,
        nav.navbar .form-control,
        nav.navbar .input-group-text,
        nav.navbar .badge {
            color: white !important;
        }

        nav.navbar .btn-primary {
            color: white !important;
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }

        nav.navbar .form-control::placeholder {
            color: #ddd !important;
        }

        nav.navbar .fa {
            color: white !important;
        }

        nav.navbar .badge.bg-danger {
            background-color: #dc3545 !important;
            color: white !important;
        }
    </style>

    <div class="container-fluid">
        <a href="#" class="navbar-brand d-flex d-lg-none me-2">
            <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a href="#" class="sidebar-toggler flex-shrink-0 me-3">
            <i class="fa fa-bars"></i>
        </a>

        <div class="collapse navbar-collapse" id="navbarContent">
            {{-- Search --}}
            @php
            $searchRoute = Auth::guard('admin')->check() ? route('admin.search.results') :
            (Auth::guard('club')->check() ? route('club.search.results') :
            (Auth::guard('coach')->check() ? route('coach.search.results') : '#'));
            @endphp
            @if(Auth::guard('admin')->check() || Auth::guard('club')->check() || Auth::guard('coach')->check())
            <form action="{{ $searchRoute }}" method="POST" class="d-none d-md-flex ms-3 w-100">
                @csrf
                <div class="input-group w-75">
                    <input type="search" name="search_term" class="form-control bg-dark border-0"
                        placeholder="Search..." required minlength="2">
                    <input type="hidden" name="search_type" value="all">
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
            @endif

            {{-- Right Section --}}
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                {{-- Notifications Toggle Button --}}
                <li class="nav-item me-3">
                    <button id="enable-notifications-btn" class="btn btn-sm btn-warning">
                        <i class="fa fa-bell me-1"></i> Enable Notifications
                    </button>
                </li>

                {{-- Notifications Dropdown --}}
                <li class="nav-item dropdown me-3">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fa fa-bell me-lg-2"></i>
                        <span class="d-none d-lg-inline-flex">Notifications</span>
                        @php
                        $unreadCount = 0;
                        $notifications = [];
                        if (Auth::guard('admin')->check()) {
                        $unreadCount = Auth::guard('admin')->user()->unreadNotifications()->count();
                        $notifications = Auth::guard('admin')->user()->unreadNotifications()->limit(5)->get();
                        } elseif (Auth::guard('club')->check()) {
                        $unreadCount = Auth::guard('club')->user()->unreadNotifications()->count();
                        $notifications = Auth::guard('club')->user()->unreadNotifications()->limit(5)->get();
                        }
                        @endphp
                        @if($unreadCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                        @forelse($notifications as $notification)
                        <a href="javascript:void(0);" class="dropdown-item notification-item fw-bold"
                            data-id="{{ $notification->id }}"
                            data-url="{{ route('notifications.read', $notification->id) }}">
                            <h6 class="fw-normal mb-0">{{ $notification->title }}</h6>
                            <small>{{ $notification->message }}</small>
                            <small class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
                        </a>
                        @if(!$loop->last)
                        <hr class="dropdown-divider">
                        @endif
                        @empty
                        <a href="#" class="dropdown-item">
                            <h6 class="fw-normal mb-0">No unread notifications</h6>
                        </a>
                        @endforelse
                        <hr class="dropdown-divider">
                        <a href="{{ route('notifications.all') }}" class="dropdown-item text-center">See all notifications</a>
                    </div>
                </li>

                {{-- Profile Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <span class="d-none d-lg-inline-flex">
                            {{ Auth::guard('admin')->user()->name ?? Auth::guard('club')->user()->name ?? Auth::guard('coach')->user()->name ?? '' }}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                        @if(Auth::guard('admin')->check())
                        <a href="{{ route('admin.profile') }}" class="dropdown-item">My Profile</a>
                        <div class="dropdown-divider"></div>
                        @endif
                        <form action="{{ route('logoutusers') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item">Log Out</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>