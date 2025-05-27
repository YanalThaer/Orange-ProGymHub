<header>
    <div class="header-area header-transparent container-fluid">
        <div class="main-header header-sticky">
            <div class="menu-wrapper d-flex align-items-center justify-content-between">
                <div class="logo">
                    <a href="{{route('home')}}"><img src="{{ asset('img/image.png') }}" style="width: 200px; height: 70px;" alt=""></a>
                </div>
                <div class="main-menu f-right d-none d-lg-block">
                    <nav>
                        <ul id="navigation">
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li><a href="{{route('all_clubs')}}">Clubs</a></li>
                            <li><a href="{{route('all_coaches')}}">Coaches</a></li>
                            <li><a href="{{route('about')}}">About</a></li>
                            <li><a href="{{route('contact')}}">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                @auth
                <div class="header-btns d-none d-lg-block f-right">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="profileDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="font-size: medium;" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logoutusers') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                @else
                <div class="header-btns d-none d-lg-block f-right">
                    <a href="{{route('login')}}" class="btn">Log in</a>
                    <a href="{{route('register')}}" class="btn">Register</a>
                </div>
                @endauth
                <div class="mobile-menu" style="width: 20px;">
                    <div class="mobile_menu d-block d-lg-none"></div>
                    @auth
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const slicknavMenu = document.querySelector('.slicknav_nav');
                            if (slicknavMenu) {
                                const profileOptions = document.createElement('div');
                                profileOptions.className = 'profile-options';
                                profileOptions.innerHTML = `
                                            <div class="profile-option-item"><a href="{{ route('profile.show') }}">My Profile</a></div>
                                            <div class="profile-option-item"><a href="{{ route('profile.edit') }}">Edit Profile</a></div>
                                            <div class="profile-option-divider"></div>
                                            <div class="profile-option-item">
                                                <form action="{{ route('logoutusers') }}" method="POST" class="m-0">
                                                    @csrf
                                                    <button type="submit">Log Out</button>
                                                </form>
                                            </div>
                                        `;
                                slicknavMenu.appendChild(profileOptions);
                            }
                        });
                    </script>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>