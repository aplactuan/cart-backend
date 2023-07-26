<?php

namespace App\Http\Resources;

use App\Http\Resources\Address\ShippingMethodResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'created_at' => $this->created_at->toDateTimeString(),
            'products' => ProductVariationResource::collection(
                $this->whenLoaded('products')
            ),
            'address' => new AddressResource($this->whenLoaded('address')),
            'shipping_method' => new ShippingMethodResource($this->whenLoaded('shippingMethod'))
        ];
    }
}
