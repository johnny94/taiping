<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class InsertPeriod extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		
		
		for ($i = 1; $i <= 7 ; $i++) {
			if ($i === 1) {
				$this->insertPeriodNameToDatabase("早自修");
				$this->insertPeriodNameToDatabase("第 {$i} 節");
				continue;
			}

			if ($i === 5) {
				$this->insertPeriodNameToDatabase("午休");
				$this->insertPeriodNameToDatabase("第 {$i} 節");
				continue;
			}

			$this->insertPeriodNameToDatabase("第 {$i} 節");
		}
	}

	private function insertPeriodNameToDatabase($name)
	{
		$date = Carbon\Carbon::now();
		DB::table('periods')->insert([
				'name' => $name,
				'created_at' => $date,
				'updated_at' => $date
		]);
	}

}
