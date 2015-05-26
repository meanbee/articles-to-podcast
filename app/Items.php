<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Items extends Model {

	const STATUS_NEW               = 'new';
	const STATUS_BEING_FETCHED     = 'being_fetched';
	const STATUS_FETCHED           = 'fetched';
	const STATUS_BEING_CONVERTED   = 'being_converted';
	const STATUS_CONVERTED         = 'converted';
    const STATUS_CONVERSION_FAILED = 'conversion_failed';
	const STATUS_BEING_UPLOADED    = 'being_uploaded';
	const STATUS_UPLOADED          = 'uploaded';

    public $incrementing = false;
    public $timestamps = false;

    public static $validation = [
        'url' => 'required|url|unique:items|unique:user_items'
    ];
	//

}
