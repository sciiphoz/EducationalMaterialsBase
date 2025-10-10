<header class="header">
    <div class="container">
        @guest
            <div class="header-nav">
                <a href="index.html">Главная</a>
            </div>
            <div class="header-logo">
                <a href="#">лого</a>
            </div>
            <div class="header-user">
                <a class="registration-button" href="{{ route('view.login') }}">Login</a>
            </div>
            <div class="header-user">
                <a class="login-button" href="{{ route('view.register') }}">Register</a>
            </div>
        @endguest
        @auth
            <div class="header-nav">
                <form method="POST" action="{{ route('view.mainpage') }}">
                    Home
                </form>
            </div>
            <div class="header-logo">
                <img src="" alt="search">
                <form method="POST" action="{{ route('view.profilepage') }}">
                    Profile
                </form>
            </div>
        @endauth
    </div>
</header>