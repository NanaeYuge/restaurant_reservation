@extends('layouts.app')
@section('title','店舗代表者ページ')

@section('head')
    @vite(['resources/css/pages/owner/dashboard.css'])
@endsection

@section('content')
<div class="owner-wrap">
    <div class="owner-header">
        <h1 class="owner-title">店舗代表者ページ</h1>

        <form method="POST" action="{{ route('staff.logout') }}" class="owner-logout-form">
            @csrf
            <button type="submit" class="owner-logout-btn">
                ログアウト
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="owner-actions">
        <a class="btn btn-primary" href="{{ route('owner.shop.editOrCreate') }}">店舗情報の作成・更新</a>
        <a class="btn btn-secondary" href="{{ route('owner.reservations.index') }}">予約情報の確認</a>
    </div>

    <div class="owner-card">
        @if(isset($shops) && $shops instanceof \Illuminate\Support\Collection)
            @if($shops->isEmpty())
                <p class="muted">店舗情報がありません。</p>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>店舗名</th>
                                <th>店舗代表者</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shops as $s)
                                <tr>
                                    <td>{{ $s->name }}</td>
                                    <td>{{ $s->owner?->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @elseif(isset($shop) && $shop)
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>店舗名</th>
                            <th>店舗代表者</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $shop->name }}</td>
                            <td>{{ $shop->owner?->name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <p class="muted">店舗情報がありません。</p>
        @endif
    </div>
</div>
@endsection
