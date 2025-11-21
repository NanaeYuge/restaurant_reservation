<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Stripe\Webhook;

class PaymentsController extends Controller
{
    public function checkout(Request $request)
{
    $reservationId = $request->input('reservation_id');
    $amount        = (int) $request->input('amount');
    $shopName      = $request->input('shop_name', 'Reservation');

    $stripe = new StripeClient(config('services.stripe.secret'));

    $session = $stripe->checkout->sessions->create([
        'mode' => 'payment',
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency'    => 'jpy',
                'unit_amount' => $amount,
                'product_data'=> ['name' => $shopName],
            ],
            'quantity' => 1,
        ]],
        'success_url' => route('thanks'),
        'cancel_url'  => route('mypage'),
    ]);

    return redirect($session->url);
}


    public function success(Request $request)
    {
        abort(404);
    }

    public function cancel(Request $request)
    {
        abort(404);
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        if ($secret) {
            try {
                $event = Webhook::constructEvent($payload, $sig, $secret);
            } catch (\Throwable $e) {
                return response('invalid', 400);
            }
        } else {
            $event = json_decode($payload);
        }

        if (!$event) return response('ok', 200);

        if ($event->type === 'checkout.session.completed') {
            $obj = $event->data->object;
            $sid = $obj->id ?? null;
            $pi  = $obj->payment_intent ?? null;

            if ($sid) {
                $r = Reservation::where('stripe_session_id', $sid)->lockForUpdate()->first();
                if ($r && !$r->paid_at) {
                    $r->status = 'paid';
                    $r->paid_at = now();
                    $r->stripe_payment_intent_id = $pi;
                    $r->save();
                }
            }
        }

        return response('ok', 200);
    }
}
