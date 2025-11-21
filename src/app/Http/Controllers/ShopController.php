<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'area'    => ['nullable', 'integer', 'exists:areas,id'],
            'genre'   => ['nullable', 'integer', 'exists:genres,id'],
            'keyword' => ['nullable', 'string', 'max:50'],
            'page'    => ['nullable', 'integer', 'min:1'],
        ]);

        $areas  = Area::orderBy('name')->get(['id','name']);
        $genres = Genre::orderBy('name')->get(['id','name']);

        $query = Shop::query()
            ->with(['area:id,name','genre:id,name'])
            ->withAvg('ratings', 'score')
            ->withCount('ratings');

        if (!empty($validated['area'])) {
            $query->where('area_id', (int)$validated['area']);
        }
        if (!empty($validated['genre'])) {
            $query->where('genre_id', (int)$validated['genre']);
        }
        if (!empty($validated['keyword'])) {
            $kw = trim($validated['keyword']);
            $kw = str_replace(['%','_'], ['\%','\_'], $kw);
            $query->where(function ($q) use ($kw) {
                $q->where('name','like',"%{$kw}%")
                    ->orWhere('summary','like',"%{$kw}%");
            });
        }

        $shops = $query->orderByDesc('id')->paginate(12)->withQueryString();

        $selected = [
            'area'    => $validated['area']    ?? null,
            'genre'   => $validated['genre']   ?? null,
            'keyword' => $validated['keyword'] ?? null,
        ];

        return view('shops.index', compact('areas','genres','shops','selected'));
    }

    public function show(Shop $shop)
    {
        $shop->load(['area:id,name','genre:id,name'])
                ->loadAvg('ratings', 'score')
                ->loadCount('ratings');

        return view('shops.show', compact('shop'));
    }
}
