@extends('template.app')
@section('content')
@section('title', 'Авторизация')
<div class="container">
    <div class="login-form">
        <h1>Войти</h1>

        <form action="{{ route('login') }}" method="post" >
            @csrf

            <label for="email">Email</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <label for="password">Пароль</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <button type="submit" class="login-button" >
                Войти
            </button>
        </form>
    </div>
</div>
@endsection