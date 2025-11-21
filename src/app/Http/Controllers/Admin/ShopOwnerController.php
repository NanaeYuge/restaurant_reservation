<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

class ShopOwnerController extends Controller
{
    public function edit(Shop $shop)
    {
        $owners = User::where('role', 'owner')->orderBy('name')->get(['id','name','email']);
        return view('admin.shops.assign_owner', compact('shop','owners'));
    }

    public function update(Request $request, Shop $shop)
    {
        $data = $request->validate([
            'owner_id' => ['required','integer','exists:users,id'],
        ]);

        $owner = User::where('id', $data['owner_id'])->where('role', 'owner')->first();
        if (!$owner) {
            return back()->withErrors(['owner_id' => '選択したユーザーは店舗代表者ではありません。'])->withInput();
        }

        $exists = Shop::where('owner_id', $owner->id)->where('id', '<>', $shop->id)->exists();
        if ($exists) {
            return back()->withErrors(['owner_id' => 'この代表者は既に別の店舗に紐付いています。'])->withInput();
        }

        $shop->owner_id = $owner->id;
        $shop->save();

        return redirect()->route('admin.shops.owner.edit', $shop)->with('success', '店舗代表者を更新しました。');
    }
}
