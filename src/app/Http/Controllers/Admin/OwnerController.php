<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    public function edit(Shop $shop)
    {
        $owners = User::query()
            ->select('id','name','email')
            ->whereHas('roles', fn($q) => $q->where('name','owner'))
            ->orderBy('name')
            ->get();

        return view('admin.shops.owner', [
            'shop' => $shop->only(['id','name','owner_id']),
            'owners' => $owners,
        ]);
    }

    public function update(Request $request, Shop $shop)
    {
        $data = $request->validate([
            'owner_id' => ['required','integer','exists:users,id'],
        ]);

        $shop->owner_id = (int)$data['owner_id'];
        $shop->save();

        return redirect()->route('admin.dashboard')->with('success','店舗代表者を更新しました。');
    }

    public function create()
    {
        return view('admin.owners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'phone'    => ['nullable','string','max:50'],
            'password' => ['required','string','min:8'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'owner',
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', '店舗代表者を作成しました。');
    }
}
