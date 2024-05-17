<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStore extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
