<?php namespace App\Taiping\Repositories;

use App\User;
use App\ClassSwitching;
use Carbon\Carbon;

class UserRepository
{
	/*public function getPassedSwitchingLastMonth(User $user)
	{
		$switchings = $user->classSwitching
						   ->where('checked_status_id', ClassSwitching::CHECKING_STATUS_PASS)
						   ->filter( 
						   		function ($item) {
								   	return $item->from >= Carbon::now()->subMonth();
								}
							)->sortByDesc('from');								   		
		
		return $switchings;
	}

	public function getPassedSwitchingFromOthersLastMonth(User $user)
	{
		$switchings = $user->withClassSwitching
						   ->where('checked_status_id', ClassSwitching::CHECKING_STATUS_PASS)
						   ->filter(
						   		function ($item) {
									return $item->to >= Carbon::now()->subMonth();
								}
							)->sortByDesc('to');
		return $switchings;
	}*/

	

}