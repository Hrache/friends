<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class SearchFindController extends Controller
{
    public function searchFriend()
    {

    }

    public function searchPeople()
    {
        if (!request()->has('toSearch')) {
            return response()->json('Please provide data to search');
        }

        $toSearch = request()->toSearch;

        $people = User::where('name', 'like', "%{$toSearch}%")
                    ->orWhere('surname', 'like', "%{$toSearch}%")->get([
                        'id', 'name', 'surname'
                    ])->makeHidden('profile_photo_url');

        return response()->json([
            "people" => $people,
            'searchQuery' => request()->toSearch
        ]);
    }
}
