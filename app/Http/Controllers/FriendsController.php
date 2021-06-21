<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Friend;

class FriendsController extends Controller
{
    public function index()
    {
        $friends = auth()->user()->friends()->with('user')->get()->makeHidden([
            'profile_photo_url', 'current_team_id', 'created_at', 'updated_at'
        ]);
        $friends = $friends->concat(
            auth()->user()->friendsTo()->get()->makeHidden([
                'profile_photo_url', 'current_team_id', 'created_at', 'updated_at'
            ])
        );

        return Inertia::render('Friends', [
            "friends" => $friends
        ]);
    }

    public function store()
    {
        if (!request()->uac) {
            $validity = $this->validate(request()->all(), [
                'uac' => ['required', 'integer']
            ]);

            dd($validity);

            return response()->json([
                'error' => 'no'
            ]);
        }

        $id = request()->uac;


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

    public function checkForFriendship()
    {
        
    }
}
