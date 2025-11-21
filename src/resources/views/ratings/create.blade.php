@extends('layouts.app')
@section('title', $shop->name.'の評価')

@section('content')
<h1 class="page-title">評価を投稿</h1>

<div class="card" style="max-width:720px;margin:0 auto;padding:16px;background:#fff">
  <div style="display:flex;gap:16px;align-items:center">
    <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}" style="width:88px;height:88px;object-fit:cover;border-radius:8px">
    <div>
      <div class="muted">対象店舗</div>
      <div style="font-weight:700">{{ $shop->name }}</div>
    </div>
  </div>

  <form method="post" action="{{ route('ratings.store',$shop->id) }}" class="form" style="margin-top:16px">
    @csrf
    <div style="display:flex;gap:8px;align-items:center">
      <input type="radio" id="s5" name="score" value="5" required><label for="s5">5</label>
      <input type="radio" id="s4" name="score" value="4"><label for="s4">4</label>
      <input type="radio" id="s3" name="score" value="3"><label for="s3">3</label>
      <input type="radio" id="s2" name="score" value="2"><label for="s2">2</label>
      <input type="radio" id="s1" name="score" value="1"><label for="s1">1</label>
    </div>
    @error('score')<p class="error">{{ $message }}</p>@enderror

    <textarea name="comment" class="textarea" rows="6" placeholder="コメント（任意・最大500文字）">{{ old('comment') }}</textarea>
    @error('comment')<p class="error">{{ $message }}</p>@enderror

    <div style="display:flex;gap:8px">
      <a class="btn" href="{{ route('shops.show',$shop->id) }}">戻る</a>
      <button class="btn primary" type="submit">投稿する</button>
    </div>
  </form>
</div>
@endsection
