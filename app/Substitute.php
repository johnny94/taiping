<?php namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Substitute extends Model {

	public function leave()
	{
		return $this->belongsTo('App\Leave');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function getFromAttribute($from)
	{
		return Carbon::parse($from)->format('Y-m-d');
	}

	public function getToAttribute($to)
	{
		return Carbon::parse($to)->format('Y-m-d');
	}
	
	public function periods()
	{
		return $this->belongsToMany('App\Period');
	}

	public function getDurationTypeStringAttribute() {
		$type = $this->attributes['duration_type'];

		//TODO: Refactor to use Enumeration
		if($type === 1){
			return '半天';

		}elseif($type === 2){
			return '全天';

		}elseif($type === 3){
			return '多節';
		}

		return $type;
		
	}

	public function getAmPmAttribute() {
		$am_pm = $this->attributes['am_pm'];

		if(!isset($am_pm)) return 'not half day';
		
		//TODO: Refactor to use Enumeration
		if($am_pm === 'am'){
			return '上午';

		}elseif($am_pm === 'pm'){
			return '下午';
		}
		
	}

}
