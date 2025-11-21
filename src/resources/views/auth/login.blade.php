@extends('layouts.app')

@section('title','ログイン')

@push('styles')
    @vite(['resources/css/pages/auth/login.css'])
@endpush

@section('content')
<div class="auth">
    <div class="auth-card" role="region" aria-labelledby="login-title">
    <div class="auth-head" id="login-title">Login</div>

    <div class="auth-body">
        @if (session('status'))
        <div class="alert status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
        <div class="alert error">
            <strong>ログインに失敗しました。</strong>
            <ul style="margin:8px 0 0 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="form auth-form" novalidate>
        @csrf

        <div class="field">
            <div class="ctrl">
            <img class="icon" src="{{ asset('images/email.png') }}" alt="" aria-hidden="true">
            <input
                id="email"
                class="input @error('email') is-invalid @enderror"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                autofocus
                placeholder="Email">
            </div>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <div class="ctrl">
            <img class="icon" src="{{ asset('images/password.png') }}" alt="" aria-hidden="true">
            <input
                id="password"
                class="input @error('password') is-invalid @enderror"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Password">
            </div>
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
        </div>

        <div class="actions">
            <button class="btn primary" type="submit">ログイン</button>
        </div>

        <div class="links">
            @if (Route::has('staff.login'))
            <span class="muted">店舗代表者・管理者の方は <a href="{{ route('staff.login') }}">スタッフログイン</a></span>
            @endif
        </div>
        </form>
    </div>
    </div>
</div>
@endsection
