<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateClassSwitchingRequest extends Request {

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
		$rules = [];

		foreach($this->request->get('classSwitching') as $key => $val) {
			$rules['classSwitching.' . $key . '.teacher'] = 'required|exists:users,id';
			$rules['classSwitching.' . $key . '.from_date'] = 'required|date';
			$rules['classSwitching.' . $key . '.from_period'] = 'required|integer';
			$rules['classSwitching.' . $key . '.from_class'] = 'required|integer';
			$rules['classSwitching.' . $key . '.to_date'] = 'required|date';
			$rules['classSwitching.' . $key . '.to_period'] = 'required|integer';
			$rules['classSwitching.' . $key . '.to_class'] = 'required|integer';		
		}

		return $rules;
	}

	public function messages()
	{
		$messages = [];
		foreach($this->request->get('classSwitching') as $key => $val) {
			$messages['classSwitching.' . $key . '.teacher.required'] = "被調課老師不能留空。";
			$messages['classSwitching.' . $key . '.teacher.exists'] = "找不到該位老師。";	
		}

		return $messages;
	}

}
