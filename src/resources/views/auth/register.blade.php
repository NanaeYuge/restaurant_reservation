@extends('layouts.app')
@section('title','会員登録')

@section('content')
<div class="register-wrap">
    <div class="reg-card" role="region" aria-labelledby="reg-title">
        <div class="reg-head" id="reg-title">Registration</div>

    <div class="reg-body">

        <h1 class="page-title">会員登録</h1>

        <form method="post" action="{{ route('register') }}" class="form auth-form" novalidate>
        @csrf

        <div class="field">
            <div class="ctrl">
            <img class="icon" src="{{ asset('images/username.png') }}" alt="" aria-hidden="true">
            <input
            id="name"
            class="input icon-left @error('name') is-invalid @enderror"
            type="text"
            name="name"
            value="{{ old('name') }}"
            required
            autocomplete="name"
            placeholder="Username">
            </div>
            @error('name') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <div class="ctrl">
            <img class="icon" src="{{ asset('images/email.png') }}" alt="" aria-hidden="true">
            <input
            id="email"
            class="input icon-left @error('email') is-invalid @enderror"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autocomplete="email"
            inputmode="email"
            placeholder="Email">
            </div>
            @error('email') <div class="error">{{ $message }}</div> @enderror
        </div>


        <div class="field">
            <div class="ctrl">
            <img class="icon" src="{{ asset('images/password.png') }}" alt="" aria-hidden="true">
            <input
            id="password"
            class="input icon-left @error('password') is-invalid @enderror"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            placeholder="Password">
            </div>
            @error('password') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="reg-actions">
            <button class="btn primary" type="submit" aria-label="会員登録を送信">登録</button>
        </div>
        </form>

    </div>
    </div>
</div>
@endsection
