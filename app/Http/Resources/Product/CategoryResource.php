<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "slug" => $this->slug,
            "parent_id" => $this->parent_id,
            "image_path" => $this->image_path,
            "parent" => $this->parent ? [
                "name" => $this->parent->name,
                "slug" => $this->parent->slug,
            ] : null,
        ];
    }
}
