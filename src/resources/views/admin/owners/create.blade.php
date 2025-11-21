@extends('layouts.app')
@section('title','店舗代表者の新規作成')
@section('content')
<div class="container" style="max-width:720px;margin:24px auto;">
    <h1 style="font-size:20px;margin-bottom:16px;">店舗代表者の新規作成</h1>

    @if($errors->any())
    <div style="background:#fee;border:1px solid #f99;padding:12px;border-radius:6px;margin-bottom:12px;">
        <ul style="margin:0;padding-left:18px;">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.owners.store') }}">
    @csrf
    <div style="margin-bottom:12px;">
        <label>氏名</label>
        <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;">
    </div>
    <div style="margin-bottom:12px;">
        <label>メールアドレス</label>
        <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;">
    </div>
    <div style="margin-bottom:12px;">
        <label>電話番号（任意）</label>
        <input type="text" name="phone" value="{{ old('phone') }}" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;">
    </div>
    <div style="margin-bottom:12px;">
        <label>初期パスワード</label>
        <input type="password" name="password" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;">
    </div>
    <div style="display:flex;gap:8px;">
        <button type="submit" class="btn btn-primary">作成する</button>
        <a href="{{ route('admin.dashboard') }}" class="btn">戻る</a>
    </div>
    </form>
</div>
@endsection
