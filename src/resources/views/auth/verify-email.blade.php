@extends('layouts.app')
@section('content')
<div class="container">
    <h1>メールアドレスを確認してください</h1>
    @if (session('status') === 'verification-link-sent')
        <p>確認用メールを再送しました。</p>
    @endif
    <form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit">確認メールを再送</button>
    </form>
</div>
@endsection
