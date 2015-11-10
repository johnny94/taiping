<?php namespace App\Taiping\Repositories;

use App\User;
use App\ClassSwitching;
use Carbon\Carbon;

class ClassSwitchingRepository
{
	private $classSwitching;

	public function __construct(ClassSwitching $c)
	{
		$this->classSwitching = $c;
	}

	public function findOrFail($id)
	{
		return $this->classSwitching->findOrFail($id);
	}

	public function deleteAll()
	{
		$this->classSwitching->whereNull('deleted_at')->delete();
	}

	public function getPassedLastMonthByUser(User $user)
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

	public function getPassedFromOthersLastMonthByUser(User $user)
	{
		$switchings = $user->withClassSwitching
						   ->where('checked_status_id', ClassSwitching::CHECKING_STATUS_PASS)
						   ->filter(
						   		function ($item) {
									return $item->to >= Carbon::now()->subMonth();
								}
							)->sortByDesc('to');
		return $switchings;
	}

	public function getRejected(User $user)
	{
		return $user->classSwitching->filter(
			function ($item) {
				return $item->checked_status_id == ClassSwitching::CHECKING_STATUS_REJECT;
			}
		);
	}

	public function getPending(User $user)
	{
		return $user->classSwitching->filter(
			function ($item) {
				return $item->checked_status_id == ClassSwitching::CHECKING_STATUS_PENDING;
			}
		);
	}

	public function getPendingFromOthers(User $user)
	{
		return $user->withClassSwitching->filter(
			function ($item) {
				return $item->checked_status_id == ClassSwitching::CHECKING_STATUS_PENDING;
			}
		);
	}
}