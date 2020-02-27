<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductForm extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'size' => 'nullable|integer|min:2',
            'price_from' => 'nullable|integer',
            'price_to' => 'nullable|integer',
            'color' => 'nullable|string',
            'weight' => 'nullable|integer',
            'tags' => 'nullable|string'
        ];
    }
}
