@extends('layouts.app')
@section('title','支払いをキャンセルしました')
@section('content')
    <div class="result">
    <div class="card">
        <p class="msg">支払いがキャンセルされました。</p>
        <a class="btn primary" href="{{ route('reservations.index') }}">予約一覧へ</a>
    </div>
    </div>
@endsection
