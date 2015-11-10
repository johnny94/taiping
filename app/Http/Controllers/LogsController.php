<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Cookie;
use DB;
use Request;

use App\Taiping\Helper\Helper;

class LogsController extends Controller {

	public function __construct()
	{
		$this->middleware('manager');
	}

	public function exportSwitchingLog() {
		$rows = Helper::buildClassSwitchingQuery(
							Request::input('searchPhrase'),
							Request::input('filterByDate'),
							Request::input('filterFrom'),
							Request::input('filterTo'))->get();

		$data = $this->queryResultToExportData($rows);

		$filename = '調課紀錄日誌_產生於_' . Carbon::now()->format('Y_m_d');
		$file = $this->generateLogFile(
					$filename,
					array('ID', '調課老師', '上課日期', '科目', '節次', '被調課老師', '上課日期', '科目', '節次', '調課情況'),
					$data
				);

		return $file->export('xls');
	}

	public function exportUserDeletionLog()
	{
		$rows = DB::table('delete_user_log')
					  ->join('users as manager', 'delete_user_log.manager_id', '=', 'manager.id')
					  ->join('users as deletedUser', 'delete_user_log.user_id', '=', 'deletedUser.id')
					  ->select('manager.name as manager', 'deletedUser.name as user', 'deletedUser.email', 'deletedUser.created_at', 'deletedUser.deleted_at')->get();

		$data = $this->queryResultToExportData($rows);

		$filename = '刪除帳號日誌_產生於_' . Carbon::now()->format('Y_m_d');
		$file = $this->generateLogFile(
					$filename,
					array('管理者', '使用者姓名', 'E-Mail', '註冊時間', '刪除時間'),
					$data
				);

		return $file->export('xls');
	}

	public function exportSwitchingDeletionLog()
	{
		$date = Helper::createDatePeriod(Request::input('start'), Request::input('end'));
		$rows = DB::table('delete_switching_log as log')
					  ->join('users as manager', 'log.manager_id', '=', 'manager.id')
					  ->join('class_switchings as switching', 'log.switching_id', '=', 'switching.id')
					  ->join('users as from_user', 'switching.user_id', '=', 'from_user.id')
					  ->join('periods as from_period', 'switching.from_period', '=', 'from_period.id')
					  ->join('classtitles as from_class', 'switching.from_class_id', '=', 'from_class.id')
					  ->join('users as with_user', 'switching.with_user_id', '=', 'with_user.id')
					  ->join('periods as to_period', 'switching.to_period', '=', 'to_period.id')
					  ->join('classtitles as to_class', 'switching.to_class_id', '=', 'to_class.id')
					  ->join('checked_status', 'switching.checked_status_id', '=', 'checked_status.id')
					  ->where(function($query) use($date) {

					  		$query->where('switching.from', '>=', $date['start'])
								  ->where('switching.from', '<=', $date['end']);
					  })
					  ->orWhere(function($query) use($date) {

					       	$query->where('switching.to', '>=', $date['start'])
					  		      ->where('switching.to', '<=', $date['end']);

					  })
					  ->select('log.id', 'manager.name', 'from_user.name as teacher', 'switching.from', 'from_class.title as from_class', 'from_period.name as from_period', 'with_user.name as with_teacher', 'switching.to', 'to_class.title as to_class', 'to_period.name as to_period', 'checked_status.title as status', 'switching.deleted_at')->get();

		$data = $this->queryResultToExportData($rows);

		$filename = '刪除調課日誌_產生於_' . Carbon::now()->format('Y_m_d');
		$file = $this->generateLogFile(
					$filename,
					array('ID', '管理者(刪除人)', '調課老師', '上課日期', '科目', '節次', '被調課老師', '上課日期', '科目', '節次', '調課情況', '刪除時間'),
					$data
				);


		return $file->download('xls');
	}

	private function generateLogFile($filename, Array $columns, $data, $sheetname = 'Excel sheet')
	{
		return \Excel::create($filename, function ($excel) use ($data, $columns, $sheetname) {

			$excel->sheet($sheetname, function ($sheet) use ($data, $columns) {

				$firstRow = 'A2';

				$sheet->row(1, $columns);
				$sheet->fromArray($data, null, $firstRow, false, false);
			});
		});
	}

	private function queryResultToExportData($rows)
	{
		$data = [];
		foreach ($rows as $row) {
			$data[] = array_values((array)$row);
		}

		return $data;
	}

}
