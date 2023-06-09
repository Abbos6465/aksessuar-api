<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStore extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'category_id'=>'required|numeric',
            'brand_id'=>"required|numeric",
            "title"=>"required|string|max:255",
            "price"=>"required|numeric",
            "content"=>"required|max:1000",
            "photo"=>"nullable|image"
        ];
    }
}
