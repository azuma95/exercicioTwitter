<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Notifications\NewFollower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index($user)
    {
        $user = User::findOrFail($user);

        return view('profile', [
            'user' => $user
        ]);
    }

    public function followOrUnfollowUser(Request $request)
    {
        if($request->follow)
        {
            $user = User::findOrFail($request->user);
            Auth::user()->following()->attach($user->id);
            $user->notify(new NewFollower(Auth::user()));
        }
        else
        {
            $user = User::findOrFail($request->user);
            Auth::user()->following()->detach($user->id);
        }

        return redirect('/u/' . $user->id);
    }
}
