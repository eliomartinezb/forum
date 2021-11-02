<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $reply->favorite();

       if (request()->expectsJson()) {
        return response()->json(['status' => 'Favorite Reply!']);
       }

        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        if (request()->expectsJson()) {
            return response()->json(['status' => 'Unfavorite Reply!']);
        }

        return back();
    }
}
