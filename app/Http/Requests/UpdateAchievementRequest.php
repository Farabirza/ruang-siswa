<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAchievementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'subject_id' => 'required',
            'title' => 'required|max:255|min:6',
            'year' => 'required|digits:4|integer|min:'.(date('Y')-10).'|max:'.(date('Y')),
            'organizer' => 'max:255',
            'url' => 'max:255',
            'description' => 'max:1000',
            'level' => 'required',
            'grade_level' => 'required',
        ];
    }
}
