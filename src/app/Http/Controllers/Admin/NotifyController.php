<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminNoticeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotifyController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required','string','max:80'],
            'body' => ['required','string','max:2000'],
            'user_ids' => ['nullable','array'],
            'user_ids.*' => ['integer','exists:users,id'],
        ]);

        $query = User::query();
        if (!empty($validated['user_ids'])) {
            $query->whereIn('id', $validated['user_ids']);
        }
        $users = $query->get();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new AdminNoticeMail($validated['subject'], $validated['body']));
        }

        return back()->with('success', '送信しました');
    }
}
