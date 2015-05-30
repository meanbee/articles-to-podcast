<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model  {

	protected $table = 'users';

    public function items()
    {
        return \DB::table('items')
                ->join('user_items', 'items.id', '=', 'user_items.item_id')
                ->join('users', 'user_items.user_id', '=', 'users.id')
                ->where('users.id', '=', $this->id)
                ->get();
    }

    public function userItems()
    {
        return $this->hasMany('App\UserItems', 'user_id', 'id');
    }

    public function secret()
    {
        return sha1($this->username . $this->id . env('APP_KEY'));
    }
}
