<?php namespace App;

use DB;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use \Auth;

class ClassSwitching extends Model {

	const CHECKING_STATUS_PENDING = 1;
	const CHECKING_STATUS_PASS = 2;
	const CHECKING_STATUS_REJECT = 3;

	use SoftDeletes;
    protected $dates = ['deleted_at'];

	protected $fillable = [
		'user_id',
		'with_user_id',
		'from',
		'from_period',
		'from_class_id',
		'to',
		'to_period',
		'to_class_id',
		'checked_status_id',
	];

	protected $table = 'class_switchings';

	public function getFromAttribute($from)
	{
		return Carbon::parse($from)->format('Y-m-d');
	}

	public function getToAttribute($to)
	{
		return Carbon::parse($to)->format('Y-m-d');
	}

	public function switchingTeacher()
	{
		return $this->belongsTo('App\User', 'user_id');
	}

	public function withSwitchingTeacher()
	{
		return $this->belongsTo('App\User', 'with_user_id');
	}

	public function fromClass() {
		return $this->belongsTo('App\ClassTitle', 'from_class_id')->withTrashed();
	}

	public function fromPeriod() {
		return $this->belongsTo('App\Period', 'from_period')->withTrashed();
	}

	public function toClass() {
		return $this->belongsTo('App\ClassTitle', 'to_class_id')->withTrashed();
	}

	public function toPeriod() {
		return $this->belongsTo('App\Period', 'to_period')->withTrashed();
	}

	public function isPass()
	{
		return $this->attributes['checked_status_id'] === DB::table('checked_status')->where('title', 'pass')->first()->id;
	}
}
