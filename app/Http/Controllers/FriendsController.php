<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Friend;

class FriendsController extends Controller
{
    public function index()
    {
        $friends = auth()->user()->friends()->with('user')->get();
        $friends = $friends->concat(auth()->user()->friendsTo()->get());

        return Inertia::render('Friends', [
            "friends" => $friends
        ]);
    }

    public function delete($friend)
    {
        if (!Friend::whereId($friend)->first()->delete()) {
            return back()->with([
                'failure' => 'Could not unfriend'
            ]);
        }

        return back()->with([
            'success' => 'The friend have been removed successfuly!'
        ]);
    }
}
