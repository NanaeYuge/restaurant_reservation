<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Rating;
use App\Http\Requests\StoreRatingRequest;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified'])->only(['create','store']);
    }

    public function index(Shop $shop)
    {
        $count = (int) Rating::where('shop_id', $shop->id)->count();
        $avg = $count ? (float) Rating::where('shop_id', $shop->id)->avg('score') : 0.0;
        $ratings = Rating::with('user:id,name')
            ->where('shop_id', $shop->id)
            ->latest()
            ->paginate(10);

        return view('ratings.index', compact('shop', 'ratings', 'avg', 'count'));
    }

    public function create(Shop $shop)
    {
        return view('ratings.create', compact('shop'));
    }

    public function store(StoreRatingRequest $request, Shop $shop)
    {
        $exists = Rating::where('user_id', $request->user()->id)
            ->where('shop_id', $shop->id)
            ->exists();

        if ($exists) {
            return redirect()->route('shops.show', $shop)->with('error', 'この店舗は既に評価済みです。');
        }

        Rating::create([
            'user_id' => $request->user()->id,
            'shop_id' => $shop->id,
            'score' => (int) $request->input('score'),
            'comment' => $request->input('comment'),
        ]);

        return redirect()->route('shops.show', $shop)->with('success', 'レビューを投稿しました。');
    }
}
