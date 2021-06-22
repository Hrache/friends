<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Inertia\Inertia;

class FriendsController extends Controller
{
    private $message = "";

    public function index()
    {
        $friends = auth()->user()->friends()->with('user')->get()->makeHidden([
            'profile_photo_url', 'current_team_id', 'created_at', 'updated_at'
        ]);
        $friends = $friends->merge(
            auth()->user()->friendsTo()->with('by_user')->get()->makeHidden([
                'profile_photo_url', 'current_team_id', 'created_at', 'updated_at'
            ])
        );

        return Inertia::render('Friends', [
            "friends" => $friends,
            'pending' => auth()->user()->pending()->with('user')->get(),
            'pendingBy' => auth()->user()->pendingBy()->with('by_user')->get()
        ]);
    }

    /**
     * Confirm friend request
     */
    public function confirm()
    {
        $validity = request()->validate([
            'frid' => 'required|integer'
        ]);

        $friend = Friend::where('id', request()->frid)->with('user')->first();
        $friend->status = Friend::STATUS_APPROVED;

        if ($friend->saveOrFail()) {
            $this->message = [
                'success' => "Congratulations you've successfuly friended {$friend->user->name} {$friend->user->surname}"
            ];
        } else {
            $this->message = [
                'failed' => "Failed to friend with {$friend->user->name} {$friend->user->surname}"
            ];
        }

        return back()->with($this->message);
    }

    /**
     * Reject friend request
     *
     */
    public function reject()
    {
        $validity = request()->validate([
            'frid' => 'required|integer'
        ]);


    }

    /**
     * Does request for a friendship
     *
     */
    public function friendRequest()
    {
        $validity = request()->validate([
            'uac' => 'required|integer'
        ]);
        $newFriend = new Friend();
        $newFriend->user_id = auth()->user()->id;
        $newFriend->friend_id = request()->uac;
        $newFriend->status = Friend::STATUS_PENDING;

        if (!$newFriend->save()) {
            $this->message = ['failed' => 'Failed to do friend request, try again.'];
        }
        else {
            $this->message = ['success' => 'Your request have been sent successfuly.'];
        }

        return response()->json($this->message);
    }

    public function delete($friend)
    {
        if (!Friend::whereId($friend)->first()->delete()) {
            $this->message = ['failed' => 'Could not unfriend'];
        }
        else {
            $this->message = ['success' => 'The friend have been removed successfuly!'];
        }

        return back()->with($this->message);
    }
}
