@extends('layouts.app')
@section('title','予約QRコード')

@section('head')
@endsection

@section('content')
<div class="qr-page">
    <div class="qr-card">
    <h1 class="qr-title">予約QRコード</h1>

    <div class="qr-info">
        <p>店舗名：{{ $reservation->shop->name ?? '' }}</p>
        <p>日付：{{ optional($reservation->reserved_at)->format('Y-m-d') }}</p>
        <p>時間：{{ optional($reservation->reserved_at)->format('H:i') }}</p>
        <p>人数：{{ $reservation->num_of_guests }}名</p>
    </div>

    <div class="qr-box">
        {!! QrCode::size(220)->encoding('UTF-8')->margin(1)->generate($qrText ?? 'No data') !!}
    </div>


    <div class="qr-actions">
        <a href="{{ route('mypage.index') }}" class="qr-back">マイページに戻る</a>
    </div>
    </div>
</div>
@endsection
