<?php namespace App\Http\Controllers;

use App\Items;
use App\User;

class FeedController extends BaseController {

    /**
     * @return Response
     */
    public function podcast($id, $secret)
    {
        // @TODO Limit by user.
        $items = Items::all();

        $user = User::find($id);

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
