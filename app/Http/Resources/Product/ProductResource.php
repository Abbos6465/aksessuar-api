<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Category\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user_id' => $this->user_id,
            'category' => new CategoryResource($this->category),
            'brand' => new BrandResource($this->brand),
            'title' => $this->title,
            'price' => $this->price,
            "content" => $this->content,
            "photo" => $this->photo ? base64_encode(Storage::disk('public')->get($this->photo)) : ''
        ];
    }
}
