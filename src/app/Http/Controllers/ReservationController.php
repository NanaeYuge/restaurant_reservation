<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Stripe\StripeClient;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = Reservation::with(['shop'])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('reservations.index', compact('reservations'));
    }

    public function edit(Reservation $reservation)
    {
        $this->authorize('update', $reservation);
        return view('reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
{
    $this->authorize('update', $reservation);

    $request->merge([
        'num_of_guests' => $request->input('num_of_guests', $request->input('number')),
    ]);

    $validated = $request->validate([
        'date'          => ['required','date'],
        'time'          => ['required'],
        'num_of_guests' => ['required','integer','min:1'],
    ]);

    $reservation->reserved_at    = $validated['date'].' '.$validated['time'].':00';
    $reservation->num_of_guests  = (int) $validated['num_of_guests'];
    $reservation->save();

    return redirect()
        ->route('mypage.index')
        ->with('success','予約を更新しました。');
}


    public function destroy(Reservation $reservation)
{
    if ($reservation->user_id !== auth()->id()) {
        abort(403);
    }

    $dateCol = $reservation->date
        ?? $reservation->reservation_date
        ?? $reservation->reserved_at
        ?? $reservation->visit_date
        ?? null;

    if (! $dateCol) {
        return back()->with('warning', 'この予約はキャンセルできません。');
    }

    $dt = Carbon::parse($dateCol);

    if ($dt->lt(Carbon::today())) {
        return back()->with('warning', 'この予約はキャンセルできません。');
    }

    $reservation->delete();

    return back()->with('success', '予約をキャンセルしました。');
    }

    public function store(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'date' => ['required','date'],
            'time' => ['required'],
            'num_of_guests' => ['required','integer','min:1'],
        ]);

        $amount = $this->calcAmount($shop, (int)$validated['num_of_guests']);

        return DB::transaction(function () use ($request, $shop, $validated, $amount) {
            $reservation = new Reservation();
            $reservation->user_id = $request->user()->id;
            $reservation->shop_id = $shop->id;
            $reservation->reserved_at = $validated['date'].' '.$validated['time'].':00';
            $reservation->num_of_guests = (int)$validated['num_of_guests'];
            $reservation->amount = $amount;
            $reservation->status = 'pending_payment';
            $reservation->save();

            $stripe = new StripeClient(config('services.stripe.secret'));

            $success = URL::to(route('payment.success', [], false)).'?sid={CHECKOUT_SESSION_ID}';
            $cancel  = URL::to(route('payment.cancel', [], false));

            $session = $stripe->checkout->sessions->create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'unit_amount' => $amount,
                        'product_data' => ['name' => $shop->name.' 予約'],
                    ],
                    'quantity' => 1,
                ]],
                'success_url' => $success,
                'cancel_url' => $cancel,
                'client_reference_id' => (string)$reservation->id,
                'metadata' => [
                    'reservation_id' => (string)$reservation->id,
                    'shop_id' => (string)$shop->id,
                    'user_id' => (string)$request->user()->id,
                ],
            ]);

            $reservation->stripe_session_id = $session->id;
            $reservation->save();

            return redirect($session->url);
        });
    }

    public function success(Request $request)
    {
    $sid = $request->query('sid');


    if (!$sid) {
        return redirect()->route('mypage.index');
    }

    $reservation = Reservation::where('stripe_session_id', $sid)->first();

    if (!$reservation) {
        return redirect()
            ->route('mypage.index')
            ->with('warning', '予約情報が見つかりませんでした。');
    }


    if ($reservation->status === 'pending_payment') {
        $reservation->status = 'paid';
        $reservation->save();
    }

    return redirect()
        ->route('reservations.done')
        ->with('reservation', $reservation);
    }


    public function cancel()
    {
        return view('payment.cancel');
    }

    public function qr(Reservation $reservation)
{
    if ($reservation->user_id !== auth()->id()) {
        abort(403);
    }

    $qrUrl = \Illuminate\Support\Facades\URL::signedRoute(
        'reservations.checkin',
        ['reservation' => $reservation->id]
    );

    return view('reservations.qr', [
        'reservation' => $reservation,
        'qrText'      => $qrUrl,
    ]);
}



    private function calcAmount(Shop $shop, int $guests): int
    {
        if (isset($shop->price_per_guest) && $shop->price_per_guest) {
            return (int)$shop->price_per_guest * $guests;
        }
        return 1000 * $guests;
    }

    public function retry(Request $request, Reservation $reservation)
{
    $this->authorize('update', $reservation);

    $amount  = (int)($reservation->amount ?? 0);
    $shop    = $reservation->shop;

    $stripe = new StripeClient(config('services.stripe.secret'));
    $success = route('payment.success', [], true).'?sid={CHECKOUT_SESSION_ID}';
    $cancel  = route('payment.cancel',  [], true);

    $session = $stripe->checkout->sessions->create([
        'mode' => 'payment',
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency'    => 'jpy',
                'unit_amount' => $amount,
                'product_data'=> ['name' => $shop->name.' 予約'],
            ],
            'quantity' => 1,
        ]],
        'success_url' => $success,
        'cancel_url'  => $cancel,
        'client_reference_id' => (string)$reservation->id,
        'metadata' => [
            'reservation_id' => (string)$reservation->id,
            'shop_id'        => (string)$shop->id,
            'user_id'        => (string)$request->user()->id,
        ],
    ]);

    $reservation->stripe_session_id = $session->id;
    $reservation->status = 'pending_payment';
    $reservation->save();

    return redirect($session->url);
}
}
