<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ClassTitle extends Model {

	protected $fillable = ['title'];
	protected $table = 'classtitles';

    use SoftDeletes;
    protected $dates = ['deleted_at'];

}
