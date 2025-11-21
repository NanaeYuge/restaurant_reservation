<?php
namespace App\Http\Controllers;


use App\Models\Shop;
use Illuminate\Support\Facades\Auth;


class FavoriteController extends Controller
{
public function store(Shop $shop)
{
$user = Auth::user();
$user->favorites()->syncWithoutDetaching([$shop->id]);
return back()->with('success', 'お気に入りに追加しました');
}


public function destroy(Shop $shop)
{
$user = Auth::user();
$user->favorites()->detach($shop->id);
return back()->with('success', 'お気に入りを解除しました');
}
}