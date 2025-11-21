@extends('layouts.app')
@section('title', $shop->name)

@section('content')
<div class="detail-page">
  @php
    $from = request('from');
    $backUrl = $from === 'mypage'
        ? route('mypage.index')
        : ($from === 'list'
            ? route('shops.index')
            : url()->previous());

    $avg = (float) \App\Models\Rating::where('shop_id', $shop->id)->avg('score');
    $cnt = (int)  \App\Models\Rating::where('shop_id', $shop->id)->count();

    $canRate = false;
    if (auth()->check() && \Illuminate\Support\Facades\Schema::hasTable('reservations')) {
        $candidate = ['reserved_at', 'reservation_date', 'date', 'visit_date', 'reserved_on'];
        $dateCol = null;
        foreach ($candidate as $c) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('reservations', $c)) {
                $dateCol = $c;
                break;
            }
        }
        if ($dateCol) {
            $yesterday = \Illuminate\Support\Carbon::today()->subDay()->toDateString();
            $row = \Illuminate\Support\Facades\DB::table('reservations')
                ->select('id', $dateCol.' as d')
                ->where('user_id', auth()->id())
                ->where('shop_id', $shop->id)
                ->whereDate($dateCol, '<=', $yesterday)
                ->orderBy($dateCol, 'desc')
                ->first();

            if ($row) {
                $visited = \Illuminate\Support\Carbon::parse($row->d)->toDateString();
                $exists = \App\Models\Rating::where('user_id', auth()->id())
                    ->where('shop_id', $shop->id)
                    ->whereDate('visited_at', $visited)
                    ->exists();
                $canRate = ! $exists;
            }
        }
    }
  @endphp

  <div class="detail-backline">
    <a class="detail-backbtn" href="{{ $backUrl }}" aria-label="前のページへ戻る">&lt;</a>
    <h1 class="detail-shopname">{{ $shop->name }}</h1>
    <div class="detail-rating">
      @if($cnt > 0)
        @include('components.stars', ['score' => $avg, 'count' => null])
        <a class="detail-link" href="{{ route('ratings.index', $shop->id) }}">もっと見る</a>
      @else
        <span class="detail-no-rating">評価なし</span>
      @endif
    </div>
  </div>

  <div class="detail-layout">
    <section class="detail-main">
      <div class="detail-hero">
        <img src="{{ $shop->image_url }}" alt="{{ $shop->name }}">
      </div>

      <div class="detail-tags">
        #{{ $shop->area->name }} #{{ $shop->genre->name }}
      </div>
      <div class="detail-overview">
        {{ $shop->summary ?? $shop->overview }}
      </div>
    </section>

    <aside class="detail-panel">
      <h2 class="detail-panel-title">予約</h2>

      <form
        method="post"
        action="{{ route('reservations.store', $shop->id) }}"
        class="detail-form"
        id="reservation-form"
      >
        @csrf

        <input
          type="date"
          name="date"
          class="detail-input detail-input-date"
          value="{{ old('date') }}"
          required
        >
        @error('date')
          <div class="detail-err">{{ $message }}</div>
        @enderror

        @php $oldTime = old('time'); @endphp
        <select name="time" class="detail-input detail-select" required>
          <option value="" disabled {{ $oldTime ? '' : 'selected' }}>--:--</option>
          @for ($h = 11; $h <= 23; $h++)
            @php $t = sprintf('%02d:00', $h); @endphp
            <option value="{{ $t }}" {{ $oldTime === $t ? 'selected' : '' }}>
              {{ $t }}
            </option>
          @endfor
        </select>
        @error('time')
          <div class="detail-err">{{ $message }}</div>
        @enderror

        @php $oldNum = (int) old('num_of_guests', 1); @endphp
        <select name="num_of_guests" class="detail-input detail-select" required>
          @for ($i = 1; $i <= 10; $i++)
            <option value="{{ $i }}" {{ $oldNum === $i ? 'selected' : '' }}>
              {{ $i }}人
            </option>
          @endfor
        </select>
        @error('num_of_guests')
          <div class="detail-err">{{ $message }}</div>
        @enderror
      </form>

      <div class="detail-summary">
        <div class="detail-summary-row">
          <span class="detail-summary-label">Shop</span>
          <span class="detail-summary-value" id="summary-shop">{{ $shop->name }}</span>
        </div>
        <div class="detail-summary-row">
          <span class="detail-summary-label">Date</span>
          <span class="detail-summary-value" id="summary-date">-</span>
        </div>
        <div class="detail-summary-row">
          <span class="detail-summary-label">Time</span>
          <span class="detail-summary-value" id="summary-time">-</span>
        </div>
        <div class="detail-summary-row">
          <span class="detail-summary-label">Number</span>
          <span class="detail-summary-value" id="summary-number">-</span>
        </div>
      </div>

      @auth
        @if($canRate)
          <a class="detail-btn wide" href="{{ route('ratings.create', $shop->id) }}">評価する</a>
        @endif
      @endauth

      <button
        class="detail-btn primary wide detail-btn-reserve"
        type="submit"
        form="reservation-form"
      >
        予約する
      </button>
    </aside>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var dateInput = document.querySelector('.detail-input[name="date"]');
    var timeInput = document.querySelector('.detail-input[name="time"]');
    var numInput  = document.querySelector('.detail-input[name="num_of_guests"]');

    var outDate = document.getElementById('summary-date');
    var outTime = document.getElementById('summary-time');
    var outNum  = document.getElementById('summary-number');

    function formatDate(value) {
      return value || '-';
    }

    function formatTime(value) {
      return value || '-';
    }

    function formatNum(value) {
      return value ? value + '人' : '-';
    }

    function sync() {
      if (dateInput) outDate.textContent = formatDate(dateInput.value);
      if (timeInput) outTime.textContent = formatTime(timeInput.value);
      if (numInput)  outNum.textContent  = formatNum(numInput.value);
    }

    if (dateInput) dateInput.addEventListener('change', sync);
    if (timeInput) timeInput.addEventListener('change', sync);
    if (numInput)  numInput.addEventListener('input', sync);

    sync();
  });
</script>
@endsection
