<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "legal_case_id" => "required|exists:legal_cases,id",
            "meeting_type_id" => "required|exists:meeting_types,id",
            "name" => "required|string",
            "description" => "required|string",
            "start_at" => "required|date",
            "participants" => "required|array"
        ];
    }
}
