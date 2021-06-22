<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Inertia\Inertia;

class SearchFindController extends Controller
{
    public function searchFriend()
    {

    }

    public function searchPeople()
    {
        $toSearch = request()->toSearch;
        $people = User::where('name', 'like', "%".$toSearch."%")
                    ->orWhere('surname', 'like', "%".$toSearch."%");
        $people = $people->get([
            'id', 'name', 'surname'
        ])->makeHidden('profile_photo_url');

        if ($people->count() && isset(auth()->user()->id)) {
            $people = $people->filter(function ($value, $key) {
                return $value->id != auth()->user()->id;
            });

            $userids = [];

            $friends = auth()->user()->friends()->get()->makeHidden([
                'profile_photo_url', 'current_team_id', 'created_at', 'updated_at'
            ]);
            $friendsBy = auth()->user()->friendsTo()->get()->makeHidden([
                'profile_photo_url', 'current_team_id', 'created_at', 'updated_at'
            ]);
            $pending = auth()->user()->pending()->get()->makeHidden([
                'profile_photo_url', 'current_team_id', 'created_at', 'updated_at'
            ]);
            $requested = auth()->user()->requested()->get()->makeHidden([
                'profile_photo_url', 'current_team_id', 'created_at', 'updated_at'
            ]);

            $friends = $friends->merge($friendsBy);
            $friends = $friends->merge($pending);
            $friends = $friends->merge($requested);

            foreach ($friends as $friend) {
                $userids[] = $friend->user_id !== auth()->user()->id? $friend->user_id: $friend->friend_id;
            }

            unset($friends);

            $people = $people->filter(function ($value, $key) use ($userids) {
                return !in_array($value->id, $userids);
            });
        }

        $people = $people->values()? $people->values(): $people;

        return response()->json([
            "people" => $people,
            'searchQuery' => request()->toSearch
        ]);
    }
}
