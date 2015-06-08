<?php namespace App\Http\Controllers;

use App\Items;
use App\User;

class FeedController extends BaseController {

    public function podcast($id, $secret)
    {
        /** @var \App\User $user */
        $user = User::find($id);

        if ($secret !== $user->secret()) {
            throw new \Exception("Secret mismatch");
        }

        return response()->view('podcast', array(
            'user'  => $user,
            'userItems' => $user->userItems->sortByDesc('updated_at'),
        ))->header('Content-Type', 'application/xml');
    }

}
