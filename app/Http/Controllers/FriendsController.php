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
            'pending' => auth()->user()->pending()->with('by_user')->get(),
            'requested' => auth()->user()->requested()->with('user')->get()
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
                'success' => "Congratulations you've successfully friended {$friend->user->name} {$friend->user->surname}"
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

        $friend = Friend::where('id', request()->frid)->with('user')->first();
        $friend->status = Friend::STATUS_REJECTED;

        if ($friend->saveOrFail()) {
            $this->message = [
                'success' => "Friend request have been rejected successfuly {$friend->user->name} {$friend->user->surname}"
            ];
        } else {
            $this->message = [
                'failed' => "Failed to friend with {$friend->user->name} {$friend->user->surname}"
            ];
        }

        return back()->with($this->message);
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
            $this->message = ['success' => 'Your request have been sent successfully.'];
        }

        return response()->json($this->message);
    }

    /**
     * Cancels friend request of own
     *
     * @param int $cancel The id of the request in the Friend model
     */
    public function cancel($cancel)
    {
        $friend = Friend::whereId($cancel)->first();

        if (!$friend->delete()) {
            $this->message = ['failed' => 'Failed to cancel the request'];
        }
        else {
            $this->message = ['success' => 'Canceled the request successfully'];
        }

        return back()->with($this->message);
    }

    /**
     * Deletes friend from the friends list
     *
     * @param int $friend The id of the friend in the Friend model
     */
    public function delete($friend)
    {
        if (!Friend::whereId($friend)->first()->delete()) {
            $this->message = ['failed' => 'Could not unfriend'];
        }
        else {
            $this->message = ['success' => 'The friend have been removed successfully!'];
        }

        return back()->with($this->message);
    }
}
