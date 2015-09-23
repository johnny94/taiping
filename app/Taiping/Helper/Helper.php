<?php namespace App\Taiping\Helper;

use DB;

use Carbon\Carbon;

class Helper
{
	public static function buildClassSwitchingQuery($searchPhrase, $filterByDate, $filterFrom, $filterTo) {
		$query = DB::table('class_switchings')
				    ->join('users as from_user', 'class_switchings.user_id', '=', 'from_user.id')
					->join('periods as from_period', 'class_switchings.from_period', '=', 'from_period.id')
					->join('classtitles as from_class', 'class_switchings.from_class_id', '=', 'from_class.id')
					->join('users as with_user', 'class_switchings.with_user_id', '=', 'with_user.id')
					->join('periods as to_period', 'class_switchings.to_period', '=', 'to_period.id')
					->join('classtitles as to_class', 'class_switchings.to_class_id', '=', 'to_class.id')
					->join('checked_status', 'class_switchings.checked_status_id', '=', 'checked_status.id')
					->whereNull('class_switchings.deleted_at')
					->where(
						function ($q) use ($searchPhrase) {
							$q->where('from_user.name', 'LIKE', "%{$searchPhrase}%")
							  ->orWhere('with_user.name', 'LIKE', "%{$searchPhrase}%");
					});
		
		if ($filterByDate === 'true') {
			$date = self::createDatePeriod($filterFrom, $filterTo);

			$query = $query->where(
						function ($query) use($date){

							$query->where(
								function ($query) use ($date) {
									$query->where('class_switchings.from', '>=', $date['start'])
										  ->where('class_switchings.from', '<=', $date['end']);
								})
								->orWhere(
									function ($query) use ($date) {
					       				$query->where('class_switchings.to', '>=', $date['start'])
					  		          	      ->where('class_switchings.to', '<=', $date['end']);
								});
						});		
		}

		$query = $query->select('class_switchings.id', 'from_user.name as teacher', 'from_period.name as from_period', 'from_class.title as from_class', 'class_switchings.from', 'with_user.name as with_teacher', 'to_period.name as to_period', 'to_class.title as to_class', 'class_switchings.to', 'checked_status.title as status');

		return $query;
	}

	public static function createDatePeriod($start, $end)
	{
		// To create the date between Y-m-d 00:00:00 and Y-m-d 23:59:59.
		$start = Carbon::createFromFormat('Y-m-d H', sprintf('%s 0', $start));
		$end = Carbon::createFromFormat('Y-m-d H', sprintf('%s 0', $end))->addDay()->subSecond();

		return ['start'=>$start, 'end'=>$end];
	}
}