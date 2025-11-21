@extends('layouts.app')
@section('title','予約完了')

@section('content')
<div class="result">
    <div class="card">
        <p class="msg">ご予約ありがとうございます</p>

    @if(isset($reservation) && in_array($reservation->status, ['pending_payment','failed']))
        <form action="{{ route('checkout', ['reservation' => $reservation->id]) }}" method="POST" style="margin-top:16px;">
            @csrf
            <button type="submit" class="btn">もう一度支払う</button>
        </form>
    @endif

        <a class="btn primary" href="{{ route('shops.index') }}" style="margin-top:16px;">戻る</a>
    </div>
</div>
@endsection
