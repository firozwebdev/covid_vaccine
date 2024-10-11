<?php

namespace App\Http\Requests;
use App\DTOs\UserData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        
        
        return [
            'vaccine_center_id' => 'required|integer|exists:vaccine_centers,id',
			'name' => 'required|string|max:100',
			'email' => 'required|email|string|max:100',
			'nid' => 'required|string|size:10|unique:users,nid',
            'mobile' => 'required|string',
			'status' => 'string|nullable',
			'scheduled_date' => 'date|nullable',
        ];
    }

    

    public function toDTO(): UserData
    {
        return new UserData(
            vaccine_center_id: $this->vaccine_center_id,
			name: $this->name,
			email: $this->email,
			nid: $this->nid,
			mobile: $this->mobile,
			status: $this->status,
			scheduled_date: $this->scheduled_date
        );
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->isMethod('post')) {
            // For creation failures
            $errorMessage = 'Sorry, User creation failed';
        } elseif ($this->isMethod('put')) {
            // For update failures
            $errorMessage = 'Sorry, User update failed';
        } else {
            // For other methods, use a generic error message
            $errorMessage = 'Sorry, Request failed';
        }

        // Create the custom error response
        $response = response()->json([
            'status' => 400,
            'error' => $errorMessage,
            'message' => $validator->errors()
        ], 400); // Setting status code to 400 (Bad Request)

        // Throw a ValidationException with the custom error response
        throw new ValidationException($validator, $response);
    }

    
    
}