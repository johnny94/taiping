<?php namespace App;

use DB;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use \Auth;

class ClassSwitching extends Model {

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

	/*public function __construct()
	{
		$this->user_id = Auth::user()->id;		
		parent::__construct();
	}*/

	public function getFromAttribute($from)
	{
		return Carbon::parse($from)->format('Y-m-d');
	}

	public function getToAttribute($to)
	{
		return Carbon::parse($to)->format('Y-m-d');
	}

	public function leave()
	{
		return $this->belongsTo('App\Leave');
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
		return $this->belongsTo('App\ClassTitle', 'from_class_id');
	}

	public function fromPeriod() {
		return $this->belongsTo('App\Period', 'from_period');
	}

	public function toClass() {
		return $this->belongsTo('App\ClassTitle', 'to_class_id');
	}

	public function toPeriod() {
		return $this->belongsTo('App\Period', 'to_period');
	}

	public function isPass()
	{
		return $this->attributes['checked_status_id'] === DB::table('checked_status')->where('title', 'pass')->first()->id;
	}

	protected static function boot() {
        parent::boot();

        static::deleting(function($classSwitching) {
             $leave = $classSwitching->leave;
             if ($leave->classSwitchings->count() === 1) {
             	$leave->delete();
             }             
        });
    }
}
