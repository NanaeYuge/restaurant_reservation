@extends('layouts.app')
@section('title','店舗代表者の紐付け')

@section('content')
<div style="max-width:720px;margin:0 auto">
  <h1 class="page-title" style="margin-bottom:12px">店舗代表者の紐付け</h1>

  @if(session('success'))
    <div class="alert-success" style="margin-bottom:12px">{{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert-danger" style="margin-bottom:12px">
      <ul style="margin:0;padding-left:18px">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div style="padding:12px;border:1px solid #eee;border-radius:8px;margin-bottom:16px">
    <div style="font-weight:600;font-size:18px">{{ $shop->name }}</div>
    <div style="color:#666;margin-top:4px">現在の代表者:
      @if($shop->owner_id)
        <span>{{ optional($shop->owner)->name }}（ID: {{ $shop->owner_id }}）</span>
      @else
        <span>未設定</span>
      @endif
    </div>
  </div>

  <form method="POST" action="{{ route('admin.shops.owner.update', $shop) }}" style="display:grid;gap:12px">
    @csrf
    @method('PUT')

    <label>代表者ユーザーを選択</label>
    <select name="owner_id" required>
      <option value="">選択してください</option>
      @foreach($owners as $o)
        <option value="{{ $o->id }}" @selected((int)old('owner_id', $shop->owner_id) === $o->id)>
          {{ $o->name }}（{{ $o->email }}）
        </option>
      @endforeach
    </select>

    <div style="display:flex;gap:8px">
      <a href="{{ url()->previous() }}" class="btn">戻る</a>
      <button type="submit" class="btn primary">保存する</button>
    </div>
  </form>
</div>
@endsection
