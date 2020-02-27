<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductForm extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|integer|min:1',
            'amount' => 'nullable|integer',
            'color_id' => 'nullable|exists:colors,id',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|distinct|exists:categories,id'
        ];
    }
}
