@extends('layouts.app')
@section('title','予約詳細')

@section('head')
@endsection

@section('content')
<div class="qr-page">
  <div class="qr-card">
    <h1 class="qr-title">予約詳細</h1>

    <div class="qr-info">
      <p>予約名：{{ $reservation->shop->name ?? '' }}</p>
      <p>予約ID：{{ $reservation->id }}</p>
      <p>予約者：{{ $reservation->user->name ?? '' }}</p>
      <p>日付：{{ optional($reservation->reserved_at)->format('Y-m-d') }}</p>
      <p>時間：{{ optional($reservation->reserved_at)->format('H:i') }}</p>
      <p>人数：{{ $reservation->num_of_guests }}名</p>
      <p>ステータス：{{ $reservation->status }}</p>
    </div>
  </div>
</div>
@endsection
