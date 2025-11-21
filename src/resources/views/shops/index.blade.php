@extends('layouts.app')
@section('title','店舗一覧')

@section('head')
@endsection

@section('content')
<h1 class="page-title">店舗一覧</h1>

@if($shops->isEmpty())
    <p style="margin:0 5% 24px;color:#666;">該当する店舗がありません。</p>
@endif

<div class="shop-grid">
    @foreach($shops as $shop)
    @php
        $user = auth()->user();

        $avg = (float)($shop->ratings_avg_score ?? 0);
        $cnt = (int)($shop->ratings_count ?? 0);

        $liked = false;
        if($user){
        if (\Illuminate\Support\Facades\Schema::hasTable('favorites')) {
            $liked = \Illuminate\Support\Facades\DB::table('favorites')
            ->where('user_id', $user->id)
            ->where('shop_id', $shop->id)
            ->exists();
        } else {
            $liked = (bool)($shop->is_favorite ?? false);
        }
        }
    @endphp

    <article class="shop-card">
        <a href="{{ route('shops.show',$shop) }}" class="thumb">
        <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
        </a>

        <div class="shop-card-body">
        <div class="shop-card-head">
            <h2 class="shop-card-title">
            <a href="{{ route('shops.show',$shop) }}"
                class="link"
                title="{{ $shop->name }}">
                {{ str_replace('_',' ',$shop->name) }}
            </a>
            </h2>
            <div class="shop-card-rating">
            @if($cnt>0)
                @include('components.stars',['score'=>$avg,'count'=>null])
            @else
                <span style="font-size:.9em;color:#666">評価なし</span>
            @endif
            </div>
        </div>

        <div class="tags">
            #{{ $shop->area->name ?? '未設定' }}
            #{{ $shop->genre->name ?? '未設定' }}
        </div>

        <div class="shop-card-actions">
            <a class="btn" href="{{ route('shops.show',$shop) }}">詳しくみる</a>

            <button
            type="button"
            class="fav-toggle"
            aria-pressed="{{ $liked ? 'true':'false' }}"
            @if($user)
                data-url="{{ route('favorites.toggle',$shop->id) }}"
                onclick="event.stopPropagation();(async(e)=>{const t=e.currentTarget;await fetch(t.dataset.url,{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}}).catch(()=>{});location.reload();})(event)"
            @else
                onclick="event.stopPropagation();alert('お気に入り機能を利用するにはログインしてください');"
            @endif
            >
            <svg viewBox="0 0 24 24" width="35" height="35" aria-hidden="true">
                <path
                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4 8.04 4 9.54 4.81 10.35 6.09 11.16 4.81 12.66 4 14.2 4 16.7 4 18.7 6 18.7 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>

            </button>
        </div>
        </div>
    </article>
    @endforeach
</div>

@include('components.pagination',['paginator'=>$shops])
@endsection
