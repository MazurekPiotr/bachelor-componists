<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectFormRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255|unique:projects|unique_slug_title',
            'description' => 'required|max:255',
            'fragmentSong' => 'required|mimes:mpga',
            'fragmentInstrument' => 'required|max:15'
        ];
    }
}
