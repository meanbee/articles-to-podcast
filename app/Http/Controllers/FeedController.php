<?php namespace App\Http\Controllers;

use App\Items;
use App\User;

class FeedController extends BaseController {

    public function podcast($id, $secret)
    {
        /** @var \App\User $user */
        $user = User::find($id);
        $items = $user->items();
        $calculated_secret = $user->secret();

        if ($secret !== $calculated_secret) {
            throw new \Exception("Secret mismatch");
        }

        return view('podcast', array(
            'user'  => $user,
            'items' => $items
        ));
    }

}
