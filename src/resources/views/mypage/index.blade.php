@extends('layouts.app')
@section('title','マイページ')

@section('head')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
@php
    $user = auth()->user();
@endphp

<div class="mypage-page">

    <header class="mypage-header">
        <div class="header-cell header-user-left"></div>
        <div class="header-cell header-user-right">
            <p class="mypage-username">{{ Auth::user()->name }}さん</p>
        </div>
        <div class="header-cell header-title-left">
            <h2 class="mypage-sec-title">予約状況</h2>
        </div>
        <div class="header-cell header-title-right">
            <p class="mypage-fav-title">お気に入り店舗</p>
        </div>
    </header>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert-warning">
            {{ session('warning') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="mypage-layout">
        {{-- 左：予約状況 --}}
        <section class="mypage-left">
            @forelse($reservations as $i => $r)
                @php
                    $dateCol = $r->date
                        ?? ($r->reservation_date
                        ?? ($r->reserved_at
                        ?? ($r->visit_date ?? null)));

                    $dt = $dateCol ? \Illuminate\Support\Carbon::parse($dateCol) : null;

                    $isPastOrYesterday = $dt ? $dt->isBefore(\Illuminate\Support\Carbon::today()) : false;

                    $rated = \App\Models\Rating::where('user_id', auth()->id())
                        ->where('shop_id', $r->shop_id)
                        ->when($dt, function ($q) use ($dt) {
                            $q->whereDate('visited_at', $dt->toDateString());
                        })
                        ->exists();

                    $canRate = $isPastOrYesterday && ! $rated;

                    // 今日以降なら変更可能
                    $canCancel = $dt && $dt->gte(\Illuminate\Support\Carbon::today());
                @endphp

                <article class="resv-card">
                    <div class="resv-card-head">
                        <div class="resv-badge-wrap">
                            <img src="{{ asset('images/watch.png') }}" alt="時計" class="resv-watch">
                            <span class="resv-badge-text">予約{{ $i + 1 }}</span>
                        </div>
                        @if($canCancel)
                            <form method="post" action="{{ route('reservations.destroy', $r) }}" class="resv-cancel">
                                @csrf
                                @method('delete')
                                <button class="resv-close" type="submit" aria-label="予約をキャンセルする">×</button>
                            </form>
                        @endif
                    </div>

                    <div class="resv-body">
                        <div class="resv-row">
                            <span class="resv-label">Shop</span>
                            <span class="resv-value">{{ $r->shop->name ?? '' }}</span>
                        </div>

                        <div class="resv-row">
                            <span class="resv-label">Date</span>
                            <span class="resv-value">
                                @if($dt)
                                    {{ $dt->format('Y-m-d') }}
                                @endif
                            </span>
                        </div>

                        <div class="resv-row">
                            <span class="resv-label">Time</span>
                            <span class="resv-value">
                                @if(isset($r->time) && $r->time !== '')
                                    {{ \Illuminate\Support\Str::of($r->time)->limit(5, '') }}
                                @elseif($dt)
                                    {{ $dt->format('H:i') }}
                                @endif
                            </span>
                        </div>

                        <div class="resv-row">
                            <span class="resv-label">Number</span>
                            <span class="resv-value">
                                {{ $r->num_of_guests ?? $r->people ?? $r->num_of_people ?? $r->party_size ?? 1 }}名
                            </span>
                        </div>

                        @if(isset($r->number))
                            <div class="resv-row">
                                <span class="resv-label">予約番号</span>
                                <span class="resv-value">{{ $r->number }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="resv-actions">
                        @if($canCancel)
                            <a class="resv-btn" href="{{ route('reservations.edit', $r) }}">変更する</a>
                        @endif

                        @if($canRate)
                            <a class="resv-btn" href="{{ route('ratings.create', $r->shop_id) }}">評価する</a>
                        @endif

                        <a class="resv-btn" href="{{ route('reservations.qr', $r) }}">QRコード</a>
                        <a class="resv-btn ghost" href="{{ route('shops.show', $r->shop_id) }}">店舗詳細</a>
                    </div>
                </article>
            @empty
                <p class="resv-empty">予約はありません。</p>
            @endforelse

            @isset($reservations)
                @includeWhen(method_exists($reservations, 'links'), 'components.pagination', ['paginator' => $reservations])
            @endisset
        </section>

        {{-- 右：お気に入り店舗 --}}
        <section class="mypage-right">
            <div class="fav-grid">
                @forelse($favorites as $shop)
                    @php
                        $img = $shop->image_url ?? $shop->image_path ?? null;
                    @endphp
                    <article class="fav-card">
                        <a href="{{ route('shops.show', $shop) }}" class="fav-img-wrap">
                            @if($img)
                                <img src="{{ $img }}" alt="{{ $shop->name }}">
                            @endif
                        </a>

                        <div class="fav-body">
                            <h3 class="fav-name">{{ $shop->name }}</h3>
                            <p class="fav-meta">
                                @if($shop->area) #{{ $shop->area->name }} @endif
                                @if($shop->genre) #{{ $shop->genre->name }} @endif
                            </p>

                            <div class="fav-footer">
                                <a href="{{ route('shops.show', $shop) }}" class="btn-primary">詳しくみる</a>

                                <button
                                    type="button"
                                    class="fav-toggle"
                                    aria-pressed="true"
                                    @if($user)
                                        data-url="{{ route('favorites.toggle', $shop->id) }}"
                                        onclick="event.stopPropagation();(async(e)=>{const t=e.currentTarget;await fetch(t.dataset.url,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).catch(()=>{});location.reload();})(event)"
                                    @else
                                        onclick="event.stopPropagation();alert('お気に入り機能を利用するにはログインしてください');"
                                    @endif
                                >
                                    <svg viewBox="0 0 24 24" width="35" height="35" aria-hidden="true">
                                        <path
                                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4 8.04 4 9.54 4.81 10.35 6.09 11.16 4.81 12.66 4 14.2 4 16.7 4 18.7 6 18.7 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="fav-empty">お気に入り店舗はまだありません。</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
