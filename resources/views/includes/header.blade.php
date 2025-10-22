<header class="header">
    <div class="container">
        @guest
            <div class="header-ui">
                <div class="header-nav">
                    <a href="{{ route('view.mainpage') }}"">Главная</a>
                </div>
                <div class="header-user">
                    <a class="registration-button" href="{{ route('view.login') }}">Войти</a>

                    <a class="login-button" href="{{ route('view.register') }}">Зарегистрироваться</a>
                </div>
            </div>
        @endguest
        
        @auth
            <div class="header-main">
                <div class="logo learnit">
                    <span class="logo-text">Learn<span class="highlight">IT</span></span>
                </div>

                <div class="divider"></div>

                <div class="header-nav">
                    <a href="{{ route('view.mainpage') }}"">Главная</a>
                </div>
            </div>

            <div class="header-user">
                @if (request()->routeIs('profile.show'))
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-button">Выйти</button>
                    </form>
                @else
                    @if (auth()->user()->role == "admin")
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-button">Выйти</button>
                        </form>
                    @else 
                        <img src="{{ asset('img/user.png') }}" alt="user">
                        <a href="{{ route('profile.show') }}">{{ auth()->user()->name }}</a>
                    @endif
                @endif
            </div>
        @endauth
    </div>
</header>