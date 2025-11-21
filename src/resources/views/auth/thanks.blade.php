@extends('layouts.app')
@section('title','登録完了')

@section('content')
<div class="thanks">
    <div class="thanks-card">
        <h1 class="thanks-title">会員登録ありがとうございます</h1>
            <div class="thanks-actions">
                <a class="btn primary" href="{{ route('login') }}">ログインする</a>
            </div>
    </div>
</div>
@endsection
