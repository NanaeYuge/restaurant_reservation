@extends('layouts.app')
@section('title', $shop->name . 'の評価一覧')

@section('content')
<h1 class="page-title" style="margin:0">{{ $shop->name }} の評価</h1>

@if($count > 0)
  @include('components.stars', ['score' => $avg, 'count' => $count])
@else
  <p>まだ評価はありません。</p>
@endif

<div class="rating-list" style="margin-top:16px">
  @foreach($ratings as $r)
    <div class="rating-item" style="padding:12px 0;border-bottom:1px solid #eee">
      <div style="font-weight:600">{{ $r->user->name ?? 'ユーザー' }}</div>
      <div>スコア: {{ $r->score }}</div>
      @if($r->comment)
        <div>{{ $r->comment }}</div>
      @endif
      <div style="color:#666;font-size:12px">{{ $r->created_at->format('Y/m/d H:i') }}</div>
    </div>
  @endforeach
</div>

<div style="margin-top:12px">
  {{ $ratings->links() }}
</div>
@endsection
