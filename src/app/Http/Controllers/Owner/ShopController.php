<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','can:access-owner']);
    }

    public function editOrCreate(Request $request)
    {
        $owner = $request->user();
        $shop = Shop::where('owner_id', $owner->id)->first();
        $areas = Area::orderBy('name')->get(['id','name']);
        $genres = Genre::orderBy('name')->get(['id','name']);

        return view('owner.shops.edit', [
            'shop' => $shop ?? new Shop(['owner_id' => $owner->id]),
            'isCreate' => $shop === null,
            'areas' => $areas,
            'genres' => $genres,
        ]);
    }

    public function store(Request $request)
    {
        $owner = $request->user();

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'area_id' => ['required','integer','exists:areas,id'],
            'genre_id' => ['required','integer','exists:genres,id'],
            'summary' => ['nullable','string','max:2000'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('shops', 'public');
        }

        $data['owner_id'] = $owner->id;

        Shop::updateOrCreate(['owner_id' => $owner->id], $data);

        return redirect()->route('owner.shop.editOrCreate')->with('success', '店舗を作成しました');
    }

    public function update(Request $request, Shop $shop)
    {
        if ($shop->owner_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'area_id' => ['required','integer','exists:areas,id'],
            'genre_id' => ['required','integer','exists:genres,id'],
            'summary' => ['nullable','string','max:2000'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'remove_image' => ['nullable','boolean'],
        ]);

        if (($data['remove_image'] ?? false) && $shop->image_path) {
            Storage::disk('public')->delete($shop->image_path);
            $data['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if ($shop->image_path) {
                Storage::disk('public')->delete($shop->image_path);
            }
            $data['image_path'] = $request->file('image')->store('shops', 'public');
        }

        unset($data['remove_image']);

        $shop->update($data);

        return redirect()->route('owner.shop.editOrCreate')->with('success', '店舗を更新しました');
    }
}
