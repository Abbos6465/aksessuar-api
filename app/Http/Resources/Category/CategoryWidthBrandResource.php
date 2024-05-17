<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Brand\BrandResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryWidthBrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brands' => BrandResource::collection($this->whenLoaded('brands')),
        ];
    }
}
