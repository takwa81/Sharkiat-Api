<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'category_id' => $this->category_id ,
            'description' => $this->description,
            'is_appear_home' => $this->is_appear_home,
            'expire_date' => $this->expire_date,
            'images' => ImageResource::collection($this->images),
        ];
    }
}
