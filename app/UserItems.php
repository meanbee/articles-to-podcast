<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserItems extends Model {

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function item()
    {
        return $this->belongsTo('App\Items')->first();
    }

    public function createdAt()
    {
        return date('d/m/Y', strtotime($this->created_at));
    }

}
