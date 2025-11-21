@extends('layouts.app')
@section('title','オンライン支払い')

@section('content')
<h1 class="page-title">オンライン支払い</h1>

@if($reservations->isEmpty())
  <p>支払い対象の予約はありません。</p>
@else
  <ul class="pay-list">
    @foreach($reservations as $reservation)
      <li class="pay-item">
        <div class="pay-meta">
          <div>予約 #{{ $reservation->id }}</div>
          <div>{{ optional($reservation->reserved_at)->format('Y/m/d H:i') }}</div>
          <div>店舗：{{ $reservation->shop->name }}</div>
        </div>

        @if($reservation->shop?->stripe_payment_link)
          <a class="btn primary"
             href="{{ $reservation->shop->stripe_payment_link }}"
             target="_blank" rel="noopener">
            この店舗に支払う（金額を入力）
          </a>
        @else
          <p class="muted">この店舗はオンライン決済未設定です</p>
        @endif
      </li>
    @endforeach
  </ul>
@endif
@endsection
