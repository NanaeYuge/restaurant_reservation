@extends('layouts.app')
@section('content')
<div class="container">
    <h1>お支払いが完了しました</h1>
    <p>ご予約の支払いが正常に完了しました。</p>
    <a href="{{ route('mypage.index') }}">マイページへ</a>
</div>
@endsection
