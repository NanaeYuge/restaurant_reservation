@if (session('success'))
  <div class="alert-success">{{ session('success') }}</div>
@endif
@if (session('warning'))
  <div class="alert-warning">{{ session('warning') }}</div>
@endif
@if (session('error'))
  <div class="alert-error">{{ session('error') }}</div>
@endif

<form method="post" action="{{ route('payments.checkout') }}" class="mt-16">
  @csrf
  <input type="hidden" name="reservation_id" value="{{ $reservation->id ?? '' }}">
  <input type="hidden" name="shop_name" value="{{ $shop->name ?? 'Rese' }}">
  <input type="hidden" name="amount" value="{{ $amount ?? 3000 }}"> {{-- JPYなら3000=¥3000 --}}
  <button type="submit" class="btn primary wide">お支払いへ（Stripe）</button>
</form>
