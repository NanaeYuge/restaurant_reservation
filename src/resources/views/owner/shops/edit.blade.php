@extends('layouts.app')
@section('title', $isCreate ? '店舗情報の新規作成' : '店舗情報の編集')

@section('content')
<h1 class="page-title">{{ $isCreate ? '店舗情報の新規作成' : '店舗情報の編集' }}</h1>

@if(session('success'))
    <div class="alert-success" style="margin-bottom:12px">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert-danger" style="margin:12px 0">
    <ul style="margin:0;padding-left:18px">
        @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
    </ul>
    </div>
@endif

<form method="POST"
        action="{{ $isCreate ? route('owner.shop.store') : route('owner.shop.update', $shop) }}"
        enctype="multipart/form-data"
        style="max-width:560px">
    @csrf
    @unless($isCreate)
    @method('PUT')
    @endunless

    <div style="margin-bottom:12px">
    <label>店舗写真</label><br>
    <input type="file" name="image" accept="image/*">
    @if(!$isCreate && $shop->image_path)
        <div style="margin-top:6px">
        <img src="{{ asset('storage/'.$shop->image_path) }}" alt="" style="max-width:240px;height:auto">
        </div>
        <label style="display:inline-flex;gap:6px;align-items:center;margin-top:6px;font-size:14px">
        <input type="checkbox" name="remove_image" value="1"> 画像を削除する
        </label>
    @endif
    </div>

    <div style="margin-bottom:12px">
    <label>店名 必須</label><br>
    <input type="text" name="name" value="{{ old('name', $shop->name ?? '') }}" class="hinp" required>
    </div>

    <div style="margin-bottom:12px">
    <label>エリア 必須</label><br>
    <select name="area_id" required>
        <option value="">選択してください</option>
        @foreach($areas as $a)
        <option value="{{ $a->id }}" @selected((int)old('area_id', $shop->area_id ?? 0) === $a->id)>{{ $a->name }}</option>
        @endforeach
    </select>
    </div>

    <div style="margin-bottom:12px">
    <label>ジャンル 必須</label><br>
    <select name="genre_id" required>
        <option value="">選択してください</option>
        @foreach($genres as $g)
        <option value="{{ $g->id }}" @selected((int)old('genre_id', $shop->genre_id ?? 0) === $g->id)>{{ $g->name }}</option>
        @endforeach
    </select>
    </div>

    <div style="margin-bottom:12px">
    <label>店舗の詳細情報</label><br>
    <textarea name="summary" rows="4" style="width:100%">{{ old('summary', $shop->summary ?? '') }}</textarea>
    </div>

    <div style="display:flex;gap:8px">
    <a class="btn" href="{{ route('owner.dashboard') }}">戻る</a>
    <button type="submit" class="btn primary">{{ $isCreate ? '新規作成' : '更新する' }}</button>
    </div>
</form>
@endsection
