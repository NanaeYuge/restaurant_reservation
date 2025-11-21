@extends('layouts.app')
@section('title','予約変更')

@push('styles')
    @vite(['resources/css/pages/reservations/edit.css'])
@endpush

@section('content')
    <div class="edit-wrap">
        <h1 class="page-title">予約変更</h1>

        <form class="edit-card" method="post" action="{{ route('reservations.update', $reservation) }}">
            @csrf
            @method('patch')

            <div class="form-row">
                <div class="label">Date</div>
                <div>
                    <input
                        class="input"
                        type="date"
                        name="date"
                        value="{{ old('date', $reservation->reserved_at->format('Y-m-d')) }}"
                        required
                    >
                    @error('date')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="label">Time</div>
                <div>
                    <input
                        class="input"
                        type="time"
                        name="time"
                        value="{{ old('time', $reservation->reserved_at->format('H:i')) }}"
                        required
                    >
                    @error('time')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="label">Number</div>
                <div>
                    <input
                        class="input"
                        type="number"
                        name="number"
                        min="1"
                        max="20"
                        value="{{ old('number', $reservation->num_of_guests) }}"
                        required
                    >
                    @error('number')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <input type="hidden" name="updated_at" value="{{ optional($reservation->updated_at)->toISOString() }}">

            <div class="actions">
                <a class="btn link" href="{{ route('mypage.index') }}">戻る</a>
                <button class="btn primary" type="submit">変更</button>
            </div>
        </form>
    </div>
@endsection
