<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
	protected $fillable = ['name'];
    protected $hidden = ['updated_at', 'created_at'];
}
