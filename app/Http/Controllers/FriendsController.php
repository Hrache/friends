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
        return Inertia::render('Friends', [
            "friends" => auth()->user()->allFriends(),
            'pending' => auth()->user()->pending()->with('by_user')->get(),
            'requested' => auth()->user()->requested()->with('user')->get()
        ]);
    }

    /**
     * Requests for a friendship
     */
    public function request()
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
     * Cancels friend request
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

    /**
     * Search for a friend
     */
    public function search()
    {
        $toSearch = request()->toSearch;
        $people = User::where('name', 'like', "%".$toSearch."%")
                    ->orWhere('surname', 'like', "%".$toSearch."%");
        $people = $people->get([
            'id', 'name', 'surname'
        ])->makeHidden('profile_photo_url');

        if ($people->count()) {
            $people = $people->filter(function ($value, $key) {
                return $value->id != auth()->user()->id;
            });

            $userids = [];

            $friends = auth()->user()->allFriends();

            foreach ($friends as $friend) {
                $userids[] = $friend->user_id !== auth()->user()->id? $friend->user_id: $friend->friend_id;
            }

            unset($friends);

            $people = $people->filter(function ($value, $key) use ($userids) {
                return in_array($value->id, $userids);
            });
        }

        $people = $people->values()? $people->values(): $people;

        return response()->json([
            "people" => $people,
            'searchQuery' => request()->toSearch
        ]);
    }
}
