<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model  {

	protected $table = 'users';

    public function items()
    {
        return $this->hasManyThrough('App\Items', 'App\UserItems', 'item_id', 'id');
    }

    public function secret()
    {
        return sha1($this->username . $this->id . env('APP_KEY'));
    }
}
