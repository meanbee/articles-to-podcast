<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model  {

	protected $table = 'users';

    public function items()
    {
        return $this->hasMany('App\UserItems');
    }

    public function secret()
    {
        return sha1($this->username . $this->id . env('APP_KEY'));
    }
}
