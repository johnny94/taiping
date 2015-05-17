<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateLeaveRequest extends Request {

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
			'leaveType' => 'required',
			'reason' => 'required',
			'from_date' => 'required|date',
			'to_date' => 'required|date',
			'from_time' => 'required|date_format:H:i',
			'to_time' => 'required|date_format:H:i',
			'curriculum' => 'required'			
		];
	}

	public function messages()
	{
		return [			
			'reason.required' => '事由不能留空。'			
		];

	}

}
