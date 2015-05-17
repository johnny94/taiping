<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateSubstituteRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'substitute_teacher' => 'required',
			'duration_type' => 'required',
			'half_day.date' => 'required|date',
			'half_day.am_pm' => 'required|in:am,pm',
			'full_day.from_date' => 'required|date',
			'full_day.to_date' => 'required|date',
			'period.date' => 'required|date',
			'period.periods' => 'required|array'
		];
	}

	public function messages()
	{
		return [
			'substitute_teacher.required' => '代課老師不能留空。'
		];
	}

}
