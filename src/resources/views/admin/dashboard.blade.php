@extends('layouts.app')
@section('title','管理者ページ')

@section('content')
<div class="admin-dashboard">

    <div class="admin-dashboard__header">
        <h1 class="admin-dashboard__title">管理者ページ</h1>

            <form method="POST" action="{{ route('staff.logout') }}">
            @csrf
                <button type="submit" class="admin-dashboard__logout-btn">ログアウト</button>
            </form>
    </div>


    <div class="admin-dashboard__grid">
        <a href="{{ route('admin.owners.create') }}" class="admin-dashboard__card">
            <div class="admin-dashboard__card-title">店舗代表者の新規作成</div>
        </a>
    </div>

</div>
@endsection
